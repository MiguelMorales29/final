<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseWeek;
use App\Models\MaterialCompletion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        // Extract text content for Smart Buddy (all material types)
        $textContent = '';
        $parts = [];
        // Title and basic context
        if (!empty($material->title)) {
            $parts[] = 'Title: ' . $material->title;
        }
        if ($material->week && $material->week->subTerm && $material->week->subTerm->term) {
            $parts[] = 'Context: ' . $material->week->subTerm->term->name . ' • ' . $material->week->subTerm->title . ' • ' . $material->week->title;
        }
        // Description (rich text)
        if (!empty($material->description)) {
            $parts[] = strip_tags($material->description);
        }
        // Primary content by type
        if ($material->type === 'text' && $material->content) {
            $parts[] = strip_tags($material->content);
        } elseif ($material->type === 'file' && $material->content) {
            // Include filename and any available meta; actual file OCR is out of scope here
            $parts[] = 'Document: ' . basename($material->content);
        } elseif ($material->type === 'video' && $material->content) {
            $parts[] = 'Video source: ' . $material->content;
        } elseif ($material->type === 'link' && $material->content) {
            $parts[] = 'External link: ' . $material->content;
        }
        $textContent = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter($parts))));

        return view('student.materials.show', compact('material', 'textContent'));
    }

    /**
     * View PDF/PPT material in a separate window
     */
    public function viewPdf(CourseMaterial $material)
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$material->course->students()->where('student_id', $studentId)->exists()) {
            abort(403, 'Unauthorized access to this material.');
        }

        // Check if material is a file (PDF or PPT)
        if ($material->type !== 'file') {
            abort(404, 'This material is not a PDF or PPT file.');
        }

        $filePath = storage_path('app/public/' . $material->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Get the file extension
        $fileExtension = strtolower(pathinfo($material->file_path, PATHINFO_EXTENSION));
        
        // For PDF files, serve directly with proper headers
        if ($fileExtension === 'pdf') {
            return response()->file($filePath, [
                'Content-Type' => mime_content_type($filePath),
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }
        
        // For other files (PPT, DOC, etc.), serve with appropriate MIME type
        return response()->file($filePath, [
            'Content-Type' => mime_content_type($filePath),
            'Cache-Control' => 'public, max-age=3600',
            'Content-Disposition' => 'inline; filename="' . basename($material->file_path) . '"',
        ]);
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

    /**
     * Mark a material as done.
     */
    public function markAsDone(CourseMaterial $material): JsonResponse
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$material->course->students()->where('student_id', $studentId)->exists()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Check if already marked as done
        $existing = MaterialCompletion::where('student_id', $studentId)
            ->where('material_id', $material->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Material already marked as done',
                'completion' => $existing
            ]);
        }

        // Create completion record
        $completion = MaterialCompletion::create([
            'student_id' => $studentId,
            'material_id' => $material->id,
            'course_id' => $material->course_id,
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Material marked as done successfully',
            'completion' => $completion
        ]);
    }

    /**
     * Unmark a material as done.
     */
    public function unmarkAsDone(CourseMaterial $material): JsonResponse
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$material->course->students()->where('student_id', $studentId)->exists()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Delete completion record
        MaterialCompletion::where('student_id', $studentId)
            ->where('material_id', $material->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material unmarked successfully'
        ]);
    }

    /**
     * Get completion status for a material.
     */
    public function getCompletionStatus(CourseMaterial $material): JsonResponse
    {
        $studentId = auth()->id();
        
        $completion = MaterialCompletion::where('student_id', $studentId)
            ->where('material_id', $material->id)
            ->first();

        return response()->json([
            'is_completed' => $completion !== null,
            'completed_at' => $completion ? $completion->completed_at : null
        ]);
    }

    /**
     * Calculate course progress for a student.
     */
    public function getCourseProgress(Course $course): JsonResponse
    {
        $studentId = auth()->id();
        
        // Check if student is enrolled in this course
        if (!$course->students()->where('student_id', $studentId)->exists()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Get all materials for this course
        $totalMaterials = $course->materials()->count();
        
        // Get completed materials
        $completedMaterials = MaterialCompletion::where('student_id', $studentId)
            ->where('course_id', $course->id)
            ->count();
        
        // Get submitted assignments
        $totalAssignments = $course->assignments()->count();
        $submittedAssignments = \App\Models\AssignmentSubmission::whereHas('assignment', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })
        ->where('student_id', $studentId)
        ->where('status', '!=', 'draft')
        ->count();

        // Calculate total progress
        $totalItems = $totalMaterials + $totalAssignments;
        $completedItems = $completedMaterials + $submittedAssignments;
        $progressPercentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100, 2) : 0;

        return response()->json([
            'total_materials' => $totalMaterials,
            'completed_materials' => $completedMaterials,
            'total_assignments' => $totalAssignments,
            'submitted_assignments' => $submittedAssignments,
            'total_items' => $totalItems,
            'completed_items' => $completedItems,
            'progress_percentage' => $progressPercentage
        ]);
    }
}
