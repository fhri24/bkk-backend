<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $featured_jobs = Job::where('visibility', '!=', 'private')
                    ->where('status', 'active')
                    ->latest()
                    ->paginate(6);
        
        $featured_events = Event::where('is_published', true)
                    ->latest()
                    ->paginate(6);
        
        $featured_news = News::where('is_published', true)
                    ->latest('published_at')
                    ->paginate(6);
        
        return view('student.Beranda', compact('user', 'featured_jobs', 'featured_events', 'featured_news'));
    }

    public function profile()
    {
        $user = auth()->user();
        $student = $user->studentProfile;
        
        return view('student.profile', compact('user', 'student'));
    }

    public function applications()
    {
        $user = auth()->user();
        $student = $user->studentProfile;
        $applications = $student->jobApplications()->with('job')->get();
        
        return view('student.applications', compact('user', 'applications'));
    }
}
