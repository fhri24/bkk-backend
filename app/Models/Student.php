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
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Student ke Job Applications (satu siswa bisa melamar banyak pekerjaan)
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'student_id', 'student_id');
    }
}
    