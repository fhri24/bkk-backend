<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications';

    /**
     * PERBAIKAN: Beritahu Laravel bahwa Primary Key kamu
     * bukan 'id', tapi 'job_application_id'
     */
    protected $primaryKey = 'job_application_id';

    /**
     * Jika Primary Key kamu bukan integer auto-increment,
     * set ini ke false. Tapi biasanya tetap true untuk ID.
     */
    public $incrementing = true;

    protected $fillable = [
        'job_id',
        'student_id',
        'status',
        'application_date',
        'cover_letter',
        'additional_file',
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
        // Parameter: (Model, foreign_key, owner_key)
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Relasi ke Lowongan (Job)
     */
    public function job(): BelongsTo
    {
        // Parameter: (Model, foreign_key, owner_key)
        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }
}
