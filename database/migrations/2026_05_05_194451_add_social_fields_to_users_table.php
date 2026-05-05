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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom untuk fitur Social Login
            $table->string('social_id')->nullable()->after('password');
            $table->string('social_provider')->nullable()->after('social_id');
            $table->string('avatar')->nullable()->after('social_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migrasi dibatalkan
            $table->dropColumn(['social_id', 'social_provider', 'avatar']);
        });
    }
};