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
        // Menggunakan nama 'system_permissions' agar tidak bentrok dengan tabel 'permissions' milik Spatie
        Schema::create('system_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('system_permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('system_permissions')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Data permissions kustom untuk sistem BKK
        $permissions = [
            ['name' => 'view_reports', 'display_name' => 'Lihat Laporan', 'description' => 'Mengizinkan akses ke halaman laporan'],
            ['name' => 'manage_users', 'display_name' => 'Kelola Pengguna', 'description' => 'Mengizinkan pembuatan, aktivasi, dan pemblokiran pengguna'],
            ['name' => 'manage_settings', 'display_name' => 'Kelola Pengaturan', 'description' => 'Mengizinkan akses ke pengaturan sistem'],
            ['name' => 'manage_jobs', 'display_name' => 'Kelola Lowongan', 'description' => 'Mengizinkan manajemen lowongan pekerjaan'],
            ['name' => 'view_activity_logs', 'display_name' => 'Lihat Log Aktivitas', 'description' => 'Mengizinkan akses ke riwayat aktivitas pengguna'],
        ];

        DB::table('system_permissions')->insert(array_map(function ($permission) {
            return array_merge($permission, ['created_at' => now(), 'updated_at' => now()]);
        }, $permissions));

        // Mapping Role ke Permission kustom
        $roles = DB::table('roles')->pluck('id', 'name')->toArray();
        if (!empty($roles)) {
            $mappings = [];
            $allPermissionIds = DB::table('system_permissions')->pluck('id', 'name')->toArray();

            foreach ($roles as $roleName => $roleId) {
                $permissionNames = [];

                if ($roleName === 'super_admin') {
                    $permissionNames = array_keys($allPermissionIds);
                } elseif ($roleName === 'admin_bkk') {
                    $permissionNames = ['view_reports', 'manage_users', 'manage_settings', 'manage_jobs', 'view_activity_logs'];
                } elseif ($roleName === 'kepala_bkk') {
                    $permissionNames = ['view_reports', 'view_activity_logs'];
                } elseif ($roleName === 'perusahaan') {
                    $permissionNames = ['manage_jobs', 'view_activity_logs'];
                } elseif ($roleName === 'kepala_sekolah') {
                    $permissionNames = ['view_reports'];
                }

                foreach ($permissionNames as $permissionName) {
                    if (isset($allPermissionIds[$permissionName])) {
                        $mappings[] = [
                            'role_id' => $roleId,
                            'permission_id' => $allPermissionIds[$permissionName],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($mappings)) {
                DB::table('system_permission_role')->insert($mappings);
            }
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('system_permission_role');
        Schema::dropIfExists('system_permissions');
    }
}; 