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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id('event_registration_id');
            
            // Event reference (using string for event_id since it's dynamic)
            $table->string('event_id'); // e.g., 'jobfair2026', 'workshop-interview'
            
            // Registrant information
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('institution')->nullable(); // Institusi/Perusahaan
            $table->string('position')->nullable(); // Posisi/Program Studi
            
            // Registration status
            $table->enum('status', ['registered', 'confirmed', 'attended', 'cancelled'])->default('registered');
            
            // Admin notes
            $table->text('admin_notes')->nullable();
            
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
            
            // Index for searching
            $table->index('event_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
