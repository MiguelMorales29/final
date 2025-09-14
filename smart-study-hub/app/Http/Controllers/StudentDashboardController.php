<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseApplication;
use App\Models\Notification;

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

        // Extended upcoming tasks for scrolling demo
        $upcomingTasks = [
            ['title' => 'Chemistry Lab Report #3', 'course' => 'Science', 'due' => 'Dec 15, 2024', 'priority' => 'high'],
            ['title' => 'Essay Draft', 'course' => 'English', 'due' => 'Dec 16, 2024', 'priority' => 'high'],
            ['title' => 'Read Chapter 12–14', 'course' => 'Mathematics', 'due' => 'Dec 18, 2024', 'priority' => 'medium'],
            ['title' => 'Physics Problem Set 5', 'course' => 'Science', 'due' => 'Dec 19, 2024', 'priority' => 'high'],
            ['title' => 'History Research Paper', 'course' => 'History', 'due' => 'Dec 20, 2024', 'priority' => 'medium'],
            ['title' => 'Math Quiz Preparation', 'course' => 'Mathematics', 'due' => 'Dec 21, 2024', 'priority' => 'low'],
            ['title' => 'Literature Analysis', 'course' => 'English', 'due' => 'Dec 22, 2024', 'priority' => 'medium'],
            ['title' => 'Final Project Presentation', 'course' => 'Science', 'due' => 'Dec 23, 2024', 'priority' => 'high'],
            ['title' => 'Filipino Oral Exam', 'course' => 'Filipino', 'due' => 'Dec 24, 2024', 'priority' => 'medium'],
        ];
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
        
        // Fallback demo activities if no notifications
        $demoActivities = [
            ['type' => 'graded', 'title' => 'Assignment Graded', 'text' => 'Assignment graded: "Modern Literature Analysis" – A', 'time' => '2 hours ago', 'subject' => 'English', 'new' => false],
            ['type' => 'material', 'title' => 'New Material', 'text' => 'New notes uploaded: "Advanced Calculus – Ch. 5"', 'time' => '4 hours ago', 'subject' => 'Mathematics', 'new' => false],
            ['type' => 'assignment', 'title' => 'New Assignment', 'text' => 'New assignment released: "Physics Lab Report"', 'time' => '1 day ago', 'subject' => 'Science', 'new' => false],
            ['type' => 'graded', 'title' => 'Quiz Graded', 'text' => 'Quiz graded: "World War II Timeline" – B+', 'time' => '2 days ago', 'subject' => 'History', 'new' => false],
            ['type' => 'material', 'title' => 'Lecture Recording', 'text' => 'Lecture recording posted: "Organic Chemistry"', 'time' => '3 days ago', 'subject' => 'Science', 'new' => false],
            ['type' => 'assignment', 'title' => 'Assignment Due', 'text' => 'Assignment due: "Filipino Essay"', 'time' => '4 days ago', 'subject' => 'Filipino', 'new' => false],
            ['type' => 'graded', 'title' => 'Exam Graded', 'text' => 'Exam graded: "Linear Algebra" – A-', 'time' => '5 days ago', 'subject' => 'Mathematics', 'new' => false],
            ['type' => 'material', 'title' => 'Study Guide', 'text' => 'Study guide uploaded: "Shakespeare Analysis"', 'time' => '1 week ago', 'subject' => 'English', 'new' => false],
        ];
        
        // Combine real notifications with demo activities for a rich activity stream
        $activityStream = array_merge($notificationActivities, $demoActivities);

        // Extended calendar markers for demo
        $calendarMarks = [
            date('Y-m-') . '13' => 'deadline',
            date('Y-m-') . '15' => 'exam',
            date('Y-m-') . '18' => 'assignment',
            date('Y-m-') . '20' => 'deadline',
            date('Y-m-') . '22' => 'exam',
            date('Y-m-') . '25' => 'assignment',
            date('Y-m-') . '28' => 'deadline',
        ];

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
            'recentNotifications',
            'unreadCount'
        ));
    }
}
