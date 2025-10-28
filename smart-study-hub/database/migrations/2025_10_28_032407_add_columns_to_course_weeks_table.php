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
        Schema::table('course_weeks', function (Blueprint $table) {
            $table->foreignId('course_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('course_term_id')->after('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_sub_term_id')->after('course_term_id')->constrained()->onDelete('cascade');
            $table->integer('week_number');
            $table->string('title');
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_weeks', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['course_term_id']);
            $table->dropForeign(['course_sub_term_id']);
            $table->dropColumn(['course_id', 'course_term_id', 'course_sub_term_id', 'week_number', 'title', 'description']);
        });
    }
};
