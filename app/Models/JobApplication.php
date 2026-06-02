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
     * Relasi ke Siswa
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
     * Menangani link full URL Supabase maupun fallback nama file saja
     */
    public function getCvUrl()
    {
        if (!$this->additional_file) {
            return null;
        }

        // KONDISI 1: Jika di database sudah berupa URL utuh (berawalan http/https)
        // Langsung kembalikan datanya secara bersih tanpa embel-embel asset() atau Storage
        if (str_starts_with($this->additional_file, 'http://') || str_starts_with($this->additional_file, 'https://')) {
            return $this->additional_file;
        }

        // KONDISI 2: Jika di database hanya tersimpan nama filenya saja (misal: 1780386264_27.pdf)
        // Kita rakit langsung ke URL Public Supabase menggunakan config yang sudah dibuat
        $supabaseUrl = rtrim(config('services.supabase.url', ''), '/');
        $bucket      = config('services.supabase.bucket', 'bkk-storage');

        if (!empty($supabaseUrl)) {
            return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$this->additional_file}";
        }

        // Fallback terakhir ke local storage jika konfigurasi kosong
        return asset('storage/cv_applications/' . $this->additional_file);
    }
}