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

        // Modify the enum column to include all valid values
        DB::statement("ALTER TABLE course_materials MODIFY COLUMN type ENUM('video', 'file', 'link', 'text') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values if needed
        DB::statement("ALTER TABLE course_materials MODIFY COLUMN type ENUM('video', 'file', 'link') NOT NULL");
    }
};