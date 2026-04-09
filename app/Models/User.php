<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // 1. Tambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    // 2. Tambahkan HasApiTokens di baris use ini
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'userable_id',
        'userable_type',
        'is_active',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];
    

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    // Relasi ke Job (User/Admin yang posting job)
    public function postedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'admin_id');
    }

    // Relasi ke Student (User yang merupakan siswa)
    public function studentProfile(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    // Relasi ke Company (User yang pemilik perusahaan)
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
