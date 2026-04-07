<?php

use App\Http\Controllers\Api\ApiExampleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SuperAdminController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
    ]);
});

Route::apiResource('example', ApiExampleController::class);
Route::apiResource('super-admins', SuperAdminController::class);

Route::get('example/search/{query}', [ApiExampleController::class, 'search']);
