<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedJob extends Model
{
    protected $table = 'saved_jobs';

    protected $fillable = ['user_id', 'job_id'];

    public function job(): BelongsTo
    {
        // Relasi ke Job menggunakan job_id sebagai foreign key dan owner key
        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}