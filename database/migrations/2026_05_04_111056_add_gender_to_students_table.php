<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
<<<<<<< HEAD
<<<<<<< HEAD
=======
            // Cek dulu, kalau belum ada kolom gender, baru bikin
>>>>>>> 91f523ad5fbadeb80d7a2fe7f7dc3ea89ff5884c
=======
            // Cek dulu, kalau belum ada kolom gender, baru bikin
>>>>>>> 91f523ad5fbadeb80d7a2fe7f7dc3ea89ff5884c
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['L', 'P'])->nullable()->after('graduation_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
