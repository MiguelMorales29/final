<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialCompletion extends Model
{
    protected $fillable = [
        'student_id',
        'material_id',
        'course_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the student that completed the material.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the material that was completed.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(CourseMaterial::class, 'material_id');
    }

    /**
     * Get the course of the completed material.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
