<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements for teachers.
     */
    public function teacherIndex(): View
    {
        $announcements = Announcement::where('teacher_id', auth()->id())
            ->with('course')
            ->latest()
            ->paginate(10);
        
        return view('teacher.announcements.index', compact('announcements'));
    }

    /**
     * Display a listing of announcements for students.
     */
    public function studentIndex(): View
    {
        $student = auth()->user();
        
        // Get all enrolled courses for the student
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        
        // Get announcements from enrolled courses
        $announcements = Announcement::whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'teacher'])
            ->latest()
            ->paginate(10);
        
        return view('student.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        $courses = auth()->user()->courses;
        return view('teacher.announcements.create', compact('courses'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_type' => 'required|in:quiz,exam,assignment,other',
            'custom_event_type' => 'nullable|string|max:255|required_if:event_type,other',
            'event_date' => 'nullable|date|after:now',
        ]);

        // Verify the teacher owns this course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== auth()->id()) {
            return redirect()->back()->withErrors(['course_id' => 'You can only create announcements for your own courses.']);
        }

        $announcement = Announcement::create([
            'course_id' => $validated['course_id'],
            'teacher_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'event_type' => $validated['event_type'],
            'custom_event_type' => $validated['event_type'] === 'other' ? $validated['custom_event_type'] : null,
            'event_date' => $validated['event_date'] ?? null,
            'show_on_calendar' => $request->has('show_on_calendar') ? true : false,
        ]);

        // Get all students enrolled in this course
        $students = $course->enrollments()->with('student')->get();
        
        // Send notification to all students
        foreach ($students as $enrollment) {
            $student = $enrollment->student;
            
            // Determine notification type based on event type
            $notificationType = $validated['event_type'] === 'other' ? 'announcement' : $validated['event_type'];
            
            // Create notification
            $student->notifications()->create([
                'type' => $notificationType,
                'title' => 'New ' . ucfirst($announcement->event_type_display),
                'message' => $announcement->title,
                'data' => json_encode([
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'announcement_id' => $announcement->id,
                    'teacher_name' => auth()->user()->name
                ])
            ]);
        }

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement created successfully and notifications sent to ' . $students->count() . ' student(s).');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        // Verify the teacher owns this announcement
        if ($announcement->teacher_id !== auth()->id()) {
            abort(403);
        }

        $courses = auth()->user()->courses;
        return view('teacher.announcements.edit', compact('announcement', 'courses'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        // Verify the teacher owns this announcement
        if ($announcement->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_type' => 'required|in:quiz,exam,assignment,other',
            'custom_event_type' => 'nullable|string|max:255|required_if:event_type,other',
            'event_date' => 'nullable|date|after:now',
        ]);

        // Verify the teacher owns this course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== auth()->id()) {
            return redirect()->back()->withErrors(['course_id' => 'You can only create announcements for your own courses.']);
        }

        $announcement->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'event_type' => $validated['event_type'],
            'custom_event_type' => $validated['event_type'] === 'other' ? $validated['custom_event_type'] : null,
            'event_date' => $validated['event_date'] ?? null,
            'show_on_calendar' => $request->has('show_on_calendar') ? true : false,
        ]);

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        // Verify the teacher owns this announcement
        if ($announcement->teacher_id !== auth()->id()) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
