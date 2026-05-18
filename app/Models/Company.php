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

    protected $table = 'companies';

    // Primary Key kustom sesuai migration kita
    protected $primaryKey = 'company_id';
    protected $guarded = [];

    /**
     * Casting untuk PostgreSQL compatibility
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    // FIX: Cast boolean agar kompatibel dengan PostgreSQL
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'company_id', 'company_id');
    }

    public function userProfile(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
