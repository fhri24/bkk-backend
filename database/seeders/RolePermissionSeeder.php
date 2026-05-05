<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cache Spatie (Penting!)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definisi Permission (Hak Akses)
        $permissions = [
            'manage-users',
            'manage-jobs',
            'apply-jobs',
            'view-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Buat Role dan Berikan Permission
        
        // Super Admin (Bisa semua)
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Admin BKK
        $roleAdminBkk = Role::firstOrCreate(['name' => 'admin_bkk']);
        $roleAdminBkk->givePermissionTo(['manage-jobs', 'view-dashboard']);

        // Siswa / Alumni
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);
        $roleSiswa->givePermissionTo(['apply-jobs', 'view-dashboard']);

        // Perusahaan
        $rolePerusahaan = Role::firstOrCreate(['name' => 'perusahaan']);
        $rolePerusahaan->givePermissionTo(['manage-jobs', 'view-dashboard']);

        $this->command->info('Roles dan Permissions berhasil dibuat!');
    }
}