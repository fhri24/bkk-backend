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
        Schema::table('job_listings', function (Blueprint $table) {
            // Kita pakai foreignId supaya nembak ke ID di tabel majors
            // constrained('majors') artinya dia wajib ada di tabel majors
            $table->foreignId('major_id')
                  ->nullable()
                  ->after('company_id') // Gue simpen setelah company_id biar rapi
                  ->constrained('majors')
                  ->onDelete('set null'); // Kalau jurusannya dihapus, lowongannya jangan ikutan ilang, set null aja
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            // Hapus foreign key dulu baru hapus kolomnya
            $table->dropForeign(['major_id']);
            $table->dropColumn('major_id');
        });
    }
};
