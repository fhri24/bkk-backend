<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlumniStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'story',
        'status',
        'photo',
    ];

    // ── Scopes ──────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ── Accessors ───────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Disetujui</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Ditolak</span>',
            default    => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Menunggu</span>',
        };
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials ?: '?';
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}