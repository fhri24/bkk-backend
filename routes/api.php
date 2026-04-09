<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;

use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\API\AuthController;

// --- 1. Rute Publik (Bisa diakses tanpa login) ---
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute contoh (jika ini untuk testing publik)
Route::apiResource('example', ApiExampleController::class);


Route::get('example/search/{query}', [ApiExampleController::class, 'search']);


// --- 2. Rute Terproteksi (Harus Login & Cek Role) ---
Route::middleware('auth:sanctum')->group(function () {

    // KELOMPOK SUPER ADMIN
    Route::middleware('role:super_admin')->group(function () {
        // Resource Super Admin dimasukkan ke sini agar hanya admin yang bisa akses
        Route::apiResource('super-admins', SuperAdminController::class);
        
        // Route::get('/admin/dashboard', [AdminController::class, 'index']);
    });

    // KELOMPOK PERUSAHAAN (COMPANY)
    Route::middleware('role:company')->group(function () {
        // Route::post('/jobs/create', [JobController::class, 'store']);
    });

    // KELOMPOK SISWA (STUDENT)
    Route::middleware('role:student')->group(function () {
        // Route::post('/apply', [JobApplicationController::class, 'store']);
    });

    // KELOMPOK ADMIN BKK / KEPALA BKK
    Route::middleware('role:admin_bkk')->group(function () {
        // Route::get('/applications/review', [JobApplicationController::class, 'index']);
    });
});