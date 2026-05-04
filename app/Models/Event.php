<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'start_date',
        'end_date',
        'capacity',
        'organizer',
        'category',
        'image',
        'is_published',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
        'capacity' => 'integer',
    ];

    /**
     * Relationship: Event memiliki banyak registrations
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id', 'id');
    }

    /**
     * Accessor: Hitung jumlah registrasi
     */
    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }
}
