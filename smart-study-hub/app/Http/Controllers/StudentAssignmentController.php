<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class StudentAssignmentController extends Controller
{
    /**
     * Display a listing of assignments for students organized by course structure.
     */
    public function index(): View
    {
        $studentId = auth()->id();
        
        // Get all courses the student is enrolled in
        $enrolledCourseIds = \App\Models\Enrollment::where('student_id', $studentId)->pluck('course_id');
        
        // Also include approved applications
        $approvedApplicationCourseIds = \App\Models\CourseApplication::where('student_id', $studentId)
            ->where('status', 'approved')
            ->pluck('course_id');
        
        $allCourseIds = $enrolledCourseIds->merge($approvedApplicationCourseIds)->unique();
        
        // Load courses with full structure including assignments
        $courses = Course::whereIn('id', $allCourseIds)
            ->with([
                'terms.subTerms.weeks.assignments' => function($query) {
                    $query->where('is_published', true);
                },
                'terms.subTerms.weeks.assignments.submissions' => function($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                },
                'teacher',
                'terms',
                'terms.subTerms',
                'terms.subTerms.weeks'
            ])
            ->get();

        return view('student.assignments.index', compact('courses'));
    }

    /**
     * Display the specified assignment for students.
     */
    public function show(Assignment $assignment): View
    {
        $student = auth()->user();
        
        // Verify the student is enrolled in the course
        if (!$assignment->course->students()->where('student_id', $student->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Verify the assignment is published
        if (!$assignment->is_published) {
            abort(404, 'Assignment not found.');
        }

        $assignment->load(['course', 'week.subTerm.term']);
        
        // Get or create student submission
        $submission = $assignment->studentSubmission($student->id);
        if (!$submission) {
            $submission = new AssignmentSubmission([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'status' => 'draft',
            ]);
        }

        // Calculate attempts information
        $submittedAttempts = $assignment->submissions()
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();
        
        $remainingAttempts = $assignment->max_attempts - $submittedAttempts;
        $canSubmit = $remainingAttempts > 0;

        return view('student.assignments.show', compact('assignment', 'submission', 'submittedAttempts', 'remainingAttempts', 'canSubmit'));
    }

    /**
     * Store or update a student submission.
     */
    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        $student = auth()->user();
        
        // Verify the student is enrolled in the course
        if (!$assignment->course->students()->where('student_id', $student->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Verify the assignment is published
        if (!$assignment->is_published) {
            abort(404, 'Assignment not found.');
        }

        // Check max attempts limit
        $existingSubmissions = $assignment->submissions()
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        if ($existingSubmissions >= $assignment->max_attempts) {
            return redirect()->back()
                ->withErrors(['error' => "You have reached the maximum number of attempts ({$assignment->max_attempts}) for this assignment."])
                ->withInput();
        }

        $request->validate([
            'text_submission' => 'nullable|string',
            'files' => 'nullable|array|max:' . $assignment->max_files,
            'files.*' => 'file|max:' . ($assignment->max_file_size * 1024), // Convert MB to KB
            'status' => 'required|in:draft,submitted',
        ]);

        // Validate file types if files are uploaded
        if ($request->hasFile('files') && $assignment->allowed_file_types) {
            $allowedTypes = $assignment->allowed_file_types;
            foreach ($request->file('files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, $allowedTypes)) {
                    return redirect()->back()
                        ->withErrors(['files' => 'File type ' . $extension . ' is not allowed. Allowed types: ' . implode(', ', $allowedTypes)])
                        ->withInput();
                }
            }
        }

        // Validate submission type
        if ($assignment->submission_type === 'text' && !$request->text_submission) {
            return redirect()->back()
                ->withErrors(['text_submission' => 'Text submission is required for this assignment.'])
                ->withInput();
        }

        if ($assignment->submission_type === 'file' && !$request->hasFile('files')) {
            return redirect()->back()
                ->withErrors(['files' => 'File upload is required for this assignment.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Get or create submission
            $submission = $assignment->studentSubmission($student->id);
            if (!$submission) {
                $submission = new AssignmentSubmission([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                ]);
            }

            // Update submission data
            $submission->text_submission = $request->text_submission ? Purify::clean($request->text_submission) : null;
            $submission->status = $request->status;

            // Handle file uploads
            if ($request->hasFile('files')) {
                $fileSubmissions = [];
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('assignment_submissions', 'public');
                    $fileSubmissions[] = [
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                    ];
                }
                $submission->file_submissions = $fileSubmissions;
            }

            // Set submission timestamp if submitting
            if ($request->status === 'submitted') {
                $submission->submitted_at = now();
            }

            $submission->save();

            DB::commit();

            $message = $request->status === 'submitted' 
                ? 'Assignment submitted successfully!' 
                : 'Assignment saved as draft.';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to submit assignment: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete a student submission.
     */
    public function destroy(Assignment $assignment): RedirectResponse
    {
        $student = auth()->user();
        
        // Verify the student is enrolled in the course
        if (!$assignment->course->students()->where('student_id', $student->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $submission = $assignment->studentSubmission($student->id);
        
        if ($submission) {
            // Delete uploaded files
            if ($submission->file_submissions) {
                foreach ($submission->file_submissions as $file) {
                    if (isset($file['file_path'])) {
                        Storage::disk('public')->delete($file['file_path']);
                    }
                }
            }
            
            $submission->delete();
        }

        return redirect()->back()->with('success', 'Submission deleted successfully.');
    }
}