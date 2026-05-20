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
    public function beranda()
    {
        // ── Berita, Lowongan, Acara, Alumni Stories (Data Utama) ──
        $news = News::whereRaw('"is_published" = true')->latest()->take(3)->get();

        $featured_jobs = Job::with('company')
            ->where('approval_status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        $featured_events = Event::with('registrations')
            ->whereRaw('"is_published" = true')
            ->latest()
            ->take(3)
            ->get();

        // OPTIMASI: Eager loading relasi student untuk mengambil foto profil alumni secara instan
        $alumni_stories = AlumniStory::with('student')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $avatarColors = [
            'from-blue-500 to-blue-700',
            'from-indigo-500 to-indigo-700',
            'from-violet-500 to-violet-700',
        ];

        // ── STAT CARDS — Data Real ──

        // 1. Alumni Terserap: yang status tracer = Bekerja atau Wirausaha
        $alumniTerserap = \App\Models\TracerStudy::whereIn('status_saat_ini', ['Bekerja', 'Wirausaha'])->count();

        // 2. Tingkat Penyaluran: (Bekerja + Wirausaha) / total yang isi tracer × 100%
        $totalTracer = \App\Models\TracerStudy::count();
        $tingkatPenyaluran = $totalTracer > 0
            ? round(($alumniTerserap / $totalTracer) * 100)
            : 0;

        // 3. Lowongan Aktif: approved + expired_at belum lewat
        $lowonganAktif = Job::where('approval_status', 'approved')
            ->where('expired_at', '>=', now())
            ->count();

        // 4. MoU Industri: jumlah perusahaan terdaftar
        $totalPerusahaan = \App\Models\Company::count();

        // School profile (untuk keperluan footer/informasi dinamis di beranda)
        $schoolProfile = \App\Models\SchoolProfile::first();

        return view('public.beranda', compact(
            'news',
            'featured_jobs',
            'featured_events',
            'alumni_stories',
            'avatarColors',
            'alumniTerserap',
            'tingkatPenyaluran',
            'lowonganAktif',
            'totalPerusahaan',
            'schoolProfile'
        ));
    }

    public function lowongan(Request $request)
    {
        $query = Job::with(['company', 'major'])->where('approval_status', 'approved')->latest();
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
            $savedJobIds = \App\Models\SavedJob::where('user_id', Auth::id())->pluck('job_id')->toArray();
        }
        return view('public.lowongan', compact('jobs', 'majors', 'savedJobIds'));
    }

    public function lowonganDetail($id)
    {
        $job = Job::with('company')->where('approval_status', 'approved')->findOrFail($id);
        $similarJobs = Job::with('company')->where('job_id', '!=', $id)->latest()->take(3)->get();
        return view('public.lowongan-detail', compact('job', 'similarJobs'));
    }

    public function applyJob(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melamar.');
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

    public function companyDetail($id)
    {
        $company = Company::findOrFail($id);
        $activeJobs = Job::where('company_id', $id)->latest()->get();
        return view('public.company-detail', compact('company', 'activeJobs'));
    }

    public function berita()
    {
        $newsItems = News::whereRaw('"is_published" = true')->latest()->paginate(6);
        return view('public.berita', compact('newsItems'));
    }

    public function beritaDetail($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $relatedNews = News::where('id', '!=', $news->id)->whereRaw('"is_published" = true')->latest()->take(2)->get();
        return view('public.berita-detail', compact('news', 'relatedNews'));
    }

    public function acara()
    {
        $events = Event::whereRaw('"is_published" = true')->latest()->paginate(10);
        return view('public.acara', compact('events'));
    }

    public function acaraDetail($id)
    {
        $event = Event::with('registrations')->whereRaw('"is_published" = true')->findOrFail($id);
        $relatedEvents = Event::whereRaw('"is_published" = true')->where('id', '!=', $id)->where('start_date', '>=', now())->take(3)->get();
        return view('public.acara-detail', compact('event', 'relatedEvents'));
    }

    public function storeEventRegistration(Request $request, $id)
    {
        $event = Event::whereRaw('"is_published" = true')->findOrFail($id);
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
        ]);
        $eventId = $event->slug;
        $existing = EventRegistration::where('event_id', $eventId)->where('email', $validated['email'])->first();
        if ($existing) return back()->with('error', 'Email ini sudah terdaftar!');
        if ($event->capacity && EventRegistration::where('event_id', $eventId)->count() >= $event->capacity) {
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

    public function tracer()
    {
        $chartData = [
            'Bekerja'       => TracerStudy::where('status_saat_ini', 'Bekerja')->count(),
            'Kuliah'        => TracerStudy::where('status_saat_ini', 'Kuliah')->count(),
            'Wirausaha'     => TracerStudy::where('status_saat_ini', 'Wirausaha')->count(),
            'Mencari Kerja' => TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count(),
        ];
        return view('public.tracer', compact('chartData'));
    }

    public function tracerReport()
    {
        $tracerStudies = TracerStudy::with('student')->get();
        $chartData = [
            'Bekerja'       => $tracerStudies->where('status_saat_ini', 'Bekerja')->count(),
            'Kuliah'        => $tracerStudies->where('status_saat_ini', 'Kuliah')->count(),
            'Wirausaha'     => $tracerStudies->where('status_saat_ini', 'Wirausaha')->count(),
            'Mencari Kerja' => $tracerStudies->where('status_saat_ini', 'Belum Bekerja')->count(),
        ];
        $totalRespondents       = $tracerStudies->count();
        $workingPercentage      = $totalRespondents > 0 ? round(($chartData['Bekerja'] / $totalRespondents) * 100) : 0;
        $entrepreneurPercentage = $totalRespondents > 0 ? round(($chartData['Wirausaha'] / $totalRespondents) * 100) : 0;
        $alignmentData = [
            'Sesuai'       => $tracerStudies->where('keselarasan_jurusan', 'Sesuai')->count(),
            'Tidak Sesuai' => $tracerStudies->where('keselarasan_jurusan', 'Tidak Sesuai')->count(),
        ];
        $majors = Major::orderBy('name')->get();
        $authStudent = null;
        if (auth()->check()) {
            $authStudent = Student::where('user_id', auth()->id())->first();
        }
        return view('public.tracer-report', compact('tracerStudies', 'chartData', 'totalRespondents', 'workingPercentage', 'entrepreneurPercentage', 'alignmentData', 'majors', 'authStudent'));
    }

    public function tracerIndustry()
    {
        return view('public.tracer-industri');
    }

    public function storeTracer(Request $request)
    {
        $request->validate([
            'status_saat_ini'     => 'required|in:Bekerja,Kuliah,Wirausaha,Belum Bekerja',
            'nama_instansi'       => 'nullable|string|max:255',
            'tgl_mulai_masuk'     => 'nullable|date|before_or_equal:today',
            'keselarasan_jurusan' => 'nullable|in:Sesuai,Tidak Sesuai',
            'pendapatan_bulanan'  => 'nullable|numeric|min:0',
        ]);
        $user = Auth::user();
        if (!in_array($user->role->name, ['siswa', 'alumni'])) return back()->with('error', 'Akses tidak diizinkan.');
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) return back()->with('error', 'Data profil tidak ditemukan.');
        TracerStudy::updateOrCreate(
            ['student_id' => $student->student_id],
            [
                'status_saat_ini'     => $request->status_saat_ini,
                'nama_instansi'       => $request->nama_instansi,
                'tgl_mulai_masuk'     => $request->tgl_mulai_masuk,
                'keselarasan_jurusan' => $request->keselarasan_jurusan,
                'pendapatan_bulanan'  => $request->pendapatan_bulanan,
            ]
        );
        return back()->with('success', 'Data Tracer Study berhasil disimpan. Terima kasih!');
    }

    public function tutorial()
    {
        return view('public.tutorial');
    }

    public function tips(Request $request)
    {
        $query = \App\Models\Tip::published();
        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')->orWhere('ringkasan', 'like', '%' . $request->search . '%');
            });
        }
        $tips          = $query->orderBy('urutan')->orderByDesc('created_at')->paginate(9)->withQueryString();
        $featured      = \App\Models\Tip::published()->featured()->orderBy('urutan')->get();
        $kategoriList  = \App\Models\Tip::kategoriList();
        $kategoriCount = \App\Models\Tip::published()->selectRaw('kategori, count(*) as total')->groupBy('kategori')->pluck('total', 'kategori');
        return view('public.tips', compact('tips', 'featured', 'kategoriList', 'kategoriCount'));
    }

    public function tipsDetail($slug)
    {
        $tip = \App\Models\Tip::published()->where('slug', $slug)->firstOrFail();
        $relatedTips = \App\Models\Tip::published()->where('id', '!=', $tip->id)->where('kategori', $tip->kategori)->latest()->take(3)->get();
        return view('public.tips-detail', compact('tip', 'relatedTips'));
    }
} 