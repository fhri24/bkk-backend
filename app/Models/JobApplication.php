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
        'cover_letter',
        'notes',
        'additional_file',
        'cv',
        'full_name',
        'email',
        'phone_number',
        'admin_notes'
    ];

    protected $casts = [
        'application_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Siswa (Tetap dipertahankan)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id')->withDefault([
            'full_name' => 'Data Siswa/Pelamar',
        ]);
    }

    /**
     * Relasi ke Lowongan
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id', 'job_id')->withDefault([
            'title' => 'Lowongan Telah Dihapus',
        ]);
    }
}
