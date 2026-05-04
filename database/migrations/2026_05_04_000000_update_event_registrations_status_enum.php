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
        Schema::table('event_registrations', function (Blueprint $table) {
            // Change enum to use 'registered' instead of 'pending'
            $table->enum('status', ['registered', 'confirmed', 'attended', 'cancelled'])
                ->default('registered')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'attended', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }
};
