<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("UPDATE event_registrations SET status = 'registered' WHERE status = 'pending'");
            DB::statement("ALTER TABLE event_registrations ALTER COLUMN status TYPE varchar(255)");
            DB::statement("ALTER TABLE event_registrations ALTER COLUMN status SET DEFAULT 'registered'");
            DB::statement("ALTER TABLE event_registrations DROP CONSTRAINT IF EXISTS event_registrations_status_check");
            DB::statement("ALTER TABLE event_registrations ADD CONSTRAINT event_registrations_status_check CHECK (status IN ('registered','confirmed','attended','cancelled'))");

            return;
        }

        Schema::table('event_registrations', function (Blueprint $table) {
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
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("UPDATE event_registrations SET status = 'pending' WHERE status = 'registered'");
            DB::statement("ALTER TABLE event_registrations ALTER COLUMN status TYPE varchar(255)");
            DB::statement("ALTER TABLE event_registrations ALTER COLUMN status SET DEFAULT 'pending'");
            DB::statement("ALTER TABLE event_registrations DROP CONSTRAINT IF EXISTS event_registrations_status_check");
            DB::statement("ALTER TABLE event_registrations ADD CONSTRAINT event_registrations_status_check CHECK (status IN ('pending','confirmed','attended','cancelled'))");

            return;
        }

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'attended', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }
};
