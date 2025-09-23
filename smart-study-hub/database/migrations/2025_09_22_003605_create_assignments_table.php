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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_week_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('instructions')->nullable();
            $table->enum('submission_type', ['text', 'file', 'both'])->default('both');
            $table->json('allowed_file_types')->nullable(); // ['pdf', 'doc', 'docx', 'txt']
            $table->integer('max_file_size')->default(10); // MB
            $table->integer('max_files')->default(1);
            $table->datetime('due_date')->nullable();
            $table->integer('points')->default(100);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
