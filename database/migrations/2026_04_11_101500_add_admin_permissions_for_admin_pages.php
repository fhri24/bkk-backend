<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        $permissions = [
            ['name' => 'manage_companies', 'display_name' => 'Kelola Perusahaan', 'description' => 'Mengizinkan akses penuh ke manajemen perusahaan'],
            ['name' => 'manage_job_applications', 'display_name' => 'Kelola Lamaran', 'description' => 'Mengizinkan akses penuh ke manajemen lamaran kerja'],
            ['name' => 'manage_students', 'display_name' => 'Kelola Alumni', 'description' => 'Mengizinkan akses ke daftar dan detail alumni'],
        ];

        // Memasukkan data ke tabel system_permissions (milik kustom Anda)
        DB::table('system_permissions')->insertOrIgnore(array_map(function ($permission) {
            return array_merge($permission, ['created_at' => now(), 'updated_at' => now()]);
        }, $permissions));

        $roles = DB::table('roles')->pluck('id', 'name')->toArray();
        $permissionIds = DB::table('system_permissions')->pluck('id', 'name')->toArray();

        $mappings = [];

        // Mapping untuk Super Admin
        if (!empty($roles['super_admin'])) {
            foreach ($permissionIds as $name => $id) {
                // Hanya petakan permission yang baru saja kita buat di atas
                if (in_array($name, ['manage_companies', 'manage_job_applications', 'manage_students'])) {
                    $mappings[] = ['role_id' => $roles['super_admin'], 'permission_id' => $id, 'created_at' => now(), 'updated_at' => now()];
                }
            }
        }

        // Mapping untuk Admin BKK
        if (!empty($roles['admin_bkk'])) {
            $adminBkkPermissions = ['manage_companies', 'manage_job_applications', 'manage_students'];
            foreach ($adminBkkPermissions as $permissionName) {
                if (isset($permissionIds[$permissionName])) {
                    $mappings[] = ['role_id' => $roles['admin_bkk'], 'permission_id' => $permissionIds[$permissionName], 'created_at' => now(), 'updated_at' => now()];
                }
            }
        }

        // Mapping untuk Perusahaan
        if (!empty($roles['perusahaan'])) {
            $companyPermissions = ['manage_job_applications'];
            foreach ($companyPermissions as $permissionName) {
                if (isset($permissionIds[$permissionName])) {
                    $mappings[] = ['role_id' => $roles['perusahaan'], 'permission_id' => $permissionIds[$permissionName], 'created_at' => now(), 'updated_at' => now()];
                }
            }
        }

        // Eksekusi mapping ke tabel system_permission_role
        if (!empty($mappings)) {
            foreach ($mappings as $mapping) {
                $exists = DB::table('system_permission_role')
                    ->where('role_id', $mapping['role_id'])
                    ->where('permission_id', $mapping['permission_id'])
                    ->exists();

                if (!$exists) {
                    DB::table('system_permission_role')->insert($mapping);
                }
            }
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        $permissionNames = ['manage_companies', 'manage_job_applications', 'manage_students'];
        $permissionIds = DB::table('system_permissions')->whereIn('name', $permissionNames)->pluck('id')->toArray();

        DB::table('system_permission_role')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('system_permissions')->whereIn('name', $permissionNames)->delete();
    }
}; 