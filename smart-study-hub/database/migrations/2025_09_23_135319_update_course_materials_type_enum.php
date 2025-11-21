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
        // First, update any existing 'text' values to a valid enum value
        DB::table('course_materials')
            ->where('type', 'text')
            ->update(['type' => 'file']);

        // PostgreSQL-compatible: Check database driver
        if (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL, alter the column type and add check constraint
            DB::statement("ALTER TABLE course_materials DROP CONSTRAINT IF EXISTS course_materials_type_check");
            DB::statement("ALTER TABLE course_materials ALTER COLUMN type TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE course_materials ADD CONSTRAINT course_materials_type_check CHECK (type IN ('video', 'file', 'link', 'text'))");
            DB::statement("ALTER TABLE course_materials ALTER COLUMN type SET NOT NULL");
        } else {
            // MySQL syntax
            DB::statement("ALTER TABLE course_materials MODIFY COLUMN type ENUM('video', 'file', 'link', 'text') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE course_materials DROP CONSTRAINT IF EXISTS course_materials_type_check");
            DB::statement("ALTER TABLE course_materials ALTER COLUMN type TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE course_materials ADD CONSTRAINT course_materials_type_check CHECK (type IN ('video', 'file', 'link'))");
            DB::statement("ALTER TABLE course_materials ALTER COLUMN type SET NOT NULL");
        } else {
            DB::statement("ALTER TABLE course_materials MODIFY COLUMN type ENUM('video', 'file', 'link') NOT NULL");
        }
    }
};