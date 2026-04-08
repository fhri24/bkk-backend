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
    Schema::create('portfolios', function (Blueprint $table) {
        $table->id();
        
        // 1. Pastikan kolom ini tipenya sama (Unsigned Big Integer)
        $table->unsignedBigInteger('student_id');
        
        $table->string('judul_projek');
        $table->text('deskripsi');
        $table->string('link_projek')->nullable();
        $table->timestamps();

        // 2. Point ke 'student_id' di tabel 'students', BUKAN ke 'id'
        $table->foreign('student_id')
              ->references('student_id') // <--- INI KUNCINYA (Harus sama dengan yang di students)
              ->on('students')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
