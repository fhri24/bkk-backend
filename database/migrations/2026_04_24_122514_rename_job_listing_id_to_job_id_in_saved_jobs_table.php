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
    Schema::table('saved_jobs', function (Blueprint $table) {
        $table->renameColumn('job_listing_id', 'job_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('saved_jobs', function (Blueprint $table) {
        $table->renameColumn('job_id', 'job_listing_id');
    });
}
};
