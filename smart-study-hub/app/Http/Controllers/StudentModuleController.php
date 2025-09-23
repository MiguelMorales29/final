<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseWeek;
use Illuminate\View\View;

class StudentModuleController extends Controller
{
    /**
     * Display the student's modules (course materials).
     */
    public function index(): View
    {
        $studentId = auth()->id();
        
        // Get all courses the student is enrolled in
        $courses = Course::whereHas('students', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->with([
            'terms.subTerms.weeks.materials',
            'terms.subTerms.weeks.assignments.submissions' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            },
            'teacher'
        ])
        ->get();

        return view('student.modules.index', compact('courses'));
    }

    /**
     * Display a specific material in detail.
     */
    public function showMaterial(CourseMaterial $material): View
    {
        $studentId = auth()->id();
        
        // Load the material with all necessary relationships
        $material->load(['course.teacher', 'week.subTerm.term']);
        
        // Check if student is enrolled in this course
        if (!$material->course->students()->where('student_id', $studentId)->exists()) {
            abort(403, 'Unauthorized access to this material.');
        }

        return view('student.materials.show', compact('material'));
    }

    /**
     * Display all materials for a specific week.
     */
    public function materials(Course $course, CourseWeek $week): View
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$course->students()->where('student_id', $studentId)->exists()) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Load the week with materials
        $week->load(['materials', 'subTerm.term', 'course.teacher']);
        
        return view('student.modules.materials', compact('course', 'week'));
    }

    /**
     * Display all materials for a course.
     */
    public function allMaterials(Course $course): View
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$course->students()->where('student_id', $studentId)->exists()) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Load all materials for the course with their relationships
        $materials = $course->materials()
            ->with(['week.subTerm.term'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type');

        return view('student.modules.all-materials', compact('course', 'materials'));
    }

    /**
     * Display all assignments for a course.
     */
    public function allAssignments(Course $course): View
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$course->students()->where('student_id', $studentId)->exists()) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Load all assignments for the course with student submissions
        $assignments = $course->assignments()
            ->with([
                'week.subTerm.term',
                'submissions' => function ($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('status');

        return view('student.modules.all-assignments', compact('course', 'assignments'));
    }
}
