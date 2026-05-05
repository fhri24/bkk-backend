<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publik extends Model
{
    protected $table = 'publik';

    protected $fillable = [
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tahun_lulus',
        'no_hp',
        'alamat',
        'foto_profile',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
