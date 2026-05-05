<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * Relasi ke User
     * Satu role bisa dimiliki banyak user
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Helper constants biar tidak typo di mana-mana
    const SUPER_ADMIN    = 'super_admin';
    const ADMIN_BKK      = 'admin_bkk';
    const KEPALA_BKK     = 'kepala_bkk';
    const KEPALA_SEKOLAH = 'kepala_sekolah';
    const SISWA          = 'siswa';
    const PERUSAHAAN     = 'perusahaan';
    const ALUMNI         = 'alumni';
    const PUBLIK         = 'publik';

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}
