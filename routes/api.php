<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;
use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\StudentController;

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
        
        // Company Management - CRUD
        Route::apiResource('companies', CompanyController::class);
        
        // Company Verification Toggle
        Route::patch('/companies/{id}/toggle-verify', [CompanyController::class, 'toggleVerify']);
        
        // Assign Company to Job
        Route::post('/companies/{id}/assign-job', [CompanyController::class, 'assignToJob']);
    });

    // KELOMPOK PERUSAHAAN (COMPANY)
    Route::middleware('role:company')->group(function () {
        // Tempat route lowongan kerja nanti
    });

    // KELOMPOK SISWA (STUDENT)
    Route::middleware('role:student')->group(function () {
        // View Companies (Read-Only)
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/{id}', [CompanyController::class, 'show']);
        
        // Route untuk melamar pekerjaan
        Route::post('/applications', [JobApplicationController::class, 'store']);
    });

    // KELOMPOK ADMIN BKK / KEPALA BKK
    Route::middleware('role:admin_bkk')->group(function () {
        // Tempat route review lamaran nanti
    });


