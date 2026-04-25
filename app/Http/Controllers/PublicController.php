<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\Student;
use App\Models\TracerStudy;

class PublicController extends Controller
{
    /**
     * Halaman Landing Page Utama
     */
    public function beranda()
    {
        $news = News::where('is_published', true)->latest()->take(3)->get();
        $featured_jobs = Job::latest()->take(3)->with('company')->get();
        $featured_events = Event::where('is_published', true)->latest()->take(3)->get();

        return view('public.beranda', compact('news', 'featured_jobs', 'featured_events'));
    }

    /** * Halaman List Lowongan (Publik) 
     */ 
    public function lowongan(Request $request) 
    { 
        $query = Job::latest()->with('company');
 
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('company', function($sq) use ($request) {
                      $sq->where('company_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('type')) { 
            $query->where('job_type', $request->type);
        } 
 
        if ($request->filled('major')) { 
            $query->where('description', 'like', '%' . $request->major . '%');
        } 

        $jobs = $query->paginate(10); 

        return view('public.lowongan', compact('jobs'));
    } 

    /**
     * Halaman Detail Lowongan (Publik) 
     */ 
    public function lowonganDetail($id)
    { 
        $job = Job::with('company')->findOrFail($id);

        $similarJobs = Job::with('company') 
            ->where('id', '!=', $id) // Diperbaiki dari job_id ke id
            ->latest() 
            ->take(3) 
            ->get();
 
        return view('public.lowongan-detail', compact('job', 'similarJobs'));
    }  

    /**
     * Halaman List Berita (Publik) 
     * Menggunakan variabel $newsItems agar sesuai dengan view public.berita
     */
    public function berita() 
    {
        $newsItems = News::where('is_published', true)->latest()->paginate(6);
        return view('public.berita', compact('newsItems'));
    }
 
    /** 
     * Halaman Detail Berita (Publik)
     * Menggunakan berita-detail (dengan strip) sesuai hasil cleanup
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
     * Halaman List Acara (Publik) 
     */ 
    public function acara()
    { 
        $events = Event::where('is_published', true)->latest()->paginate(10);
        return view('public.acara', compact('events')); 
    }

    /**
     * Halaman Detail Acara (Publik)
     */
    public function acaraDetail($id)
    {
        $event = Event::where('is_published', true)->findOrFail($id);

        $relatedEvents = Event::where('is_published', true)
                             ->where('id', '!=', $id)
                             ->where('start_date', '>=', now())
                             ->take(3)
                             ->get();

        return view('public.acara-detail', compact('event', 'relatedEvents'));
    } 

    /**
     * Halaman Tracer Alumni
     */ 
    public function tracer(Request $request)
    { 
        $alumni = Student::where('alumni_flag', true) 
                        ->orderBy('graduation_year', 'desc')
                        ->get(); 

        $siswaAktif = Student::where('alumni_flag', false) 
                        ->get();

        return view('public.tracer', compact('alumni', 'siswaAktif')); 
    }

    /**
     * Simpan Data Tracer Study
     */
    public function storeTracer(Request $request)
    {
        $request->validate([
            'status_kerja' => 'required|string',
            'company' => 'nullable|string',
            'position' => 'nullable|string',
            'salary' => 'nullable|string',
        ]);

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return back()->with('error', 'Data profil siswa tidak ditemukan.');
        }

        TracerStudy::create([
            'student_id' => $student->student_id,
            'status_saat_ini' => $request->status_kerja,
            'nama_instansi' => $request->company,
            'pendapatan_bulanan' => $request->salary,
            'keselarasan_jurusan' => $request->position, 
        ]);

        return back()->with('success', 'Tracer Study berhasil disimpan!');
    }

    /** * Halaman Tutorial
     */ 
    public function tutorial()
    { 
        return view('public.tutorial'); 
    }
} 