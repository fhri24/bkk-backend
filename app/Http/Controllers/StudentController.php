<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\Major;
use App\Models\GraduationYear;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Menampilkan Halaman Beranda
     */
    public function index()
    {
        $featured_jobs = Job::latest()->take(3)->with('company')->get();
        $featured_events = Event::latest()->take(3)->get();
        $featured_news = News::latest()->take(3)->get();

        return view('student.beranda', compact('featured_jobs', 'featured_events', 'featured_news'));
    }

    /**
     * Menampilkan Halaman Profil
     * Lokasi: resources/views/student/profile.blade.php
     */
    public function showProfile()
    {
        // 1. Ambil user yang login
        $user = Auth::user();

        // 2. Ambil data student. 
        // Menggunakan where agar lebih pasti meskipun relasi di model belum diset.
        $student = Student::where('user_id', $user->id)->first();

        // 3. Proteksi jika data student tidak ada di database
        if (!$student) {
            return redirect()->route('student.home')->with('error', 'Profil siswa tidak ditemukan.');
        }

        // 4. AMBIL DATA UNTUK DROPDOWN (Ini wajib agar @foreach di view tidak error)
        $majors = Major::orderBy('name', 'asc')->get();
        $years = GraduationYear::orderBy('year', 'desc')->get();

        // 5. Inisialisasi variabel kosong agar view tidak error saat panggil ->count()
        $applications = collect(); 
        $saved_jobs = collect();

        // 6. Kirim SEMUA variabel ke view
        return view('student.profile', compact(
            'user', 
            'student', 
            'majors', 
            'years', 
            'applications', 
            'saved_jobs'
        ));
    }

    /**
     * Proses Update Profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Profil gagal diperbarui.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nis' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'birth_info' => 'nullable|string|max:255',
            'major' => 'required|string',
            'graduation_year' => 'required|integer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle Upload Foto
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // Set status alumni otomatis
        $validated['alumni_flag'] = ($request->graduation_year <= date('Y'));

        // Update data student
        $student->update($validated);

        // Update nama di table users agar sinkron
        $user->update(['name' => $request->full_name]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
