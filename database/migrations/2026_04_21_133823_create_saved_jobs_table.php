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
            
            // Relasi ke User
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke Job Listings (Sesuai nama tabel di project kamu)
            $table->unsignedBigInteger('job_listing_id'); 
            
            $table->timestamps();

            // Setup Foreign Key manual ke tabel job_listings
            $table->foreign('job_listing_id')
                  ->references('job_listing_id') // Kolom PK di tabel job_listings
                  ->on('job_listings')          // Nama tabel tujuan
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};