<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSubTerm extends Model
{
    protected $fillable = [
        'course_id',
        'course_term_id',
        'title',
        'description',
        'order',
    ];

    /**
     * Get the course that owns the sub-term.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the term that owns the sub-term.
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(CourseTerm::class, 'course_term_id');
    }

    /**
     * Get the weeks for the sub-term.
     */
    public function weeks(): HasMany
    {
        return $this->hasMany(CourseWeek::class)->orderBy('week_number');
    }
}
