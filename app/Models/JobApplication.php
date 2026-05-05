<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    protected $primaryKey = 'job_application_id';

    public $incrementing = true;

    protected $fillable = [
        'job_id',
        'student_id',
        'status',
        'application_date',
        'cover_letter', // Untuk data lama
        'notes',        // TAMBAHAN: Untuk nangkep input notes dari form baru
        'additional_file', // Untuk data lama
        'cv',           // TAMBAHAN: Untuk nangkep path file CV dari form baru
        'full_name',
        'email',
        'phone_number',
        'admin_notes'
    ];

    /**
     * Relasi ke Siswa
     */
    public function student(): BelongsTo
    {
        // Tetap pake student_id sebagai foreign key
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Relasi ke Lowongan (Job)
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }
}
