<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'companies';
    
    // Primary Key kustom sesuai migration kita
    protected $primaryKey = 'company_id';

    // Membolehkan pengisian semua kolom (mass assignment)
    protected $guarded = [];

    /**
     * Relasi ke User
     * Perusahaan punya satu user akun (owner/contact person)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Lowongan Kerja
     * Satu perusahaan bisa memposting banyak lowongan kerja
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'company_id', 'company_id');
    }

    /**
     * Relasi polymorphic ke User (untuk profil user company)
     * Perusahaan bisa punya profil sebagai userable di tabel users
     */
    public function userProfile(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}