<?php

namespace App\Http\Controllers;

use App\Models\Job; // Jangan lupa panggil Model Job-nya
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        // Di sini kita pakai Scope yang kita buat di Model kemarin
        // Kita ambil lowongan yang statusnya 'active' DAN 'public'
        $lowonganUmum = Job::active()->public()->get();

        return view('lowongan.index', compact('lowonganUmum'));
    }

    public function khususAlumni()
    {
    // Cegah user non-alumni masuk
    if (!auth()->check() || !auth()->user()->alumni_flag) {
        abort(403, 'Akses hanya untuk alumni');
    }

    $lowonganAlumni = Job::active()
        ->alumniOnly()
        ->get();

    return view('lowongan.alumni', compact('lowonganAlumni'));
    }
}
