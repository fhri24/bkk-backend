<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KepalaBkkController extends Controller
{
    public function index()
    {
        // Di sini nanti Kelompok 4 akan mengambil data asli dari tabel tracer_study
        return view('kepala_bkk.dashboard');
    }

    public function laporan()
    {
        // Fungsi untuk export PDF (Kelompok 4)
        return "Halaman Laporan PDF";
    }
}