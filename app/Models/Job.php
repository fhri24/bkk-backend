<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Job extends Model
{
    // Agar kolom bisa diisi lewat form
    protected $fillable = [
        'company_id', 
        'admin_id', 
        'title', 
        'description', 
        'status', 
        'visibility'
    ];

    // --- RELASI ---

    // Menghubungkan Job ke Perusahaan
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Menghubungkan Job ke Admin yang posting
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // --- VISIBILITY SCOPES (Filter Otomatis) ---

    // Mengambil yang statusnya 'active'
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    // Mengambil yang visibility-nya 'public'
    public function scopePublic(Builder $query)
    {
        return $query->where('visibility', 'public');
    }

    // Mengambil yang khusus 'alumni_only'
    public function scopeAlumniOnly(Builder $query)
    {
        return $query->where('visibility', 'alumni_only');
    }
}