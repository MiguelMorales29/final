<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMaterial extends Model
{
    protected $fillable = [
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
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the course that owns the material.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the week that owns the material.
     */
    public function week(): BelongsTo
    {
        return $this->belongsTo(CourseWeek::class, 'course_week_id');
    }

    /**
     * Get human readable file size.
     */
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get formatted file size (alias for getFileSizeHumanAttribute).
     */
    public function getFileSizeFormattedAttribute(): string
    {
        return $this->getFileSizeHumanAttribute();
    }
}


