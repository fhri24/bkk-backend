<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Student\HomeController as StudentHomeController;
use App\Http\Controllers\Student\PageController as StudentPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| PUBLIC ROUTES
*/

// Halaman Landing Page
Route::get('/', [PublicController::class, 'beranda'])->name('public.home');
Route::get('/beranda', [PublicController::class, 'beranda'])->name('public.beranda');
Route::get('/home', function () {
    return redirect('/');
});
Route::get('/tutorial', [PublicController::class, 'tutorial'])->name('public.tutorial');

// Authentication Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| STUDENT/PUBLIC ROUTES (Authenticated Students Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [StudentHomeController::class, 'index'])->name('home');
    Route::get('/profile', [StudentHomeController::class, 'profile'])->name('profile');
    Route::get('/applications', [StudentHomeController::class, 'applications'])->name('applications');
    
    // Pages
    Route::get('/lowongan', [StudentPageController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/{id}', [StudentPageController::class, 'lowonganDetail'])->name('lowongan.detail');
    Route::get('/berita', [StudentPageController::class, 'berita'])->name('berita');
    Route::get('/berita/{id}', [StudentPageController::class, 'beritaDetail'])->name('berita.detail');
    Route::get('/acara', [StudentPageController::class, 'acara'])->name('acara');
    Route::get('/tracer', [StudentPageController::class, 'tracer'])->name('tracer');
    Route::get('/profil', [StudentPageController::class, 'profil'])->name('profil');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Authenticated Admin/Company/BKK Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Job Management
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [AdminJobController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobController::class, 'create'])->name('create');
        Route::post('/', [AdminJobController::class, 'store'])->name('store');
        Route::delete('/{job}', [AdminJobController::class, 'destroy'])->name('destroy');
    });
});
