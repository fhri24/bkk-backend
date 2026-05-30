<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // === MENU UTAMA ===
            ['name' => 'dashboard',           'label' => 'Dashboard',          'icon' => 'fas fa-chart-line',    'route' => 'admin.dashboard',                'group' => 'main', 'order' => 1],
            ['name' => 'jobs',                'label' => 'Lowongan Kerja',     'icon' => 'fas fa-briefcase',     'route' => 'admin.jobs.index',               'group' => 'main', 'order' => 2],
            ['name' => 'job_applications',    'label' => 'Lamaran',            'icon' => 'fas fa-file-alt',      'route' => 'admin.job-applications.index',   'group' => 'main', 'order' => 3],
            ['name' => 'students',            'label' => 'Alumni',             'icon' => 'fas fa-users',         'route' => 'admin.students.index',           'group' => 'main', 'order' => 4],
            ['name' => 'companies',           'label' => 'Perusahaan',         'icon' => 'fas fa-building',      'route' => 'admin.companies.index',          'group' => 'main', 'order' => 5],
            ['name' => 'company_accounts',    'label' => 'Akun Perusahaan',    'icon' => 'fas fa-user-tie',      'route' => 'admin.company-accounts.index',   'group' => 'main', 'order' => 6],
            ['name' => 'events',              'label' => 'Manajemen Acara',    'icon' => 'fas fa-calendar-alt',  'route' => 'admin.events.index',             'group' => 'main', 'order' => 7],
            ['name' => 'event_registrations', 'label' => 'Peserta Acara',      'icon' => 'fas fa-users',         'route' => 'admin.event-registrations.index','group' => 'main', 'order' => 8],
            ['name' => 'news',                'label' => 'Berita',             'icon' => 'fas fa-newspaper',     'route' => 'admin.news.index',               'group' => 'main', 'order' => 9],
            ['name' => 'alumni_stories',      'label' => 'Kisah Sukses',       'icon' => 'fas fa-star',          'route' => 'admin.alumni-stories.index',     'group' => 'main', 'order' => 10],
            ['name' => 'tracer',              'label' => 'Tracer Study',       'icon' => 'fas fa-chart-line',    'route' => 'admin.tracer.index',             'group' => 'main', 'order' => 11],
            ['name' => 'tips',                'label' => 'Tips & Tricks',      'icon' => 'fas fa-lightbulb',     'route' => 'admin.tips.index',               'group' => 'main', 'order' => 12],
            ['name' => 'broadcast',           'label' => 'Broadcast',          'icon' => 'fas fa-bullhorn',      'route' => 'admin.broadcast.index',          'group' => 'main', 'order' => 13],
            ['name' => 'reports',             'label' => 'Laporan',            'icon' => 'fas fa-chart-bar',     'route' => 'admin.reports.index',            'group' => 'main', 'order' => 14],
            ['name' => 'settings',            'label' => 'Pengaturan',         'icon' => 'fas fa-sliders-h',     'route' => 'admin.settings.profile',         'group' => 'main', 'order' => 15],

            // === MANAJEMEN ===
            ['name' => 'users',               'label' => 'Pengguna',           'icon' => 'fas fa-user',          'route' => 'admin.users.index',              'group' => 'management', 'order' => 16],
            ['name' => 'roles',               'label' => 'Hak Akses',          'icon' => 'fas fa-user-shield',   'route' => 'admin.roles.index',              'group' => 'management', 'order' => 17],
            ['name' => 'activity_logs',       'label' => 'Log Aktivitas',      'icon' => 'fas fa-history',       'route' => 'admin.activity-logs.index',      'group' => 'management', 'order' => 18],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->insertOrIgnore(array_merge($menu, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Berikan semua menu ke Super Admin secara default
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $menuIds = DB::table('menus')->pluck('id');
            foreach ($menuIds as $menuId) {
                DB::table('role_menu')->insertOrIgnore([
                    'role_id' => $superAdminRole->id,
                    'menu_id' => $menuId,
                ]);
            }
        }

        // Berikan menu utama ke Admin BKK
        $adminBkkRole = DB::table('roles')->where('name', 'admin_bkk')->first();
        if ($adminBkkRole) {
            $mainMenuIds = DB::table('menus')
                ->whereIn('name', ['dashboard', 'jobs', 'job_applications', 'students', 'companies', 'events', 'event_registrations', 'news', 'alumni_stories', 'broadcast', 'tips'])
                ->pluck('id');
            foreach ($mainMenuIds as $menuId) {
                DB::table('role_menu')->insertOrIgnore([
                    'role_id' => $adminBkkRole->id,
                    'menu_id' => $menuId,
                ]);
            }
        }
    }
}
