<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip if table doesn't exist
        if (!Schema::hasTable('course_applications')) {
            return;
        }

        // PostgreSQL-compatible: Check database driver
        if (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL, drop constraint if exists
            DB::statement("DO \$\$ 
                BEGIN
                    ALTER TABLE course_applications DROP CONSTRAINT IF EXISTS course_applications_status_check;
                EXCEPTION WHEN OTHERS THEN NULL;
                END \$\$");
            
            // Add new constraint
            DB::statement("ALTER TABLE course_applications ADD CONSTRAINT course_applications_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'dropped'))");
            DB::statement("ALTER TABLE course_applications ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            // MySQL syntax
            DB::statement("ALTER TABLE course_applications MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'dropped') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE course_applications DROP CONSTRAINT IF EXISTS course_applications_status_check");
            DB::statement("ALTER TABLE course_applications ADD CONSTRAINT course_applications_status_check CHECK (status IN ('pending', 'approved', 'rejected'))");
            DB::statement("ALTER TABLE course_applications ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            DB::statement("ALTER TABLE course_applications MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        }
    }
};