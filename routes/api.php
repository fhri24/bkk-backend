<?php

use App\Http\Controllers\Api\ApiExampleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
    ]);
});

Route::apiResource('example', ApiExampleController::class);

Route::get('example/search/{query}', [ApiExampleController::class, 'search']);
