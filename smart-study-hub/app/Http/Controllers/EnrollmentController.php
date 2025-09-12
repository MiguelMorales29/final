<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $enrollments = auth()->user()->enrollments()->with('course.teacher')->latest()->get();
        
        return view('student.my-courses', compact('enrollments'));
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
     * Admin's master view of all enrollments.
     */
    public function allEnrollments(): View
    {
        $enrollments = Enrollment::with(['student', 'course.teacher'])
            ->latest()
            ->paginate(20);
        
        return view('admin.enrollments.index', compact('enrollments'));
    }
}
