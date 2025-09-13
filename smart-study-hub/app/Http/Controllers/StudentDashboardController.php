<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Combine enrolled and locked courses
        $allCourses = array_merge($myCourses, $lockedCourses);
        
        $approvedCoursesCount = count($myCourses);
        $avgProgress = $approvedCoursesCount ? intval(collect($myCourses)->avg('progress')) : 0;

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

        // Extended activity stream for scrolling demo
        $activityStream = [
            ['type' => 'grade', 'text' => 'Assignment graded: "Modern Literature Analysis" – A', 'time' => '2 hours ago', 'tag' => 'English'],
            ['type' => 'material', 'text' => 'New notes uploaded: "Advanced Calculus – Ch. 5"', 'time' => '4 hours ago', 'tag' => 'Mathematics'],
            ['type' => 'assignment', 'text' => 'New assignment released: "Physics Lab Report"', 'time' => '1 day ago', 'tag' => 'Science'],
            ['type' => 'grade', 'text' => 'Quiz graded: "World War II Timeline" – B+', 'time' => '2 days ago', 'tag' => 'History'],
            ['type' => 'material', 'text' => 'Lecture recording posted: "Organic Chemistry"', 'time' => '3 days ago', 'tag' => 'Science'],
            ['type' => 'assignment', 'text' => 'Assignment due: "Filipino Essay"', 'time' => '4 days ago', 'tag' => 'Filipino'],
            ['type' => 'grade', 'text' => 'Exam graded: "Linear Algebra" – A-', 'time' => '5 days ago', 'tag' => 'Mathematics'],
            ['type' => 'material', 'text' => 'Study guide uploaded: "Shakespeare Analysis"', 'time' => '1 week ago', 'tag' => 'English'],
        ];

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
            'calendarMarks'
        ));
    }
}
