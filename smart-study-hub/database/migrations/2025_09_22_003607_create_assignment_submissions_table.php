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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->text('text_submission')->nullable();
            $table->json('file_submissions')->nullable(); // [{'file_path': '', 'file_name': '', 'file_size': ''}]
            $table->enum('status', ['draft', 'submitted', 'graded'])->default('draft');
            $table->integer('points_earned')->nullable();
            $table->text('feedback')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->datetime('graded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
