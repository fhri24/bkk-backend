<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('job_listings', function (Blueprint $table) {
        if (!Schema::hasColumn('job_listings', 'approval_status')) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('approved')
                  ->nullable(); // hapus ->after('stsatus')
        }
    });
}

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'approval_notes']);
        });
    }
};
