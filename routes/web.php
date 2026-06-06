<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
// Import Controllers Utama
use App\Http\Controllers\Admin\AlumniStoryController;
use App\Http\Controllers\Admin\BroadcastController as AdminBroadcastController;
use App\Http\Controllers\Admin\CompanyAccountController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DashboardActionController;
// Import Controller Baru (Auth Tambahan)
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
// Import Company Controller
use App\Http\Controllers\Admin\EventController as AdminEventController;
// Admin Controllers
use App\Http\Controllers\Admin\EventRegistrationController as AdminEventRegistrationController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PublikController as AdminPublikController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TipController as AdminTipController;
use App\Http\Controllers\Admin\TracerStudyController as AdminTracerStudyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Company\CompanyPanelController;
use App\Http\Controllers\IndustryTracerController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\SearchController;
// Student Controllers
use App\Http\Controllers\Student\HomeController;
use App\Http\Controllers\Student\PageController as StudentPageController;
// Import TracerStudyController untuk form publik
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TracerStudyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * PUBLIC ROUTES
 */
Route::get('/home-redirect', function () {
    if (! auth()->check()) {
        return redirect()->route('public.beranda');
    }

    $role = auth()->user()->role->name;

    return match ($role) {
        'publik' => redirect()->route('publik.home'),
        'alumni' => redirect()->route('alumni.home'),
        'siswa' => redirect()->route('student.home'),
        'perusahaan' => redirect()->route('company.dashboard'),
        default => redirect()->route('admin.dashboard'),
    };
})->name('home');

Route::get('/', [PublikController::class, 'beranda'])->name('public.beranda');

Route::get('/lowongan', [PublikController::class, 'lowongan'])->name('public.lowongan');
Route::get('/lowongan-tersimpan', [StudentController::class, 'savedJobs'])
    ->middleware(['auth'])
    ->name('universal.saved-jobs');
Route::post('/lowongan/apply-universal/{id}', [StudentController::class, 'applyJob'])
    ->middleware(['auth'])
    ->name('universal.apply');
Route::post('/lowongan/{id}/save-toggle', [StudentController::class, 'saveJob'])
    ->middleware(['auth'])
    ->name('universal.save');
Route::get('/lowongan/{id}', [PublikController::class, 'lowonganDetail'])->name('public.lowongan.detail');

Route::get('/berita', [AdminNewsController::class, 'index_student'])->name('public.berita');
Route::get('/berita/{slug}', [AdminNewsController::class, 'show'])->name('public.berita.detail');

Route::get('/acara-mendatang', [PublikController::class, 'acara'])->name('public.acara');
Route::get('/acara/{id}', [PublikController::class, 'acaraDetail'])->name('public.acara.detail');
Route::post('/acara/{id}/register', [PublikController::class, 'storeEventRegistration'])->name('public.event.register');

Route::get('/tracer-study-report', [PublikController::class, 'tracerReport'])->name('tracer.report');

Route::get('/tutorial', [PublikController::class, 'tutorial'])->name('public.tutorial');
Route::get('/tips', [PublikController::class, 'tips'])->name('public.tips');
Route::get('/tips/{slug}', [PublikController::class, 'tipsDetail'])->name('public.tips.detail');

Route::get('/alumni-stories', [PublikController::class, 'alumniStories'])->name('public.alumni-stories');
Route::post('/alumni-stories', [AlumniStoryController::class, 'store'])->name('alumni-stories.store');

/**
 * TRACER STUDY PUBLIC ROUTES (requires auth)
 */
Route::middleware(['auth'])->group(function () {
    Route::get('/tracer-study', [TracerStudyController::class, 'index'])->name('public.tracer');
    Route::post('/tracer-study/store', [TracerStudyController::class, 'store'])->name('student.tracer.store');
});

/**
 * AUTH ROUTES
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', fn () => redirect()->route('login'));

    // OAuth Routes
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * PROFILE GLOBAL
 */
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        $user = auth()->user();
        // Redirect company users to company dashboard
        if ($user->role->name === 'perusahaan') {
            return redirect()->route('company.dashboard');
        }

        // For other users (students, alumni, publik), show profile
        return app(StudentController::class)->showProfile();
    })->name('profile');
    Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::get('/lamaran-saya', [StudentController::class, 'myApplications'])->name('universal.applications');
});

