<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'course_week_id',
        'title',
        'description',
        'instructions',
        'submission_type',
        'allowed_file_types',
        'max_file_size',
        'max_files',
        'due_date',
        'points',
        'max_attempts',
        'is_published',
    ];

    protected $casts = [
        'allowed_file_types' => 'array',
        'due_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function week(): BelongsTo
    {
        return $this->belongsTo(CourseWeek::class, 'course_week_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function studentSubmission($studentId): ?AssignmentSubmission
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}
