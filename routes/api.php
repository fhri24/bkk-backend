<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;
use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\StudentController;

// --- 1. Rute Publik ---
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


// --- 2. Rute Terproteksi ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/students/me', [StudentController::class, 'me']);
        Route::put('/students/me', [StudentController::class, 'updateMe']);
    });

    // KELOMPOK SUPER ADMIN
    Route::middleware('role:super_admin')->group(function () {

        Route::apiResource('super-admins', SuperAdminController::class);

    });

    // KELOMPOK PERUSAHAAN (COMPANY)
    Route::middleware('role:company')->group(function () {
        // Tempat route lowongan kerja nanti
    });

    // KELOMPOK SISWA (STUDENT)
    Route::middleware('role:student')->group(function () {
        // Route untuk melamar pekerjaan
        Route::post('/applications', [JobApplicationController::class, 'store']);
    });

    // KELOMPOK ADMIN BKK / KEPALA BKK
    Route::middleware('role:admin_bkk')->group(function () {
        // Tempat route review lamaran nanti
    });


