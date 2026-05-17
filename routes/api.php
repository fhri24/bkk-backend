<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (! Auth::attempt($credentials)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah',
        ], 401);
    }

    $user = Auth::user();

    // create personal access token (Sanctum)
    $token = $user->createToken('api-token')->plainTextToken;

    $response = [
        'status' => 'success',
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ];

    // Optional: push token record to Supabase table if env vars provided
    $supabaseUrl = env('SUPABASE_URL');
    $supabaseKey = env('SUPABASE_KEY');
    $supabaseTable = env('SUPABASE_TABLE', 'personal_access_tokens');

    if ($supabaseUrl && $supabaseKey) {
        try {
            $payload = [
                'user_id' => $user->id,
                'token' => $token,
                'name' => 'api-token',
                'created_at' => now()->toIso8601String(),
            ];

            $supResp = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => 'application/json',
            ])->post(rtrim($supabaseUrl, '/') . '/rest/v1/' . $supabaseTable, $payload);

            $response['supabase'] = [
                'status' => $supResp->status(),
                'body' => $supResp->successful() ? $supResp->json() : $supResp->body(),
            ];
        } catch (\Exception $e) {
            $response['supabase'] = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    return response()->json($response);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});