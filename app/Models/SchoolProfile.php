<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        'name',
        'site_title',
        'site_description',
        'tagline',
        'phone',
        'email',
        'address',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'logo',
    ];
}