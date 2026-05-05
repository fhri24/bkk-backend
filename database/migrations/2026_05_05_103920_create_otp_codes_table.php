<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel otp_codes.
     */
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users, jika user dihapus maka OTP juga terhapus
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('otp', 6); // Tempat menyimpan kode OTP (biasanya 6 digit)
            $table->timestamp('expires_at'); // Waktu kedaluwarsa kode
            $table->boolean('valid')->default(true); // Status apakah kode masih bisa digunakan
            $table->timestamps(); // Menghasilkan created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};