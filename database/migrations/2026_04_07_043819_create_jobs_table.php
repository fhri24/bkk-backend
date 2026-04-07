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
        // Kita gunakan 'job_listings' agar tidak bentrok dengan tabel 'jobs' bawaan Laravel
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id('job_id'); // Primary Key sesuai ERD
            $table->unsignedBigInteger('company_id'); 
            
            // Relasi Foreign Key ke tabel companies
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->string('job_type')->nullable(); 
            
            // Enum untuk visibilitas lowongan
            $table->enum('visibility', ['public', 'private', 'internal'])->default('public'); 
            
            $table->boolean('is_active')->default(true);
            $table->dateTime('posted_at')->nullable();
            $table->dateTime('expired_at')->nullable();
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