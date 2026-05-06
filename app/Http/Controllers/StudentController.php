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

        $savedJobIds = [];
        $savedCount  = 0;

        if (Auth::check()) {
            $userId      = Auth::id();
            $savedJobIds = SavedJob::where('user_id', $userId)->pluck('job_id')->toArray();
            $savedCount  = SavedJob::where('user_id', $userId)->count();
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
        $job    = Job::where('job_id', $id)->firstOrFail();

        $saved = SavedJob::where('user_id', $userId)
            ->where('job_id', $job->job_id)
            ->first();

        if ($saved) {
            $saved->delete();
            return response()->json([
                'status'  => 'removed',
                'saved'   => false,
                'message' => 'Lowongan dihapus dari daftar simpan.',
                'count'   => SavedJob::where('user_id', $userId)->count(),
            ]);
        }

        SavedJob::create([
            'user_id' => $userId,
            'job_id'  => $job->job_id,
        ]);

        return response()->json([
            'status'  => 'added',
            'saved'   => true,
            'message' => 'Lowongan berhasil disimpan!',
            'count'   => SavedJob::where('user_id', $userId)->count(),
        ]);
    }

    /**
     * Hapus Lowongan dari Tersimpan
     */
    public function unsaveJob(Request $request, $id)
    {
        $userId  = auth()->id();
        $deleted = SavedJob::where('user_id', $userId)->where('job_id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Lowongan dihapus dari tersimpan.']);
        }

        return response()->json(['success' => false, 'message' => 'Lowongan tidak ditemukan.'], 404);
    }

    /**
     * Halaman Khusus Lowongan Tersimpan
     */
    public function savedJobs()
    {
        $savedJobs = SavedJob::where('user_id', auth()->id())
            ->with(['job.company'])
            ->latest()
            ->get()
            ->pluck('job')
            ->filter();

        return view('student.saved-jobs', compact('savedJobs'));
    }

    /**
     * Menampilkan Halaman Profil Utama
     */
    public function showProfile()
    {
        $user      = Auth::user();
        $roleName  = $user->role->name;

        $savedCount = SavedJob::where('user_id', $user->id)->count();
        $majors     = Major::orderBy('name', 'asc')->get();
        $years      = GraduationYear::orderBy('year', 'desc')->get();
        $savedJobs  = SavedJob::where('user_id', $user->id)->with(['job.company'])->latest()->get();

        if ($roleName === 'siswa') {
            // ===== SISWA =====
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return redirect()->route('student.home')
                    ->with('error', 'Profil tidak ditemukan.');
            }

            $applications = JobApplication::where('student_id', $student->student_id)
                ->with(['job.company'])
                ->latest()
                ->get();

        } elseif ($roleName === 'alumni') {
            // ===== ALUMNI — data dari userable (tabel alumni) =====
            $profil = $user->userable;

            if (!$profil) {
                return redirect()->route('alumni.home')
                    ->with('error', 'Profil tidak ditemukan.');
            }

            $student = new Student();
            $student->forceFill([
                'student_id'      => $profil->getKey(),
                'user_id'         => $user->id,
                'nis'             => $profil->nisn ?? null,
                'full_name'       => $profil->nama_lengkap ?? null,
                'gender'          => $profil->jenis_kelamin ?? null,
                'birth_info'      => ($profil->tempat_lahir ?? '') . ', ' . ($profil->tanggal_lahir ?? ''),
                'major'           => $profil->jurusan ?? null,
                'graduation_year' => $profil->tahun_lulus ?? null,
                'phone'           => $profil->no_hp ?? null,
                'address'         => $profil->alamat ?? null,
                'profile_picture' => $profil->foto_profile ?? null,
                'alumni_flag'     => true,
                'status'          => 'active',
            ]);

            // Ambil lamaran berdasarkan email untuk alumni
            $applications = JobApplication::where('email', $user->email)
                ->with(['job.company'])
                ->latest()
                ->get();

        } else {
            // ===== PUBLIK — data dari userable (tabel publik) =====
            $profil = $user->userable;

            if (!$profil) {
                return redirect()->route('publik.home')
                    ->with('error', 'Profil tidak ditemukan.');
            }

            $student = new Student();
            $student->forceFill([
                'student_id'      => $profil->getKey(),
                'user_id'         => $user->id,
                'nis'             => $profil->nisn ?? null,
                'full_name'       => $profil->nama_lengkap ?? null,
                'gender'          => $profil->jenis_kelamin ?? null,
                'birth_info'      => ($profil->tempat_lahir ?? '') . ', ' . ($profil->tanggal_lahir ?? ''),
                'major'           => null,
                'graduation_year' => $profil->tahun_lulus ?? null,
                'phone'           => $profil->no_hp ?? null,
                'address'         => $profil->alamat ?? null,
                'profile_picture' => $profil->foto_profile ?? null,
                'alumni_flag'     => false,
                'status'          => 'active',
            ]);

            // Ambil lamaran berdasarkan email untuk publik
            $applications = JobApplication::where('email', $user->email)
                ->with(['job.company'])
                ->latest()
                ->get();
        }

        return view('student.profile', compact(
            'user', 'student', 'majors', 'years', 'applications', 'savedJobs', 'savedCount'
        ));
    }

    /**
     * Proses Update Profil
     */
    public function updateProfile(Request $request)
    {
        $user     = Auth::user();
        $roleName = $user->role->name;

        // ===== ALUMNI =====
        if ($roleName === 'alumni') {
            $profil = $user->userable;

            if (!$profil) {
                return redirect()->back()->with('error', 'Profil tidak ditemukan.');
            }

            $validated = $request->validate([
                'full_name'       => 'required|string|max:255',
                'nis'             => 'nullable|string|max:50',
                'gender'          => 'nullable|in:L,P',
                'major'           => 'nullable|string',
                'graduation_year' => 'nullable|integer',
                'phone'           => 'nullable|string|max:20',
                'address'         => 'nullable|string',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Upload foto profil
            if ($request->hasFile('profile_picture')) {
                if ($profil->foto_profile) {
                    Storage::disk('public')->delete($profil->foto_profile);
                }
                $path = $request->file('profile_picture')->store('foto_profile', 'public');
                $profil->foto_profile = $path;
            }

            // Map input ke field alumni
            $profil->nama_lengkap = $validated['full_name'];
            if (array_key_exists('nis', $validated))             $profil->nisn        = $validated['nis'];
            if (array_key_exists('gender', $validated))          $profil->jenis_kelamin = $validated['gender'];
            if (array_key_exists('major', $validated))           $profil->jurusan     = $validated['major'];
            if (array_key_exists('graduation_year', $validated)) $profil->tahun_lulus = $validated['graduation_year'];
            if (array_key_exists('phone', $validated))           $profil->no_hp       = $validated['phone'];
            if (array_key_exists('address', $validated))         $profil->alamat      = $validated['address'];

            DB::transaction(function () use ($profil, $user, $request) {
                $profil->save();
                $user->update(['name' => $request->full_name]);
            });

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        }

        // ===== PUBLIK =====
        if ($roleName === 'publik') {
            $publik = $user->userable;

            if (!$publik) {
                return redirect()->back()->with('error', 'Profil tidak ditemukan.');
            }

            $validated = $request->validate([
                'full_name'       => 'required|string|max:255',
                'nis'             => 'nullable|string|max:50',
                'gender'          => 'nullable|in:L,P',
                'graduation_year' => 'nullable|integer',
                'phone'           => 'nullable|string|max:20',
                'address'         => 'nullable|string',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Upload foto profil
            if ($request->hasFile('profile_picture')) {
                if ($publik->foto_profile) {
                    Storage::disk('public')->delete($publik->foto_profile);
                }
                $path = $request->file('profile_picture')->store('foto_profile', 'public');
                $publik->foto_profile = $path;
            }

            // Map input ke field publik
            $publik->nama_lengkap = $validated['full_name'];
            if (array_key_exists('nis', $validated))             $publik->nisn          = $validated['nis'];
            if (array_key_exists('gender', $validated))          $publik->jenis_kelamin = $validated['gender'];
            if (array_key_exists('graduation_year', $validated)) $publik->tahun_lulus   = $validated['graduation_year'];
            if (array_key_exists('phone', $validated))           $publik->no_hp         = $validated['phone'];
            if (array_key_exists('address', $validated))         $publik->alamat        = $validated['address'];

            DB::transaction(function () use ($publik, $user, $request) {
                $publik->save();
                $user->update(['name' => $request->full_name]);
            });

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        }

        // ===== SISWA =====
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Profil tidak ditemukan.');
        }

        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'nis'             => 'nullable|string|max:50',
            'gender'          => 'nullable|in:L,P',
            'birth_info'      => 'nullable|string|max:255',
            'major'           => 'nullable|string',
            'graduation_year' => 'nullable|integer',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload foto profil (konsisten di folder foto_profile)
        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture) {
                Storage::disk('public')->delete($student->profile_picture);
            }
            $path = $request->file('profile_picture')->store('foto_profile', 'public');
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
        $user     = Auth::user();
        $roleName = $user->role->name;
        $student  = null;

        if ($roleName === 'publik' || $roleName === 'alumni') {
            $profil = $user->userable;
            if (!$profil) {
                return back()->with('error', 'Silahkan lengkapi profil terlebih dahulu.');
            }
        } else {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return back()->with('error', 'Silahkan lengkapi profil terlebih dahulu.');
            }
        }

        $request->validate([
            'cv_file'      => 'required|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        // Cek apakah sudah melamar
        if ($roleName === 'publik' || $roleName === 'alumni') {
            $existing = JobApplication::where('email', $user->email)->where('job_id', $id)->exists();
        } else {
            $existing = JobApplication::where('student_id', $student->student_id)->where('job_id', $id)->exists();
        }

        if ($existing) {
            return back()->with('warning', 'Anda sudah melamar lowongan ini.');
        }

        try {
            $fileName   = null;
            $filePrefix = $student ? $student->student_id : ($roleName . '_' . $user->id);

            if ($request->hasFile('cv_file')) {
                $fileName = time() . '_' . $filePrefix . '.' .
                    $request->file('cv_file')->getClientOriginalExtension();
                $request->file('cv_file')->storeAs('public/cv_applications', $fileName);
            }

            $profil = ($roleName === 'publik' || $roleName === 'alumni') ? $user->userable : null;

            JobApplication::create([
                'student_id'      => $student ? $student->student_id : null,
                'job_id'          => $id,
                'status'          => 'pending',
                'application_date'=> now(),
                'cover_letter'    => $request->cover_letter,
                'additional_file' => $fileName,
                'full_name'       => $request->full_name  ?? ($student ? $student->full_name  : ($profil->nama_lengkap ?? $user->name)),
                'email'           => $request->email       ?? $user->email,
                'phone_number'    => $request->phone_number ?? ($student ? $student->phone : ($profil->no_hp ?? null)),
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

        return view('public.acara', compact('events'));
    }

    /**
     * Detail Acara
     */
    public function detailAcara($id)
    {
        $event   = Event::findOrFail($id);
        $user    = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        $isRegistered = EventRegistration::where('event_id', $event->slug)
            ->where('email', $user->email)
            ->exists();

        return view('public.acara-detail', compact('event', 'user', 'student', 'isRegistered'));
    }

    /**
     * Pendaftaran Acara
     */
    public function daftarAcara(Request $request, $id)
    {
        $event   = Event::findOrFail($id);
        $user    = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $request->validate(['phone' => 'required|string|max:20']);

        $isRegistered = EventRegistration::where('event_id', $event->slug)
            ->where('email', $user->email)
            ->exists();

        if ($isRegistered) {
            return back()->with('error', 'Anda sudah terdaftar.');
        }

        EventRegistration::create([
            'event_id'      => $event->slug,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $request->phone,
            'institution'   => $request->institution ?? ($student ? 'SMKN 1 Garut' : 'Umum'),
            'position'      => $request->position    ?? ($student ? ($student->major ?? 'Siswa / Alumni') : 'Publik'),
            'status'        => 'pending',
            'registered_at' => now(),
        ]);

        return back()->with('success', 'Berhasil mendaftar!');
    }

    /**
     * Halaman Lamaran Saya
     */
    public function myApplications()
    {
        $user     = Auth::user();
        $roleName = $user->role->name;

        // Publik & Alumni: query berdasarkan email
        if ($roleName === 'publik' || $roleName === 'alumni') {
            $applications = JobApplication::where('email', $user->email)
                ->with(['job.company'])
                ->latest('application_date')
                ->get();
        } else {
            // Siswa: query berdasarkan student_id
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return redirect()->route('student.home')
                    ->with('error', 'Profil siswa tidak ditemukan.');
            }

            $applications = JobApplication::where('student_id', $student->student_id)
                ->with(['job.company'])
                ->latest('application_date')
                ->get();
        }

        return view('student.applications', compact('applications'));
    }

    /**
     * Hapus Lamaran
     */
    public function deleteApplication($id)
    {
        $user     = Auth::user();
        $roleName = $user->role->name;

        // Publik & Alumni: cari berdasarkan email
        if ($roleName === 'publik' || $roleName === 'alumni') {
            $application = JobApplication::where('job_application_id', $id)
                ->where('email', $user->email)
                ->first();
        } else {
            // Siswa: cari berdasarkan student_id
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return redirect()->back()->with('error', 'Profil tidak ditemukan.');
            }

            $application = JobApplication::where('job_application_id', $id)
                ->where('student_id', $student->student_id)
                ->first();
        }

        if (!$application) {
            return redirect()->back()->with('error', 'Lamaran tidak ditemukan.');
        }

        // Hapus file CV jika ada
        if ($application->additional_file) {
            Storage::delete('public/cv_applications/' . $application->additional_file);
        }

        $application->delete();

        return redirect()->back()->with('success', 'Lamaran berhasil dihapus.');
    }
} 