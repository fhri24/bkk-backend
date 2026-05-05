<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Role individual
        Gate::define('super_admin',    fn(User $u) => $u->role->name === 'super_admin');
        Gate::define('admin_bkk',      fn(User $u) => $u->role->name === 'admin_bkk');
        Gate::define('kepala_bkk',     fn(User $u) => $u->role->name === 'kepala_bkk');
        Gate::define('kepala_sekolah', fn(User $u) => $u->role->name === 'kepala_sekolah');
        Gate::define('siswa',          fn(User $u) => $u->role->name === 'siswa');
        Gate::define('perusahaan',     fn(User $u) => $u->role->name === 'perusahaan');
        Gate::define('alumni',         fn(User $u) => $u->role->name === 'alumni');
        Gate::define('publik',         fn(User $u) => $u->role->name === 'publik');

        // Gate gabungan
        Gate::define('any_admin', fn(User $u) => in_array($u->role->name, [
            'super_admin', 'admin_bkk', 'kepala_bkk', 'kepala_sekolah'
        ]));

        // ✅ any_user — cover siswa, alumni, publik sekaligus
        Gate::define('any_user', fn(User $u) => in_array($u->role->name, [
            'siswa', 'alumni', 'publik'
        ]));
    }
}