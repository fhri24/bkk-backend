<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tips', function (Blueprint $table) {
            $table->json('pro_tips')->nullable()->after('konten');
            $table->json('avoid_mistakes')->nullable()->after('pro_tips');
        });
    }

    public function down(): void
    {
        Schema::table('tips', function (Blueprint $table) {
            $table->dropColumn(['pro_tips', 'avoid_mistakes']);
        });
    }
};
