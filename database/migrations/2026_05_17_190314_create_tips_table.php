<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->enum('kategori', [
                'Interview',
                'Psikotes',
                'CV & Portofolio',
                'Dunia Kerja',
                'Wirausaha',
                'Lainnya',
            ]);
            $table->text('ringkasan');
            $table->longText('konten');
            $table->string('icon')->default('fas fa-lightbulb');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tips');
    }
};
