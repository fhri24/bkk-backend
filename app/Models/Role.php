<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
