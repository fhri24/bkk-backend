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

        $featured_jobs = Job::where('visibility', '!=', 'private')
                    ->where('status', 'active')
                    ->latest()
                    ->paginate(6);

        $featured_events = Event::whereRaw('"is_published" = true')
                    ->latest()
                    ->paginate(6);

        $news = News::whereRaw('"is_published" = true')
                    ->latest('published_at')
                    ->paginate(6);

        $alumni_stories = AlumniStory::where('status', 'approved')
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