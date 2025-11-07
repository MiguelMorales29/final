<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EnrolledController extends Controller
{
    public function allEnrolled()
    {
        $teacher = auth()->user();
        
        // Get all courses for this teacher with their enrolled students
        $courses = $teacher->courses()
            ->with(['enrollments.student'])
            ->get()
            ->map(function ($course) {
                $enrolledStudents = $course->enrollments->map(function ($enrollment) use ($course) {
                    return [
                        'enrollment' => $enrollment,
                        'student' => $enrollment->student,
                        'section' => $course->section ?? 'Default',
                        'course_title' => $course->title,
                        'course_id' => $course->id,
                        'attendance_today' => null // Will be populated by JavaScript
                    ];
                });
                
                return [
                    'course' => $course,
                    'students' => $enrolledStudents,
                    'section' => $course->section ?? 'Default'
                ];
            });

        // Get all unique sections from courses that have students
        $sections = $courses->filter(function ($courseData) {
            return count($courseData['students']) > 0;
        })->pluck('section')->unique()->values();
        
        // If no sections found, add a default one
        if ($sections->isEmpty()) {
            $sections = collect(['Default']);
        }
        
        // Get attendance records for the current month
        $currentMonth = now()->format('Y-m');
        $attendanceRecords = Attendance::whereIn('course_id', $courses->pluck('course.id'))
            ->where('date', 'like', $currentMonth . '%')
            ->get()
            ->groupBy(['student_id', 'date']);

        return view('teacher.all-enrolled', compact('courses', 'sections', 'attendanceRecords'));
    }

    public function index(Course $course)
    {
        // Get enrolled students
        $enrolledStudents = $course->enrollments()
            ->with(['student'])
            ->get()
            ->map(function ($enrollment) use ($course) {
                return [
                    'enrollment' => $enrollment,
                    'student' => $enrollment->student,
                    'section' => $course->section ?? 'Default',
                    'attendance_today' => null // Will be populated by JavaScript
                ];
            });

        // Get attendance records for the current month
        $currentMonth = now()->format('Y-m');
        $attendanceRecords = Attendance::where('course_id', $course->id)
            ->where('date', 'like', $currentMonth . '%')
            ->get()
            ->groupBy(['student_id', 'date']);

        return view('teacher.enrolled', compact('course', 'enrolledStudents', 'attendanceRecords'));
    }

    public function markAttendance(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent'
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'course_id' => $course->id,
                'date' => $request->date
            ],
            [
                'status' => $request->status
            ]
        );

        return response()->json([
            'success' => true,
            'attendance' => $attendance
        ]);
    }

    public function dropStudent(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id'
        ]);

        DB::transaction(function () use ($course, $request) {
            // Remove from enrollments
            $course->enrollments()->where('student_id', $request->student_id)->delete();
            
            // Update course application status to dropped
            $course->applications()
                ->where('student_id', $request->student_id)
                ->where('status', 'approved')
                ->update(['status' => 'dropped']);
        });

        return response()->json(['success' => true]);
    }

    public function getStudentProfile(User $student)
    {
        $student->loadMissing(['enrollments.course']);

        $profilePictureUrl = null;
        if (!empty($student->profile_picture)) {
            $profilePictureUrl = Storage::url($student->profile_picture);
        } elseif (!empty($student->profile_photo_path)) {
            $profilePictureUrl = Storage::url($student->profile_photo_path);
        } elseif (!empty($student->profile_photo_url ?? null)) {
            $profilePictureUrl = $student->profile_photo_url;
        }

        $courses = $student->enrollments->map(function ($enrollment) {
            $course = $enrollment->course;

            return [
                'id' => $course?->id,
                'title' => $course?->title,
                'section' => $course?->section,
                'enrolled_at' => optional($enrollment->enrolled_at)->toDateString(),
            ];
        })->filter(function ($course) {
            return !empty($course['title']);
        })->values();

        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'student_number' => $student->student_number,
                'bio' => $student->bio,
                'gender' => $student->gender,
                'birth_date' => optional($student->birth_date)->toDateString(),
                'birth_date_formatted' => optional($student->birth_date)->format('M j, Y'),
                'profile_picture_url' => $profilePictureUrl,
                'courses' => $courses,
            ],
        ]);
    }

    public function getAttendanceForDate(Request $request, Course $course)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $attendance = Attendance::where('course_id', $course->id)
            ->where('date', $request->date)
            ->with('student')
            ->get();

        return response()->json($attendance);
    }

    public function markAttendanceGlobal(Request $request)
    {
        \Log::info('Attendance marking request received:', $request->all());
        
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late'
        ]);

        \Log::info('Validation passed, creating attendance record');

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'date' => $request->date
            ],
            [
                'status' => $request->status
            ]
        );

        \Log::info('Attendance record created/updated:', $attendance->toArray());

        return response()->json([
            'success' => true,
            'attendance' => $attendance,
            'course_id' => $attendance->course_id,
            'student_id' => $attendance->student_id
        ]);
    }

    public function submitRollCall(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array'
        ]);

        $course = Course::findOrFail($request->course_id);
        $teacher = auth()->user();

        // Update attendance records
        foreach ($request->attendance_data as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $request->course_id,
                    'date' => $request->date
                ],
                [
                    'status' => $status
                ]
            );
        }

        // Get all students in the course
        $students = $course->enrollments()->with('student')->get();
        
        // Send notification to all students
        foreach ($students as $enrollment) {
            $student = $enrollment->student;
            
            // Create notification
            $student->notifications()->create([
                'type' => 'attendance_roll_call',
                'title' => 'Roll Call Attendance Submitted',
                'message' => "Roll call attendance for {$course->title} has been submitted for " . now()->format('M d, Y'),
                'data' => json_encode([
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'date' => $request->date,
                    'teacher_name' => $teacher->name
                ])
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Roll call attendance submitted successfully. All students have been notified.',
            'notified_students' => $students->count()
        ]);
    }
}