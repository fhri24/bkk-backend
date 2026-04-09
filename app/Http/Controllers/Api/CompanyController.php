<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;
use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\CompanyController; // WAJIB ADA INI

// --- 1. Rute Publik ---
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
    ]);
});

// --- 2. Rute Terproteksi ---
Route::middleware('auth:sanctum')->group(function () {

    // KELOMPOK SUPER ADMIN
    Route::middleware('role:super_admin')->group(function () {
        Route::apiResource('super-admins', SuperAdminController::class);
    });

    // KELOMPOK SISWA (STUDENT)
    Route::middleware('role:student')->group(function () {
        // Fitur Company View (Read-Only)
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/{id}', [CompanyController::class, 'show']);
        
        // Fitur Melamar Pekerjaan
        Route::post('/applications', [JobApplicationController::class, 'store']);
    });

    // KELOMPOK LAIN (Bisa diisi nanti)
    Route::middleware('role:company')->group(function () { });
    Route::middleware('role:admin_bkk')->group(function () { });
});