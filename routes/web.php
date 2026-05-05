<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

// Import Controllers Utama
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SearchController;

// Import Controller Baru (Auth Tambahan)
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\OtpController; // Tambahan Baru

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AlumniStoryController as AdminAlumniStoryController;
use App\Http\Controllers\Admin\DashboardActionController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\EventRegistrationController as AdminEventRegistrationController;

// Student Controllers
use App\Http\Controllers\Student\PageController as StudentPageController;
use App\Http\Controllers\Student\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * PUBLIC ROUTES
 */
Route::get('/home-redirect', function () {
    return auth()->check() ? redirect()->route('student.home') : redirect()->route('public.beranda');
})->name('home');

Route::get('/', [PublicController::class, 'beranda'])->name('public.beranda');

// --- LOWONGAN PUBLIC ---
Route::get('/lowongan', [PublicController::class, 'lowongan'])->name('public.lowongan');
Route::get('/lowongan/{id}', [PublicController::class, 'lowonganDetail'])->name('public.lowongan.detail');

// --- BERITA PUBLIC ---
Route::get('/berita', [AdminNewsController::class, 'index_student'])->name('public.berita');
Route::get('/berita/{slug}', [AdminNewsController::class, 'show'])->name('public.berita.detail');

// --- ACARA ---
Route::get('/acara-mendatang', [PublicController::class, 'acara'])->name('public.acara');
Route::get('/acara/{id}', [PublicController::class, 'acaraDetail'])->name('public.acara.detail');
Route::post('/acara/{id}/register', [PublicController::class, 'storeEventRegistration'])->name('public.event.register');

// --- TRACER STUDY ---
Route::get('/tracer-study', [PublicController::class, 'tracer'])->name('public.tracer');
Route::post('/tracer-study/store', [PublicController::class, 'storeTracer'])
    ->middleware(['auth', 'student'])
    ->name('student.tracer.store');

Route::get('/tutorial', [PublicController::class, 'tutorial'])->name('public.tutorial');

