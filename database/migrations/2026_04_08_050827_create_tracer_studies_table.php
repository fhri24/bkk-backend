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
    Schema::create('tracer_studies', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke tabel students menggunakan student_id
        $table->unsignedBigInteger('student_id');
        $table->foreign('student_id')
              ->references('student_id')
              ->on('students')
              ->onDelete('cascade');

        $table->enum('status_saat_ini', ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja']);
        $table->string('nama_instansi')->nullable(); // Bisa nama kantor atau kampus
        $table->date('tgl_mulai_masuk')->nullable();
        $table->decimal('pendapatan_bulanan', 15, 2)->nullable();
        $table->enum('keselarasan_jurusan', ['Sesuai', 'Tidak Sesuai'])->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
