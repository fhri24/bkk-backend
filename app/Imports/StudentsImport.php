<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    protected int $graduationYear;

    public function __construct(int $graduationYear)
    {
        $this->graduationYear = $graduationYear;
    }

    public function model(array $row)
    {
        $nama   = isset($row['nama'])            ? trim($row['nama'])             : null;
        $nipd   = isset($row['nipd'])            ? trim((string) $row['nipd'])    : null;
        $jk     = isset($row['jk'])              ? strtoupper(trim($row['jk']))   : null;
        $rombel = isset($row['rombel_saat_ini']) ? trim($row['rombel_saat_ini'])     : null;

        // --- FIX NISN: Menjaga leading zero (angka 0 di depan) ---
        $nisnRaw = $row['nisn'] ?? null;
        $nisn = null;

        if ($nisnRaw !== null) {
            // Paksa jadi string, jaga leading zero, pastikan 10 digit
            $nisn = str_pad((string)(int)$nisnRaw, 10, '0', STR_PAD_LEFT);
        }
        // --------------------------------------------------------

        if (empty($nama)) return null;

        $jurusan = $this->extractJurusan($rombel);

        // Gunakan NISN sebagai identifier, jika tidak ada pakai NIPD
        $identifier = $nisn ?? $nipd ?? Str::random(8);
        $email      = $identifier . '@alumni.smkn1garut.sch.id';

        $roleId = Role::where('name', 'alumni')->value('id') ?? 3;

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $nama,
                'password' => Hash::make($nisn ?? 'password123'),
                'role_id'  => $roleId,
            ]
        );

        // Skip jika data duplikat berdasarkan NISN dan Tahun Lulus
        $existing = Student::where('nisn', $nisn)
            ->where('graduation_year', $this->graduationYear)
            ->exists();

        if ($existing) return null;

        return new Student([
            'user_id'         => $user->id,
            'nis'             => $nipd,
            'nisn'            => $nisn,
            'full_name'       => $nama,
            'gender'          => in_array($jk, ['L', 'P']) ? $jk : null,
            'major'           => $jurusan,
            'graduation_year' => $this->graduationYear,
            'alumni_flag'     => true,
            'status'          => 'active',
        ]);
    }

    private function extractJurusan(?string $rombel): ?string
    {
        if (!$rombel) return null;
        // Hapus kelas (XII/XI/X) di awal
        $cleaned = preg_replace('/^(XII|XI|X)\s+/i', '', trim($rombel));
        $parts   = explode(' ', trim($cleaned));
        return strtoupper($parts[0]) ?? null;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
