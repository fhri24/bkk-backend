<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
    'title', 'slug', 'description', 'location',
    'start_date', 'end_date', 'capacity', 'organizer',
    'category', 'image', 'thumbnail', 'is_published', 'fee',
    ];

    protected $casts = [
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'is_published' => 'integer',
        'capacity'     => 'integer',
        'fee'          => 'decimal:2',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id', 'slug');
    }

    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }
}
