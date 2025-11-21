<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendance')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            // Drop existing constraint if it exists
            DB::statement("ALTER TABLE attendance DROP CONSTRAINT IF EXISTS attendance_status_check");
            // Alter column to VARCHAR
            DB::statement("ALTER TABLE attendance ALTER COLUMN status TYPE VARCHAR(255)");
            // Add new constraint
            DB::statement("ALTER TABLE attendance ADD CONSTRAINT attendance_status_check CHECK (status IN ('present', 'absent', 'late'))");
            DB::statement("ALTER TABLE attendance ALTER COLUMN status SET DEFAULT 'present'");
        } else {
            Schema::table('attendance', function (Blueprint $table) {
                $table->enum('status', ['present', 'absent', 'late'])->default('present')->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('attendance')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE attendance DROP CONSTRAINT IF EXISTS attendance_status_check");
            DB::statement("ALTER TABLE attendance ALTER COLUMN status TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE attendance ADD CONSTRAINT attendance_status_check CHECK (status IN ('present', 'absent'))");
            DB::statement("ALTER TABLE attendance ALTER COLUMN status SET DEFAULT 'present'");
        } else {
            Schema::table('attendance', function (Blueprint $table) {
                $table->enum('status', ['present', 'absent'])->default('present')->change();
            });
        }
    }
};