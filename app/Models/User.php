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
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi (Mass Assignable). 
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'nis',
        'major',
        'gender',
        'graduation_year',
        'phone',           // Untuk OTP via WhatsApp
        'social_id',       // Untuk Google/Facebook login
        'social_provider', // 'google' | 'facebook'
        'avatar',          // Foto profil dari social
        'email_verified_at',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'social_id',
    ];

    /**
     * Penanganan Casting Atribut (Laravel 11 style).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /**
     * Relasi ke Kode OTP.
     */
    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    /**
     * Relasi One-to-One ke Student.
     */
    public function student(): HasOne
    { 
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    /**
     * Relasi Many-to-One ke Role (Manual Role System).
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

    // ─── Helpers & Custom Logic ──────────────────────────────────────────────

    /**
     * Cek apakah user login via social (tidak punya password asli)
     */
    public function isSocialUser(): bool
    {
        return !is_null($this->social_provider);
    }

    /**
     * Accessor untuk Avatar: dari social jika ada, atau generate inisial.
     * Panggil di Blade dengan: $user->avatar_url
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) return $this->avatar;
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=001f3f&color=fff&bold=true';
    }

    /**
     * Method hasPermission untuk mengecek hak akses di View Admin.
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

        // 3. Logika pengecekan permission manual
        $adminRoles = ['admin_bkk', 'kepala_bkk', 'perusahaan'];
        if (in_array($this->role->name, $adminRoles)) { 
            if (isset($this->role->permissions) && is_array($this->role->permissions)) {
                return in_array($permission, $this->role->permissions);
            } 
            return true; 
        }

        return false;
    } 
}