<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Import di bawah ini wajib ada agar tidak merah
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SuperAdmin extends Model
{
    protected $fillable = [
        
        'nama_lengkap',
        'kontak'
    ];

    /**
     * Relasi ke User (Polymorphic)
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}