<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ── BAGIAN 1: Buat / update semua role ──────────────────
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Administrator utama sistem',
            ],
            [
                'name' => 'admin_bkk',
                'display_name' => 'Admin BKK',
                'description' => 'Administrator BKK',
            ],
            [
                'name' => 'kepala_bkk',
                'display_name' => 'Kepala BKK',
                'description' => 'Kepala Bursa Kerja Khusus',
            ],
            [
                'name' => 'kepala_sekolah',
                'display_name' => 'Kepala Sekolah',
                'description' => 'Kepala Sekolah',
            ],
            [
                'name' => 'siswa',
                'display_name' => 'Siswa',
                'description' => 'Siswa sekolah',
            ],
            [
                'name' => 'perusahaan',
                'display_name' => 'Perusahaan',
                'description' => 'Perusahaan mitra',
            ],
            [
                'name' => 'alumni',
                'display_name' => 'Alumni',
                'description' => 'Alumni sekolah',
            ],
            [
                'name' => 'publik',
                'display_name' => 'Publik',
                'description' => 'Pengguna umum',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }

        $this->command->info('Roles berhasil dibuat!');

        // ── BAGIAN 2: Assign permissions ke setiap role ─────────
        $allPermissions = Permission::pluck('id', 'name');

        if ($allPermissions->isEmpty()) {
            $this->command->warn('Tidak ada permission di database. Jalankan PermissionSeeder dulu.');

            return;
        }

        // Helper: ambil array ID dari nama-nama permission
        $perm = fn ($names) => collect($names)
            ->map(fn ($n) => $allPermissions[$n] ?? null)
            ->filter()
            ->values()
            ->toArray();

        // Super Admin → semua permission
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync($allPermissions->values()->toArray());
            $this->command->info('Super Admin: semua permission di-assign.');
        }

        // Admin BKK → hampir semua, kecuali manage_settings
        $adminBkk = Role::where('name', 'admin_bkk')->first();
        if ($adminBkk) {
            $adminBkk->permissions()->sync($perm([
                'view_reports',
                'manage_jobs',
                'manage_job_applications',
                'manage_companies',
                'manage_students',
                'manage_users',
                'view_activity_logs',
            ]));
            $this->command->info('Admin BKK: permissions di-assign.');
        }

        // Kepala BKK → lihat laporan dan log aktivitas
        $kepalaBkk = Role::where('name', 'kepala_bkk')->first();
        if ($kepalaBkk) {
            $kepalaBkk->permissions()->sync($perm([
                'view_reports',
                'view_activity_logs',
            ]));
            $this->command->info('Kepala BKK: permissions di-assign.');
        }

        // Kepala Sekolah → lihat laporan saja
        $kepalaSekolah = Role::where('name', 'kepala_sekolah')->first();
        if ($kepalaSekolah) {
            $kepalaSekolah->permissions()->sync($perm([
                'view_reports',
            ]));
            $this->command->info('Kepala Sekolah: permissions di-assign.');
        }

        // Siswa, Alumni, Perusahaan, Publik → tidak perlu permission admin panel
        foreach (['siswa', 'alumni', 'perusahaan', 'publik'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->permissions()->sync([]);
            }
        }

        $this->command->info('RolePermissionSeeder selesai!');
    }
}
