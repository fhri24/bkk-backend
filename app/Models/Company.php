<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relasi ke User (Profil Polymorphic)
     * Menghubungkan perusahaan ke akun di tabel users
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * Relasi ke Lowongan Kerja
     * Satu perusahaan bisa memposting banyak lowongan kerja
     */
    public function jobs(): HasMany
    {
        // Pastikan nama modelnya 'Job'. Jika namanya 'JobListing', ganti di bawah ini.
        return $this->hasMany(Job::class, 'company_id', 'company_id');
    }
}