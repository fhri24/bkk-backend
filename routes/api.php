<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;
use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\CompanyController; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AlumniStoryController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 1. RUTE PUBLIK (Bisa diakses siapa saja tanpa login) ---
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
    ]);
});

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Alumni Stories
Route::get('/alumni-stories', [AlumniStoryController::class, 'index']);
Route::post('/alumni-stories', [AlumniStoryController::class, 'store']);

// Rute contoh
Route::apiResource('example', ApiExampleController::class);
Route::get('example/search/{query}', [ApiExampleController::class, 'search']);


// --- 2. RUTE TERPROTEKSI (Harus Login / Pakai Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Rute Profile Siswa
    Route::get('/students/me', [StudentController::class, 'me']);
    Route::put('/students/me', [StudentController::class, 'updateMe']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // KELOMPOK SUPER ADMIN
    Route::middleware('role:super_admin')->group(function () {
        Route::apiResource('super-admins', SuperAdminController::class);
        
        // Company Management - CRUD Lengkap untuk Admin
        Route::apiResource('companies', CompanyController::class);
        
        // Fitur Khusus Admin untuk Perusahaan
        Route::patch('/companies/{id}/toggle-verify', [CompanyController::class, 'toggleVerify']);
        Route::post('/companies/{id}/assign-job', [CompanyController::class, 'assignToJob']);
    });

    // KELOMPOK SISWA (STUDENT)
    Route::middleware('role:student')->group(function () {
        // View Companies (Hanya Melihat)
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/{id}', [CompanyController::class, 'show']);
        
        // Route untuk melamar pekerjaan
        Route::post('/applications', [JobApplicationController::class, 'store']);
    });

    // KELOMPOK PERUSAHAAN (Bisa diisi nanti)
    Route::middleware('role:company')->group(function () { 
        // Contoh: Route::apiResource('jobs', JobController::class);
    });

    // KELOMPOK ADMIN BKK (Bisa diisi nanti)
    Route::middleware('role:admin_bkk')->group(function () { 
        // Contoh: Route::get('/reports', [ReportController::class, 'index']);
    });
});