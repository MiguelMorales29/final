<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'course_id',
        'teacher_id',
        'title',
        'content',
        'event_type',
        'custom_event_type',
        'event_date',
        'show_on_calendar',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'show_on_calendar' => 'boolean',
    ];

    /**
     * Get the course that owns the announcement.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the teacher that created the announcement.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the display name for the event type.
     */
    public function getEventTypeDisplayAttribute(): string
    {
        if ($this->event_type === 'other' && $this->custom_event_type) {
            return $this->custom_event_type;
        }
        
        return ucfirst($this->event_type);
    }
}
