<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTerm;
use App\Models\CourseSubTerm;
use App\Models\CourseWeek;
use App\Models\CourseMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Stevebauman\Purify\Facades\Purify;

class CourseController extends Controller
{
    /**
     * Display a listing of the teacher's courses.
     */
    public function index(): View
    {
        $courses = auth()->user()->courses()->latest()->get();
        
        return view('teacher.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        return view('teacher.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'student_capacity' => 'required|integer|min:1|max:200',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'terms' => 'required|array|min:1|max:4',
            'terms.*.name' => 'required|string|max:255',
            'terms.*.description' => 'nullable|string|max:1000',
            'terms.*.total_weeks' => 'required|integer|min:1|max:52',
            'terms.*.sections' => 'required|array|min:1',
            'terms.*.sections.*.title' => 'required|string|max:255',
            'terms.*.sections.*.description' => 'nullable|string|max:1000',
            'terms.*.weeks' => 'nullable|array',
            'terms.*.weeks.*.title' => 'nullable|string|max:255',
            'terms.*.weeks.*.notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Create the course
        $data = [
            'title' => $request->title,
            'section' => $request->section,
            'student_capacity' => $request->student_capacity,
            'description' => $request->description,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('course_images', 'public');
            $data['image'] = $imagePath;
        }

        $course = auth()->user()->courses()->create($data);

        // Create terms and their structure
        foreach ($request->terms as $termIndex => $termData) {
            $term = $course->terms()->create([
                'name' => $termData['name'],
                'description' => $termData['description'],
                'total_weeks' => $termData['total_weeks'],
                'order' => $termIndex + 1,
            ]);

            // Create default sections (Prelims, Midterms, Finals)
            $defaultSections = [
                ['title' => 'Prelims', 'description' => 'Preliminary examinations and assessments'],
                ['title' => 'Midterms', 'description' => 'Midterm examinations and assessments'],
                ['title' => 'Finals', 'description' => 'Final examinations and assessments'],
            ];

            // Use provided sections or default ones
            $sections = isset($termData['sections']) ? $termData['sections'] : $defaultSections;

            foreach ($sections as $sectionIndex => $sectionData) {
                $subTerm = $term->subTerms()->create([
                    'course_id' => $course->id,
                    'title' => $sectionData['title'],
                    'description' => $sectionData['description'],
                    'order' => $sectionIndex + 1,
                ]);

                // Calculate weeks per section
                $weeksPerSection = max(1, floor($termData['total_weeks'] / count($sections)));
                $remainingWeeks = $termData['total_weeks'] % count($sections);

                // Distribute weeks across sections
                $weeksToCreate = $weeksPerSection;
                if ($sectionIndex < $remainingWeeks) {
                    $weeksToCreate++;
                }

                // Create weeks for this section
                for ($weekNum = 1; $weekNum <= $weeksToCreate; $weekNum++) {
                    // Get week data from request if available
                    $weekData = $termData['weeks'][$weekNum - 1] ?? null;
                    $weekTitle = $weekData['title'] ?? "Week {$weekNum}";
                    $weekNotes = $weekData['notes'] ?? "Content for {$sectionData['title']} - Week {$weekNum}";
                    
                    $subTerm->weeks()->create([
                        'course_id' => $course->id,
                        'course_term_id' => $term->id,
                        'week_number' => $weekNum,
                        'title' => $weekTitle,
                        'description' => $weekNotes,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Course created successfully with structure.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create course: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified course with its structure.
     */
    public function show(Course $course): View
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $course->load(['terms.subTerms.weeks.materials', 'terms.weeks.materials']);
        
        // Calculate total materials and weeks across all terms
        $totalMaterials = $course->terms->sum(function($term) {
            return $term->subTerms->sum(function($subTerm) {
                return $subTerm->weeks->sum(function($week) {
                    return $week->materials->count();
                });
            });
        });

        $totalWeeks = $course->terms->sum(function($term) {
            return $term->subTerms->sum(function($subTerm) {
                return $subTerm->weeks->count();
            });
        });
        
        return view('teacher.courses.show', compact('course', 'totalMaterials', 'totalWeeks'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('teacher.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'student_capacity' => 'required|integer|min:1|max:200',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'section' => $request->section,
            'student_capacity' => $request->student_capacity,
            'description' => $request->description,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('course_images', 'public');
            $data['image'] = $imagePath;
        }

        $course->update($data);

        return redirect()->route('teacher.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $course->delete();

        return redirect()->route('teacher.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Show the course selection page for uploading materials.
     */
    public function selectCourseForUpload(): View
    {
        $courses = auth()->user()->courses()->latest()->get();
        
        return view('teacher.courses.select-course-upload', compact('courses'));
    }

    /**
     * Show the upload materials page for a specific course.
     */
    public function uploadMaterials(Course $course): View
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $course->load(['terms.subTerms.weeks.materials', 'terms.weeks']);
        
        // Calculate total materials and weeks
        $totalMaterials = $course->terms->sum(function($term) {
            return $term->subTerms->sum(function($subTerm) {
                return $subTerm->weeks->sum(function($week) {
                    return $week->materials->count();
                });
            });
        });

        $totalWeeks = $course->terms->sum(function($term) {
            return $term->subTerms->sum(function($subTerm) {
                return $subTerm->weeks->count();
            });
        });
        
        // Prepare course structure data for JavaScript
        $courseStructure = $course->terms->map(function($term) {
            $weeks = collect();
            foreach($term->subTerms as $subTerm) {
                foreach($subTerm->weeks as $week) {
                    $weeks->push([
                        'id' => $week->id,
                        'title' => $week->title,
                        'sub_term' => $subTerm->title
                    ]);
                }
            }
            return [
                'id' => $term->id,
                'name' => $term->name,
                'weeks' => $weeks->toArray()
            ];
        });
        
        return view('teacher.courses.upload-materials', compact('course', 'courseStructure', 'totalMaterials', 'totalWeeks'));
    }

    /**
     * Store uploaded material.
     */
    public function storeMaterial(Request $request, Course $course): RedirectResponse
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Debug: Log the request data
        \Log::info('Material upload request data:', $request->all());

        try {
            $request->validate([
                'course_week_id' => 'required|exists:course_weeks,id',
                'title' => 'required|string|max:500',
                'description' => 'nullable|string|max:5000',
                'type' => 'required|in:video,file,link,text',
                'file' => 'nullable|file|max:2097152', // 2GB max (in KB)
                'youtube_url' => 'nullable|url',
                'external_url' => 'nullable|url',
                'text_content' => 'nullable|string|max:10000',
                'is_required' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $week = CourseWeek::findOrFail($request->course_week_id);
        
        // Ensure the week belongs to the course
        if ($week->course_id !== $course->id) {
            abort(403, 'Unauthorized access.');
        }

        $data = [
            'course_id' => $course->id,
            'course_week_id' => $week->id,
            'title' => $request->title,
            'description' => $request->description ? Purify::clean($request->description) : null,
            'type' => $request->type,
            'is_required' => $request->boolean('is_required', false),
            'order' => $week->materials()->max('order') + 1,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('course_materials', 'public');
            $data['content'] = $filePath;
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        // Handle URLs and text content
        if ($request->type === 'video' && $request->youtube_url) {
            $data['content'] = $request->youtube_url;
            $data['youtube_url'] = $request->youtube_url;
        } elseif ($request->type === 'link' && $request->external_url) {
            $data['content'] = $request->external_url;
            $data['external_url'] = $request->external_url;
        } elseif ($request->type === 'text' && $request->text_content) {
            $data['content'] = Purify::clean($request->text_content);
        }

        $material = $course->materials()->create($data);

        // Send notifications to all enrolled students
        $students = $course->enrollments()->with('student')->get();
        
        foreach ($students as $enrollment) {
            $student = $enrollment->student;
            
            // Determine notification type based on material type
            $notificationType = 'material_' . $material->type; // material_video, material_file, material_link, material_text
            
            $student->notifications()->create([
                'type' => $notificationType,
                'title' => 'New ' . ucfirst($material->type) . ' Material',
                'message' => $material->title,
                'data' => json_encode([
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'material_id' => $material->id,
                    'material_type' => $material->type,
                    'teacher_name' => auth()->user()->name
                ])
            ]);
        }

        return redirect()->back()
            ->with('success', 'Material uploaded successfully. Notifications sent to ' . $students->count() . ' student(s).');
    }

    /**
     * Show the form for editing a material.
     */
    public function editMaterial(Course $course, CourseMaterial $material): View
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure the material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized access.');
        }

        $course->load(['terms.subTerms.weeks']);
        
        return view('teacher.courses.edit-material', compact('course', 'material'));
    }

    /**
     * Update a material.
     */
    public function updateMaterial(Request $request, Course $course, CourseMaterial $material): RedirectResponse
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure the material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:video,file,link,text',
            'text_content' => 'nullable|string',
            'course_week_id' => 'required|exists:course_weeks,id',
        ]);

        $data = $request->only(['title', 'type', 'course_week_id']);
        $data['description'] = $request->description ? Purify::clean($request->description) : null;

        if ($request->type === 'file' && $request->hasFile('file')) {
            // Delete old file
            if ($material->content) {
                Storage::disk('public')->delete($material->content);
            }
            
            // Store new file
            $data['content'] = $request->file('file')->store('course_materials', 'public');
        } elseif ($request->type !== 'file') {
            $data['content'] = $request->text_content ? Purify::clean($request->text_content) : null;
        }

        $material->update($data);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Material updated successfully.');
    }

    /**
     * Get material data for editing (AJAX).
     */
    public function getMaterialEditData(CourseMaterial $material)
    {
        try {
            // Ensure the material belongs to the authenticated teacher
            if ($material->course->teacher_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized access.'], 403);
            }

            // Load necessary relationships
            $material->load(['course', 'week.term']);

            // Get term and week IDs safely
            $termId = null;
            $weekId = $material->course_week_id;
            
            if ($material->week && $material->week->term) {
                $termId = $material->week->term->id;
            }

            $data = [
                'id' => $material->id,
                'course_title' => $material->course->title,
                'term_id' => $termId,
                'week_id' => $weekId,
                'title' => $material->title,
                'description' => $material->description,
                'type' => $material->type,
                'is_required' => $material->is_required,
                'content' => $material->content,
                'youtube_url' => $material->youtube_url,
                'external_url' => $material->external_url,
                'file_name' => $material->file_name,
                'file_size_formatted' => $material->file_size_formatted,
            ];

            \Log::info('Material edit data:', $data);

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error fetching material edit data: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading material data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a material via AJAX.
     */
    public function updateMaterialAjax(Request $request, CourseMaterial $material)
    {
        // Ensure the material belongs to the authenticated teacher
        if ($material->course->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        // Ensure the material belongs to the course
        if ($material->course_id !== $material->course->id) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'type' => 'required|in:video,file,link,text',
            'course_week_id' => 'required|exists:course_weeks,id',
            'is_required' => 'boolean',
            'youtube_url' => 'nullable|url',
            'external_url' => 'nullable|url',
            'text_content' => 'nullable|string|max:10000',
            'file' => 'nullable|file|max:2097152', // 2GB max (in KB)
        ]);

        $data = $request->only(['title', 'type', 'course_week_id']);
        $data['description'] = $request->description ? Purify::clean($request->description) : null;
        $data['is_required'] = $request->boolean('is_required', false);

        // Handle content based on type
        if ($request->type === 'video' && $request->youtube_url) {
            $data['content'] = $request->youtube_url;
            $data['youtube_url'] = $request->youtube_url;
        } elseif ($request->type === 'link' && $request->external_url) {
            $data['content'] = $request->external_url;
            $data['external_url'] = $request->external_url;
        } elseif ($request->type === 'text' && $request->text_content) {
            $data['content'] = Purify::clean($request->text_content);
        } elseif ($request->type === 'file' && $request->hasFile('file')) {
            // Delete old file
            if ($material->content) {
                Storage::disk('public')->delete($material->content);
            }
            
            // Store new file
            $file = $request->file('file');
            $filePath = $file->store('course_materials', 'public');
            $data['content'] = $filePath;
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $material->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Material updated successfully.'
        ]);
    }

    /**
     * Get weeks for a specific term (AJAX).
     */
    public function getTermWeeks(CourseTerm $term)
    {
        try {
            // Ensure the term belongs to the authenticated teacher
            if ($term->course->teacher_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized access.'], 403);
            }

            // Get weeks for this term
            $weeks = $term->weeks()->get();

            $weeksData = $weeks->map(function ($week) {
                return [
                    'id' => $week->id,
                    'title' => $week->title,
                    'sub_term' => $week->subTerm ? $week->subTerm->title : 'Week',
                    'week_number' => $week->week_number,
                ];
            });

            return response()->json($weeksData);
        } catch (\Exception $e) {
            \Log::error('Error fetching term weeks: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading weeks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a material.
     */
    public function deleteMaterial(Course $course, CourseMaterial $material): RedirectResponse
    {
        // Ensure the course belongs to the authenticated teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure the material belongs to the course
        if ($material->course_id !== $course->id) {
            abort(403, 'Unauthorized access.');
        }

        // Delete file if exists
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->back()
            ->with('success', 'Material deleted successfully.');
    }

    /**
     * Display the specified course for students.
     */
    public function showForStudent(Course $course): View
    {
        // Load course with all related data
        $course->load([
            'terms.subTerms.weeks.materials',
            'teacher'
        ]);

        // Check if student is enrolled in this course
        $isEnrolled = false;
        if (auth()->check() && auth()->user()->role === 'student') {
            $isEnrolled = $course->students()->where('student_id', auth()->id())->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }
}
