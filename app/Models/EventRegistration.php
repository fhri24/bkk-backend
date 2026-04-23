<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';
    protected $primaryKey = 'event_registration_id';
    public $incrementing = true;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'institution',
        'position',
        'status',
        'admin_notes',
        'registered_at'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
