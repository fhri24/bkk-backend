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
        // Kita gunakan 'job_listings' agar tidak bentrok dengan tabel 'jobs' sistem Laravel
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id('job_id'); // Primary Key sesuai ERD kamu
            
            // Relasi ke tabel companies
            // Kita gunakan unsignedBigInteger karena primary key di tabel companies adalah company_id
            $table->unsignedBigInteger('company_id'); 
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->string('job_type')->nullable(); // Misal: Full-time, Part-time
            
            // Gabungan opsi visibility yang lebih lengkap
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('visibility', ['public', 'alumni_only', 'private', 'internal'])->default('public'); 
            
            $table->boolean('is_active')->default(true);
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};