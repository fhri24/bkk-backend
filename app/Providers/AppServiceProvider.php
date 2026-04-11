<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Biarkan kosong jika belum ada service yang didaftarkan
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Super Admin selalu diizinkan untuk semua permission
        Gate::before(function (User $user, string $ability) {
            return $user->role && $user->role->name === 'super_admin' ? true : null;
        });

        // Gate untuk Super Admin
        Gate::define('super_admin', function (User $user) {
            return $user->role && $user->role->name === 'super_admin';
        });

        // Gate untuk Admin BKK / Kepala BKK
        Gate::define('admin_bkk', function (User $user) {
            return $user->role && ($user->role->name === 'admin_bkk' || $user->role->name === 'kepala_bkk');
        });

        // Gate untuk Perusahaan (Company)
        Gate::define('company', function (User $user) {
            return $user->role && $user->role->name === 'company';
        });

        // Gate untuk Siswa (Student)
        Gate::define('student', function (User $user) {
            return $user->role && $user->role->name === 'student';
        });

        // Gate generic untuk mengecek permission berdasarkan role
        Gate::define('permission', function (User $user, string $permission) {
            return $user->hasPermission($permission);
        });
    }
}