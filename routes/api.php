<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExampleController;

use App\Http\Controllers\API\SuperAdminController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\CompanyController; 
use App\Http\Controllers\Api\AuthController; // Pastikan ini ada

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

// Rute Login: Ini gerbang utama untuk dapet token
Route::post('/login', [AuthController::class, 'login']); 

Route::apiResource('example', ApiExampleController::class);


Route::get('example/search/{query}', [ApiExampleController::class, 'search']);


// --- 2. RUTE TERPROTEKSI (Harus Login / Pakai Token) ---
Route::middleware('auth:sanctum')->group(function () {

    // Rute Logout: Harus login dulu baru bisa logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // KELOMPOK SUPER ADMIN
    Route::middleware('role:super_admin')->group(function () {

        Route::apiResource('super-admins', SuperAdminController::class);

    });


    // KELOMPOK SISWA (STUDENT)
    Route::middleware('role:student')->group(function () {
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/{id}', [CompanyController::class, 'show']);
        
        Route::post('/applications', [JobApplicationController::class, 'store']);
    });

    // KELOMPOK PERUSAHAAN
    Route::middleware('role:company')->group(function () {
        // Bisa diisi rute posting lowongan nanti
    });

    // KELOMPOK ADMIN BKK
    Route::middleware('role:admin_bkk')->group(function () {
        // Bisa diisi rute verifikasi lamaran nanti
    });
});