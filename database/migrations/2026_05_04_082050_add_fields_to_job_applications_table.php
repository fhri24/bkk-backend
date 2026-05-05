<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
<<<<<<< HEAD
<<<<<<< HEAD
            if (!Schema::hasColumn('job_applications', 'full_name')) {
                $table->string('full_name')->nullable()->after('additional_file');
            }
            if (!Schema::hasColumn('job_applications', 'email')) {
                $table->string('email')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('job_applications', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('email');
            }
=======
=======
>>>>>>> 91f523ad5fbadeb80d7a2fe7f7dc3ea89ff5884c
            // Kita bungkus pake check biar ga error duplicate lagi
            if (!Schema::hasColumn('job_applications', 'full_name')) {
                $table->string('full_name')->nullable()->after('additional_file');
            }
<<<<<<< HEAD
>>>>>>> 91f523ad5fbadeb80d7a2fe7f7dc3ea89ff5884c
=======
>>>>>>> 91f523ad5fbadeb80d7a2fe7f7dc3ea89ff5884c
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
             if (Schema::hasColumn('job_applications', 'full_name')) {
                $table->dropColumn('full_name');
            }
        });
    }
};
