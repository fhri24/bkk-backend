<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected int $graduationYear;

    public function __construct(int $graduationYear)
    {
        $this->graduationYear = $graduationYear;
    }

    public function collection(Collection $rows)
    {
        // Ambil role alumni sekali saja
        $roleId = Role::where('name', 'alumni')->value('id') ?? 3;

        // Ambil semua NISN yang sudah ada di DB sekaligus (1 query, bukan N query)
        $existingNisns = Student::where('graduation_year', $this->graduationYear)
            ->whereNotNull('nisn')
            ->pluck('nisn')
            ->flip(); // jadikan key untuk O(1) lookup

        // Ambil semua email user yang sudah ada sekaligus
        $existingEmails = User::pluck('id', 'email'); // [email => id]

        $usersToInsert   = [];
        $studentsToInsert = [];
        $now = now();

        foreach ($rows as $row) {
            $nama   = isset($row['nama'])            ? trim($row['nama'])           : null;
            $nipd   = isset($row['nipd'])            ? trim((string) $row['nipd'])  : null;
            $jk     = isset($row['jk'])              ? strtoupper(trim($row['jk'])) : null;
            $rombel = isset($row['rombel_saat_ini']) ? trim($row['rombel_saat_ini']): null;

            $nisnRaw = $row['nisn'] ?? null;
            $nisn = null;
            if ($nisnRaw !== null) {
                $nisn = str_pad((string)(int)$nisnRaw, 10, '0', STR_PAD_LEFT);
            }

            if (empty($nama)) continue;

            // Skip duplikat
            if ($nisn && isset($existingNisns[$nisn])) continue;

            $jurusan    = $this->extractJurusan($rombel);
            $identifier = $nisn ?? $nipd ?? Str::random(8);
            $email      = $identifier . '@alumni.smkn1garut.sch.id';

            // Buat user kalau belum ada
            if (!isset($existingEmails[$email])) {
                $userId = DB::table('users')->insertGetId([
                    'name'       => $nama,
                    'email'      => $email,
                    'password'   => Hash::make($nisn ?? 'password123'),
                    'role_id'    => $roleId,
                    'is_active'  => DB::raw('true'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $existingEmails[$email] = $userId;
            }

            $userId = $existingEmails[$email];

            // Tandai NISN sudah diproses
            if ($nisn) $existingNisns[$nisn] = true;

            $studentsToInsert[] = [
                'user_id'         => $userId,
                'nis'             => $nipd,
                'nisn'            => $nisn,
                'full_name'       => strtolower($nama),
                'gender'          => in_array($jk, ['L', 'P']) ? $jk : null,
                'major'           => $jurusan,
                'graduation_year' => $this->graduationYear,
                'alumni_flag'     => DB::raw('true'), // FIX: boolean PostgreSQL
                'status'          => 'active',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];

            // Batch insert setiap 50 baris agar tidak timeout
            if (count($studentsToInsert) >= 50) {
                DB::table('students')->insert($studentsToInsert);
                $studentsToInsert = [];
            }
        }

        // Insert sisa data
        if (!empty($studentsToInsert)) {
            DB::table('students')->insert($studentsToInsert);
        }
    }

    private function extractJurusan(?string $rombel): ?string
    {
        if (!$rombel) return null;
        $cleaned = preg_replace('/^(XII|XI|X)\s+/i', '', trim($rombel));
        $parts   = explode(' ', trim($cleaned));
        return strtoupper($parts[0]) ?? null;
    }

    public function headingRow(): int
    {
        return 1;
    }
} 