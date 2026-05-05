<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpCode extends Model
{
    protected $fillable = [
        'user_id', 
        'otp', 
        'token', 
        'send_via', 
        'expires_at', 
        'created_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relasi balik ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}