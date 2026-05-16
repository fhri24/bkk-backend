<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\Student;
use App\Models\Company;
use App\Models\TracerStudy;
use App\Models\EventRegistration;
use App\Models\Major;
use App\Models\AlumniStory;

class PublikController extends Controller
{
    /**
     * Halaman Landing Page Utama
     */
    public function beranda()
    {
        $news = News::where('is_published', true)->latest()->take(3)->get();

        $featured_jobs = Job::with('company')
            ->latest()
            ->take(3)
            ->get();

        $featured_events = Event::with('registrations')
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        $alumni_stories = AlumniStory::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $avatarColors = [
            'from-blue-500 to-blue-700',
            'from-indigo-500 to-indigo-700',
            'from-violet-500 to-violet-700'
        ];

        return view('public.beranda', compact(
            'news',
            'featured_jobs',
            'featured_events',
            'alumni_stories',
            'avatarColors'
        ));
    }

    /**
     * Halaman List Lowongan (Publik)
     */
    public function lowongan(Request $request)
    {
        $query = Job::with(['company', 'major'])
            ->where('approval_status', 'approved')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($sq) use ($search) {
                        $sq->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        $jobs = $query->get();
        $majors = Major::all();

        $savedJobIds = [];
        if (Auth::check()) {
            $savedJobIds = \App\Models\SavedJob::where('user_id', Auth::id())
                ->pluck('job_id')
                ->toArray();
        }

        return view('public.lowongan', compact('jobs', 'majors', 'savedJobIds'));
    }

    /**
     * Detail Lowongan
     */
    public function lowonganDetail($id)
    {
        $job = Job::with('company')
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        $similarJobs = Job::with('company')
            ->where('job_id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('public.lowongan-detail', compact('job', 'similarJobs'));
    }

    /**
     * Proses Lamaran Kerja
     */
    public function applyJob(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melamar.');
        }

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'phone_number' => 'nullable|string|max:20',
            'cv_file'      => 'required|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|string',
        ]);

        return back()->with('success', 'Lamaran Anda berhasil dikirim!');
    }

    /**
     * Detail Perusahaan
     */
    public function companyDetail($id)
    {
        $company = Company::findOrFail($id);

        $activeJobs = Job::where('company_id', $id)
            ->latest()
            ->get();

        return view('public.company-detail', compact('company', 'activeJobs'));
    }

    /**
     * Berita
     */
    public function berita()
    {
        $newsItems = News::where('is_published', true)
            ->latest()
            ->paginate(6);

        return view('public.berita', compact('newsItems'));
    }

    /**
     * Detail Berita
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
     * Acara
     */
    public function acara()
    {
        $events = Event::where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('public.acara', compact('events'));
    }

    /**
     * Detail Acara
     */
    public function acaraDetail($id)
    {
        $event = Event::with('registrations')
            ->where('is_published', true)
            ->findOrFail($id);

        $relatedEvents = Event::where('is_published', true)
            ->where('id', '!=', $id)
            ->where('start_date', '>=', now())
            ->take(3)
            ->get();

        return view('public.acara-detail', compact('event', 'relatedEvents'));
    }

    /**
     * Simpan Pendaftaran Event
     */
    public function storeEventRegistration(Request $request, $id)
    {
        $event = Event::where('is_published', true)->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
        ]);

        $eventId = $event->slug;

        $existing = EventRegistration::where('event_id', $eventId)
            ->where('email', $validated['email'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Email ini sudah terdaftar!');
        }

        if (
            $event->capacity &&
            EventRegistration::where('event_id', $eventId)->count() >= $event->capacity
        ) {
            return back()->with('error', 'Kuota pendaftaran penuh!');
        }

        EventRegistration::create([
            'event_id'      => $eventId,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'institution'   => $validated['institution'] ?? null,
            'status'        => 'registered',
            'registered_at' => now(),
        ]);

        return back()->with('registration_success', 'Pendaftaran berhasil!');
    }

    /**
     * Tracer Study
     */
    public function tracer()
    {
        $alumni = Student::where('alumni_flag', true)
            ->orderBy('graduation_year', 'desc')
            ->get();

        return view('public.tracer', compact('alumni'));
    }

    public function tracerReport()
    {
        // Get all tracer study data
        $tracerStudies = TracerStudy::with('student')->get();

        // Prepare chart data based on status_saat_ini
        $chartData = [
            'Bekerja' => $tracerStudies->where('status_saat_ini', 'Bekerja')->count(),
            'Kuliah' => $tracerStudies->where('status_saat_ini', 'Kuliah')->count(),
            'Wirausaha' => $tracerStudies->where('status_saat_ini', 'Wirausaha')->count(),
            'Mencari Kerja' => $tracerStudies->where('status_saat_ini', 'Belum Bekerja')->count(),
        ];

        // Get statistics
        $totalRespondents = $tracerStudies->count();
        $workingPercentage = $totalRespondents > 0 ? round(($chartData['Bekerja'] / $totalRespondents) * 100) : 0;
        $entrepreneurPercentage = $totalRespondents > 0 ? round(($chartData['Wirausaha'] / $totalRespondents) * 100) : 0;

        // Get alignment data (keselarasan_jurusan)
        $alignmentData = [
            'Sesuai' => $tracerStudies->where('keselarasan_jurusan', 'Sesuai')->count(),
            'Tidak Sesuai' => $tracerStudies->where('keselarasan_jurusan', 'Tidak Sesuai')->count(),
        ];

        return view('public.tracer-report', compact(
            'tracerStudies',
            'chartData',
            'totalRespondents',
            'workingPercentage',
            'entrepreneurPercentage',
            'alignmentData'
        ));
    }
    /**
     * Simpan Tracer
     */
    public function storeTracer(Request $request)
    {
        $request->validate([
            'status_saat_ini' => 'required|string|in:Bekerja,Kuliah,Wirausaha,Belum Bekerja',
            'company'         => 'nullable|string',
        ]);

        $user = Auth::user();

        if (
            $user->role->name === 'siswa' ||
            $user->role->name === 'alumni'
        ) {
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return back()->with('error', 'Data profil tidak ditemukan.');
            }

            TracerStudy::create([
                'student_id'      => $student->id,
                'status_saat_ini' => $request->input('status_saat_ini'),
                'nama_instansi'   => $request->company,
            ]);
        }

        return back()->with('success', 'Data Tracer berhasil disimpan!');
    }

    /**
     * Tutorial
     */
    public function tutorial()
    {
        return view('public.tutorial');
    }
}
