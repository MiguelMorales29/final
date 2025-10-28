<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'gender',
        'bio',
        'profile_picture',
        'role',
        'theme',
        'student_number',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    /**
     * Get the courses for the user (teacher).
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Get the enrollments for the user (student).
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Get the courses that the user is enrolled in (student).
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
                    ->withPivot('enrolled_at')
                    ->withTimestamps();
    }

    /**
     * Get the course applications for the user (student).
     */
    public function courseApplications(): HasMany
    {
        return $this->hasMany(CourseApplication::class, 'student_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications for the user.
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('read', false);
    }

    /**
     * Get the material completions for the user (student).
     */
    public function materialCompletions(): HasMany
    {
        return $this->hasMany(MaterialCompletion::class, 'student_id');
    }

    /**
     * Generate a unique 7-digit student number starting with 2.
     */
    public static function generateStudentNumber(): string
    {
        do {
            // Generate 6 random digits after the starting 2
            $randomDigits = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $studentNumber = '2' . $randomDigits;
        } while (self::where('student_number', $studentNumber)->exists());

        return $studentNumber;
    }
}
