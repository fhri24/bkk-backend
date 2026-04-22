<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    use HasFactory;

    protected $table = 'saved_jobs';

    /**
     * PERBAIKAN: Masukkan 'job_listing_id' ke fillable 
     * agar bisa disimpan ke database.
     */
    protected $fillable = [
        'user_id', 
        'job_listing_id'
    ];

    /**
     * Relasi ke model Job (atau JobListing)
     */
    public function job()
    {
        /**
         * PERBAIKAN: 
         * Parameter 1: Model tujuan (Job::class)
         * Parameter 2: Foreign Key di tabel ini (job_listing_id)
         * Parameter 3: Primary Key di tabel tujuan (job_listing_id)
         */
        return $this->belongsTo(Job::class, 'job_listing_id', 'job_listing_id');
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}