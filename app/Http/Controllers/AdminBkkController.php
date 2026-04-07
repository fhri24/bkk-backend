<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan Model sudah ada
use App\Models\Perusahaan;
use App\Models\Lowongan;

class AdminBkkController extends Controller
{
    public function index()
    {
        // Contoh data dummy dulu jika database belum migrasi
        return view('admin_bkk.dashboard');
    }
}
