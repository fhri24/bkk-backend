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
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Menampilkan Halaman Daftar Lowongan (Student)
     */
    public function lowongan(Request $request)
    {
        $query = Job::with('company');

        // Filter pencarian
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

        // --- PERBAIKAN: Ambil ID dan Count untuk konsistensi UI ---
        $savedJobIds = [];
        $savedCount = 0;

        if (Auth::check()) {
            $userId = Auth::id();

            // Untuk indikator warna merah di tombol
            $savedJobIds = SavedJob::where('user_id', $userId)
                ->pluck('job_id')
                ->toArray();

            // Untuk angka notifikasi di badge "Tersimpan"
            $savedCount = SavedJob::where('user_id', $userId)->count();
        }

        return view('student.lowongan', compact('jobs', 'savedJobIds', 'savedCount'));
    }

    /**
     * Menampilkan Detail Lowongan
     */
    public function detailLowongan($id)
    {
        $job = Job::with('company')
            ->where('job_id', $id)
            ->firstOrFail();

        $similarJobs = Job::with('company')
            ->where('job_id', '!=', $id)
            ->where(function ($query) use ($job) {
                $query->where('job_type', $job->job_type)
                    ->orWhere('company_id', $job->company_id);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('student.lowongan-detail', compact('job', 'similarJobs'));
    }

    /**
     * Fitur Simpan / Bookmark Lowongan (Toggle via AJAX)
     */
    public function saveJob(Request $request, $id)
    {
        $userId = auth()->id();
        $job = Job::where('job_id', $id)->firstOrFail();

        $saved = SavedJob::where('user_id', $userId)
            ->where('job_id', $job->job_id)
            ->first();

        if ($saved) {
            $saved->delete();
            return response()->json([
                'status' => 'removed',
                'saved' => false,
                'message' => 'Lowongan dihapus dari daftar simpan.',
                'count' => SavedJob::where('user_id', $userId)->count()
            ]);
        }

        SavedJob::create([
            'user_id' => $userId,
            'job_id' => $job->job_id,
        ]);

        return response()->json([
            'status' => 'added',
            'saved' => true,
            'message' => 'Lowongan berhasil disimpan!',
            'count' => SavedJob::where('user_id', $userId)->count()
        ]);
    }

    /**
     * Hapus Lowongan dari Tersimpan (Fungsi Tambahan)
     */
    public function unsaveJob(Request $request, $id)
    {
        $userId = auth()->id();
        $deleted = SavedJob::where('user_id', $userId)
                           ->where('job_id', $id)
                           ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Lowongan dihapus dari tersimpan.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Lowongan tidak ditemukan.',
        ], 404);
    }

    /**
     * Halaman Khusus Lowongan Tersimpan
     */
    public function savedJobs()
    {
        // Mengambil data Job melalui relasi dari table saved_jobs
        $savedJobs = SavedJob::where('user_id', auth()->id())
            ->with(['job.company'])
            ->latest()
            ->get()
            ->pluck('job')
            ->filter(); // Menghapus item jika relasi job-nya null

        return view('student.saved-jobs', compact('savedJobs'));
    }

    /**
     * Menampilkan Halaman Profil Utama
     */
    public function showProfile()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.home')
                ->with('error', 'Profil siswa tidak ditemukan.');
        }

        $savedCount = SavedJob::where('user_id', $user->id)->count();
        $majors = Major::orderBy('name', 'asc')->get();
        $years = GraduationYear::orderBy('year', 'desc')->get();

        $applications = JobApplication::where('student_id', $student->student_id)
            ->with(['job.company'])
            ->latest()
            ->get();

        $savedJobs = SavedJob::where('user_id', $user->id)
            ->with(['job.company'])
            ->latest()
            ->get();

        return view('student.profile', compact(
            'user', 'student', 'majors', 'years', 'applications', 'savedJobs', 'savedCount'
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
            if ($student->profile_picture) {
                Storage::disk('public')->delete($student->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        $validated['alumni_flag'] = ($request->graduation_year <= date('Y'));

        DB::transaction(function () use ($student, $user, $validated, $request) {
            $student->update($validated);
            $user->update(['name' => $request->full_name]);
        });

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Proses Melamar Lowongan
     */
    public function applyJob(Request $request, $id)
    {
        $student = Student::where('user_id', auth()->id())->first();

        if (!$student) {
            return back()->with('error', 'Silahkan lengkapi profil terlebih dahulu.');
        }

        $request->validate([
            'cv_file' => 'required|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        $existing = JobApplication::where('student_id', $student->student_id)
            ->where('job_id', $id)
            ->exists();

        if ($existing) {
            return back()->with('warning', 'Anda sudah melamar lowongan ini.');
        }

        try {
            $fileName = null;
            if ($request->hasFile('cv_file')) {
                $fileName = time() . '_' . $student->student_id . '.' .
                    $request->file('cv_file')->getClientOriginalExtension();
                $request->file('cv_file')->storeAs('public/cv_applications', $fileName);
            }

            JobApplication::create([
                'student_id' => $student->student_id,
                'job_id' => $id,
                'status' => 'pending',
                'application_date' => now(),
                'cover_letter' => $request->cover_letter,
                'additional_file' => $fileName,
                'full_name' => $request->full_name ?? $student->full_name,
                'email' => $request->email ?? $user->email,
                'phone_number' => $request->phone_number ?? $student->phone,
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
     * Detail Acara
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

    /**
     * Pendaftaran Acara
     */
    public function daftarAcara(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        $request->validate(['phone' => 'required|string|max:20']);

        $isRegistered = EventRegistration::where('event_id', $event->slug)
            ->where('email', $user->email)
            ->exists();

        if ($isRegistered) {
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

    /**
     * Halaman Lamaran Saya
     */
    public function myApplications()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.home')
                ->with('error', 'Profil siswa tidak ditemukan.');
        }

        $applications = JobApplication::where('student_id', $student->student_id)
            ->with(['job.company'])
            ->latest('application_date')
            ->get();

        return view('student.applications', compact('applications'));
    }

    /**
     * Hapus Lamaran
     */
    public function deleteApplication($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Profil tidak ditemukan.');
        }

        $application = JobApplication::where('job_application_id', $id)
            ->where('student_id', $student->student_id)
            ->first();

        if (!$application) {
            return redirect()->back()->with('error', 'Lamaran tidak ditemukan.');
        }

        // Hapus file jika ada
        if ($application->additional_file) {
            Storage::delete('public/cv_applications/' . $application->additional_file);
        }

        $application->delete();

        return redirect()->back()->with('success', 'Lamaran berhasil dihapus.');
    }
}
