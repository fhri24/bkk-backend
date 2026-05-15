<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Illuminate\Support\Facades\View; 
use App\Services\SchoolProfileService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Force HTTPS di production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        try {
            View::share('schoolProfile', SchoolProfileService::get());
        } catch (\Exception $e) {
            View::share('schoolProfile', null);
        }

        Gate::define('super_admin',    fn(User $u) => $u->role->name === 'super_admin');
        Gate::define('admin_bkk',      fn(User $u) => $u->role->name === 'admin_bkk');
        Gate::define('kepala_bkk',     fn(User $u) => $u->role->name === 'kepala_bkk');
        Gate::define('kepala_sekolah', fn(User $u) => $u->role->name === 'kepala_sekolah');
        Gate::define('siswa',          fn(User $u) => $u->role->name === 'siswa');
        Gate::define('perusahaan',     fn(User $u) => $u->role->name === 'perusahaan');
        Gate::define('alumni',         fn(User $u) => $u->role->name === 'alumni');
        Gate::define('publik',         fn(User $u) => $u->role->name === 'publik');
        Gate::define('any_admin', fn(User $u) => in_array($u->role->name, [
            'super_admin', 'admin_bkk', 'kepala_bkk', 'kepala_sekolah'
        ]));
        Gate::define('any_user', fn(User $u) => in_array($u->role->name, [
            'siswa', 'alumni', 'publik'
        ]));
    }
}