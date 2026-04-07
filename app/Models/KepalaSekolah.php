<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaSekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'periode_jabatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
