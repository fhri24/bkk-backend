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
        Schema::table('students', function (Blueprint $table) {
            // Cek apakah kolom gender sudah ada sebelum menambahkannya
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['L', 'P'])->nullable()->after('graduation_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};