/**
 * ALUMNI ROUTES
 */
Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', fn () => redirect()->route('alumni.home'));

    Route::get('/daftar-lowongan', [StudentController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/{id}', [StudentController::class, 'detailLowongan'])->name('lowongan.detail');
    Route::post('/lowongan/{id}/apply', [StudentController::class, 'applyJob'])->name('lowongan.apply');
    Route::post('/lowongan/{id}/save', [StudentController::class, 'saveJob'])->name('lowongan.save');
    Route::delete('/lowongan/unsave/{id}', [StudentController::class, 'unsaveJob'])->name('lowongan.unsave');

    Route::get('/acara', [StudentController::class, 'acara'])->name('acara');
    Route::get('/acara/{id}', [StudentController::class, 'detailAcara'])->name('acara.detail');
    Route::post('/acara/{id}/daftar', [StudentController::class, 'daftarAcara'])->name('acara.daftar');

    Route::get('/lamaran', [StudentController::class, 'myApplications'])->name('applications');
    Route::delete('/lamaran/{id}', [StudentController::class, 'deleteApplication'])->name('applications.delete');

    Route::get('/berita', [AdminNewsController::class, 'index_student'])->name('berita');
    Route::get('/berita/{slug}', [AdminNewsController::class, 'show'])->name('berita.detail');
});

/**
 * PUBLIK ROUTES
 */
Route::middleware(['auth', 'role:publik'])->prefix('publik')->name('publik.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', fn () => redirect()->route('publik.home'));

    Route::get('/daftar-lowongan', [StudentController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/{id}', [StudentController::class, 'detailLowongan'])->name('lowongan.detail');
    Route::post('/lowongan/{id}/apply', [StudentController::class, 'applyJob'])->name('lowongan.apply');

    Route::get('/berita', [AdminNewsController::class, 'index_student'])->name('berita');
    Route::get('/berita/{slug}', [AdminNewsController::class, 'show'])->name('berita.detail');

    Route::get('/acara', [StudentController::class, 'acara'])->name('acara');
    Route::get('/acara/{id}', [StudentController::class, 'detailAcara'])->name('acara.detail');
    Route::post('/acara/{id}/daftar', [StudentController::class, 'daftarAcara'])->name('acara.daftar');

    Route::get('/tracer', fn () => redirect()->route('public.tracer'))->name('tracer');
});

/**
 * STUDENT ROUTES
 */
Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', fn () => redirect()->route('student.home'));

    Route::get('/profile-detail', [StudentController::class, 'profileDetail'])->name('profile.detail');

    Route::get('/daftar-lowongan', [StudentController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan-tersimpan', [StudentController::class, 'savedJobs'])->name('saved-jobs');
    Route::post('/lowongan/save/{id}', [StudentController::class, 'saveJob'])->name('lowongan.save');
    Route::delete('/lowongan/unsave/{id}', [StudentController::class, 'unsaveJob'])->name('lowongan.unsave');
    Route::post('/lowongan/apply/{id}', [StudentController::class, 'applyJob'])->name('lowongan.apply');
    Route::get('/lowongan/{id}', [StudentController::class, 'detailLowongan'])->name('lowongan.detail');

    Route::get('/acara', [StudentController::class, 'acara'])->name('acara');
    Route::get('/acara/{id}', [StudentController::class, 'detailAcara'])->name('acara.detail');
    Route::post('/acara/{id}/daftar', [StudentController::class, 'daftarAcara'])->name('acara.daftar');

    Route::get('/lamaran', [StudentController::class, 'myApplications'])->name('applications');
    Route::delete('/lamaran/{id}', [StudentController::class, 'deleteApplication'])->name('applications.delete');

    Route::get('/tracer', fn () => redirect()->route('public.tracer'))->name('tracer');

    Route::get('/berita', [AdminNewsController::class, 'index_student'])->name('berita');
    Route::get('/berita/{slug}', [AdminNewsController::class, 'show'])->name('berita.detail');

    Route::get('/bantuan', [StudentPageController::class, 'bantuan'])->name('bantuan');
    Route::get('/tentang', [StudentPageController::class, 'tentang'])->name('tentang');
});

/**
 * COMPANY ROUTES
 */
