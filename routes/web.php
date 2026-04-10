<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;

use App\Http\Controllers\Student\PageController as StudentPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Bisa diakses siapa saja)
*/

// Halaman Landing Page & Beranda
Route::get('/', [PublicController::class, 'beranda'])->name('public.home');
Route::get('/beranda', [PublicController::class, 'beranda'])->name('public.beranda');
Route::get('/home', function () {
    return redirect('/');
});

// Fitur Publik (Daftar & Detail)
Route::get('/lowongan-kerja', [PublicController::class, 'lowongan'])->name('public.lowongan');
Route::get('/lowongan/{id}', [PublicController::class, 'lowonganDetail'])->name('public.lowongan.detail'); // Tambahan rute detail publik

Route::get('/berita-terbaru', [PublicController::class, 'berita'])->name('public.berita');
Route::get('/berita/{id}', [PublicController::class, 'beritaDetail'])->name('public.berita.detail'); // Tambahan rute detail publik

Route::get('/acara-mendatang', [PublicController::class, 'acara'])->name('public.acara');
Route::get('/tracer-study', [PublicController::class, 'tracer'])->name('public.tracer');
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
| STUDENT ROUTES (Hanya untuk Siswa yang sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    
    // Beranda Student
    Route::get('/', [StudentController::class, 'index'])->name('home');
    
    // Profile
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    
    // Fitur Student
    Route::get('/lowongan', [StudentPageController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/{id}', [StudentPageController::class, 'lowonganDetail'])->name('lowongan.detail');
    Route::get('/berita', [StudentPageController::class, 'berita'])->name('berita');
    Route::get('/berita/{id}', [StudentPageController::class, 'beritaDetail'])->name('berita.detail');
    Route::get('/acara', [StudentPageController::class, 'acara'])->name('acara');
    Route::get('/tracer', [StudentPageController::class, 'tracer'])->name('tracer');
    
    Route::get('/profil-lengkap', [StudentPageController::class, 'profil'])->name('profil.page');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    

    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [AdminJobController::class, 'index'])->name('index');
        Route::get('/create', [AdminJobController::class, 'create'])->name('create');
        Route::post('/', [AdminJobController::class, 'store'])->name('store');
        Route::delete('/{job}', [AdminJobController::class, 'destroy'])->name('destroy');
    });
});
