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
        Schema::table('course_materials', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('course_materials', 'course_id')) {
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('course_materials', 'course_week_id')) {
                $table->foreignId('course_week_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('course_materials', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('course_materials', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'type')) {
                $table->enum('type', ['video', 'file', 'link', 'text']);
            }
            if (!Schema::hasColumn('course_materials', 'content')) {
                $table->text('content')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'file_size')) {
                $table->bigInteger('file_size')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'youtube_url')) {
                $table->string('youtube_url')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'external_url')) {
                $table->string('external_url')->nullable();
            }
            if (!Schema::hasColumn('course_materials', 'order')) {
                $table->integer('order')->default(0);
            }
            if (!Schema::hasColumn('course_materials', 'is_required')) {
                $table->boolean('is_required')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['course_week_id']);
            $table->dropColumn([
                'course_id',
                'course_week_id', 
                'title',
                'description',
                'type',
                'content',
                'file_path',
                'file_name',
                'file_size',
                'youtube_url',
                'external_url',
                'order',
                'is_required'
            ]);
        });
    }
};
