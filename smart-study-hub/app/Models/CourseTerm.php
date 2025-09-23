<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseTerm extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'order',
        'description',
        'total_weeks',
    ];

    /**
     * Get the course that owns the term.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the sub-terms for the term.
     */
    public function subTerms(): HasMany
    {
        return $this->hasMany(CourseSubTerm::class)->orderBy('order');
    }

    /**
     * Get the weeks for the term.
     */
    public function weeks(): HasMany
    {
        return $this->hasMany(CourseWeek::class)->orderBy('week_number');
    }
}



