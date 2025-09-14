<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseApplication;

class ArchivedApplicationsController extends Controller
{
    /**
     * Display archived applications
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        $applications = CourseApplication::with(['student', 'course'])
            ->whereHas('course', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('status', 'archived')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group applications by course and section
        $groupedApplications = $applications->groupBy(function ($application) {
            return $application->course->title . ' - ' . ($application->course->section ?? 'No Section');
        });

        // Get recent notifications for the notification dropdown
        $recentNotifications = $teacher->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $teacher->unreadNotifications()->count();

        return view('teacher.applications.archived', compact('applications', 'groupedApplications', 'recentNotifications', 'unreadCount'));
    }

    /**
     * Unarchive a single application
     */
    public function unarchive(CourseApplication $application)
    {
        $teacher = Auth::user();

        // Check if the application belongs to the teacher's course
        if ($application->course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if the application is archived
        if ($application->status !== 'archived') {
            return back()->with('error', 'This application is not archived.');
        }

        // Restore to the original status before it was archived
        $originalStatus = $application->original_status ?: 'pending';
        
        $application->update([
            'status' => $originalStatus,
            'original_status' => null, // Clear the original status
            'reviewed_at' => now(),
            'message' => 'Restored from archive'
        ]);

        return back()->with('success', 'Application unarchived successfully.');
    }

    /**
     * Unarchive multiple applications
     */
    public function bulkUnarchive(Request $request)
    {
        $teacher = Auth::user();
        $applicationIds = $request->input('application_ids', []);

        if (empty($applicationIds)) {
            return back()->with('error', 'No applications selected.');
        }

        $applications = CourseApplication::whereIn('id', $applicationIds)
            ->whereHas('course', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->where('status', 'archived')
            ->get();

        $count = $applications->count();
        $applications->each(function ($application) {
            $originalStatus = $application->original_status ?: 'pending';
            
            $application->update([
                'status' => $originalStatus,
                'original_status' => null, // Clear the original status
                'reviewed_at' => now(),
                'message' => 'Restored from archive'
            ]);
        });

        return back()->with('success', "Successfully unarchived {$count} application(s).");
    }
}