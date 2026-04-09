<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Student;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        // Verify user has role loaded
        $user = auth()->user();
        if (!$user->role) {
            return redirect('/')->with('error', 'User role not found');
        }

        // Statistics
        $total_jobs = Job::count();
        $total_applications = JobApplication::count();
        $total_students = Student::count();
        $total_companies = Company::count();
        $pending_applications = JobApplication::where('status', 'pending')->count();
        $active_jobs = Job::where('status', 'active')->count();
        
        // Recent data
        $recent_jobs = Job::latest()->take(5)->with('company')->get();
        $recent_applications = JobApplication::latest()->take(5)->with(['job', 'student'])->get();
        
        return view('admin.dashboard', compact(
            'total_jobs',
            'total_applications',
            'total_students',
            'total_companies',
            'pending_applications',
            'active_jobs',
            'recent_jobs',
            'recent_applications'
        ));
    }
}
