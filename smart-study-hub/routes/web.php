<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArchivedApplicationsController;
use App\Http\Controllers\CourseApplicationController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseListingController;
use App\Http\Controllers\EnrolledController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Role-based dashboard routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Admin enrollment management
    Route::get('/admin/enrollments', [EnrollmentController::class, 'allEnrollments'])->name('admin.enrollments');
});

Route::middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    
    // Teacher course management routes
    Route::get('/teacher/courses', [CourseController::class, 'index'])->name('teacher.courses.index');
    Route::get('/teacher/courses/create', [CourseController::class, 'create'])->name('teacher.courses.create');
    Route::post('/teacher/courses', [CourseController::class, 'store'])->name('teacher.courses.store');
    Route::get('/teacher/courses/{course}/edit', [CourseController::class, 'edit'])->name('teacher.courses.edit');
    Route::put('/teacher/courses/{course}', [CourseController::class, 'update'])->name('teacher.courses.update');
    Route::delete('/teacher/courses/{course}', [CourseController::class, 'destroy'])->name('teacher.courses.destroy');
    
    // Teacher enrollment management
    Route::get('/teacher/courses/{id}/students', [EnrollmentController::class, 'courseStudents'])->name('teacher.course.students');
    Route::delete('/teacher/courses/{courseId}/students/{studentId}', [EnrollmentController::class, 'dropStudent'])->name('teacher.drop.student');
    
    // Teacher enrolled students management
    Route::get('/enrolled', [EnrolledController::class, 'allEnrolled'])->name('teacher.enrolled.all');
    Route::get('/courses/{course}/enrolled', [EnrolledController::class, 'index'])->name('teacher.course.enrolled');
    Route::post('/courses/{course}/attendance', [EnrolledController::class, 'markAttendance'])->name('teacher.attendance.mark');
    Route::post('/attendance/mark', [EnrolledController::class, 'markAttendanceGlobal'])->name('teacher.attendance.mark.global');
    Route::post('/courses/{course}/drop-student', [EnrolledController::class, 'dropStudent'])->name('teacher.enrolled.drop');
    Route::get('/students/{student}/profile', [EnrolledController::class, 'getStudentProfile'])->name('teacher.student.profile');
    Route::get('/courses/{course}/attendance/date', [EnrolledController::class, 'getAttendanceForDate'])->name('teacher.attendance.date');
    Route::post('/attendance/roll-call-submit', [EnrolledController::class, 'submitRollCall'])->name('teacher.attendance.roll-call-submit');
    
    // Teacher application management
    Route::get('/teacher/applications', [CourseApplicationController::class, 'index'])->name('teacher.applications.index');
    Route::get('/teacher/applications/archived', [ArchivedApplicationsController::class, 'index'])->name('teacher.applications.archived');
    Route::post('/applications/{application}/approve', [CourseApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [CourseApplicationController::class, 'reject'])->name('applications.reject');
    Route::post('/applications/{application}/archive', [CourseApplicationController::class, 'archive'])->name('applications.archive');
    Route::post('/applications/bulk-archive', [CourseApplicationController::class, 'bulkArchive'])->name('applications.bulk-archive');
    Route::post('/applications/archive-all', [CourseApplicationController::class, 'archiveAll'])->name('applications.archive-all');
    Route::post('/applications/{application}/unarchive', [ArchivedApplicationsController::class, 'unarchive'])->name('applications.unarchive');
    Route::post('/applications/bulk-unarchive', [ArchivedApplicationsController::class, 'bulkUnarchive'])->name('applications.bulk-unarchive');
});

Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    
    // Student course browsing and enrollment
    Route::get('/courses', [CourseListingController::class, 'index'])->name('student.courses.browse');
    Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'store'])->name('enroll');
    Route::delete('/courses/{id}/unenroll', [EnrollmentController::class, 'destroy'])->name('unenroll');
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('student.courses');
    
    // Student course applications
    Route::post('/courses/{course}/apply', [CourseApplicationController::class, 'apply'])->name('courses.apply');
    Route::delete('/applications/{application}/remove', [CourseApplicationController::class, 'removeFromView'])->name('applications.remove');
});

// Legacy dashboard route (redirects based on role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'teacher':
            return redirect()->route('teacher.dashboard');
        case 'student':
        default:
            return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'recent'])->name('api.notifications.recent');
});

require __DIR__.'/auth.php';
