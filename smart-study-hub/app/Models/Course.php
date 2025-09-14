<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'section',
        'student_capacity',
        'description',
        'teacher_id',
        'image',
    ];

    /**
     * Get the teacher that owns the course.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * Get the students enrolled in the course.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
                    ->withPivot('enrolled_at')
                    ->withTimestamps();
    }

    /**
     * Get the applications for the course.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(CourseApplication::class);
    }

    /**
     * Get pending applications for the course.
     */
    public function pendingApplications(): HasMany
    {
        return $this->hasMany(CourseApplication::class)->where('status', 'pending');
    }
}
