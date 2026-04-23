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
        Schema::table('news', function (Blueprint $table) {
            // Cek dulu biar nggak error kalau kolomnya ternyata udah ada
            if (!Schema::hasColumn('news', 'tags')) {
                $table->string('tags')->nullable()->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }
};
