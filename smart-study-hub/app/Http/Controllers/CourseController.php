<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        ]);

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

        auth()->user()->courses()->create($data);

        return redirect()->route('teacher.courses.index')
            ->with('success', 'Course created successfully.');
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
}
