<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

// Login Admin/Staff (pakai email)
Route::post('/login', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Login Siswa (pakai NISN)
Route::post('/login/siswa', function (Request $request) {
    try {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        // Cari student berdasarkan NISN
        $student = Student::where('nisn', $request->nisn)->first();

        if (!$student || !$student->user) {
            return response()->json([
                'status' => 'error',
                'message' => 'NISN tidak ditemukan',
            ], 404);
        }

        $user = $student->user;

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NISN atau password salah',
            ], 401);
        }

        $token = $user->createToken('siswa-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'student' => $student,
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Get user yang sedang login
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});