/**
 * AUTH ROUTES
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');

    // --- Forgot Password (OTP) - Menggunakan OtpController Baru ---
    Route::prefix('forgot-password')->group(function () {
        Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('password.otp.send');
        Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('password.otp.check');
        Route::post('/reset', [OtpController::class, 'resetPassword'])->name('password.reset.update');
    });

    // --- Social Auth (Google) ---
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])
         ->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
         ->name('auth.google.callback');

    // --- Social Auth (Facebook) ---
    Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])
         ->name('auth.facebook');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])
         ->name('auth.facebook.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * STUDENT ROUTES
 */
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', fn () => redirect()->route('student.home'));

    // Profile
    Route::get('/profile', [StudentController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile-detail', [StudentController::class, 'profileDetail'])->name('profile.detail');

    // --- LOWONGAN STUDENT ---
    Route::get('/daftar-lowongan', [StudentController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan-tersimpan', [StudentController::class, 'savedJobs'])->name('saved-jobs');
    Route::post('/lowongan/{id}/save', [StudentController::class, 'saveJob'])->name('lowongan.save');
    Route::delete('/lowongan/unsave/{id}', [StudentController::class, 'unsaveJob'])->name('lowongan.unsave');
    Route::post('/lowongan/{id}/apply', [StudentController::class, 'applyJob'])->name('lowongan.apply');
    Route::get('/lowongan/{id}', [StudentController::class, 'detailLowongan'])->name('lowongan.detail');

    // --- ACARA STUDENT ---
    Route::get('/acara', [StudentController::class, 'acara'])->name('acara');
    Route::get('/acara/{id}', [StudentController::class, 'detailAcara'])->name('acara.detail');
    Route::post('/acara/{id}/daftar', [StudentController::class, 'daftarAcara'])->name('acara.daftar');

    // --- LAMARAN STUDENT ---
    Route::get('/lamaran', [StudentController::class, 'myApplications'])->name('applications');
    Route::delete('/lamaran/{id}', [StudentController::class, 'deleteApplication'])->name('applications.delete');

    // --- BERITA STUDENT ---
    Route::get('/tracer', fn () => redirect()->route('public.tracer'))->name('tracer');
    Route::get('/berita', [AdminNewsController::class, 'index_student'])->name('berita');
    Route::get('/berita/{slug}', [AdminNewsController::class, 'show'])->name('berita.detail');

    // Informasional
    Route::get('/bantuan', [StudentPageController::class, 'bantuan'])->name('bantuan');
    Route::get('/tentang', [StudentPageController::class, 'tentang'])->name('tentang');
});

/**
 * ADMIN ROUTES
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::resource('news', AdminNewsController::class);
    Route::resource('events', AdminEventController::class);

    Route::get('/export-data', [DashboardActionController::class, 'export'])->name('export');
    Route::get('/laporan-cepat', [DashboardActionController::class, 'laporan'])->name('laporan');
    Route::get('/broadcast', [DashboardActionController::class, 'broadcast'])->name('broadcast');

    // Companies
    Route::prefix('companies')->name('companies.')->middleware('permission:manage_companies')->group(function () {
        Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
        Route::get('/create', [AdminCompanyController::class, 'create'])->name('create');
        Route::post('/', [AdminCompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [AdminCompanyController::class, 'show'])->name('show');
        Route::get('/{company}/edit', [AdminCompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [AdminCompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [AdminCompanyController::class, 'destroy'])->name('destroy');
    });

    // Jobs
    Route::prefix('jobs')->name('jobs.')->middleware('permission:manage_jobs')->group(function () {
        Route::get('/', [AdminJobController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobController::class, 'create'])->name('create');
        Route::post('/', [AdminJobController::class, 'store'])->name('store');
        Route::get('/{job}', [AdminJobController::class, 'show'])->name('show');
        Route::get('/{job}/edit', [AdminJobController::class, 'edit'])->name('edit');
        Route::put('/{job}', [AdminJobController::class, 'update'])->name('update');
        Route::delete('/{job}', [AdminJobController::class, 'destroy'])->name('destroy');
    });

    // Job Applications
    Route::prefix('job-applications')->name('job-applications.')->middleware('permission:manage_job_applications')->group(function () {
        Route::get('/', [AdminJobApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminJobApplicationController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminJobApplicationController::class, 'updateStatus'])->name('update-status');
    });

    // Event Registrations
    Route::prefix('event-registrations')->name('event-registrations.')->group(function () {
        Route::get('/', [AdminEventRegistrationController::class, 'index'])->name('index');
        Route::put('/{id}', [AdminEventRegistrationController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminEventRegistrationController::class, 'destroy'])->name('destroy');
    });

    // Students
    Route::prefix('students')->name('students.')->middleware('permission:manage_students')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminStudentController::class, 'show'])->name('show');
    });

    // Users
    Route::prefix('users')->name('users.')->middleware('permission:manage_users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::put('/{id}/status', [AdminUserController::class, 'updateStatus'])->name('update-status');
    });

    // Roles
    Route::prefix('roles')->name('roles.')->middleware('permission:manage_settings')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->name('index');
        Route::put('/{role}', [AdminRoleController::class, 'update'])->name('update');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->middleware('permission:manage_settings')->group(function () {
        Route::get('/profile', [AdminSettingController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminSettingController::class, 'updateProfile'])->name('profile.update');

        Route::get('/majors', [AdminSettingController::class, 'majorsIndex'])->name('majors.index');
        Route::post('/majors', [AdminSettingController::class, 'storeMajor'])->name('majors.store');
        Route::get('/majors/{major}/edit', [AdminSettingController::class, 'editMajor'])->name('majors.edit');
        Route::put('/majors/{major}', [AdminSettingController::class, 'updateMajor'])->name('majors.update');
        Route::delete('/majors/{major}', [AdminSettingController::class, 'destroyMajor'])->name('majors.destroy');

        Route::get('/years', [AdminSettingController::class, 'yearsIndex'])->name('years.index');
        Route::post('/years', [AdminSettingController::class, 'storeYear'])->name('years.store');
        Route::get('/years/{year}/edit', [AdminSettingController::class, 'editYear'])->name('years.edit');
        Route::put('/years/{year}', [AdminSettingController::class, 'updateYear'])->name('years.update');
        Route::delete('/years/{year}', [AdminSettingController::class, 'destroyYear'])->name('years.destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/export/alumni/csv', [AdminReportController::class, 'exportAlumniCsv'])->name('export.alumni.csv');
        Route::get('/export/jobs/csv', [AdminReportController::class, 'exportJobsCsv'])->name('export.jobs.csv');
        Route::get('/export/alumni/print', [AdminReportController::class, 'printAlumni'])->name('export.alumni.print');
        Route::get('/export/jobs/print', [AdminReportController::class, 'printJobs'])->name('export.jobs.print');
    });

    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])
        ->name('activity-logs.index')
        ->middleware('permission:view_activity_logs');

    // Alumni Stories
    Route::prefix('alumni-stories')->name('alumni-stories.')->group(function () {
        Route::get('/', [AdminAlumniStoryController::class, 'index'])->name('index');
        Route::delete('/{id}', [AdminAlumniStoryController::class, 'destroy'])->name('destroy');
    });

    // Notifications
    Route::get('/notifications', function () {
        $users = User::latest()->limit(5)->get();
        return response()->json($users->map(fn ($u) => [
            'title' => 'User baru: ' . $u->email,
            'time'  => $u->created_at->diffForHumans(),
            'link'  => route('admin.users.index'),
        ]));
    })->name('notifications');
}); 