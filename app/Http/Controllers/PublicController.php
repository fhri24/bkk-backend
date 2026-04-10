<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;

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
     */
    public function lowongan()
    {
        $jobs = Job::latest()->with('company')->paginate(10);
        return view('public.lowongan', compact('jobs'));
    }

    /**
     * Halaman Detail Lowongan (Publik)
     */
    public function lowonganDetail($id)
    {
        $job = Job::with('company')->findOrFail($id);
        return view('public.lowongan-detail', compact('job'));
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
        // Mencari berita berdasarkan ID atau Slug, memuat relasi author
        // Gunakan with(['author']) agar nama penulis tampil di detail
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
     * Halaman Tambahan
     */
    public function tracer()
    {
        return view('public.tracer');
    }

    public function tutorial()
    {
        return view('public.tutorial');
    }
}
