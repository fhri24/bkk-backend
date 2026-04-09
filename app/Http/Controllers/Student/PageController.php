<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\News;
use App\Models\Event;

class PageController extends Controller
{
    /**
     * Lowongan Page - Show all job listings
     */
    public function lowongan()
    {
        $user = auth()->user();
        $jobs = Job::where('status', 'active')
                    ->where('visibility', '!=', 'private')
                    ->latest()
                    ->paginate(12);
        
        return view('student.pages.lowongan', compact('user', 'jobs'));
    }

    /**
     * Lowongan Detail Page
     */
    public function lowonganDetail($id)
    {
        $user = auth()->user();
        $job = Job::findOrFail($id);
        
        return view('student.pages.lowongan-detail', compact('user', 'job'));
    }

    /**
     * Berita Page - Show all news
     */
    public function berita()
    {
        $user = auth()->user();
        $berita = News::where('is_published', true)
                      ->latest('published_at')
                      ->paginate(9);
        
        return view('student.pages.berita', compact('user', 'berita'));
    }

    /**
     * Berita Detail Page
     */
    public function beritaDetail($id)
    {
        $user = auth()->user();
        $berita = News::findOrFail($id);
        
        return view('student.pages.berita-detail', compact('user', 'berita'));
    }

    /**
     * Acara Page - Show all events
     */
    public function acara()
    {
        $user = auth()->user();
        $acara = Event::where('is_published', true)
                     ->where('start_date', '>=', now())
                     ->latest('start_date')
                     ->paginate(12);
        
        return view('student.pages.acara', compact('user', 'acara'));
    }

    /**
     * Tracer Study Page
     */
    public function tracer()
    {
        $user = auth()->user();
        $student = $user->studentProfile;
        
        return view('student.pages.tracer', compact('user', 'student'));
    }

    /**
     * Profil Page - Student Profile
     */
    public function profil()
    {
        $user = auth()->user();
        $student = $user->studentProfile;
        
        return view('student.pages.profil', compact('user', 'student'));
    }
}

