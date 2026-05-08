<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    // BARU: Beritahu Laravel agar menggunakan tabel kustom dari migration kamu
    protected $table = 'system_permissions';

    protected $fillable = ['name', 'display_name', 'description'];

    public function roles(): BelongsToMany
    {
        // BARU: Sesuaikan nama tabel pivot agar pakai 'system_permission_role'
        return $this->belongsToMany(Role::class, 'system_permission_role', 'permission_id', 'role_id');
    }
} 