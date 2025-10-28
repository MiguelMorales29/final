<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseApplication;
use App\Models\Notification;
use App\Models\Assignment;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Homepage courses data (same as homepage)
        $homepageCourses = [
            [
                'id' => 1,
                'title' => 'Mathematics',
                'teacher' => 'Prof. Sarah Johnson',
                'weeks' => 12,
                'cover' => asset('images/math.jpg'),
                'desc'  => 'Unlock the world of numbers, logic, and problem-solving with practical applications.',
                'progress' => 75,
                'next_due' => 'Dec 20, 2024',
            ],
            [
                'id' => 2,
                'title' => 'English',
                'teacher' => 'Dr. Michael Brown',
                'weeks' => 10,
                'cover' => asset('images/english.png'),
                'desc'  => 'Master communication through literature, writing, and critical thinking.',
                'progress' => 60,
                'next_due' => 'Dec 18, 2024',
            ],
            [
                'id' => 3,
                'title' => 'Science',
                'teacher' => 'Dr. Emily Davis',
                'weeks' => 14,
                'cover' => asset('images/science.jpg'),
                'desc'  => 'Understand the wonders of physics, chemistry, and biology.',
                'progress' => 45,
                'next_due' => 'Dec 22, 2024',
            ],
            [
                'id' => 4,
                'title' => 'Programming',
                'teacher' => 'Prof. Alex Chen',
                'weeks' => 16,
                'cover' => asset('images/default-course.png'),
                'desc'  => 'Learn coding fundamentals and build real-world applications.',
                'progress' => 30,
                'next_due' => 'Dec 25, 2024',
            ],
        ];
        
        // My enrolled courses (first 3 from homepage)
        $myCourses = array_slice($homepageCourses, 0, 3);
        
        // Add locked courses (History and Filipino)
        $lockedCourses = [
            [
                'id' => 5,
                'title' => 'Filipino',
                'teacher' => 'Prof. Michael Chen',
                'weeks' => 10,
                'cover' => asset('images/filipino.png'),
                'desc'  => 'Celebrate language and culture while improving reading, writing, and speaking skills.',
                'progress' => 0,
                'next_due' => null,
                'locked' => true,
            ],
            [
                'id' => 6,
                'title' => 'History',
                'teacher' => 'Dr. Emily Davis',
                'weeks' => 14,
                'cover' => asset('images/history.png'),
                'desc'  => 'Dive into key historical events, dates, and significant figures.',
                'progress' => 0,
                'next_due' => null,
                'locked' => true,
            ],
        ];
        
        // Get application data
        // Get applications that are NOT removed (cooldown_until is null)
        $applications = $user->courseApplications()
            ->with('course')
            ->whereNull('cooldown_until')
            ->get();
            
        $pendingApplications = $applications->where('status', 'pending');
        $rejectedApplications = $applications->where('status', 'rejected');
        $approvedApplications = $applications->where('status', 'approved');
        $droppedApplications = $applications->where('status', 'dropped');
        
        // Add approved courses to enrolled courses (these should show as enrolled)
        $approvedCourses = [];
        foreach ($approvedApplications as $application) {
            $approvedCourses[] = [
                'id' => $application->course->id,
                'title' => $application->course->title,
                'teacher' => $application->course->teacher->name,
                'weeks' => 12, // Default value
                'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                'desc' => $application->course->description,
                'progress' => 0, // New course, no progress yet
                'next_due' => null,
                'locked' => false, // This is an enrolled course
                'status' => 'enrolled',
            ];
        }
        
        // Add pending courses to locked courses
        $pendingCourses = [];
        foreach ($pendingApplications as $application) {
            $pendingCourses[] = [
                'id' => $application->course->id,
                'title' => $application->course->title,
                'teacher' => $application->course->teacher->name,
                'weeks' => 12, // Default value
                'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                'desc' => $application->course->description,
                'progress' => 0,
                'next_due' => null,
                'locked' => true,
                'status' => 'pending',
            ];
        }
        
        // Add rejected courses
        $rejectedCourses = [];
        foreach ($rejectedApplications as $application) {
            $rejectedCourses[] = [
                'id' => $application->course->id,
                'title' => $application->course->title,
                'teacher' => $application->course->teacher->name,
                'weeks' => 12, // Default value
                'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                'desc' => $application->course->description,
                'progress' => 0,
                'next_due' => null,
                'locked' => true,
                'status' => 'rejected',
                'application_id' => $application->id,
            ];
        }
        
        // Add dropped courses
        $droppedCourses = [];
        foreach ($droppedApplications as $application) {
            $droppedCourses[] = [
                'id' => $application->course->id,
                'title' => $application->course->title,
                'teacher' => $application->course->teacher->name,
                'weeks' => 12, // Default value
                'cover' => $application->course->image ? asset('storage/' . $application->course->image) : asset('images/default-course.png'),
                'desc' => $application->course->description,
                'progress' => 0,
                'next_due' => null,
                'locked' => true,
                'status' => 'dropped',
                'application_id' => $application->id,
            ];
        }
        
        // Combine enrolled, approved, locked, pending, rejected, and dropped courses
        $allCourses = array_merge($myCourses, $approvedCourses, $lockedCourses, $pendingCourses, $rejectedCourses, $droppedCourses);
        
        $approvedCoursesCount = count($myCourses) + count($approvedCourses);
        $avgProgress = $approvedCoursesCount ? intval(collect(array_merge($myCourses, $approvedCourses))->avg('progress')) : 0;

        // Get enrolled course IDs
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        
        // Get assignments the student has already submitted
        $submittedAssignmentIds = \App\Models\AssignmentSubmission::where('student_id', $user->id)
            ->where('status', 'submitted')
            ->pluck('assignment_id')
            ->unique()
            ->toArray();
        
        // Get real upcoming assignments from enrolled courses (excluding submitted ones)
        $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->whereNotNull('due_date')
            ->whereNotIn('id', $submittedAssignmentIds)
            ->orderBy('due_date', 'asc')
            ->with('course')
            ->limit(20)
            ->get();
        
        // Convert assignments to dashboard format
        $upcomingTasks = $upcomingAssignments->map(function ($assignment) {
            // Calculate days until due (reverse the order to get positive numbers)
            $daysUntilDue = now()->diffInDays($assignment->due_date, false);
            $priority = 'low';
            
            if ($daysUntilDue <= 3 && $daysUntilDue >= 0) {
                $priority = 'high';
            } elseif ($daysUntilDue <= 7 && $daysUntilDue > 3) {
                $priority = 'medium';
            } elseif ($daysUntilDue < 0) {
                // Overdue
                $priority = 'high';
            }
            
            return [
                'title' => $assignment->title,
                'course' => $assignment->course->title,
                'due' => $assignment->due_date->format('M d, Y'),
                'priority' => $priority,
                'assignment_id' => $assignment->id,
                'days_until_due' => $daysUntilDue,
            ];
        })->toArray();
        
        // Sort by priority: high, medium, low (then by days until due)
        if (!empty($upcomingTasks)) {
            usort($upcomingTasks, function($a, $b) {
                // First sort by priority
                $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
                
                // Get priority order numbers
                $aPriority = $priorityOrder[$a['priority']] ?? 3;
                $bPriority = $priorityOrder[$b['priority']] ?? 3;
                
                // Compare priorities
                $priorityDiff = $aPriority - $bPriority;
                
                // If different priorities, return comparison
                if ($priorityDiff !== 0) {
                    return $priorityDiff;
                }
                
                // If same priority, sort by days until due (ascending - soonest first)
                return $a['days_until_due'] - $b['days_until_due'];
            });
        }
        
        $pendingTasks = count($upcomingTasks);

        // Get recent notifications and convert to activity stream format
        $recentNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        
        // Convert notifications to activity stream format
        $notificationActivities = $recentNotifications->map(function ($notification) {
            $type = $notification->type;
            $timeAgo = $notification->created_at->diffForHumans();
            
            // Extract course name from data if available
            $courseName = 'Course';
            if ($notification->data && isset($notification->data['course_id'])) {
                $course = \App\Models\Course::find($notification->data['course_id']);
                if ($course) {
                    $courseName = $course->title;
                }
            }
            
            return [
                'type' => $type,
                'title' => $notification->title,
                'text' => $notification->message,
                'time' => $timeAgo,
                'subject' => $courseName,
                'new' => !$notification->read,
                'notification_id' => $notification->id,
            ];
        })->toArray();
        
        // Use only real notifications, no demo data
        $activityStream = $notificationActivities;

        // Get real calendar marks from announcements with event dates
        $announcements = \App\Models\Announcement::whereIn('course_id', $enrolledCourseIds)
            ->whereNotNull('event_date')
            ->where('event_date', '>=', now())
            ->where('show_on_calendar', true)
            ->get();
        
        $calendarMarks = [];
        $calendarEventsByDate = [];
        
        foreach ($announcements as $announcement) {
            $dateKey = $announcement->event_date->format('Y-m-d');
            $eventType = $announcement->event_type;
            
            // Map announcement event types to calendar marker types and colors
            if ($eventType === 'exam') {
                $calendarMarks[$dateKey] = 'exam';
                $badgeColor = 'blue';
            } elseif ($eventType === 'assignment') {
                $calendarMarks[$dateKey] = 'assignment';
                $badgeColor = 'orange';
            } elseif ($eventType === 'quiz') {
                $calendarMarks[$dateKey] = 'quiz';
                $badgeColor = 'red';
            } else {
                // Custom event type - use purple color
                $calendarMarks[$dateKey] = 'custom';
                $badgeColor = 'purple';
            }
            
            // Store event details
            if (!isset($calendarEventsByDate[$dateKey])) {
                $calendarEventsByDate[$dateKey] = [];
            }
            
            $calendarEventsByDate[$dateKey][] = [
                'type' => $eventType,
                'type_display' => $announcement->event_type_display,
                'title' => $announcement->title,
                'url' => route('student.announcements.index'),
                'badge_color' => $badgeColor,
            ];
        }
        
        // Also add assignment due dates to calendar
        foreach ($upcomingAssignments as $assignment) {
            $dateKey = $assignment->due_date->format('Y-m-d');
            
            // Store event details
            if (!isset($calendarEventsByDate[$dateKey])) {
                $calendarEventsByDate[$dateKey] = [];
            }
            
            $calendarEventsByDate[$dateKey][] = [
                'type' => 'assignment',
                'type_display' => 'Assignment',
                'title' => $assignment->title,
                'url' => route('student.assignments.index'),
                'badge_color' => 'orange',
            ];
            
            if (!isset($calendarMarks[$dateKey])) {
                $calendarMarks[$dateKey] = 'assignment';
            } else if ($calendarMarks[$dateKey] === 'deadline') {
                $calendarMarks[$dateKey] = 'deadline';
            }
        }

        // Get recent notifications for the notification dropdown
        $recentNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $unreadCount = $user->unreadNotifications()->count();

        return view('student.dashboard', compact(
            'user',
            'homepageCourses',
            'myCourses',
            'allCourses',
            'approvedCoursesCount',
            'avgProgress',
            'upcomingTasks',
            'pendingTasks',
            'activityStream',
            'calendarMarks',
            'calendarEventsByDate',
            'recentNotifications',
            'unreadCount'
        ));
    }

    public function calendar(Request $request)
    {
        $user = Auth::user();
        
        // Handle month navigation
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        
        // Validate month and year
        $year = max(2020, min(2099, (int)$year));
        $month = max(1, min(12, (int)$month));
        
        // Get enrolled course IDs
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        
        // Get assignments the student has already submitted
        $submittedAssignmentIds = \App\Models\AssignmentSubmission::where('student_id', $user->id)
            ->where('status', 'submitted')
            ->pluck('assignment_id')
            ->unique()
            ->toArray();
        
        // Get real upcoming assignments from enrolled courses (excluding submitted ones)
        $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->whereNotNull('due_date')
            ->whereNotIn('id', $submittedAssignmentIds)
            ->orderBy('due_date', 'asc')
            ->with('course')
            ->get();
        
        // Get real calendar marks from announcements with event dates
        $announcements = \App\Models\Announcement::whereIn('course_id', $enrolledCourseIds)
            ->whereNotNull('event_date')
            ->where('show_on_calendar', true)
            ->get();
        
        $calendarMarks = [];
        $calendarEventsByDate = [];
        
        foreach ($announcements as $announcement) {
            $dateKey = $announcement->event_date->format('Y-m-d');
            $eventType = $announcement->event_type;
            
            // Map announcement event types to calendar marker types and colors
            if ($eventType === 'exam') {
                $calendarMarks[$dateKey] = 'exam';
                $badgeColor = 'blue';
            } elseif ($eventType === 'assignment') {
                $calendarMarks[$dateKey] = 'assignment';
                $badgeColor = 'orange';
            } elseif ($eventType === 'quiz') {
                $calendarMarks[$dateKey] = 'quiz';
                $badgeColor = 'red';
            } else {
                // Custom event type - use purple color
                $calendarMarks[$dateKey] = 'custom';
                $badgeColor = 'purple';
            }
            
            // Store event details with proper URL based on type
            if (!isset($calendarEventsByDate[$dateKey])) {
                $calendarEventsByDate[$dateKey] = [];
            }
            
            $calendarEventsByDate[$dateKey][] = [
                'type' => $eventType,
                'type_display' => $announcement->event_type_display,
                'title' => $announcement->title,
                'url' => route('student.announcements.index'),
                'badge_color' => $badgeColor,
            ];
        }
        
        // Also add assignment due dates to calendar
        foreach ($upcomingAssignments as $assignment) {
            $dateKey = $assignment->due_date->format('Y-m-d');
            
            // Store event details
            if (!isset($calendarEventsByDate[$dateKey])) {
                $calendarEventsByDate[$dateKey] = [];
            }
            
            $calendarEventsByDate[$dateKey][] = [
                'type' => 'assignment',
                'type_display' => 'Assignment',
                'title' => $assignment->title,
                'url' => route('student.assignments.show', $assignment),
                'badge_color' => 'orange',
                'assignment_id' => $assignment->id,
            ];
            
            if (!isset($calendarMarks[$dateKey])) {
                $calendarMarks[$dateKey] = 'assignment';
            } else if ($calendarMarks[$dateKey] === 'deadline') {
                $calendarMarks[$dateKey] = 'deadline';
            }
        }

        return view('student.calendar', compact(
            'user',
            'calendarMarks',
            'calendarEventsByDate',
            'year',
            'month'
        ));
    }
}
