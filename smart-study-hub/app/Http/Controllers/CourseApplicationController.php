<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseApplication;
use App\Models\Enrollment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseApplicationController extends Controller
{
    /**
     * Apply for a course
     */
    public function apply(Request $request, Course $course)
    {
        $student = Auth::user();

        // Check if already enrolled
        if ($student->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Check if already applied
        if ($student->courseApplications()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You have already applied for this course.');
        }

        // Create application
        CourseApplication::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'pending',
        ]);

        // Notify teacher
        $this->createNotification(
            $course->teacher_id,
            'course_application',
            'New Course Application',
            "{$student->name} has applied for your course: {$course->title}",
            [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'student_id' => $student->id,
                'student_name' => $student->name
            ]
        );

        return back()->with('success', 'Application submitted successfully!');
    }

    /**
     * Approve a course application
     */
    public function approve(Request $request, CourseApplication $application)
    {
        $teacher = Auth::user();

        // Check if teacher owns the course
        if ($application->course->teacher_id !== $teacher->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $message = $request->input('message');
        
        DB::transaction(function () use ($application, $message) {
            // Update application status
            $application->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'message' => $message,
            ]);

            // Create enrollment only if it doesn't already exist
            $existingEnrollment = Enrollment::where('student_id', $application->student_id)
                ->where('course_id', $application->course_id)
                ->first();

            if (!$existingEnrollment) {
                Enrollment::create([
                    'student_id' => $application->student_id,
                    'course_id' => $application->course_id,
                    'enrolled_at' => now(),
                ]);
            }

            // Notify student
            $this->createNotification(
                $application->student_id,
                'course_approved',
                'Course Application Approved',
                "Your application for '{$application->course->title}' has been approved!",
                [
                    'course_id' => $application->course_id,
                    'course_title' => $application->course->title,
                    'student_name' => $application->student->name
                ]
            );
        });

        return back()->with('success', 'Application approved successfully!');
    }

    /**
     * Reject a course application
     */
    public function reject(Request $request, CourseApplication $application)
    {
        $teacher = Auth::user();

        // Check if teacher owns the course
        if ($application->course->teacher_id !== $teacher->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $message = $request->input('message');
        
        // Update application status
        $application->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'message' => $message,
        ]);

        // Notify student
        $this->createNotification(
            $application->student_id,
            'course_rejected',
            'Course Application Rejected',
            "Your application for '{$application->course->title}' has been rejected.",
            [
                'course_id' => $application->course_id,
                'course_title' => $application->course->title,
                'student_name' => $application->student->name
            ]
        );

        return back()->with('success', 'Application rejected.');
    }

    /**
     * Remove a rejected/dropped course from student's view
     */
    public function removeFromView(CourseApplication $application)
    {
        $student = Auth::user();

        // Check if the application belongs to the student
        if ($application->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if the application is rejected or dropped
        if (!in_array($application->status, ['rejected', 'dropped'])) {
            return back()->with('error', 'This course cannot be removed from your view.');
        }

        // Set cooldown period (24 hours from now)
        $application->update([
            'cooldown_until' => now()->addHours(24)
        ]);

        return back()->with('success', 'Course removed from your view. You can reapply after 24 hours.');
    }

    /**
     * Archive a single application
     */
    public function archive(CourseApplication $application)
    {
        $teacher = Auth::user();

        // Check if the application belongs to the teacher's course
        if ($application->course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access.');
        }

        $application->update([
            'original_status' => $application->status,
            'status' => 'archived'
        ]);

        return back()->with('success', 'Application archived successfully.');
    }

    /**
     * Archive multiple applications
     */
    public function bulkArchive(Request $request)
    {
        $teacher = Auth::user();
        $applicationIds = $request->input('application_ids', []);

        if (empty($applicationIds)) {
            return back()->with('error', 'No applications selected.');
        }

        $applications = CourseApplication::whereIn('id', $applicationIds)
            ->whereHas('course', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->get();

        $count = $applications->count();
        $applications->each(function ($application) {
            $application->update([
                'original_status' => $application->status,
                'status' => 'archived'
            ]);
        });

        return back()->with('success', "Successfully archived {$count} application(s).");
    }

    /**
     * Archive all applications
     */
    public function archiveAll()
    {
        $teacher = Auth::user();

        $applications = CourseApplication::whereHas('course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->where('status', '!=', 'archived')->get();

        $count = $applications->count();
        $applications->each(function ($application) {
            $application->update([
                'original_status' => $application->status,
                'status' => 'archived'
            ]);
        });

        return back()->with('success', "Successfully archived {$count} application(s).");
    }

    /**
     * Get applications for teacher's courses
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $showArchived = $request->boolean('show_archived', false);
        
        $applications = CourseApplication::with(['student', 'course'])
            ->whereHas('course', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->when(!$showArchived, function ($query) {
                $query->where('status', '!=', 'archived');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recent notifications for the notification dropdown
        $recentNotifications = $teacher->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $teacher->unreadNotifications()->count();

        return view('teacher.applications.index', compact('applications', 'recentNotifications', 'unreadCount', 'showArchived'));
    }

    /**
     * Create a notification
     */
    private function createNotification($userId, $type, $title, $message, $data = [])
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}