<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

// Import Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SearchController;

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
Route::get('/', [PublicController::class, 'beranda'])->name('public.home');
Route::get('/beranda', [PublicController::class, 'beranda'])->name('public.beranda');
Route::get('/home', fn() => redirect('/'));

Route::get('/lowongan-kerja', [PublicController::class, 'lowongan'])->name('public.lowongan');
Route::get('/lowongan/{id}', [PublicController::class, 'lowonganDetail'])->name('public.lowongan.detail');
Route::get('/berita-terbaru', [PublicController::class, 'berita'])->name('public.berita');
Route::get('/berita/{id}', [PublicController::class, 'beritaDetail'])->name('public.berita.detail');
Route::get('/acara-mendatang', [PublicController::class, 'acara'])->name('public.acara');
Route::get('/tracer-study', [PublicController::class, 'tracer'])->name('public.tracer');
Route::get('/tutorial', [PublicController::class, 'tutorial'])->name('public.tutorial');

/**
 * AUTH ROUTES
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * STUDENT ROUTES
 */
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Profil
    Route::get('/profile', [StudentController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profil-lengkap', [StudentPageController::class, 'profil'])->name('profil.page');

    // Pages
    Route::get('/lowongan', [StudentPageController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/{id}', [StudentPageController::class, 'lowonganDetail'])->name('lowongan.detail');
    Route::get('/berita', [StudentPageController::class, 'berita'])->name('berita');
    Route::get('/berita/{id}', [StudentPageController::class, 'beritaDetail'])->name('berita.detail');
    Route::get('/acara', [StudentPageController::class, 'acara'])->name('acara');
    Route::get('/tracer', [StudentPageController::class, 'tracer'])->name('tracer');
});

/**
 * ADMIN ROUTES
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Utama & Search
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Tombol Aksi Cepat Dashboard (Fitur dari HEAD lu)
    Route::get('/export-data', [DashboardActionController::class, 'export'])->name('export');
    Route::get('/laporan-cepat', [DashboardActionController::class, 'laporan'])->name('laporan');
    Route::get('/broadcast', [DashboardActionController::class, 'broadcast'])->name('broadcast');

    // Manajemen Perusahaan
    Route::prefix('companies')->name('companies.')->middleware('permission:manage_companies')->group(function () {
        Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
        Route::get('/create', [AdminCompanyController::class, 'create'])->name('create');
        Route::post('/', [AdminCompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [AdminCompanyController::class, 'show'])->name('show');
        Route::get('/{company}/edit', [AdminCompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [AdminCompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [AdminCompanyController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Lowongan
    Route::prefix('jobs')->name('jobs.')->middleware('permission:manage_jobs')->group(function () {
        Route::get('/', [AdminJobController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobController::class, 'create'])->name('create');
        Route::post('/', [AdminJobController::class, 'store'])->name('store');
        Route::get('/{job}', [AdminJobController::class, 'show'])->name('show');
        Route::get('/{job}/edit', [AdminJobController::class, 'edit'])->name('edit');
        Route::put('/{job}', [AdminJobController::class, 'update'])->name('update');
        Route::delete('/{job}', [AdminJobController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Lamaran
    Route::prefix('job-applications')->name('job-applications.')->middleware('permission:manage_job_applications')->group(function () {
        Route::get('/', [AdminJobApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminJobApplicationController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminJobApplicationController::class, 'updateStatus'])->name('update-status');
    });

    // Manajemen Siswa
    Route::prefix('students')->name('students.')->middleware('permission:manage_students')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminStudentController::class, 'show'])->name('show');
    });

    // Manajemen User & Role
    Route::prefix('users')->name('users.')->middleware('permission:manage_users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::put('/{id}/status', [AdminUserController::class, 'updateStatus'])->name('update-status');
    });

    Route::prefix('roles')->name('roles.')->middleware('permission:manage_settings')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->name('index');
        Route::put('/{role}', [AdminRoleController::class, 'update'])->name('update');
    });

    // Pengaturan Sistem
    Route::prefix('settings')->name('settings.')->middleware('permission:manage_settings')->group(function () {
        Route::get('/profile', [AdminSettingController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminSettingController::class, 'updateProfile'])->name('profile.update');

        // Majors & Years
        Route::resource('majors', AdminSettingController::class)->except(['show']);
        Route::resource('years', AdminSettingController::class)->except(['show']);
    });

    // Laporan & Log
    Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/export/alumni/csv', [AdminReportController::class, 'exportAlumniCsv'])->name('export.alumni.csv');
        Route::get('/export/jobs/csv', [AdminReportController::class, 'exportJobsCsv'])->name('export.jobs.csv');
        Route::get('/export/alumni/print', [AdminReportController::class, 'printAlumni'])->name('export.alumni.print');
        Route::get('/export/jobs/print', [AdminReportController::class, 'printJobs'])->name('export.jobs.print');
    });

    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index')->middleware('permission:view_activity_logs');

    // Alumni Stories
    Route::prefix('alumni-stories')->name('alumni-stories.')->group(function () {
        Route::get('/', [AdminAlumniStoryController::class, 'index'])->name('index');
        Route::delete('/{id}', [AdminAlumniStoryController::class, 'destroy'])->name('destroy');
    });

    // JSON Notifications
    Route::get('/notifications', function () {
        $users = User::latest()->limit(5)->get();
        return response()->json($users->map(fn($u) => [
            'title' => 'User baru: ' . $u->email,
            'time'  => $u->created_at->diffForHumans(),
            'link'  => route('admin.users.index')
        ]));
    })->name('notifications');
});
