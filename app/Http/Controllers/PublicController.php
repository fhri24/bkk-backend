<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Event;
use App\Models\News;

class PublicController extends Controller
{
    public function beranda()
    {
        // Get 3 featured active jobs
        $featured_jobs = Job::where('status', 'active')
            ->whereNotIn('visibility', ['private'])
            ->latest('posted_at')
            ->take(3)
            ->with('company')
            ->get();

        // Get 3 featured upcoming events
        $featured_events = Event::where('is_published', true)
            ->where('start_date', '>=', now())
            ->latest('start_date')
            ->take(3)
            ->get();

        // Get 3 featured published news
        $featured_news = News::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.beranda', compact('featured_jobs', 'featured_events', 'featured_news'));
    }

    public function tutorial()
    {
        return view('public.tutorial');
    }
}
