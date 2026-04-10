<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;

class StudentController extends Controller
{
    /**
     * Menampilkan Halaman Beranda Siswa (Web)
     */
    public function index()
    {
        // 1. Ambil Lowongan (Query disederhanakan agar tidak error kolom 'status')
        $featured_jobs = collect(); 
        try {
            $featured_jobs = Job::latest()->take(3)->with('company')->get();
        } catch (\Exception $e) {
            // Jika error kolom status, halaman tetap tampil walau lowongan kosong
        }

        // 2. Ambil Acara Unggulan (Try-catch agar aman jika tabel belum ada)
        $featured_events = collect();
        try {
            $featured_events = Event::latest()->take(3)->get();
        } catch (\Exception $e) {}

        // 3. Ambil Berita Unggulan
        $featured_news = collect();
        try {
            $featured_news = News::latest()->take(3)->get();
        } catch (\Exception $e) {}

        // Mengarah ke resources/views/student/home.blade.php sesuai screenshot folder kamu
        return view('student.beranda', compact('featured_jobs', 'featured_events', 'featured_news'));
    }

    /**
     * Menampilkan Halaman Profil (Web)
     */
    public function profile()
    {
        // Mengarah ke resources/views/student/profile.blade.php
        return view('student.profil');
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
