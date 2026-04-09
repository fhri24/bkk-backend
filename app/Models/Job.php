<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    protected $table = 'job_listings';
    protected $primaryKey = 'job_id';

    // Agar kolom bisa diisi lewat form
    protected $fillable = [
        'company_id', 
        'admin_id', 
        'title', 
        'description',
        'requirements',
        'location',
        'job_type',
        'status', 
        'visibility',
        'is_active',
        'posted_at',
        'expired_at'
    ];

    // --- RELASI ---

    // Menghubungkan Job ke Perusahaan
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    // Menghubungkan Job ke Admin yang posting
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Job ke Job Applications (satu lowongan bisa punya banyak lamaran)
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id', 'job_id');
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

    public function scopeVisibleFor(Builder $query, $user)
    {
    // Kalau belum login → hanya public
    if (!$user) {
        return $query->public();
    }

    // Kalau user adalah alumni → bisa lihat semua
    if ($user->alumni_flag) {
        return $query;
    }

    // Selain alumni → hanya public
    return $query->public();
}
}
