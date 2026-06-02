<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tip_steps');

        Schema::create('tip_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tip_id')->constrained()->onDelete('cascade');
            $table->integer('step_order')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tip_steps');
    }
};
