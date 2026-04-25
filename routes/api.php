<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;
use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\EventRegistrationController;
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

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Job Applications (Lamaran Pekerjaan) - CRUD untuk Siswa dan Admin
    Route::apiResource('applications', JobApplicationController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);

    // Event Registrations (Registrasi Acara) - Publik bisa daftar, Admin bisa lihat/edit/hapus
    Route::post('/event-registrations', [EventRegistrationController::class, 'store']); // Publik bisa daftar
    Route::get('/event-registrations/{id}', [EventRegistrationController::class, 'show']); // Detail registrasi
    
    // Admin/Kepala BKK bisa lihat semua registrasi, edit status, dan hapus
    Route::middleware('role:super_admin,admin_bkk,kepala_bkk')->group(function () {
        Route::get('/event-registrations', [EventRegistrationController::class, 'index']); // Lihat semua registrasi per event
        Route::put('/event-registrations/{id}', [EventRegistrationController::class, 'update']); // Update status registrasi
        Route::delete('/event-registrations/{id}', [EventRegistrationController::class, 'destroy']); // Hapus registrasi
    });

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