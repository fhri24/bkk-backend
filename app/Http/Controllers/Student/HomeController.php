<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Event;
use App\Models\News;
use App\Models\AlumniStory;
use App\Models\TracerStudy;
use App\Models\Company;
use App\Models\SchoolProfile;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $featured_jobs = Job::with('company')
            ->where('approval_status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        $featured_events = Event::with('registrations')
            ->whereRaw('"is_published" = true')
            ->latest()
            ->take(3)
            ->get();

        $news = News::whereRaw('"is_published" = true')
            ->latest('published_at')
            ->take(3)
            ->get();

        $alumni_stories = AlumniStory::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $avatarColors = [
            'from-blue-500 to-blue-700',
            'from-indigo-500 to-indigo-700',
            'from-violet-500 to-violet-700',
        ];

        // Stat Cards
        $alumniTerserap = TracerStudy::whereIn('status_saat_ini', ['Bekerja', 'Wirausaha'])->count();

        $totalTracer = TracerStudy::count();
        $tingkatPenyaluran = $totalTracer > 0
            ? round(($alumniTerserap / $totalTracer) * 100)
            : 0;

        $lowonganAktif = Job::where('approval_status', 'approved')
            ->where('expired_at', '>=', now())
            ->count();

        $totalPerusahaan = Company::count();

        $schoolProfile = SchoolProfile::first();

        return view('public.beranda', compact(
            'user',
            'featured_jobs',
            'featured_events',
            'news',
            'alumni_stories',
            'avatarColors',
            'alumniTerserap',
            'tingkatPenyaluran',
            'lowonganAktif',
            'totalPerusahaan',
            'schoolProfile'
        ));
    }
}