Route::middleware(['auth', 'role:perusahaan'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', [CompanyPanelController::class, 'dashboard'])->name('dashboard');

    Route::get('/lowongan', [CompanyPanelController::class, 'lowonganIndex'])->name('lowongan.index');
    Route::get('/lowongan/create', [CompanyPanelController::class, 'lowonganCreate'])->name('lowongan.create');
    Route::post('/lowongan', [CompanyPanelController::class, 'lowonganStore'])->name('lowongan.store');
    Route::get('/lowongan/{job}/edit', [CompanyPanelController::class, 'lowonganEdit'])->name('lowongan.edit');
    Route::put('/lowongan/{job}', [CompanyPanelController::class, 'lowonganUpdate'])->name('lowongan.update');
    Route::delete('/lowongan/{job}', [CompanyPanelController::class, 'lowonganDestroy'])->name('lowongan.destroy');

    Route::get('/lamaran', [CompanyPanelController::class, 'lamaranIndex'])->name('lamaran.index');
    Route::get('/lamaran/{application}', [CompanyPanelController::class, 'lamaranShow'])->name('lamaran.show');
    Route::put('/lamaran/{application}/status', [CompanyPanelController::class, 'lamaranUpdateStatus'])->name('lamaran.update-status');

    // Tracer Industri untuk perusahaan
    Route::get('/tracer-industri', [IndustryTracerController::class, 'index'])->name('tracer.index');
    Route::post('/tracer-industri', [IndustryTracerController::class, 'store'])->name('tracer.store');
});

/**
 * ADMIN ROUTES
 */
