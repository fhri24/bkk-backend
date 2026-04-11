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
            // Kita gunakan student_id sebagai Primary Key
            $table->id('student_id'); 
            
            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Data Identitas
            $table->string('nis')->unique();
            $table->string('full_name');

            // --- PERBAIKAN DI SINI ---
            // Kita ubah menjadi nullable agar tidak error jika saat daftar gender belum diisi
            $table->string('gender')->nullable(); 
            // Atau jika tetap ingin enum, gunakan: $table->enum('gender', ['L', 'P'])->nullable();
            
            $table->string('birth_info')->nullable(); 
            
            // Data Akademik
            $table->string('major'); 
            $table->year('graduation_year');
            $table->boolean('alumni_flag')->default(false);
            
            // Data Kontak & Alamat
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Berkas Media
            $table->string('resume_url')->nullable(); 
            $table->string('profile_picture')->nullable(); 
            
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
