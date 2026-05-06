<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            // Bagian ->after('name') dihapus agar otomatis diletakkan di urutan terakhir
            $table->string('site_title')->nullable(); 
            $table->text('site_description')->nullable()->after('site_title');
            $table->string('tagline')->nullable()->after('site_description');
            $table->string('phone')->nullable()->after('tagline');
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            $table->string('facebook')->nullable()->after('address');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('twitter')->nullable()->after('instagram');
            $table->string('youtube')->nullable()->after('twitter');
            $table->string('logo')->nullable()->after('youtube');
        });
    }

    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'site_title', 'site_description', 'tagline',
                'phone', 'email', 'address',
                'facebook', 'instagram', 'twitter', 'youtube', 'logo',
            ]);
        });
    }
};