<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'full_name',
        'gender',
        'birth_info',
        'major',
        'graduation_year',
        'phone',
        'address',
        'resume_url',
        'profile_picture',
        'status',
        'alumni_flag',
        'career_path'
    ];

    // FIX: cast boolean agar kompatibel dengan PostgreSQL
    protected $casts = [
        'alumni_flag' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'student_id', 'student_id');
    }

    // FIX: pakai whereRaw agar tidak error di PostgreSQL pooler
    public function scopeAlumniFilter($query)
    {
        return $query->whereRaw('"alumni_flag" = true');
    }

    // FIX: pakai whereRaw dengan string tunggal khusus PostgreSQL
    public function scopeActive($query)
    {
        return $query->whereRaw('"status" = \'active\'');
    }

    public function getFullNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getGraduationLabelAttribute()
    {
        return $this->graduation_year . ' (' . ($this->alumni_flag ? 'Alumni' : 'Siswa Aktif') . ')';
    }

    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = strtolower($value);
    }
}