Route::middleware(['auth', 'role:any_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('publik', [AdminPublikController::class, 'index'])->name('publik.index');
    Route::delete('publik/{id}', [AdminPublikController::class, 'destroy'])->name('publik.destroy');

    Route::resource('news', AdminNewsController::class);
    Route::get('news/{id}/preview-json', [AdminNewsController::class, 'previewJson'])->name('news.preview-json');
    Route::resource('events', AdminEventController::class);

    Route::get('/export-data', [DashboardActionController::class, 'export'])->name('export');
    Route::get('/laporan-cepat', [DashboardActionController::class, 'laporan'])->name('laporan');

    Route::get('/broadcast', [AdminBroadcastController::class, 'index'])->name('broadcast.index');

    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
        Route::get('/create', [AdminCompanyController::class, 'create'])->name('create');
        Route::post('/', [AdminCompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [AdminCompanyController::class, 'show'])->name('show');
        Route::get('/{company}/edit', [AdminCompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [AdminCompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [AdminCompanyController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [AdminJobController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobController::class, 'create'])->name('create');
        Route::post('/', [AdminJobController::class, 'store'])->name('store');
        Route::get('/{job}', [AdminJobController::class, 'show'])->name('show');
        Route::get('/{job}/edit', [AdminJobController::class, 'edit'])->name('edit');
        Route::put('/{job}', [AdminJobController::class, 'update'])->name('update');
        Route::delete('/{job}', [AdminJobController::class, 'destroy'])->name('destroy');
        Route::post('/{job}/approve', [AdminJobController::class, 'approve'])->name('approve');
        Route::post('/{job}/reject', [AdminJobController::class, 'reject'])->name('reject');
    });

    Route::prefix('company-accounts')->name('company-accounts.')->group(function () {
        Route::get('/', [CompanyAccountController::class, 'index'])->name('index');
        Route::get('/create', [CompanyAccountController::class, 'create'])->name('create');
        Route::post('/', [CompanyAccountController::class, 'store'])->name('store');
        Route::put('/{user}/toggle', [CompanyAccountController::class, 'toggle'])->name('toggle');
        Route::get('/{user}/reset-password', [CompanyAccountController::class, 'resetPasswordForm'])->name('reset-password');
        Route::put('/{user}/reset-password', [CompanyAccountController::class, 'resetPassword'])->name('reset-password.update');
        Route::delete('/{user}', [CompanyAccountController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('job-applications')->name('job-applications.')->group(function () {
        Route::get('/', [AdminJobApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminJobApplicationController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminJobApplicationController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [AdminJobApplicationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('event-registrations')->name('event-registrations.')->group(function () {
        Route::get('/', [AdminEventRegistrationController::class, 'index'])->name('index');
        Route::get('/export/csv', [AdminEventRegistrationController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/print', [AdminEventRegistrationController::class, 'exportPrint'])->name('export.print');
        Route::get('/export/pdf', [AdminEventRegistrationController::class, 'exportPdf'])->name('export.pdf');
        Route::put('/{id}', [AdminEventRegistrationController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminEventRegistrationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::post('/import', [AdminStudentController::class, 'import'])->name('import');
        Route::delete('/destroy-by-major', [AdminStudentController::class, 'destroyByMajor'])->name('destroy.by.major');
        Route::delete('/destroy-by-year', [AdminStudentController::class, 'destroyByYear'])->name('destroy.by.year');
        Route::delete('/{id}', [AdminStudentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [AdminStudentController::class, 'show'])->name('show');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::put('/{id}/status', [AdminUserController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-action', [AdminUserController::class, 'bulkAction'])->name('bulk-action');
    });

    // Perubahan ada di sini: ditambahkan route update menu untuk roles matrix toggle
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->name('index');
        Route::put('/{role}', [AdminRoleController::class, 'update'])->name('update');
        Route::put('/{role}/menus', [AdminRoleController::class, 'updateMenus'])->name('menus');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
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

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/export/alumni/csv', [AdminReportController::class, 'exportAlumniCsv'])->name('export.alumni.csv');
        Route::get('/export/jobs/csv', [AdminReportController::class, 'exportJobsCsv'])->name('export.jobs.csv');
        Route::get('/export/alumni/print', [AdminReportController::class, 'printAlumni'])->name('export.alumni.print');
        Route::get('/export/jobs/print', [AdminReportController::class, 'printJobs'])->name('export.jobs.print');
    });

    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('/activity-logs', [AdminActivityLogController::class, 'destroyAll'])->name('activity-logs.clear');

    /**
     * TRACER STUDY (ADMIN)
     * ⚠️ Urutan penting: route spesifik (/alumni, /industri, /industri/{id})
     * harus di atas route wildcard (/{tracerStudy})
     */
    Route::prefix('tracer')->name('tracer.')->group(function () {
        Route::get('/', [AdminTracerStudyController::class, 'index'])->name('index');
        Route::get('/alumni', [AdminTracerStudyController::class, 'alumni'])->name('alumni');
        Route::get('/export/csv', [AdminTracerStudyController::class, 'exportCsv'])->name('export.csv');
        Route::get('/print', [AdminTracerStudyController::class, 'print'])->name('print');

        // Industri — Mengarah ke method industri() baru di AdminTracerStudyController
        Route::get('/industri', [AdminTracerStudyController::class, 'industri'])->name('industri');
        Route::get('/industri/{industryTracer}', [AdminTracerStudyController::class, 'industryShow'])->name('industri.show');
        Route::delete('/industri/{industryTracer}', [AdminTracerStudyController::class, 'industryDestroy'])->name('industri.destroy');

        // TracerStudy show & destroy — wildcard, harus paling bawah
        Route::get('/{tracerStudy}', [AdminTracerStudyController::class, 'show'])->name('show');
        Route::delete('/{tracerStudy}', [AdminTracerStudyController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('tips')->name('tips.')->group(function () {
        Route::get('/', [AdminTipController::class, 'index'])->name('index');
        Route::get('/create', [AdminTipController::class, 'create'])->name('create');
        Route::post('/', [AdminTipController::class, 'store'])->name('store');
        Route::get('/{tip}/edit', [AdminTipController::class, 'edit'])->name('edit');
        Route::put('/{tip}', [AdminTipController::class, 'update'])->name('update');
        Route::delete('/{tip}', [AdminTipController::class, 'destroy'])->name('destroy');
        Route::patch('/{tip}/publish', [AdminTipController::class, 'togglePublish'])->name('publish');
        Route::patch('/{tip}/featured', [AdminTipController::class, 'toggleFeatured'])->name('featured');
    });

    Route::prefix('alumni-stories')->name('alumni-stories.')->group(function () {
        Route::get('/', [AlumniStoryController::class, 'index'])->name('index');
        Route::get('/{alumniStory}', [AlumniStoryController::class, 'show'])->name('show');
        Route::patch('/{alumniStory}/approve', [AlumniStoryController::class, 'approve'])->name('approve');
        Route::patch('/{alumniStory}/reject', [AlumniStoryController::class, 'reject'])->name('reject');
        Route::patch('/{alumniStory}/featured', [AlumniStoryController::class, 'toggleFeatured'])->name('featured');
        Route::delete('/{alumniStory}', [AlumniStoryController::class, 'destroy'])->name('destroy');
    });

});
