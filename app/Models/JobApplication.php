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

    /**
     * Get CV file URL
     * Handles both old (applications/cvs/) and new (cv_applications/) paths
     */
    public function getCvUrl()
    {
        if (!$this->additional_file) {
            return null;
        }

        // Check jika file ada di cv_applications folder (format baru)
        $newPath = 'cv_applications/' . $this->additional_file;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($newPath)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->temporaryUrl(
                    $newPath,
                    now()->addHours(1)
                );
            } catch (\Exception $e) {
                // Fallback ke URL biasa jika temporaryUrl gagal
                return \Illuminate\Support\Facades\Storage::disk('public')->url($newPath);
            }
        }

        // Check jika file ada di applications/cvs folder (format lama)
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('applications/cvs/' . $this->additional_file)) {
            $oldPath = 'applications/cvs/' . $this->additional_file;
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->temporaryUrl(
                    $oldPath,
                    now()->addHours(1)
                );
            } catch (\Exception $e) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($oldPath);
            }
        }

        // Fallback: assume file ada di cv_applications folder
        try {
            return \Illuminate\Support\Facades\Storage::disk('public')->temporaryUrl(
                $newPath,
                now()->addHours(1)
            );
        } catch (\Exception $e) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($newPath);
        }
    }
}
