<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Job;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $jobs = Job::where('visibility', '!=', 'private')
                    ->where('status', 'active')
                    ->latest()
                    ->paginate(6);
        
        return view('student.home', compact('user', 'jobs'));
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
