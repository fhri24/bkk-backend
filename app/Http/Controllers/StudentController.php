<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Job;
use App\Models\Event;
use App\Models\Major; 
use App\Models\GraduationYear;
use App\Models\JobApplication; 
use App\Models\SavedJob; 
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{ 
    /**
     * Menampilkan Halaman Daftar Lowongan (Student)
     */
    public function lowongan(Request $request)
    {
        $query = Job::with('company');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('job_type', $request->type);
        }

        if ($request->filled('major') && $request->major != 'Semua Jurusan') {
            $query->where('major', $request->major);
        }

        $jobs = $query->latest()->paginate(12);
        
        return view('student.lowongan', compact('jobs'));
    }

    /**
     * Menampilkan Detail Lowongan
     */
    public function detailLowongan($id)
    {
        $job = Job::with('company')->where('job_id', $id)->firstOrFail();
        
        $similarJobs = Job::with('company')
                        ->where('job_id', '!=', $id)
                        ->where(function($query) use ($job) {
                            $query->where('job_type', $job->job_type)
                                  ->orWhere('company_id', $job->company_id);
                        })
                        ->latest()
                        ->limit(5)
                        ->get();
        
        return view('student.lowongan-detail', compact('job', 'similarJobs'));
    }

    /**
     * Fitur Simpan/Bookmark Lowongan
     */
    public function saveJob($id)
    {
        $userId = auth()->id();
        $existing = SavedJob::where('user_id', $userId)->where('job_id', $id)->first();

        if ($existing) {
            $existing->delete();
            return redirect()->back()->with('success', 'Lowongan dihapus dari daftar simpan.');
        }

        SavedJob::create([
            'user_id' => $userId,
            'job_id' => $id
        ]);

        return redirect()->back()->with('success', 'Lowongan berhasil disimpan!');
    }

    /**
     * Menampilkan Halaman Profil Utama
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

        $applications = JobApplication::where('student_id', $student->student_id)
            ->with(['job.company']) 
            ->latest()
            ->get();
 
        $saved_jobs = SavedJob::where('user_id', $user->id)
            ->with(['job.company']) 
            ->latest()
            ->get();

        return view('student.profile', compact(
            'user', 'student', 'majors', 'years', 'applications', 'saved_jobs'
        ));
    }

    /**
     * Menampilkan Halaman Profil Lengkap
     */
    public function profileDetail()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        return view('student.profile-detail', compact('user', 'student'));
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
            return redirect()->back()->with('error', 'Profil tidak ditemukan.');
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
            // Hapus foto lama jika ada
            if ($student->profile_picture) {
                Storage::disk('public')->delete($student->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        } 

        $validated['alumni_flag'] = ($request->graduation_year <= date('Y')); 

        $student->update($validated); 
        $user->update(['name' => $request->full_name]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Proses Melamar Lowongan
     */
    public function applyJob(Request $request, $id)
    { 
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return back()->with('error', 'Silahkan lengkapi profil terlebih dahulu.');
        }
 
        $request->validate([
            'cv_file' => 'required|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|string|max:2000',
        ]);
 
        $existing = JobApplication::where('student_id', $student->student_id)
                                    ->where('job_id', $id)
                                    ->first();

        if ($existing) {
            return back()->with('warning', 'Anda sudah melamar lowongan ini.');
        }

        try { 
            $fileName = null;
            if ($request->hasFile('cv_file')) { 
                $fileName = time() . '_' . $student->student_id . '.' . $request->file('cv_file')->getClientOriginalExtension();
                $request->file('cv_file')->storeAs('public/cv_applications', $fileName);
            }
 
            JobApplication::create([
                'student_id'       => $student->student_id,
                'job_id'           => $id,
                'status'           => 'pending',
                'application_date' => now(),
                'cover_letter'     => $request->cover_letter,
                'additional_file'  => $fileName, 
            ]);

            return back()->with('success', 'Lamaran berhasil terkirim!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Daftar Acara Student
     */
    public function acara()
    {
        $events = Event::where('is_published', true)
                     ->where('start_date', '>=', now())
                     ->latest('start_date')
                     ->paginate(12);
        
        return view('student.acara', compact('events'));
    }

    /**
     * Detail Acara & Cek Status Registrasi
     */
    public function detailAcara($id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
 
        $isRegistered = EventRegistration::where('event_id', $event->slug)
            ->where('email', $user->email)
            ->exists();

        return view('student.acara-detail', compact('event', 'user', 'student', 'isRegistered'));
    }
 
    public function daftarAcara(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        $request->validate([
            'phone' => 'required|string|max:20', 
        ]);
 
        if (EventRegistration::where('event_id', $event->slug)->where('email', $user->email)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar.');
        }

        EventRegistration::create([
            'event_id' => $event->slug,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $request->phone,
            'institution' => $request->institution ?? 'SMKN 1 Garut',
            'position' => $request->position ?? ($student->major ?? 'Siswa / Alumni'),
            'status' => 'pending',
            'registered_at' => now()
        ]);

        return back()->with('success', 'Berhasil mendaftar!');
    }
}