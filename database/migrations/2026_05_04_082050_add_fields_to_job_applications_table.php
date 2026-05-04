<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Kita bungkus pake check biar ga error duplicate lagi
            if (!Schema::hasColumn('job_applications', 'full_name')) {
                $table->string('full_name')->nullable()->after('additional_file');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
             if (Schema::hasColumn('job_applications', 'full_name')) {
                $table->dropColumn('full_name');
            }
        });
    }
};
