<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
        $table->id('job_application_id'); 

        // Relasi ke Lowongan
        $table->unsignedBigInteger('job_id');
        $table->foreign('job_id')->references('job_id')->on('job_listings')->onDelete('cascade');

        // Relasi ke Siswa
        $table->unsignedBigInteger('student_id');
        $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');

        // Detail Lamaran
        $table->text('cover_letter')->nullable(); 
        $table->string('additional_file')->nullable(); 
        $table->text('admin_notes')->nullable(); 
        
        // --- UBAH BAGIAN INI AGAR SESUAI TUGAS ENDPOINT ---
        $table->enum('status', [
            'pending', 
            'review', 
            'accepted', 
            'rejected'
        ])->default('pending');
        // --------------------------------------------------

        $table->timestamp('application_date')->useCurrent(); 
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
