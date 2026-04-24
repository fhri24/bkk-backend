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
    // Pastikan variabelnya 'news' (sesuai yang dipanggil di Blade)
    // Dan dipaksa take(3)
    $news = \App\Models\News::latest()->take(3)->with('author')->get();

    // Lowongan dan Event tetap 3
    $featured_jobs = \App\Models\Job::latest()->take(3)->with('company')->get();
    $featured_events = \App\Models\Event::latest()->take(3)->get();

    return view('public.beranda', compact('news', 'featured_jobs', 'featured_events'));
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

    public function detailBerita($id)

    {



       $news = \App\Models\News::with('author')->findOrFail($id);



        return view('public.berita_detail', compact('news'));

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
     * Store Tracer Study Data
     */
    public function storeTracer(Request $request)
    {
        $request->validate([
            'status_kerja' => 'required|string',
            'company' => 'nullable|string',
            'position' => 'nullable|string',
            'salary' => 'nullable|string',
        ]);

        $student = auth()->user(); // Assuming user is student

        TracerStudy::create([
            'student_id' => $student->student_id,
            'status_saat_ini' => $request->status_kerja,
            'nama_instansi' => $request->company,
            'pendapatan_bulanan' => $request->salary,
            'keselarasan_jurusan' => $request->position, // Using position as alignment for now
        ]);

        return back()->with('success', 'Tracer Study berhasil disimpan!');
    }

    /**

     * Halaman Tutorial / Panduan

     */

    public function tutorial()

    {

        return view('public.tutorial');

    }



}

