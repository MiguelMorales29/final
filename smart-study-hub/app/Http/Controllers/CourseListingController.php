<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseListingController extends Controller
{
    /**
     * Display available courses for students to browse and enroll.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get enrolled course IDs
        $enrolledCourses = $user->enrolledCourses()->pluck('course_id');
        
        // Get all applications for this user
        $allApplications = $user->courseApplications()
            ->with('course.teacher')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Group by course_id and get the latest status for each course
        $latestApplicationsByCourse = $allApplications->groupBy('course_id')->map(function ($applications) {
            return $applications->first(); // First one is the most recent due to orderBy desc
        });
        
        // Get applications that should NOT appear on browse page (pending, approved, rejected without cooldown)
        $excludeApplications = $latestApplicationsByCourse
            ->filter(function ($application) {
                return in_array($application->status, ['pending', 'approved']) || 
                       ($application->status === 'rejected' && is_null($application->cooldown_until));
            })
            ->pluck('course_id');
        
        // Get removed applications (with cooldown) - these SHOULD appear on browse page
        $removedApplications = $latestApplicationsByCourse
            ->filter(function ($application) {
                return !is_null($application->cooldown_until);
            })
            ->values();
        
        // Get removed course IDs to exclude from regular courses
        $removedCourseIds = $removedApplications->pluck('course_id');
        
        // Filter out enrolled courses, applications that should be excluded, AND removed applications
        $excludeCourseIds = $enrolledCourses->merge($excludeApplications)->merge($removedCourseIds)->unique();
        
        // Show regular courses (excluding enrolled and active applications)
        $courses = Course::with('teacher')
            ->whereNotIn('id', $excludeCourseIds)
            ->latest()
            ->get();
            
        $applications = $user->courseApplications()->pluck('status', 'course_id');
        
        // Get notification data for the dropdown
        $recentNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $user->unreadNotifications()->count();
        
        return view('student.courses', compact('courses', 'enrolledCourses', 'applications', 'removedApplications', 'recentNotifications', 'unreadCount'));
    }
}
