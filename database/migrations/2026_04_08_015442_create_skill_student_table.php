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
    Schema::create('skill_student', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke tabel skills (Pastikan tabel 'skills' sudah ada!)
        $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');

        // Relasi ke tabel students (Pakai cara manual karena nama ID-nya kustom)
        $table->unsignedBigInteger('student_id');
        $table->foreign('student_id')
              ->references('student_id') // Nama asli di tabel students
              ->on('students')
              ->onDelete('cascade');
              
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_student');
    }
};
