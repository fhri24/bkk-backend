<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     * Pastikan role_id juga ada jika kamu menggunakan relasi role.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Tambahkan ini jika kolom role_id ada di tabel users
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (API/JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Penanganan Casting Atribut.
     * Laravel 11+ merekomendasikan penggunaan method casts() daripada property $casts.
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // PERBAIKAN: Gunakan 'hashed' bukan 'password'
        ];
    }

    /**
     * Relasi One-to-One ke Student.
     */
    public function student()
    {
        // Menghubungkan user_id di tabel students dengan id di tabel users
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Relasi Many-to-One ke Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Relasi One-to-Many ke SavedJob (Lowongan Tersimpan).
     */
    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class, 'user_id', 'id');
    }
}