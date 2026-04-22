<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\Major;
use App\Models\GraduationYear;
use App\Models\JobApplication; 
use App\Models\SavedJob; 
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
     * 
     */
    public function showProfile()
    {

        $user = Auth::user();


        $student = Student::where('user_id', $user->id)->first();


        if (!$student) {
            return redirect()->route('student.home')->with('error', 'Profil siswa tidak ditemukan.');
        }


        $majors = Major::orderBy('name', 'asc')->get();
        $years = GraduationYear::orderBy('year', 'desc')->get();

        // Data Lamaran
        $applications = JobApplication::where('student_id', $student->id)
            ->with(['job.company']) 
            ->latest()
            ->get();

        // Data Tersimpan
        $saved_jobs = SavedJob::where('user_id', $user->id)
            ->with(['job.company']) 
            ->latest()
            ->get();

        return view('student.profile', compact(
            'user', 'student', 'majors', 'years', 'applications', 'saved_jobs'
        ));
    }

    /**
     * Menampilkan Halaman Khusus Lowongan Tersimpan
     */
    public function savedJobs()
    {
        $saved_jobs = SavedJob::where('user_id', auth()->id())
                    ->with(['job.company']) 
                    ->latest()
                    ->get();

        return view('student.saved-jobs', compact('saved_jobs'));
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


        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }


        $validated['alumni_flag'] = ($request->graduation_year <= date('Y'));


        $student->update($validated);


        $user->update(['name' => $request->full_name]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
