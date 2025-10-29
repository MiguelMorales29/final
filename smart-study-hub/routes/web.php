<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ArchivedApplicationsController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseApplicationController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseListingController;
use App\Http\Controllers\EnrolledController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\SmartBuddyController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentModuleController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EmailTestController;
use App\Models\Course;
use App\Models\CourseWeek;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Role-based dashboard routes
Route::middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    
    // Teacher course management routes
    Route::get('/teacher/courses', [CourseController::class, 'index'])->name('teacher.courses.index');
    Route::get('/teacher/courses/create', [CourseController::class, 'create'])->name('teacher.courses.create');
    Route::post('/teacher/courses', [CourseController::class, 'store'])->name('teacher.courses.store');
    Route::get('/teacher/courses/{course}', [CourseController::class, 'show'])->name('teacher.courses.show');
    Route::get('/teacher/courses/{course}/edit', [CourseController::class, 'edit'])->name('teacher.courses.edit');
    Route::put('/teacher/courses/{course}', [CourseController::class, 'update'])->name('teacher.courses.update');
    Route::delete('/teacher/courses/{course}', [CourseController::class, 'destroy'])->name('teacher.courses.destroy');
    
        // Teacher material management routes
        Route::get('/teacher/upload-materials', [CourseController::class, 'selectCourseForUpload'])->name('teacher.upload-materials.select');
        Route::get('/teacher/courses/{course}/upload-materials', [CourseController::class, 'uploadMaterials'])->name('teacher.upload-materials');
        Route::post('/teacher/courses/{course}/materials', [CourseController::class, 'storeMaterial'])->name('teacher.materials.store');
        Route::get('/teacher/courses/{course}/materials/{material}/edit', [CourseController::class, 'editMaterial'])->name('teacher.materials.edit');
        Route::get('/teacher/materials/{material}/edit-data', [CourseController::class, 'getMaterialEditData'])->name('teacher.materials.edit-data');
        Route::get('/teacher/terms/{term}/weeks', [CourseController::class, 'getTermWeeks'])->name('teacher.terms.weeks');
        Route::put('/teacher/courses/{course}/materials/{material}', [CourseController::class, 'updateMaterial'])->name('teacher.materials.update');
        Route::post('/teacher/materials/{material}', [CourseController::class, 'updateMaterialAjax'])->name('teacher.materials.update-ajax');
        Route::delete('/teacher/courses/{course}/materials/{material}', [CourseController::class, 'deleteMaterial'])->name('teacher.materials.delete');
        
        // Teacher assignment management routes
        Route::get('/teacher/assignments', [AssignmentController::class, 'index'])->name('teacher.assignments.index');
        Route::get('/teacher/assignments/select-course', [AssignmentController::class, 'selectCourse'])->name('teacher.assignments.select-course');
