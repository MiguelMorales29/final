<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('course_applications')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            // Drop existing constraint if it exists
            DB::statement("ALTER TABLE course_applications DROP CONSTRAINT IF EXISTS course_applications_status_check");
            // Add new constraint with archived
            DB::statement("ALTER TABLE course_applications ADD CONSTRAINT course_applications_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'dropped', 'archived'))");
            DB::statement("ALTER TABLE course_applications ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            Schema::table('course_applications', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'dropped', 'archived'])->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('course_applications')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE course_applications DROP CONSTRAINT IF EXISTS course_applications_status_check");
            DB::statement("ALTER TABLE course_applications ADD CONSTRAINT course_applications_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'dropped'))");
            DB::statement("ALTER TABLE course_applications ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            Schema::table('course_applications', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'dropped'])->default('pending')->change();
            });
        }
    }
};