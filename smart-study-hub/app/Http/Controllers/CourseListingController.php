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
        $courses = Course::with('teacher')->latest()->get();
        $enrolledCourses = auth()->user()->enrolledCourses()->pluck('course_id');
        
        return view('student.courses', compact('courses', 'enrolledCourses'));
    }
}
