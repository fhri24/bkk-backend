<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->alumniFilter()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }
}