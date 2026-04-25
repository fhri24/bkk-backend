<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function tracer()
    {
        $user = auth()->user();
        $student = $user->studentProfile;

        // DIUBAH KE PUBLIC: Karena file di folder student sudah Anda hapus
        return view('public.tracer', compact('user', 'student'));
    }

    public function profil()
    {
        $user = auth()->user();
        $student = $user->studentProfile;

        return view('student.profile', compact('user', 'student'));
    }

    public function bantuan()
    {
        return view('student.bantuan');
    }

    public function tentang()
    {
        return view('student.tentang');
    }
}