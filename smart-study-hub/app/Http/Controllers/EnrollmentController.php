<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    /**
     * Student enrolls in a course.
     */
    public function store(Request $request, $courseId): RedirectResponse
    {
        $course = Course::findOrFail($courseId);
        
        // Check if already enrolled
        $existingEnrollment = Enrollment::where('student_id', auth()->id())
            ->where('course_id', $courseId)
            ->first();
            
        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }
        
        // Create enrollment
        Enrollment::create([
            'student_id' => auth()->id(),
            'course_id' => $courseId,
            'enrolled_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Successfully enrolled in ' . $course->title . '!');
    }

    /**
     * Student unenrolls from a course.
     */
    public function destroy(Request $request, $courseId): RedirectResponse
    {
        $course = Course::findOrFail($courseId);
        
        $enrollment = Enrollment::where('student_id', auth()->id())
            ->where('course_id', $courseId)
            ->first();
            
        if (!$enrollment) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }
        
        $enrollment->delete();
        
        return redirect()->back()->with('success', 'Successfully unenrolled from ' . $course->title . '.');
    }

    /**
     * Student's enrolled courses.
     */
    public function myCourses(): View
    {
        $user = auth()->user();
        
        // Get traditional enrollments
        $enrollments = $user->enrollments()->with('course.teacher')->latest()->get();
        
        // Get approved applications that DON'T have corresponding enrollments
        $approvedApplications = $user->courseApplications()
            ->where('status', 'approved')
            ->whereNull('cooldown_until')
            ->with('course.teacher')
            ->latest()
            ->get()
            ->filter(function ($application) use ($enrollments) {
                // Only include if there's no corresponding enrollment
                return !$enrollments->contains('course_id', $application->course_id);
            });
            
        // Get pending applications (show as pending courses) - only non-removed ones
        $pendingApplications = $user->courseApplications()
            ->where('status', 'pending')
            ->whereNull('cooldown_until')
            ->with('course.teacher')
            ->latest()
            ->get();
            
        // Get rejected applications (show as locked courses) - only non-removed ones
        $rejectedApplications = $user->courseApplications()
            ->where('status', 'rejected')
            ->whereNull('cooldown_until')
            ->with('course.teacher')
            ->latest()
            ->get();
            
        // Get dropped applications (treated as locked courses) - only non-removed ones
        $droppedApplications = $user->courseApplications()
            ->where('status', 'dropped')
            ->whereNull('cooldown_until')
            ->with('course.teacher')
            ->latest()
            ->get();
        
        // Get notification data for the dropdown
        $recentNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $user->unreadNotifications()->count();
        
        return view('student.my-courses', compact('enrollments', 'approvedApplications', 'pendingApplications', 'rejectedApplications', 'droppedApplications', 'recentNotifications', 'unreadCount'));
    }

    /**
     * Teacher's view of students enrolled in their course.
     */
    public function courseStudents($courseId): View
    {
        $course = Course::findOrFail($courseId);
        
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $enrollments = $course->enrollments()->with('student')->latest()->get();
        
        return view('teacher.courses.students', compact('course', 'enrollments'));
    }

    /**
     * Teacher drops a student from their course.
     */
    public function dropStudent(Request $request, $courseId, $studentId): RedirectResponse
    {
        $course = Course::findOrFail($courseId);
        
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $student = \App\Models\User::findOrFail($studentId);
        $message = $request->input('message', '');
        
        // Check if student has an enrollment record
        $enrollment = Enrollment::where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->first();
            
        // Check if student has an approved application
        $application = \App\Models\CourseApplication::where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->where('status', 'approved')
            ->first();
        
        if (!$enrollment && !$application) {
            return redirect()->back()->with('error', 'Student is not enrolled in this course.');
        }
        
        DB::transaction(function () use ($enrollment, $application, $course, $student, $message) {
            // Delete the enrollment if it exists
            if ($enrollment) {
                $enrollment->delete();
            }
            
            // Update application status to 'dropped' if it exists
            if ($application) {
                $application->update([
                    'status' => 'dropped',
                    'message' => $message,
                    'reviewed_at' => now()
                ]);
            }
            
            // Create notification message with custom reason if provided
            $notificationMessage = "You have been removed from the course '{$course->title}' by your teacher.";
            if (!empty($message)) {
                $notificationMessage .= "\n\nReason: {$message}";
            }
            
            // Notify the student
            Notification::create([
                'user_id' => $student->id,
                'type' => 'course_dropped',
                'title' => 'Removed from Course',
                'message' => $notificationMessage,
                'data' => [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'teacher_name' => auth()->user()->name,
                    'reason' => $message
                ]
            ]);
        });
        
        $successMessage = "Successfully dropped {$student->name} from {$course->title}.";
        if (!empty($message)) {
            $successMessage .= " The student has been notified with your reason.";
        }
        
        return redirect()->back()->with('success', $successMessage);
    }

}
