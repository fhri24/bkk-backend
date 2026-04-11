<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Menampilkan Halaman Beranda Siswa (Web)
     */
    public function index()
    {


        $featured_jobs = collect(); 
        try {
            $featured_jobs = Job::latest()->take(3)->with('company')->get();
        } catch (\Exception $e) {}



        $featured_events = collect();
        try {
            $featured_events = Event::latest()->take(3)->get();
        } catch (\Exception $e) {}


        $featured_news = collect();
        try {
            $featured_news = News::latest()->take(3)->get();
        } catch (\Exception $e) {}

        
        return view('student.beranda', compact('featured_jobs', 'featured_events', 'featured_news'));
    }

    /**
     * Menampilkan Halaman Profil (Web)
     */
    public function showProfile()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // 2. Ambil data detail student yang terhubung
        $student = Student::where('user_id', $user->id)->first();

        // 3. Proteksi jika data student tidak ditemukan
        if (!$student) {
            return redirect()->route('student.home')->with('error', 'Profil siswa tidak ditemukan.');
        }

        /** * 4. Inisialisasi variabel sebagai koleksi KOSONG.
         * Tampilan akan otomatis menunjukkan "Belum ada data" sampai 
         * kamu membuat fitur 'Simpan Lowongan' dan 'Lamar Pekerjaan'.
         */
        $applications = collect(); 
        $saved_jobs = collect();   

        // 5. Kirim semua variabel ke view student/profile.blade.php
        return view('student.profile', compact('user', 'student', 'applications', 'saved_jobs'));
    }

    /**
     * Mendapatkan data profil saya (API)
     */
    public function me(Request $request)
    {
        $student = Student::where('user_id', $request->user()->id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }

        return response()->json($student);
    }

    /**
     * Update profil saya (API)
     */
    public function updateMe(Request $request)
    {
        $student = $request->user()->student;

        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nis' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'birth_info' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'resume_url' => 'nullable|url',
            'profile_picture' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'alumni_flag' => 'required|boolean',
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $student
        ]);
    }
}
