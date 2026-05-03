<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_listings', 'salary')) {
                $table->string('salary')->nullable()->after('location');
            }
            if (!Schema::hasColumn('job_listings', 'source')) {
                $table->enum('source', ['internal', 'kemenaker'])->default('internal')->after('salary');
            }
            if (!Schema::hasColumn('job_listings', 'skill_required')) {
                $table->string('skill_required')->nullable()->after('source');
            }
            if (!Schema::hasColumn('job_listings', 'benefits')) {
                $table->text('benefits')->nullable()->after('skill_required');
            }
            if (!Schema::hasColumn('job_listings', 'responsibilities')) {
                $table->text('responsibilities')->nullable()->after('benefits');
            }
            if (!Schema::hasColumn('job_listings', 'logo')) {
                $table->string('logo')->nullable()->after('responsibilities');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['salary', 'source', 'skill_required', 'benefits', 'responsibilities', 'logo']);
        });
    }
};