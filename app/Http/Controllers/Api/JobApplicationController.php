<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Siswa melamar ke sebuah lowongan (POST /applications)
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'job_listing_id' => 'required|exists:job_listings,job_listing_id',
            'cover_letter' => 'nullable|string',
            'additional_file' => 'nullable|string', // Nanti bisa diganti file upload
        ]);

        // 2. Ambil data siswa yang sedang login
        $user = Auth::user();
        
        // Pastikan yang login beneran siswa (mengambil student_id dari relasi)
        $student = $user->userable; 

        if (!$student || get_class($student) !== 'App\Models\Student') {
            return response()->json(['message' => 'Hanya siswa yang bisa melamar.'], 403);
        }

        // 3. CEK: Sudah pernah apply ke job yang sama belum?
        $alreadyApplied = JobApplication::where('student_id', $student->student_id)
            ->where('job_listing_id', $request->job_listing_id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json([
                'message' => 'Kamu sudah melamar di lowongan ini sebelumnya.'
            ], 400);
        }

        // 4. Simpan lamaran baru
        $application = JobApplication::create([
            'job_listing_id' => $request->job_listing_id,
            'student_id' => $student->student_id,
            'cover_letter' => $request->cover_letter,
            'additional_file' => $request->additional_file,
            'application_date' => now(), // Simpan tgl_melamar otomatis
            'status' => 'Seleksi Administrasi', // Sesuai migration kita (default awal)
        ]);

        return response()->json([
            'message' => 'Lamaran berhasil dikirim!',
            'data' => $application
        ], 201);
    }
}