<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminBkkController;
use App\Http\Controllers\KepalaBkkController;
use App\Http\Controllers\Admin\JobController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Rute Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.prosess');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Rute untuk Admin BKK
Route::get('/admin-bkk/dashboard', [AdminBkkController::class, 'index'])->name('admin.bkk.dashboard');

// 4. Rute untuk Kepala BKK
Route::get('/kepala-bkk/dashboard', [KepalaBkkController::class, 'index'])->name('kepala.bkk.dashboard');
Route::get('/kepala-bkk/laporan', [KepalaBkkController::class, 'laporan'])->name('kepala.bkk.laporan');

// 5. Rute untuk Job Management (Admin)
Route::prefix('admin/jobs')->name('admin.jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/create', [JobController::class, 'create'])->name('create');
    Route::post('/', [JobController::class, 'store'])->name('store');
    Route::delete('/{job}', [JobController::class, 'destroy'])->name('destroy');
});