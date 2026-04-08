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
        Schema::create('students', function (Blueprint $table) {
            // Kita gunakan student_id sebagai Primary Key agar sinkron dengan Job Applications
            $table->id('student_id'); 
            
            // Relasi ke tabel users (Polymorphic)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Data Identitas (Gabungan dari versi Student & Siswa)
            $table->string('nis')->unique();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']); 
            $table->string('birth_info')->nullable(); // Contoh: "Jakarta, 01-01-2005"
            
            // Data Akademik
            $table->string('major'); // Jurusan
            $table->year('graduation_year');
            $table->boolean('alumni_flag')->default(false);
            
            // Data Kontak & Alamat
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Berkas Media
            $table->string('resume_url')->nullable(); // Untuk upload CV
            $table->string('profile_picture')->nullable(); // Untuk foto profil
            
            // Status Akun
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
