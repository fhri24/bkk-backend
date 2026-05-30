<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    const SUPER_ADMIN = 'super_admin';

    const ADMIN_BKK = 'admin_bkk';

    const KEPALA_BKK = 'kepala_bkk';

    const KEPALA_SEKOLAH = 'kepala_sekolah';

    const SISWA = 'siswa';

    const PERUSAHAAN = 'perusahaan';

    const ALUMNI = 'alumni';

    const PUBLIK = 'publik';

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'system_permission_role', 'role_id', 'permission_id');
    }

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
    }
}
