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
        'alumni_flag'
    ];

    // ✅ Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relasi ke Job Applications
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'student_id', 'student_id');
    }

    // ✅ Scope
    public function scopeAlumniFilter($query)
    {
        return $query->where('alumni_flag', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ✅ Accessor
    public function getFullNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getGraduationLabelAttribute()
    {
        return $this->graduation_year . ' (' . ($this->alumni_flag ? 'Alumni' : 'Siswa Aktif') . ')';
    }

    // ✅ Mutator
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = strtolower($value);
    }
}
