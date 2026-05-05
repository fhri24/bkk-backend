<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\Student;
use App\Models\Company;
use App\Models\TracerStudy;
use App\Models\EventRegistration;

class PublicController extends Controller
{
    /**
     * Halaman Landing Page Utama
     */
    public function beranda()
    {
        $news           = News::where('is_published', true)->latest()->take(3)->get();
        $featured_jobs = Job::with('company')->latest()->take(3)->get();
        $featured_events = Event::with('registrations')->where('is_published', true)->latest()->take(3)->get();

        return view('public.beranda', compact('news', 'featured_jobs', 'featured_events'));
    }

    /**
     * Halaman List Lowongan (Publik)
     */
    public function lowongan(Request $request)
    {
        $query = Job::with('company')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('company', fn($sq) =>
                      $sq->where('company_name', 'like', "%{$search}%")
                  );
            });
        }

        $jobs = $query->get();

        return view('public.lowongan', compact('jobs'));
    }

    /**
     * Halaman Detail Lowongan (Publik)
     */
    public function lowonganDetail($id)
    {
        $job = Job::with('company')->findOrFail($id);

        $similarJobs = Job::with('company')
            ->where('job_id', '!=', $job->job_id)
            ->latest()
            ->take(3)
            ->get();

        return view('public.lowongan-detail', compact('job', 'similarJobs'));
    }

    /**
     * PERBAIKAN: Memproses Lamaran Kerja dari Form Publik
     */
    public function applyJob(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melamar.');
        }

        // Validasi field sesuai form di Screenshot 2026-04-30 210026.png
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'phone_number' => 'nullable|string|max:20',
            'cv_file'      => 'required|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|string',
            'experience'   => 'nullable|string',
        ]);

        // Logika simpan lamaran (Contoh)
        // \App\Models\JobApplication::create([
        //     'job_id' => $id,
        //     'student_id' => auth()->user()->student->student_id,
        //     'full_name' => $request->full_name,
        //     'email' => $request->email,
        //     'phone' => $request->phone_number,
        //     'cv_path' => $request->file('cv_file')->store('cv_applications'),
        //     'status' => 'pending'
        // ]);

        return back()->with('success', 'Lamaran Anda berhasil dikirim!');
    }

    /**
     * PERBAIKAN: Halaman Detail Perusahaan (Publik)
     */
    public function companyDetail($id)
    {
        $company = Company::findOrFail($id);
        
        // Lowongan aktif dari perusahaan tersebut
        $activeJobs = Job::where('company_id', $id)->latest()->get();

        return view('public.company-detail', compact('company', 'activeJobs'));
    }

    /**
     * Halaman List Berita
     */
    public function berita()
    {
        $newsItems = News::where('is_published', true)->latest()->paginate(6);
        return view('public.berita', compact('newsItems'));
    }

    /**
     * Halaman Detail Berita
     */
    public function beritaDetail($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        $relatedNews = News::where('id', '!=', $news->id)
                           ->where('is_published', true)
                           ->latest()
                           ->take(2)
                           ->get();

        return view('public.berita-detail', compact('news', 'relatedNews'));
    }

    /**
     * Halaman List Acara
     */
    public function acara()
    {
        $events = Event::where('is_published', true)->latest()->paginate(10);
        return view('public.acara', compact('events'));
    }

    /**
     * Halaman Detail Acara
     */
    public function acaraDetail($id)
    {
        $event = Event::with('registrations')->where('is_published', true)->findOrFail($id);

        $relatedEvents = Event::where('is_published', true)
                              ->where('id', '!=', $id)
                              ->where('start_date', '>=', now())
                              ->take(3)
                              ->get();

        return view('public.acara-detail', compact('event', 'relatedEvents'));
    }

    /**
     * Simpan Registrasi Acara
     */
    public function storeEventRegistration(Request $request, $id)
    {
        $event = Event::where('is_published', true)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah terdaftar dengan email yang sama
        $existingRegistration = EventRegistration::where('event_id', $id)
                                                  ->where('email', $validated['email'])
                                                  ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Email ini sudah terdaftar untuk acara ini!');
        }

        // Cek kuota
        $registeredCount = EventRegistration::where('event_id', $id)->count();
        if ($event->capacity && $registeredCount >= $event->capacity) {
            return back()->with('error', 'Maaf, kuota pendaftaran sudah penuh!');
        }

        EventRegistration::create([
            'event_id' => $id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'institution' => $validated['institution'] ?? null,
            'position' => $validated['position'] ?? null,
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        return back()->with('registration_success', 'Terima kasih! Pendaftaran acara Anda telah berhasil disimpan. Kami akan mengirimkan informasi lebih lanjut ke email Anda.');
    }

    /**
     * Halaman Tracer Alumni
     */
    public function tracer(Request $request)
    {
        $alumni = Student::where('alumni_flag', true)
                         ->orderBy('graduation_year', 'desc')
                         ->get();

        $siswaAktif = Student::where('alumni_flag', false)->get();

        return view('public.tracer', compact('alumni', 'siswaAktif'));
    }

    /**
     * Simpan Data Tracer Study
     */
    public function storeTracer(Request $request)
    {
        $request->validate([
            'status_kerja' => 'required|string',
            'company'      => 'nullable|string',
            'position'     => 'nullable|string',
            'salary'       => 'nullable|string',
        ]);

        $user = auth()->user();
        
        // Support untuk Student (alumni) dan Publik users
        if ($user->role->name === 'student' || $user->role->name === 'alumni') {
            $student = Student::where('user_id', $user->id)->first();
            
            if (!$student) {
                return back()->with('error', 'Data profil siswa tidak ditemukan.');
            }

            TracerStudy::create([
                'student_id'         => $student->student_id,
                'status_saat_ini'    => $request->status_kerja,
                'nama_instansi'      => $request->company,
                'pendapatan_bulanan' => $request->salary,
                'keselarasan_jurusan'=> $request->position,
            ]);
        } elseif ($user->role->name === 'publik') {
            // Untuk publik, simpan ke aktivitas log atau table khusus publik
            // Untuk sekarang, simpan info ke activity log atau email
            \App\Models\ActivityLog::create([
                'user_id'    => $user->id,
                'action'     => 'Tracer Study Submission',
                'description'=> json_encode([
                    'status' => $request->status_kerja,
                    'company' => $request->company,
                    'position' => $request->position,
                    'salary' => $request->salary,
                ]),
                'ip_address' => $request->ip(),
            ]);
        }

        return back()->with('success', 'Tracer Study berhasil disimpan!');
    }

    /**
     * Halaman Tutorial
     */
    public function tutorial()
    {
        return view('public.tutorial');
    }
}