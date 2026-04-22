<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\Student;

class PublicController extends Controller
{
    /**
     * Halaman Landing Page Utama
     */
    public function beranda()
    {
        $featured_jobs = collect();
        try {
            $featured_jobs = Job::latest()->take(3)->with('company')->get();
        } catch (\Exception $e) {}

        $featured_events = collect();
        try {
            $featured_events = Event::latest()->take(3)->get();
        } catch (\Exception $e) {}

        $featured_news = collect();
        try {
            $featured_news = News::latest()->take(3)->with('author')->get();
        } catch (\Exception $e) {}

        return view('public.beranda', compact('featured_jobs', 'featured_events', 'featured_news'));
    }
    

    /**
     * Halaman List Lowongan (Publik)
     * Ditambahkan fitur Search dan Filter agar View berfungsi maksimal
     */
    public function lowongan(Request $request)
    {
        $query = Job::latest()->with('company');

        // Filter Pencarian Kata Kunci
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('company', function($q) use ($request) {
                      $q->where('company_name', 'like', '%' . $request->search . '%');
                  });
        }

        // Filter Tipe Pekerjaan (Full-time, Magang, dll)
        if ($request->filled('type')) {
            $query->where('job_type', $request->type);
        }

        // Filter Berdasarkan Bidang/Jurusan (Jika ada kolom major/category)
        if ($request->filled('major')) {
            $query->where('description', 'like', '%' . $request->major . '%');
        }

        $jobs = $query->paginate(10);
        
        return view('public.lowongan', compact('jobs'));
    }

    /**
     * Halaman Detail Lowongan (Publik)
     * Memperbaiki error Undefined variable $similarJobs
     */
    public function lowonganDetail($id)
    {
        // 1. Ambil data lowongan utama berdasarkan ID
        $job = Job::with('company')->findOrFail($id);

        // 2. Ambil data lowongan serupa untuk sidebar
        // Mengambil 3 lowongan terbaru selain yang sedang dibuka
        $similarJobs = Job::with('company')
            ->where('job_id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('public.lowongan-detail', compact('job', 'similarJobs'));
    }

    /**
     * Halaman List Berita (Publik)
     */
    public function berita()
    {
        $news = News::latest()->with('author')->paginate(10);
        return view('public.berita', compact('news'));
    }

    /**
     * Halaman Detail Berita (Publik)
     */
    public function beritaDetail($id)
    {

        $news = News::with(['author'])->findOrFail($id);

        return view('public.berita-detail', compact('news'));
    }

    /**
     * Halaman List Acara (Publik)
     */
    public function acara()
    {
        $events = Event::latest()->paginate(10);
        return view('public.acara', compact('events'));
    }

    /**
     * Halaman Tracer Alumni
     */
    public function tracer(Request $request)
    {
        // Ambil data Alumni
        $alumni = Student::where('alumni_flag', true)
                    ->orderBy('graduation_year', 'desc')
                    ->get();

        // Ambil data Siswa Aktif (Bukan Alumni)
        $siswaAktif = Student::where('alumni_flag', false)
                    ->get();

        return view('public.tracer', compact('alumni', 'siswaAktif'));
    }

    /**
     * Halaman Tutorial / Panduan
     */
    public function tutorial()
    {
        return view('public.tutorial');
    }

}
