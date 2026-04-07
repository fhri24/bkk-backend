<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class User extends Authenticatable
{

    use HasFactory, Notifiable;

    /**
     * Gabungan $fillable (Kolom yang boleh diisi)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'userable_id',
        'userable_type',
        'is_active',
    ];

    /**
     * Gabungan $hidden (Kolom yang disembunyikan dari API/JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Gabungan $casts (Mengubah tipe data otomatis)
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi Polymorphic (Inti dari tugas kamu)
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }
}
