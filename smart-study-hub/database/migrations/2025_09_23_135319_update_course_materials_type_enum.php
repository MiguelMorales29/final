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
        if (!Schema::hasTable('course_materials')) {
            return;
        }

        // First, update any existing 'text' values to a valid enum value
        if (Schema::hasColumn('course_materials', 'type')) {
            DB::table('course_materials')
                ->where('type', 'text')
                ->update(['type' => 'file']);
        }

        // PostgreSQL-compatible: Check database driver
        if (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL, try to drop common constraint names, then alter column
            try {
                DB::statement("ALTER TABLE course_materials DROP CONSTRAINT IF EXISTS course_materials_type_check");
            } catch (\Exception $e) {}
            
            try {
                // Try to find and drop any check constraint on type column
                $constraints = DB::select("
                    SELECT conname 
                    FROM pg_constraint 
                    WHERE conrelid = 'course_materials'::regclass 
                    AND contype = 'c'
                ");
                foreach ($constraints as $constraint) {
                    try {
                        DB::statement("ALTER TABLE course_materials DROP CONSTRAINT IF EXISTS {$constraint->conname}");
                    } catch (\Exception $e) {}
                }
            } catch (\Exception $e) {}
            
            // Now alter the column and add new constraint
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