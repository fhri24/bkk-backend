<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop dulu kalau sudah ada (versi lama pakai student_id)
        Schema::dropIfExists('saved_jobs');

        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->timestamps();

            $table->unique(['user_id', 'job_id']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('job_id')
                  ->references('job_id')
                  ->on('job_listings')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};