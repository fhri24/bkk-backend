<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class KepalaSekolah extends Model
{
    use HasFactory;

    // Nama tabel harus jamak (plural) sesuai migration: kepala_sekolahs
    protected $table = 'kepala_sekolahs';

    // Primary key kustom karena bukan 'id'
    protected $primaryKey = 'kepala_sekolah_id';

    // Menggantikan $fillable yang panjang, sekarang lebih fleksibel
    protected $guarded = [];

    /**
     * Relasi ke User (Profil Polymorphic)
     * Ini yang menghubungkan Kepala Sekolah ke tabel login (users)
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
