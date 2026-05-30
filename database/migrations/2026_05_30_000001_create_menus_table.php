<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // key unik, e.g. 'dashboard', 'jobs'
            $table->string('label');          // label tampil, e.g. 'Lowongan Kerja'
            $table->string('icon')->nullable(); // font awesome class
            $table->string('route')->nullable(); // route name
            $table->string('group')->nullable(); // 'main', 'management', dll
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('role_menu', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->primary(['role_id', 'menu_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_menu');
        Schema::dropIfExists('menus');
    }
};
