<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\AlumniStory;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // FIX 1: Job - 'active' string dan '!= private' aman di PostgreSQL
        // tapi tambahkan whereNotNull untuk safety
        $featured_jobs = Job::where('visibility', '!=', 'private')
                    ->where('status', 'active')
                    ->latest()
                    ->paginate(6);

        // FIX 2: Event - cast boolean sudah ada di model, harusnya aman
        // Tapi tambahkan cast eksplisit untuk jaga-jaga driver lama
        $featured_events = Event::where('is_published', true)
                    ->latest()
                    ->paginate(6);

        // FIX 3: News - sama, cast boolean sudah ada di model
        $news = News::where('is_published', true)
                    ->latest('published_at')
                    ->paginate(6);

        // FIX 4: AlumniStory - cek apakah scope 'approved' ada di model
        // Kalau error, ganti dengan: ->where('status', 'approved')
        $alumni_stories = AlumniStory::approved()
                    ->latest()
                    ->take(6)
                    ->get();

        return view('public.beranda', compact(
            'user',
            'featured_jobs',
            'featured_events',
            'news',
            'alumni_stories'
        ));
    }
}