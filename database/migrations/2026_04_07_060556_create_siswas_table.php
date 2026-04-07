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
    Schema::create('siswas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('nisn')->unique();
        $table->string('nama_lengkap');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('tempat_tanggal_lahir');
        $table->string('jurusan');
        $table->year('tahun_lulus');
        $table->string('no_hp')->nullable();
        $table->text('alamat')->nullable();
        $table->string('cv_file')->nullable();
        $table->string('foto_profil')->nullable();
        $table->timestamps();
    });
}
};
