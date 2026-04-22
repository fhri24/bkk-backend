<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     * 
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Penanganan Casting Atribut (Laravel 11+ style).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * PERBAIKAN: Method hasPermission untuk mengecek hak akses di View Admin.
     * Method ini yang dipanggil di resources\views\layouts\admin.blade.php:216
     */
    public function hasPermission($permission)
    {
        // 1. Pastikan user memiliki role
        if (!$this->role) {
            return false;
        }

        // 2. Super Admin biasanya punya akses ke semua hal
        if ($this->role->name === 'super_admin') {
            return true;
        }

        /** 
         * 3. Logika pengecekan permission.
         * Asumsi: Tabel roles memiliki kolom 'permissions' yang menyimpan JSON atau Array.
         * Jika kamu belum membuat sistem permission yang kompleks, kita kembalikan true 
         * untuk semua admin agar kamu bisa login dulu.
         */
        $adminRoles = ['admin_bkk', 'kepala_bkk', 'perusahaan'];
        if (in_array($this->role->name, $adminRoles)) {
            // Jika kolom permissions di database ada, gunakan ini:
            if (isset($this->role->permissions) && is_array($this->role->permissions)) {
                return in_array($permission, $this->role->permissions);
            }
            // Jika belum ada sistem permission di DB, berikan akses penuh untuk role admin
            return true; 
        }

        return false;
    }

    /**
     * Relasi One-to-One ke Student.
     */
    public function student(): HasOne
    {
        
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Relasi Many-to-One ke Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Relasi One-to-Many ke SavedJob (Lowongan Tersimpan).
     */
    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'user_id', 'id');
    }
}