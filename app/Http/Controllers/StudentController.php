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
use App\Models\Role;
use App\Models\User;
use App\Notifications\JobApplicationSubmitted;
use App\Services\SupabaseStorageService; // ✅ Ditambahkan untuk Supabase
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
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
            $query->where(function($q) use ($request) {
                $q->where('major', $request->major)
                  ->orWhere('major', 'Semua Jurusan');
            });
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

    public function detailLowongan($id)
    {
        $job = Job::with('company')->where('job_id', $id)->firstOrFail();

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

    public function saveJob(Request $request, $id)
    {
        $userId = Auth::id();
        $job    = Job::where('job_id', $id)->firstOrFail();

        $saved = SavedJob::where('user_id', $userId)->where('job_id', $job->job_id)->first();

        if ($saved) {
            $saved->delete();
            return response()->json([
                'status'  => 'removed',
                'saved'   => false,
                'message' => 'Lowongan dihapus dari daftar simpan.',
                'count'   => SavedJob::where('user_id', $userId)->count(),
            ]);
        }

        SavedJob::create(['user_id' => $userId, 'job_id' => $job->job_id]);

        return response()->json([
            'status'  => 'added',
            'saved'   => true,
            'message' => 'Lowongan berhasil disimpan!',
            'count'   => SavedJob::where('user_id', $userId)->count(),
        ]);
    }

    public function unsaveJob(Request $request, $id)
    {
        $userId  = Auth::id();
        $deleted = SavedJob::where('user_id', $userId)->where('job_id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Lowongan dihapus dari tersimpan.']);
        }

        return response()->json(['success' => false, 'message' => 'Lowongan tidak ditemukan.'], 404);
    }

    public function savedJobs()
    {
        $savedJobs = SavedJob::where('user_id', Auth::id())
            ->with(['job.company'])
            ->latest()
            ->get()
            ->pluck('job')
            ->filter();

        return view('student.saved-jobs', compact('savedJobs'));
    }

    public function showProfile()
    {
        $user = Auth::user();
        if (!$user->role) return redirect()->route('login')->with('error', 'Role tidak terdefinisi.');

        $roleName   = strtolower($user->role->name);
        $majors     = Major::orderBy('name', 'asc')->get();
        $years      = GraduationYear::orderBy('year', 'desc')->get();
        $savedJobs  = SavedJob::where('user_id', $user->id)->with(['job.company'])->latest()->get();
        $savedCount = $savedJobs->count();

        if ($roleName === 'siswa') {
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return redirect()->route('student.home')->with('error', 'Profil tidak ditemukan.');
            }

            $applications = JobApplication::where('student_id', $student->student_id)
                ->with(['job.company'])
                ->latest()
                ->get();
        } else {
            $profil = $user->userable ?? $user->student;

            if (!$profil) {
                return redirect()->back()->with('error', 'Data profil tambahan tidak ditemukan.');
            }

            if ($profil instanceof \App\Models\Student) {
    $student = $profil;
    if ($roleName === 'alumni') {
        $applications = JobApplication::where('email', $user->email)
            ->with(['job.company'])
            ->latest()
            ->get();
    } else {
        $applications = JobApplication::where('student_id', $student->student_id)
            ->with(['job.company'])
            ->latest()
            ->get();
    }
}
             else {
                $student = (object) [
                    'student_id'      => $profil->getKey(),
                    'user_id'         => $user->id,
                    'nis'             => $profil->nisn ?? $profil->nis ?? null,
                    'full_name'       => $profil->nama_lengkap ?? $user->name,
                    'gender'          => $profil->jenis_kelamin ?? null,
                    'birth_info'      => trim(($profil->tempat_lahir ?? '') . ', ' . ($profil->tanggal_lahir ?? ''), ', '),
                    'major'           => $profil->jurusan ?? null,
                    'graduation_year' => $profil->tahun_lulus ?? null,
                    'phone'           => $profil->no_hp ?? null,
                    'address'         => $profil->alamat ?? null,
                    'profile_picture' => $profil->foto_profile ?? null,
                    'alumni_flag'     => ($roleName === 'alumni'),
                    'status'          => 'active',
                ];

                $applications = JobApplication::where('email', $user->email)
                    ->with(['job.company'])
                    ->latest()
                    ->get();
            }
        }

        return view('student.profile', compact(
            'user',
            'student',
            'majors',
            'years',
            'applications',
            'savedJobs',
            'savedCount'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user     = Auth::user();
        $roleName = strtolower($user->role->name);

        $request->validate([
            'full_name'       => 'required|string|max:255',
            'nis'             => 'nullable|string|max:50',
            'gender'          => 'nullable|in:L,P',
            'major'           => 'nullable|string',
            'graduation_year' => 'nullable|integer',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string',
            'birth_info'      => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($roleName === 'siswa') {
                $student = Student::where('user_id', $user->id)->firstOrFail();

                if ($request->hasFile('profile_picture')) {
                    if ($student->profile_picture) Storage::disk('public')->delete($student->profile_picture);
                    $student->profile_picture = $request->file('profile_picture')->store('foto_profile', 'public');
                }

                $student->full_name       = $request->full_name;
                $student->nis             = $request->nis;
                $student->gender          = $request->gender;
                $student->major           = $request->major;
                $student->graduation_year = $request->graduation_year;
                $student->phone           = $request->phone;
                $student->address         = $request->address;
                $student->birth_info      = $request->birth_info;
                $student->alumni_flag     = ($request->graduation_year <= date('Y'));
                $student->save();
            } elseif ($profil = $user->student) {
                if ($request->hasFile('profile_picture')) {
                    if ($profil->profile_picture) Storage::disk('public')->delete($profil->profile_picture);
                    $profil->profile_picture = $request->file('profile_picture')->store('foto_profile', 'public');
                }

                $profil->full_name        = $request->full_name;
                $profil->gender           = $request->gender;
                $profil->phone            = $request->phone;
                $profil->address          = $request->address;
                $profil->major            = $request->major;
                $profil->graduation_year  = $request->graduation_year;
                $profil->save();
            } else {
                $profil = $user->userable;
                if ($request->hasFile('profile_picture')) {
                    if ($profil->foto_profile) Storage::disk('public')->delete($profil->foto_profile);
                    $profil->foto_profile = $request->file('profile_picture')->store('foto_profile', 'public');
                }

                $profil->nama_lengkap  = $request->full_name;
                $profil->nisn          = $request->nis;
                $profil->jenis_kelamin = $request->gender;
                $profil->no_hp         = $request->phone;
                $profil->alamat        = $request->address;
                $profil->tahun_lulus   = $request->graduation_year;

                if ($roleName === 'alumni') {
                    $profil->jurusan = $request->major;
                }
                $profil->save();
            }

            $user->name = $request->full_name;
            $user->save();

            DB::commit();
            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function applyJob(Request $request, $id)
    {
        $user     = User::find(Auth::id());
        $roleName = strtolower($user->role->name);

        // ✅ Menggunakan validasi 'cover_letter' sesuai tambahan baru kamu
        $request->validate([
            'cv_file'      => 'required|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        $student = ($roleName === 'siswa') ? Student::where('user_id', $user->id)->first() : null;
        $profil  = ($roleName !== 'siswa') ? ($user->userable ?? $user->student) : null;

        if (!$student && !$profil) {
            $msg = 'Silahkan lengkapi profil terlebih dahulu.';
            return $request->ajax()
                ? response()->json(['status' => 'error', 'message' => $msg], 403)
                : back()->with('error', $msg);
        }

        $existing = ($roleName === 'siswa')
            ? JobApplication::where('student_id', $student->student_id)->where('job_id', $id)->exists()
            : JobApplication::where('email', $user->email)->where('job_id', $id)->exists();

        if ($existing) {
            $msg = 'Anda sudah melamar lowongan ini.';
            return $request->ajax()
                ? response()->json(['status' => 'error', 'message' => $msg], 422)
                : back()->with('warning', $msg);
        }

        try {
            $fileName = null;

            // ✅ Upload ke Supabase Storage menggunakan SupabaseStorageService bawaan kamu
            if ($request->hasFile('cv_file')) {
                $supabase   = new SupabaseStorageService();
                $identifier = $student ? $student->student_id : $user->id;
                $filename   = time() . '_' . $identifier . '.pdf';
                
                $result = $supabase->upload($request->file('cv_file'), $filename);

                if ($result) {
                    $fileName = $supabase->getPublicUrl($filename); // Menyimpan URL Publik Supabase
                } else {
                    throw new \Exception('Gagal mengunggah file CV ke Supabase Storage.');
                }
            }

            $application = JobApplication::create([
                'student_id'       => $student ? $student->student_id : null,
                'job_id'           => $id,
                'status'           => 'pending',
                'application_date' => now(),
                'cover_letter'     => $request->cover_letter, // ✅ Menyimpan field cover_letter
                'additional_file'  => $fileName,             // ✅ Menyimpan URL Supabase kedalam DB
                'full_name'        => DB::table('users')->where('id', Auth::id())->value('name'),
                'email'            => $user->email,
                'phone_number'     => $student ? $student->phone : ($profil->no_hp ?? $profil->phone ?? null),
            ]);

            // Mempertahankan pengiriman notifikasi ke admin sistem
            $admins = User::whereHas('role', fn($q) =>
                $q->whereIn('name', ['super_admin', 'admin_bkk'])
            )->get();

            try {
                Notification::send($admins, new JobApplicationSubmitted($application->load('job.company')));
            } catch (\Exception $e) {
                // Proses tetap aman berjalan walaupun sistem mail/notifikasi error
            }

            session(['name' => $user->name]);

            $successMsg = 'Lamaran berhasil terkirim!';
            return $request->ajax()
                ? response()->json(['status' => 'success', 'message' => $successMsg])
                : back()->with('success', $successMsg);
        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500)
                : back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function acara()
    {
        $events = Event::where('is_published', 1)
            ->where('start_date', '>=', now())
            ->latest('start_date')
            ->paginate(12);

        return view('public.acara', compact('events'));
    }

    public function detailAcara($id)
    {
        $event   = Event::findOrFail($id);
        $user    = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $isRegistered = EventRegistration::where('event_id', $event->slug)
            ->where('email', $user->email)
            ->exists();

        return view('public.acara-detail', compact('event', 'user', 'student', 'isRegistered'));
    }

    public function daftarAcara(Request $request, $id)
    {
        $event   = Event::findOrFail($id);
        $user    = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $request->validate(['phone' => 'required|string|max:20']);

        $isRegistered = EventRegistration::where('event_id', $event->slug)
            ->where('email', $user->email)
            ->exists();

        if ($isRegistered) return back()->with('error', 'Anda sudah terdaftar.');

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

    public function myApplications()
    {
        $user     = Auth::user();
        $roleName = strtolower($user->role->name);

        $query = JobApplication::with(['job.company'])->latest('application_date');

        if ($roleName === 'siswa') {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) return redirect()->route('student.home')->with('error', 'Profil tidak ditemukan.');
            $applications = $query->where('student_id', $student->student_id)->get();
        } else {
            $applications = $query->where('email', $user->email)->get();
        }

        return view('student.applications', compact('applications'));
    }

    public function deleteApplication($id)
    {
        $user     = Auth::user();
        $roleName = strtolower($user->role->name);

        $application = ($roleName === 'siswa')
            ? JobApplication::where('job_application_id', $id)
                ->where('student_id', Student::where('user_id', $user->id)->value('student_id'))
                ->first()
            : JobApplication::where('job_application_id', $id)
                ->where('email', $user->email)
                ->first();

        if (!$application) return redirect()->back()->with('error', 'Lamaran tidak ditemukan.');

        // Proteksi jika file berupa URL Supabase atau local disk
        if ($application->additional_file) {
            if (filter_var($application->additional_file, FILTER_VALIDATE_URL)) {
                // Jika URL Supabase, coba hapus via service
                if (str_contains($application->additional_file, config('services.supabase.url'))) {
                    try {
                        $supabase = new SupabaseStorageService();
                        $pathParts = explode('/', parse_url($application->additional_file, PHP_URL_PATH));
                        $filename = end($pathParts);
                        $supabase->delete($filename);
                    } catch (\Exception $e) {
                        \Log::error("Gagal menghapus file di Supabase: " . $e->getMessage());
                    }
                }
            } else {
                Storage::disk('public')->delete('cv_applications/' . $application->additional_file);
            }
        }

        $application->delete();
        return redirect()->back()->with('success', 'Lamaran berhasil dihapus.');
    }
}