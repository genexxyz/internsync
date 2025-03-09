<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Supervisors;
use App\Http\Controllers\UserFormController;
use App\Http\Controllers\VerificationEmailController;
use App\Livewire\Auth\EmailVerification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TestUploadController;
use App\Livewire\Student\AcceptanceLetterForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Livewire\Supervisor\CompleteProfileForm;
use App\Http\Middleware\SupervisorFirstLoginMiddleware;
use App\Livewire\Supervisor\Menu;

Route::get('document/preview/{path}', function (Request $request, $path) {
    if (!$request->hasValidSignature()) {
        abort(401);
    }

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Response::file(storage_path('app/public/' . $path));
})
->where('path', '.*')
->name('document.preview')
->middleware('web');

Route::get('test-upload', [TestUploadController::class, 'showForm']);
Route::post('test-upload', [TestUploadController::class, 'upload'])->name('test.upload');
// Route::get('/', function () {
//     return view('auth.login');
// });
// Default login route

Route::get('/', function () {
    if (Auth::check()) {
        
        // Redirect authenticated users based on their roles
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'supervisor' => redirect()->route('supervisor.dashboard'),
            default => redirect('/unauthorized'),
        };
    }
    return view('auth.login'); // Login page for unauthenticated users
});


Route::get('/auth/verify-email/{email}', EmailVerification::class)->name('verify.email');


// Unauthorized page
Route::get('/unauthorized', function () {
    return response()->view('errors.403', [], 403);
})->name('unauthorized');

Route::get('/404', function () {
    return response()->view('errors.404', [], 404);
})->name('404');

// Admin dashboard
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard (custom route, since REST doesn't have "dashboard")
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Instructors (listing)
        Route::get('/instructors', [AdminController::class, 'instructors'])->name('instructors');

        // Courses (index and show)
        Route::get('/courses', [CourseController::class, 'index'])->name('courses'); // List all courses
        Route::get('/courses/{course_code}', [CourseController::class, 'show'])->name('courses.show'); // Show a specific course

        // Sections under a course (specific section show)
        Route::get('/courses/{course_code}/{year_level}{class_section}', [SectionController::class, 'show'])->name('courses.sections.show');
        Route::get('/companies', [CompanyController::class, 'index'])->name('company');
        Route::get('/supervisors', [AdminController::class, 'supervisors'])->name('supervisors');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    });
});


// Student dashboard
Route::middleware(['auth', RoleMiddleware::class . ':student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/student/ojt-journey', [StudentController::class, 'journey'])->name('student.journey');
    Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/student/task-and-attendance', [StudentController::class, 'taskAttendance'])->name('student.taskAttendance');
    Route::get('/student/ojt-document', [StudentController::class, 'ojtDocument'])->name('student.document');
    Route::get('/documents/acceptance-letter', AcceptanceLetterForm::class)->name('student.acceptance-letter');
    Route::get('/weekly-report/{report}/pdf', [StudentController::class, 'generateWeeklyReportPdf'])
        ->name('student.weekly-report.pdf');
});

// Instructor dashboard
Route::middleware(['auth', RoleMiddleware::class . ':instructor'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
    Route::get('/instructor/task-and-attendance', [InstructorController::class, 'taskAttendance'])->name('instructor.taskAttendance');
    Route::get('/instructor/deployments/section', [InstructorController::class, 'deployments'])->name('instructor.deployments.section');
    Route::get('/instructor/deployments/section/{course_code}/{year_level}{class_section}', [DeploymentController::class, 'sectionDeployment'])->name('instructor.deployments.section.show');
    Route::get('/instructor/deployments/filter/{filter}', [InstructorController::class, 'filterDeployments'])->name('instructor.deployments.filter');
});

// Supervisor dashboard
Route::middleware(['auth', RoleMiddleware::class . ':supervisor'])->group(function () {
    // First time profile completion - doesn't need the first login middleware check
    // Route::get('/supervisor/complete-form', [SupervisorController::class, 'completeForm'])
    //     ->name('supervisor.complete-form');
    
    // // Regular supervisor routes - add the first login middleware
    // Route::middleware([SupervisorFirstLoginMiddleware::class])->group(function () {
    // });
        Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])
            ->name('supervisor.dashboard');
        Route::get('/supervisor/weekly-reports', [SupervisorController::class, 'weeklyReports'])
            ->name('supervisor.weeklyReports');
        Route::get('/weekly-reports/{report}', [SupervisorController::class, 'viewWeeklyReport'])
            ->name('supervisor.weekly-reports.view');
        Route::get('/supervisor/evaluation', [SupervisorController::class, 'evaluation'])
            ->name('supervisor.evaluation');
        Route::get('/supervisor/interns', [SupervisorController::class, 'interns'])
            ->name('supervisor.interns');
    
});

// Profile management (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class,'destroy'])->name('logout');
});


Route::get('/send-email', [EmailController::class, 'sendSimpleEmail']);
Route::get('/send-email-with-attachment', [EmailController::class, 'sendEmailWithAttachment']);






require __DIR__.'/auth.php';