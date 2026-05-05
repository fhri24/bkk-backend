<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'super_admin',
                'display_name' => 'Super Admin',
                'description'  => 'Administrator utama sistem',
            ],
            [
                'name'         => 'admin_bkk',
                'display_name' => 'Admin BKK',
                'description'  => 'Administrator BKK',
            ],
            [
                'name'         => 'kepala_bkk',
                'display_name' => 'Kepala BKK',
                'description'  => 'Kepala Bursa Kerja Khusus',
            ],
            [
                'name'         => 'kepala_sekolah',
                'display_name' => 'Kepala Sekolah',
                'description'  => 'Kepala Sekolah',
            ],
            [
                'name'         => 'siswa',
                'display_name' => 'Siswa',
                'description'  => 'Siswa sekolah',
            ],
            [
                'name'         => 'perusahaan',
                'display_name' => 'Perusahaan',
                'description'  => 'Perusahaan mitra',
            ],
            [
                'name'         => 'alumni',
                'display_name' => 'Alumni',
                'description'  => 'Alumni sekolah',
            ],
            [
                'name'         => 'publik',
                'display_name' => 'Publik',
                'description'  => 'Pengguna umum',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description'  => $role['description'],
                ]
            );
        }

        $this->command->info('Roles berhasil dibuat!');
    }
}