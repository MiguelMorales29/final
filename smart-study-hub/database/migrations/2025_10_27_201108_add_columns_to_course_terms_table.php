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
        Schema::table('course_terms', function (Blueprint $table) {
            $table->foreignId('course_id')->after('id')->constrained()->onDelete('cascade');
            $table->string('name')->after('course_id');
            $table->text('description')->nullable()->after('name');
            $table->integer('total_weeks')->after('description');
            $table->integer('order')->default(1)->after('total_weeks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_terms', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'name', 'description', 'total_weeks', 'order']);
        });
    }
};
