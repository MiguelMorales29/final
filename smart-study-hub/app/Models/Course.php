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

    /**
     * Get the terms for the course.
     */
    public function terms(): HasMany
    {
        return $this->hasMany(CourseTerm::class)->orderBy('order');
    }

    /**
     * Get the sub-terms for the course.
     */
    public function subTerms(): HasMany
    {
        return $this->hasMany(CourseSubTerm::class)->orderBy('order');
    }

    /**
     * Get the weeks for the course.
     */
    public function weeks(): HasMany
    {
        return $this->hasMany(CourseWeek::class)->orderBy('week_number');
    }

    /**
     * Get the materials for the course.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class)->orderBy('order');
    }

    /**
     * Get the assignments for the course.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderBy('created_at', 'desc');
    }
}
