<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseWeek extends Model
{
    protected $fillable = [
        'course_id',
        'course_term_id',
        'course_sub_term_id',
        'week_number',
        'title',
        'description',
        'instructions',
    ];

    /**
     * Get the course that owns the week.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the term that owns the week.
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(CourseTerm::class, 'course_term_id');
    }

    /**
     * Get the sub-term that owns the week.
     */
    public function subTerm(): BelongsTo
    {
        return $this->belongsTo(CourseSubTerm::class, 'course_sub_term_id');
    }

    /**
     * Get the materials for the week.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class)->orderBy('order');
    }

    /**
     * Get the assignments for the week.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'course_week_id')->orderBy('created_at', 'desc');
    }
}
