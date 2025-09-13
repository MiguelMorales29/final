<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseListingController;
use App\Http\Controllers\EnrollmentController;
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
});

Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    
    // Student course browsing and enrollment
    Route::get('/courses', [CourseListingController::class, 'index'])->name('student.courses.browse');
    Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'store'])->name('enroll');
    Route::delete('/courses/{id}/unenroll', [EnrollmentController::class, 'destroy'])->name('unenroll');
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('student.courses');
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
});

require __DIR__.'/auth.php';
