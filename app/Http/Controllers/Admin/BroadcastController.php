<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Company;
use App\Models\Event;
use App\Models\News;

class BroadcastController extends Controller
{
    public function index()
    {
        $recent_jobs = Job::with('company')
            ->latest()
            ->take(10)
            ->get();

        $recent_applications = JobApplication::with(['student', 'job'])
            ->latest()
            ->take(10)
            ->get();

        $recent_companies = Company::latest()
            ->take(10)
            ->get();

        $recent_events = Event::latest()
            ->take(10)
            ->get();

        $recent_news = News::latest()
            ->take(10)
            ->get();

        return view('admin.broadcast.index', compact(
            'recent_jobs',
            'recent_applications',
            'recent_companies',
            'recent_events',
            'recent_news'
        ));
    }
}