<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tracer_studies');

        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();

            // Relasi ke students (nullable karena publik/alumni bisa tidak punya student)
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')
                  ->references('student_id')
                  ->on('students')
                  ->onDelete('cascade');

            // Relasi ke users
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // ===== IDENTITAS PRIBADI =====
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            // ===== DATA SEKOLAH =====
            $table->year('tahun_lulus')->nullable();
            $table->string('jurusan')->nullable();

            // ===== STATUS KEGIATAN =====
            $table->enum('status_saat_ini', ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja']);

            // ===== DETAIL PEKERJAAN (Bekerja) =====
            $table->string('lokasi_kerja')->nullable();        // Dalam Negeri / Luar Negeri
            $table->string('nama_instansi')->nullable();       // Nama Perusahaan
            $table->string('alamat_perusahaan')->nullable();
            $table->string('posisi_jabatan')->nullable();
            $table->date('tmt_bekerja')->nullable();
            $table->string('range_gaji')->nullable();
            $table->decimal('pendapatan_bulanan', 15, 2)->nullable();
            $table->enum('keselarasan_jurusan', ['Sesuai', 'Tidak Sesuai'])->nullable();

            // ===== DETAIL KULIAH (Kuliah) =====
            $table->string('status_pt')->nullable();           // PTN / PTS
            $table->string('nama_pt')->nullable();             // Nama PT & Fakultas
            $table->string('jurusan_pt')->nullable();
            $table->string('jenjang_kuliah')->nullable();      // D1/D2/D3/S1/S2
            $table->date('tmt_kuliah')->nullable();

            // ===== DETAIL WIRAUSAHA (Wirausaha) =====
            $table->string('nama_usaha')->nullable();
            $table->string('status_usaha')->nullable();        // Milik Pribadi / Patungan
            $table->date('tmt_wirausaha')->nullable();
            $table->string('omzet_per_bulan')->nullable();

            // ===== DETAIL BELUM BEKERJA =====
            $table->string('detail_kegiatan')->nullable();     // Mencari pekerjaan / Mencari kuliah / Lainnya
            $table->string('detail_kegiatan_lainnya')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};