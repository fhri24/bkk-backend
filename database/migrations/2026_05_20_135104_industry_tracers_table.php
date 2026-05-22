<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industry_tracers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Informasi Perusahaan
            $table->string('nama_perusahaan');
            $table->string('jenis_perusahaan');
            $table->text('alamat_perusahaan');
            $table->string('bisnis_utama');

            // Informasi Responden
            $table->string('nama_responden');
            $table->string('jabatan_responden');
            $table->string('email_responden');

            // Penilaian Kemampuan Lulusan (skala 1-5)
            $table->tinyInteger('nilai_integritas')->nullable();
            $table->tinyInteger('nilai_keahlian')->nullable();
            $table->tinyInteger('nilai_bahasa_inggris')->nullable();
            $table->tinyInteger('nilai_teknologi')->nullable();
            $table->tinyInteger('nilai_komunikasi')->nullable();
            $table->tinyInteger('nilai_kerjasama')->nullable();
            $table->tinyInteger('nilai_analitis')->nullable();
            $table->tinyInteger('nilai_kepemimpinan')->nullable();
            $table->tinyInteger('nilai_tekanan')->nullable();

            // Masukan & Saran
            $table->text('saran')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
{
    Schema::table('industry_tracers', function (Blueprint $table) {
        $table->dropForeign(['student_id']); // ✅ bukan dropForeignKey
        $table->dropColumn('student_id');
    });
}
};