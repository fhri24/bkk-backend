<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function index()
{
    // Mengambil data tracer study beserta nama siswanya
    $reports = TracerStudy::with('student')->latest()->get();
    
    return view('admin.tracer.index', compact('reports'));
}
}
 