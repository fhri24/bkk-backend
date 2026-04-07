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
<<<<<<< HEAD
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi Polymorphic (Inti dari tugas kamu)
     */
    public function userable(): MorphTo
=======
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        public function kepalaSekolah()
        {
            return $this->hasOne(KepalaSekolah::class);
        }

        public function siswa()
        {
            return $this->hasOne(Siswa::class);
        }
    public function superAdmin()
>>>>>>> 0fba9585499a55aa46eec1fa5cd10108e3723000
    {
        return $this->morphTo();
    }
}
