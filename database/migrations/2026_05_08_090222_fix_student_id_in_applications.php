<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('job_applications', function (Blueprint $table) {
        // Ini kuncinya: nambahin ->nullable() biar gak error SQLSTATE[23000]
        $table->unsignedBigInteger('student_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('job_applications', function (Blueprint $table) {
        $table->unsignedBigInteger('student_id')->nullable(false)->change();
    });
}
};