Route::get('/teacher/courses/{course}/assignments', [AssignmentController::class, 'courseAssignments'])->name('teacher.course.assignments');
Route::get('/teacher/courses/{course}/assignments/create', [AssignmentController::class, 'create'])->name('teacher.assignments.create');
Route::get('/teacher/courses/{course}/assignments/preview', [AssignmentController::class, 'assignmentsPreview'])->name('teacher.course.assignments.preview');
        Route::get('/teacher/assignments/{assignment}/edit-data', [AssignmentController::class, 'getAssignmentEditData'])->name('teacher.assignments.edit-data');
        Route::post('/teacher/assignments', [AssignmentController::class, 'store'])->name('teacher.assignments.store');
        Route::get('/teacher/assignments/{assignment}', [AssignmentController::class, 'show'])->name('teacher.assignments.show');
        Route::get('/teacher/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('teacher.assignments.edit');
        Route::put('/teacher/assignments/{assignment}', [AssignmentController::class, 'update'])->name('teacher.assignments.update');
        Route::delete('/teacher/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('teacher.assignments.destroy');
        Route::post('/teacher/assignments/{assignment}/submissions/{submission}/grade', [AssignmentController::class, 'grade'])->name('teacher.assignments.grade');
        Route::get('/teacher/courses/{course}/weeks', [AssignmentController::class, 'getWeeks'])->name('teacher.assignments.weeks');
    
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
    
    // Teacher announcement routes
    Route::get('/teacher/announcements', [AnnouncementController::class, 'teacherIndex'])->name('teacher.announcements.index');
    Route::get('/teacher/announcements/create', [AnnouncementController::class, 'create'])->name('teacher.announcements.create');
    Route::post('/teacher/announcements', [AnnouncementController::class, 'store'])->name('teacher.announcements.store');
    Route::get('/teacher/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('teacher.announcements.edit');
    Route::put('/teacher/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('teacher.announcements.update');
    Route::delete('/teacher/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('teacher.announcements.destroy');
});

Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/calendar', [StudentDashboardController::class, 'calendar'])->name('student.calendar');
    
    // Student course browsing and enrollment
    Route::get('/courses', [CourseListingController::class, 'index'])->name('student.courses.browse');
    Route::get('/courses/{course}', [CourseController::class, 'showForStudent'])->name('student.course.show');
    Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'store'])->name('enroll');
    Route::delete('/courses/{id}/unenroll', [EnrollmentController::class, 'destroy'])->name('unenroll');
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('student.courses');
    
    // Student course applications
    Route::post('/courses/{course}/apply', [CourseApplicationController::class, 'apply'])->name('courses.apply');
    Route::delete('/applications/{application}/remove', [CourseApplicationController::class, 'removeFromView'])->name('applications.remove');
    
        // Student modules route
        Route::get('/student/modules', [StudentModuleController::class, 'index'])->name('student.modules.index');
        Route::get('/student/materials/{material}', [StudentModuleController::class, 'showMaterial'])->name('student.materials.show');
        Route::get('/student/materials/{material}/view', [StudentModuleController::class, 'viewPdf'])->name('student.materials.view');
        Route::get('/student/courses/{course}/materials', [StudentModuleController::class, 'allMaterials'])->name('student.course.all-materials');
        Route::get('/student/courses/{course}/assignments', [StudentModuleController::class, 'allAssignments'])->name('student.course.all-assignments');
        
        // Material completion tracking routes
        Route::post('/student/materials/{material}/mark-done', [StudentModuleController::class, 'markAsDone'])->name('student.materials.mark-done');
        Route::delete('/student/materials/{material}/unmark-done', [StudentModuleController::class, 'unmarkAsDone'])->name('student.materials.unmark-done');
        Route::get('/student/materials/{material}/completion-status', [StudentModuleController::class, 'getCompletionStatus'])->name('student.materials.completion-status');
        Route::get('/student/courses/{course}/progress', [StudentModuleController::class, 'getCourseProgress'])->name('student.course.progress');
    
    // Student assignment routes
    Route::get('/student/assignments', [StudentAssignmentController::class, 'index'])->name('student.assignments.index');
    Route::get('/student/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('student.assignments.show');
    Route::post('/student/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('student.assignments.submit');
    Route::delete('/student/assignments/{assignment}/submission', [StudentAssignmentController::class, 'destroy'])->name('student.assignments.destroy');
    
    // Student announcement routes
    Route::get('/student/announcements', [AnnouncementController::class, 'studentIndex'])->name('student.announcements.index');
    Route::get('/student/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('student.announcements.show');

    // Smart Buddy minimal NLP endpoint
    Route::post('/smart-buddy/nlp', [SmartBuddyController::class, 'nlp'])->name('smart-buddy.nlp');
});

// Legacy dashboard route (redirects based on role)
Route::get('/dashboard', function () {
    $user = auth()->user();
    switch ($user->role) {
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

// Email testing routes (for development only)
Route::get('/email-test', function () {
    return view('email-test');
})->name('email.test');
Route::post('/test-email', [EmailTestController::class, 'testEmail'])->name('test.email');
Route::post('/test-password-reset', [EmailTestController::class, 'testPasswordReset'])->name('test.password-reset');

// Model binding
Route::model('course', Course::class);
Route::model('week', CourseWeek::class);

require __DIR__.'/auth.php';
