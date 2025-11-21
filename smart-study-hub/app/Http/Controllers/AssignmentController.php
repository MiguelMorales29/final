<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\CourseWeek;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments for teachers.
     */
    public function index(): View
    {
        $courses = auth()->user()->courses()
            ->with(['assignments.week', 'assignments.submissions', 'terms.weeks'])
            ->get()
            ->map(function ($course) {
                $course->assignments_count = $course->assignments->count();
                $course->published_assignments_count = $course->assignments->where('is_published', true)->count();
                $course->draft_assignments_count = $course->assignments->where('is_published', false)->count();
                $course->total_submissions = $course->assignments->sum(function ($assignment) {
                    return $assignment->submissions->count();
                });
                return $course;
            });

        return view('teacher.assignments.index', compact('courses'));
    }

    /**
     * Display assignments for a specific course.
     */
    public function courseAssignments(Course $course): View
    {
        // Verify the course belongs to the teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $assignments = $course->assignments()
            ->with(['week.term', 'submissions'])
            ->orderBy('created_at', 'desc')
            ->get();

        $course->load([
            'terms.subTerms.weeks.assignments' => function ($query) {
                $query->with('submissions')->orderBy('due_date')->orderBy('created_at', 'desc');
            },
            'terms.subTerms.weeks' => function ($query) {
                $query->orderBy('week_number');
            },
            'terms.subTerms' => function ($query) {
                $query->orderBy('order');
            },
            'terms' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        return view('teacher.assignments.course-assignments', compact('course', 'assignments'));
    }

    public function assignmentsPreview(Course $course)
    {
        // Verify the course belongs to the teacher
        if ($course->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $assignments = $course->assignments()
            ->with(['week.term', 'submissions'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $assignments->count(),
            'published' => $assignments->where('is_published', true)->count(),
            'drafts' => $assignments->where('is_published', false)->count(),
            'submissions' => $assignments->sum(function ($assignment) {
                return $assignment->submissions->count();
            })
        ];

        $assignmentsData = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'points' => $assignment->points,
                'max_attempts' => $assignment->max_attempts,
                'due_date' => $assignment->due_date,
                'is_published' => $assignment->is_published,
                'term_name' => $assignment->week && $assignment->week->term ? $assignment->week->term->name : 'Unassigned',
                'week_title' => $assignment->week ? $assignment->week->title : 'Unassigned',
            ];
        });

        return response()->json([
            'stats' => $stats,
            'assignments' => $assignmentsData
        ]);
    }

    public function getAssignmentEditData(Assignment $assignment)
    {
        try {
            // Load necessary relationships
            $assignment->load(['course', 'week.term']);
            
            // Check if course exists
            if (!$assignment->course) {
                return response()->json(['error' => 'Assignment course not found.'], 404);
            }
            
            // Ensure the assignment belongs to the authenticated teacher
            if ($assignment->course->teacher_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized access.'], 403);
            }

            // Get term and week IDs safely
            $termId = null;
            $weekId = $assignment->course_week_id;
            
            if ($assignment->week && $assignment->week->term) {
                $termId = $assignment->week->term->id;
            }

            $data = [
                'id' => $assignment->id,
                'course_title' => $assignment->course->title,
                'course_id' => $assignment->course_id,
                'term_id' => $termId,
                'week_id' => $weekId,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'instructions' => $assignment->instructions,
                'points' => $assignment->points,
                'max_attempts' => $assignment->max_attempts,
                'due_date' => $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : null,
                'is_published' => $assignment->is_published,
                'submission_type' => $assignment->submission_type,
                'allowed_file_types' => $assignment->allowed_file_types ?? [],
                'max_file_size' => $assignment->max_file_size,
                'max_files' => $assignment->max_files,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error fetching assignment edit data: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading assignment data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show course selection for assignment upload.
     */
    public function selectCourse(): View
    {
        $courses = auth()->user()->courses()
            ->with(['terms.weeks', 'assignments'])
            ->get();
        
        return view('teacher.assignments.select-course', compact('courses'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(Course $course): View
    {
        // Verify the course belongs to the teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $course->load([
            'terms.subTerms.weeks.assignments' => function ($query) {
                $query->withCount('submissions')
                    ->orderBy('due_date')
                    ->orderBy('created_at', 'desc');
            },
            'terms.subTerms.weeks' => function ($query) {
                $query->orderBy('week_number');
            },
            'terms.subTerms' => function ($query) {
                $query->orderBy('order');
            },
            'terms' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        $course->loadCount('enrollments');

        $allAssignments = $course->assignments()
            ->withCount('submissions')
            ->with(['week.term'])
            ->orderBy('created_at', 'desc')
            ->get();

        $assignmentStats = [
            'total' => $allAssignments->count(),
            'published' => $allAssignments->where('is_published', true)->count(),
            'drafts' => $allAssignments->where('is_published', false)->count(),
            'submissions' => $allAssignments->sum(fn ($assignment) => $assignment->submissions_count ?? 0),
        ];

        $unassignedAssignments = $allAssignments->whereNull('course_week_id')->values();

        $courseStructure = $course->terms->map(function ($term) {
            $weeks = collect();
            foreach ($term->subTerms as $subTerm) {
                foreach ($subTerm->weeks as $week) {
                    $weeks->push([
                        'id' => $week->id,
                        'title' => $week->title,
                        'sub_term' => $subTerm->title,
                        'sub_term_id' => $subTerm->id,
                        'term_id' => $term->id,
                    ]);
                }
            }

            return [
                'id' => $term->id,
                'name' => $term->name,
                'weeks' => $weeks->toArray(),
            ];
        });

        $courseEnrollmentCount = $course->enrollments_count ?? $course->enrollments()->count();

        return view('teacher.assignments.create', compact('course', 'courseStructure', 'assignmentStats', 'unassignedAssignments', 'courseEnrollmentCount'));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'course_week_id' => 'nullable|exists:course_weeks,id',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string|max:5000',
            'instructions' => 'nullable|string|max:5000',
            'submission_type' => 'required|in:text,file,both',
            'allowed_file_types' => 'nullable|array',
            'allowed_file_types.*' => 'string|in:pdf,doc,docx,txt,jpg,jpeg,png,gif',
            'max_file_size' => 'required|integer|min:1|max:100',
            'max_files' => 'required|integer|min:1|max:10',
            'due_date' => 'required|date|after:now',
            'points' => 'required|integer|min:1|max:1000',
            'max_attempts' => 'required|integer|min:1|max:10',
            'is_published' => 'boolean',
        ]);

        // Verify the course belongs to the teacher
        $course = Course::where('id', $request->course_id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        // If week is specified, verify it belongs to the course
        if ($request->course_week_id) {
            $week = CourseWeek::where('id', $request->course_week_id)
                ->where('course_id', $course->id)
                ->firstOrFail();
        }

        $assignment = Assignment::create([
            'course_id' => $request->course_id,
            'course_week_id' => $request->course_week_id,
            'title' => $request->title,
            'description' => $request->description ? Purify::clean($request->description) : null,
            'instructions' => $request->instructions ? Purify::clean($request->instructions) : null,
            'submission_type' => $request->submission_type,
            'allowed_file_types' => $request->allowed_file_types,
            'max_file_size' => $request->max_file_size,
            'max_files' => $request->max_files,
            'due_date' => $request->due_date,
            'points' => $request->points,
            'max_attempts' => $request->max_attempts,
            'is_published' => $request->boolean('is_published', false),
        ]);

        // Send notifications to all enrolled students
        if ($assignment->is_published) {
            $students = $course->enrollments()->with('student')->get();
            
            foreach ($students as $enrollment) {
                $student = $enrollment->student;
                
                $student->notifications()->create([
                    'type' => 'assignment',
                    'title' => 'New Assignment',
                    'message' => $assignment->title,
                    'data' => json_encode([
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'assignment_id' => $assignment->id,
                        'teacher_name' => auth()->user()->name,
                        'assignment_description' => $assignment->description
                    ])
                ]);
            }
        }

        $message = 'Assignment created successfully.';
        if ($assignment->is_published) {
            $message .= ' Notifications sent to ' . $students->count() . ' student(s).';
        }

        return redirect()->route('teacher.assignments.index')
            ->with('success', $message);
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment): View
    {
        // Verify the assignment belongs to the teacher
        if ($assignment->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $assignment->load(['course', 'week', 'submissions.student']);
        
        return view('teacher.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Assignment $assignment): View
    {
        // Verify the assignment belongs to the teacher
        if ($assignment->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $courses = auth()->user()->courses()->with(['terms.subTerms.weeks'])->get();
        
        return view('teacher.assignments.edit', compact('assignment', 'courses'));
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, Assignment $assignment)
    {
        // Verify the assignment belongs to the teacher
        if ($assignment->course->teacher_id !== auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized access.'], 403);
            }
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'course_week_id' => 'nullable|exists:course_weeks,id',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string|max:5000',
            'instructions' => 'nullable|string|max:5000',
            'submission_type' => 'required|in:text,file,both',
            'allowed_file_types' => 'nullable|array',
            'allowed_file_types.*' => 'string|in:pdf,doc,docx,txt,jpg,jpeg,png,gif',
            'max_file_size' => 'nullable|integer|min:1|max:100',
            'max_files' => 'nullable|integer|min:1|max:10',
            'due_date' => 'required|date',
            'points' => 'required|integer|min:1|max:1000',
            'max_attempts' => 'required|integer|min:1|max:10',
            'is_published' => 'boolean',
        ]);

        $data = [
            'course_id' => $request->course_id,
            'course_week_id' => $request->course_week_id,
            'title' => $request->title,
            'description' => $request->description ? Purify::clean($request->description) : null,
            'instructions' => $request->instructions ? Purify::clean($request->instructions) : null,
            'submission_type' => $request->submission_type,
            'allowed_file_types' => $request->allowed_file_types,
            'max_file_size' => $request->max_file_size,
            'max_files' => $request->max_files,
            'due_date' => $request->due_date,
            'points' => $request->points,
            'max_attempts' => $request->max_attempts,
            'is_published' => $request->boolean('is_published', false),
        ];

        // Check if assignment is being published (was draft, now published)
        $wasDraft = !$assignment->is_published;
        $isNowPublished = $request->boolean('is_published', false);
        
        $assignment->update($data);
        
        // Send notifications if assignment is being published for the first time
        if ($wasDraft && $isNowPublished) {
            $course = \App\Models\Course::findOrFail($request->course_id);
            $students = $course->enrollments()->with('student')->get();
            
            foreach ($students as $enrollment) {
                $student = $enrollment->student;
                
                $student->notifications()->create([
                    'type' => 'assignment',
                    'title' => 'New Assignment',
                    'message' => $assignment->title,
                    'data' => json_encode([
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'assignment_id' => $assignment->id,
                        'teacher_name' => auth()->user()->name,
                        'assignment_description' => $assignment->description
                    ])
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            $message = 'Assignment updated successfully.';
            if ($wasDraft && $isNowPublished) {
                $message .= ' Notifications sent to ' . count($students ?? []) . ' student(s).';
            }
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        $message = 'Assignment updated successfully.';
        if ($wasDraft && $isNowPublished) {
            $message .= ' Notifications sent to ' . count($students ?? []) . ' student(s).';
        }

        return redirect()->route('teacher.assignments.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Request $request, Assignment $assignment)
    {
        // Verify the assignment belongs to the teacher
        if ($assignment->course->teacher_id !== auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized access.'], 403);
            }
            abort(403, 'Unauthorized access.');
        }

        // Delete associated files
        foreach ($assignment->submissions as $submission) {
            if ($submission->file_submissions) {
                foreach ($submission->file_submissions as $file) {
                    if (isset($file['file_path'])) {
                        Storage::disk('public')->delete($file['file_path']);
                    }
                }
            }
        }

        $assignment->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Assignment deleted successfully.'
            ]);
        }

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }

    /**
     * Grade a submission.
     */
    public function grade(Request $request, Assignment $assignment, AssignmentSubmission $submission): RedirectResponse
    {
        // Verify the assignment belongs to the teacher
        if ($assignment->course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Verify the submission belongs to the assignment
        if ($submission->assignment_id !== $assignment->id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'submission_id' => 'required|integer|in:' . $submission->id,
            'points_earned' => 'required|integer|min:0|max:' . $assignment->points,
            'feedback' => 'nullable|string',
        ]);

        $assignment->loadMissing('course');
        $submission->loadMissing('student');

        $wasPreviouslyGraded = $submission->status === 'graded' && !is_null($submission->points_earned);
        $previousPoints = $submission->points_earned;

        $gradedAt = now();

        $submission->update([
            'points_earned' => $request->points_earned,
            'feedback' => $request->feedback,
            'status' => 'graded',
            'graded_at' => $gradedAt,
        ]);

        $student = $submission->student;
        if ($student) {
            $course = $assignment->course;
            $notificationType = $wasPreviouslyGraded ? 'assignment_regraded' : 'assignment_graded';
            $scored = $request->points_earned . '/' . $assignment->points;
            $message = $wasPreviouslyGraded
                ? "Your submission for {$assignment->title} was regraded: {$scored}."
                : "Your submission for {$assignment->title} was graded: {$scored}.";

            $student->notifications()->create([
                'type' => $notificationType,
                'title' => $assignment->title,
                'message' => $message,
                'data' => [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'assignment_id' => $assignment->id,
                    'assignment_title' => $assignment->title,
                    'submission_id' => $submission->id,
                    'points_earned' => (int) $request->points_earned,
                    'max_points' => (int) $assignment->points,
                    'previous_points' => $previousPoints,
                    'graded_at' => $gradedAt->toIso8601String(),
                    'regraded' => $wasPreviouslyGraded,
                    'teacher_name' => auth()->user()->name,
                ],
            ]);
        }

        $successMessage = $wasPreviouslyGraded ? 'Submission regraded successfully.' : 'Submission graded successfully.';

        return redirect()->back()
            ->with('success', $successMessage);
    }

    /**
     * Get weeks for a specific course (AJAX).
     */
    public function getWeeks(Course $course): \Illuminate\Http\JsonResponse
    {
        // Verify the course belongs to the teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $weeks = $course->terms()
            ->with(['subTerms.weeks'])
            ->get()
            ->pluck('subTerms')
            ->flatten()
            ->pluck('weeks')
            ->flatten()
            ->map(function ($week) {
                return [
                    'id' => $week->id,
                    'title' => $week->title,
                    'sub_term' => $week->subTerm->title,
                    'term' => $week->subTerm->term->name,
                ];
            });

        return response()->json($weeks);
    }
}