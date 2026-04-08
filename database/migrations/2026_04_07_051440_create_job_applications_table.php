<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            // 1. ID Utama (Konsisten dengan model lain)
            $table->id('job_application_id'); 

            // 2. Relasi ke Lowongan (Pastikan merujuk ke 'job_listing_id' jika itu nama di tabel listings)
            $table->unsignedBigInteger('job_listing_id');
            $table->foreign('job_listing_id')->references('job_listing_id')->on('job_listings')->onDelete('cascade');

            // 3. Relasi ke Siswa
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');

            // 4. Detail Lamaran sesuai Tugas Nomor 9
            $table->text('cover_letter')->nullable(); // pesan dari siswa
            $table->string('additional_file')->nullable(); // file_pendukung (opsional)
            $table->text('admin_notes')->nullable(); // catatan_admin
            
            // 5. Status Lamaran (Sesuai bahasa tugas kamu)
            $table->enum('status', [
                'Seleksi Administrasi', 
                'Interview', 
                'Diterima', 
                'Ditolak'
            ])->default('Seleksi Administrasi');

            $table->timestamp('application_date')->useCurrent(); // tgl_melamar
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
