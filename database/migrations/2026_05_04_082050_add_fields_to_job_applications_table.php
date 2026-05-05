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
        Schema::table('job_applications', function (Blueprint $table) {
            // Cek satu per satu apakah kolom sudah ada untuk menghindari error duplicate
            if (!Schema::hasColumn('job_applications', 'full_name')) {
                $table->string('full_name')->nullable()->after('additional_file');
            }
            
            if (!Schema::hasColumn('job_applications', 'email')) {
                $table->string('email')->nullable()->after('full_name');
            }
            
            if (!Schema::hasColumn('job_applications', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'email', 'phone_number']);
        });
    }
};