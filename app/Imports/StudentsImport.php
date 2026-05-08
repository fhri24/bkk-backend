<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
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
        $nisn  = isset($row['nisn'])           ? trim((string) $row['nisn'])           : null;
        $nipd  = isset($row['nipd'])           ? trim((string) $row['nipd'])           : null;
        $nama  = isset($row['nama'])           ? trim($row['nama'])                    : null;
        $jk    = isset($row['jk'])             ? strtoupper(trim($row['jk']))          : null;
        $rombel = isset($row['rombel_saat_ini']) ? trim($row['rombel_saat_ini'])       : null;

        if (empty($nama)) return null;

        $jurusan = $this->extractJurusan($rombel);

        // Buat email unik dari NISN atau NIPD
        $identifier = $nisn ?? $nipd ?? Str::random(8);
        $email = $identifier . '@alumni.smkn1garut.sch.id';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $nama,
                'password' => Hash::make($nisn ?? 'password123'),
                'role_id'  => \App\Models\Role::where('name', 'alumni')->value('id') ?? 3,
            ]
        );

        // Skip duplikat berdasarkan NISN + tahun lulus
        $existing = Student::where('nisn', $nisn)
            ->where('graduation_year', $this->graduationYear)
            ->exists();

        if ($existing) return null;

        return new Student([
            'user_id'         => $user->id,
            'nis'             => $nipd,   // NIPD masuk ke kolom nis
            'nisn'            => $nisn,   // NISN masuk ke kolom nisn (baru)
            'full_name'       => $nama,
            'gender'          => in_array($jk, ['L', 'P']) ? $jk : null,
            'major'           => $jurusan,
            'graduation_year' => $this->graduationYear,
            'alumni_flag'     => true,
            'status'          => 'active',
        ]);
    }

    /**
     * Ekstrak jurusan dari nama Rombel
     * "XII RPL 1" → "RPL"
     * "XII TKJ 2" → "TKJ"
     * "XII DKV"   → "DKV"
     */
    private function extractJurusan(?string $rombel): ?string
    {
        if (!$rombel) return null;
        $cleaned = preg_replace('/^(XII|XI|X)\s+/i', '', trim($rombel));
        $parts   = explode(' ', trim($cleaned));
        return strtoupper($parts[0]) ?? null;
    }

    public function headingRow(): int
    {
        return 5; // Baris header di file Dapodik ada di baris ke-5
    }
}