<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardActionController extends Controller
{
    /**
     * Shortcut langsung ke bagian Export di halaman Laporan
     */
    public function export()
    {
        // Mengarahkan ke admin/reports lalu otomatis scroll ke #export-data
        return redirect()->route('admin.reports.index')->withFragment('export-data');
    }

    /**
     * Shortcut langsung ke bagian Laporan BMW di halaman Laporan
     */
    public function laporan()
    {
        // Mengarahkan ke admin/reports lalu otomatis scroll ke #bmw-report
        return redirect()->route('admin.reports.index')->withFragment('bmw-report');
    }

    /**
     * Fitur Broadcast (Pending - sesuai permintaan)
     */
    public function broadcast()
    {
        return back()->with('info', 'Fitur Broadcast sedang dalam pengembangan.');
    }
}