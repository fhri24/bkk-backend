<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar semua siswa/alumni.
     */
    public function index()
    {
        // Menggunakan scope alumniFilter() sesuai kode awal Anda
        $students = Student::with('user')
            ->alumniFilter()
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Menampilkan detail spesifik dari seorang siswa/alumni.
     */
    public function show($id)
    {
        // Mencari data student berdasarkan ID beserta relasi user-nya
        $student = Student::with('user')->findOrFail($id);

        return view('admin.students.show', compact('student'));
    }
}