<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Penting untuk insert data

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ID internal: 'super_admin'
            $table->string('display_name');  // Tampilan: 'Super Admin'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Langsung isi daftar role agar kamu tidak perlu input manual
        DB::table('roles')->insert([
    ['name' => 'super_admin',    'display_name' => 'Super Admin',    'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'admin_bkk',      'display_name' => 'Admin BKK',      'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'kepala_bkk',     'display_name' => 'Kepala BKK',     'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'kepala_sekolah', 'display_name' => 'Kepala Sekolah', 'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'siswa',          'display_name' => 'Siswa',          'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'perusahaan',     'display_name' => 'Perusahaan',     'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'alumni',         'display_name' => 'Alumni',         'description' => null, 'created_at' => now(), 'updated_at' => now()],
    ['name' => 'publik',         'display_name' => 'Publik',         'description' => null, 'created_at' => now(), 'updated_at' => now()],
]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
