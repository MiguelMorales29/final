<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'original_status',
        'message',
        'reviewed_at',
        'cooldown_until',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'cooldown_until' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isDropped(): bool
    {
        return $this->status === 'dropped';
    }

    public function isOnCooldown(): bool
    {
        return $this->cooldown_until && $this->cooldown_until->isFuture();
    }

    public function canReapply(): bool
    {
        return in_array($this->status, ['rejected', 'dropped']) && !$this->isOnCooldown();
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }
}