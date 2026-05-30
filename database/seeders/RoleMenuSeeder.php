<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua menu ID
        $allMenuIds = Menu::pluck('id')->toArray();

        // Super Admin → dapat semua menu
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->menus()->sync($allMenuIds);
        }

        // Admin BKK → dapat semua menu
        $adminBkk = Role::where('name', 'admin_bkk')->first();
        if ($adminBkk) {
            $adminBkk->menus()->sync($allMenuIds);
        }

        // Kepala BKK → menu terbatas (lihat saja)
        $kepalaBkk = Role::where('name', 'kepala_bkk')->first();
        if ($kepalaBkk) {
            $ids = Menu::whereIn('name', [
                'dashboard', 'jobs', 'job_applications', 'students',
                'companies', 'events', 'event_registrations',
                'news', 'alumni_stories', 'tracer', 'tips',
                'broadcast', 'reports',
            ])->pluck('id')->toArray();
            $kepalaBkk->menus()->sync($ids);
        }

        // Kepala Sekolah → menu terbatas
        $kepalaSekolah = Role::where('name', 'kepala_sekolah')->first();
        if ($kepalaSekolah) {
            $ids = Menu::whereIn('name', [
                'dashboard', 'jobs', 'job_applications', 'students',
                'companies', 'events', 'news', 'tracer', 'reports',
            ])->pluck('id')->toArray();
            $kepalaSekolah->menus()->sync($ids);
        }

        // Role lain (siswa, alumni, perusahaan, publik) → kosong,
        // mereka punya panel sendiri, bukan admin panel
        foreach (['siswa', 'alumni', 'perusahaan', 'publik'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->menus()->sync([]);
            }
        }

        echo "RoleMenuSeeder selesai!\n";
    }
}
