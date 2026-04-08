<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphOne;

class SuperAdmin extends Model
{
    use HasFactory;

    // Nama tabel plural sesuai migration
    protected $table = 'super_admins';

    // Primary Key kustom
    protected $primaryKey = 'super_admin_id';

    // Membolehkan pengisian semua kolom secara fleksibel
    protected $guarded = [];

    /**
     * Relasi ke User (Profil Polymorphic)
     * SuperAdmin terhubung ke tabel users lewat sistem Morph
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}