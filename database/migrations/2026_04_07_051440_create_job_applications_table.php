<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id(); // Ini Primary Key untuk tabel lamaran ini sendiri

            // Relasi ke Lowongan Kerja (Job Listings)
            $table->unsignedBigInteger('job_id');
            $table->foreign('job_id')->references('job_id')->on('job_listings')->onDelete('cascade');

            // Relasi ke Siswa (Students)
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');

            // Detail Lamaran
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('application_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
