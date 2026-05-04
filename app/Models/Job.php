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

    protected $fillable = [
        'company_id',
        'admin_id',
        'major_id', // Tambahan: Sinkron dengan tabel majors
        'title',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'location',
        'salary',
        'job_type',
        'source',
        'skill_required',
        'logo',
        'status',
        'visibility',
        'is_active',
        'posted_at',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'posted_at'  => 'datetime',
    ];

    // --- RELASI ---

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id', 'job_id');
    }

    public function savedByStudents(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'job_id', 'job_id');
    }

    // --- SCOPES & HELPERS (Tetap Sama) ---
    public function scopeActive(Builder $query) { return $query->where('status', 'active'); }
    public function scopePublic(Builder $query) { return $query->where('visibility', 'public'); }
    public function scopeAlumniOnly(Builder $query) { return $query->where('visibility', 'alumni_only'); }

    public function scopeVisibleFor(Builder $query, $user)
    {
        if (!$user) return $query->public();
        if ($user->alumni_flag ?? false) return $query;
        return $query->public();
    }

    public function getIsActiveJobAttribute(): bool
    {
        return $this->status === 'active' && ($this->expired_at === null || $this->expired_at->isFuture());
    }

    public function getCompanyNameAttribute(): string { return $this->company->company_name ?? '-'; }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) return \Illuminate\Support\Facades\Storage::url($this->logo);
        if ($this->company && $this->company->logo) return \Illuminate\Support\Facades\Storage::url($this->company->logo);
        return null;
    }
}
