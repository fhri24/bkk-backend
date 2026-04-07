<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_tanggal_lahir',
        'jurusan',
        'tahun_lulus',
        'no_hp',
        'alamat',
        'cv_file',
        'foto_profil'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
