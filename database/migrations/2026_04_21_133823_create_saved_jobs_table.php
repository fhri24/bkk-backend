<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // PASTIKAN BAGIAN INI SAMA DENGAN PRIMARY KEY DI JOB_LISTINGS
            // Kalau di job_listings pakai job_id, maka di sini harus pakai:
            $table->unsignedBigInteger('job_listing_id'); 
            
            
            $table->foreign('job_listing_id')
                  ->references('job_id') // Nama kolom primary di job_listings
                  ->on('job_listings')
                  ->onDelete('cascade');
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};