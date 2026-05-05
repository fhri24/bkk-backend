<?php

namespace App\Models;

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
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'userable_id', 
        'userable_type',
        'is_active',
        'nis',
        'major',
        'gender',
        'graduation_year',
        'phone',
        'social_id',
        'social_provider',
        'avatar',
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
     * Casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ─── RELASI ─────────────────────────────────────────

    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    public function student(): HasOne
    { 
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    
    public function userable()
    {
        return $this->morphTo();
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'user_id', 'id');
    }

    // ─── ROLE HELPERS ───────────────────────────────────

    public function isSuperAdmin()    { return $this->role?->name === Role::SUPER_ADMIN; }
    public function isAdminBkk()      { return $this->role?->name === Role::ADMIN_BKK; }
    public function isKepalaBkk()     { return $this->role?->name === Role::KEPALA_BKK; }
    public function isKepalaSekolah() { return $this->role?->name === Role::KEPALA_SEKOLAH; }
    public function isSiswa()         { return $this->role?->name === Role::SISWA; }
    public function isPerusahaan()    { return $this->role?->name === Role::PERUSAHAAN; }
    public function isAlumni()        { return $this->role?->name === Role::ALUMNI; }
    public function isPublik()        { return $this->role?->name === Role::PUBLIK; }

    public function isAnyAdmin()
    {
        return in_array($this->role?->name, [
            Role::SUPER_ADMIN,
            Role::ADMIN_BKK,
            Role::KEPALA_BKK,
            Role::KEPALA_SEKOLAH,
        ]);
    }

    // ─── LOGIC ──────────────────────────────────────────

    public function isSocialUser(): bool
    {
        return !is_null($this->social_provider);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) return $this->avatar;

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=001f3f&color=fff&bold=true';
    }

    public function hasPermission($permission)
    {
        if (!$this->role) return false;

        if ($this->role->name === 'super_admin') return true;

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