<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'kontak'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}