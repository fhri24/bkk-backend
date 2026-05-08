<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\AlumniStory; // Import model AlumniStory

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Mengambil lowongan yang aktif dan publik
        $featured_jobs = Job::where('visibility', '!=', 'private')
                    ->where('status', 'active')
                    ->latest()
                    ->paginate(6);
        
        // Mengambil event yang sudah dipublish
        $featured_events = Event::where('is_published', true)
                    ->latest()
                    ->paginate(6);
        
        // Mengambil berita yang sudah dipublish
        $news = News::where('is_published', true)
                    ->latest('published_at')
                    ->paginate(6);

        /**
         * TAMBAHAN: Mengambil kisah sukses alumni untuk dashboard.
         * Hanya mengambil yang statusnya 'approved'.
         */
        $alumni_stories = AlumniStory::approved()
                    ->latest()
                    ->take(6) 
                    ->get();
        
        // Pastikan variabel 'alumni_stories' dikirim ke view
        return view('public.beranda', compact(
    'user',
    'featured_jobs', 
    'featured_events', 
    'news', 
    'alumni_stories'
));
    }
}