<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'dropped', 'archived'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_applications', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'dropped'])->default('pending')->change();
        });
    }
};