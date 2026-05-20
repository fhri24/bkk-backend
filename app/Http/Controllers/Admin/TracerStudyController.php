<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use App\Models\GraduationYear;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = TracerStudy::with('student')->latest();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }
        if ($request->filled('year')) {
            $query->where('tahun_lulus', $request->year);
        }

        $tracerStudies   = $query->paginate(15)->withQueryString();
        $total           = TracerStudy::count();
        $working         = TracerStudy::where('status_saat_ini', 'Bekerja')->count();
        $studying        = TracerStudy::where('status_saat_ini', 'Kuliah')->count();
        $entrepren       = TracerStudy::where('status_saat_ini', 'Wirausaha')->count();
        $unemployed      = TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count();
        $graduationYears = TracerStudy::distinct()->pluck('tahun_lulus')->sort();
        $chartData       = [
            'Bekerja'       => $working,
            'Kuliah'        => $studying,
            'Wirausaha'     => $entrepren,
            'Belum Bekerja' => $unemployed,
        ];

        return view('admin.tracer.index', compact(
            'tracerStudies', 'total', 'working', 'studying',
            'entrepren', 'unemployed', 'graduationYears', 'chartData'
        ));
    }

    public function show(TracerStudy $tracerStudy)
    {
        return view('admin.tracer.show', compact('tracerStudy'));
    }

    public function destroy(TracerStudy $tracerStudy)
    {
        $tracerStudy->delete();
        return redirect()->route('admin.tracer.index')
            ->with('success', 'Data tracer study berhasil dihapus.');
    }

    public function alumni(Request $request)
    {
        $query = TracerStudy::with('student')->latest();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }
        if ($request->filled('year')) {
            $query->where('tahun_lulus', $request->year);
        }

        $tracerStudies   = $query->paginate(15)->withQueryString();
        $total           = TracerStudy::count();
        $working         = TracerStudy::where('status_saat_ini', 'Bekerja')->count();
        $studying        = TracerStudy::where('status_saat_ini', 'Kuliah')->count();
        $entrepren       = TracerStudy::where('status_saat_ini', 'Wirausaha')->count();
        $graduationYears = TracerStudy::distinct()->pluck('tahun_lulus')->sort();
        $chartData       = [
            'Bekerja'       => $working,
            'Kuliah'        => $studying,
            'Wirausaha'     => $entrepren,
            'Belum Bekerja' => TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count(),
        ];

        return view('admin.tracer.alumni', compact(
            'tracerStudies', 'total', 'working', 'studying',
            'entrepren', 'graduationYears', 'chartData'
        ));
    }

    public function industri(Request $request)
    {
        $data = TracerStudy::where('status_saat_ini', 'Bekerja')
            ->select('nama_instansi', 'lokasi_kerja', 'posisi_jabatan', 'range_gaji', 'jurusan', 'tahun_lulus')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalIndustri  = TracerStudy::where('status_saat_ini', 'Bekerja')->count();
        $dalamNegeri    = TracerStudy::where('status_saat_ini', 'Bekerja')->where('lokasi_kerja', 'Dalam Negeri')->count();
        $luarNegeri     = TracerStudy::where('status_saat_ini', 'Bekerja')->where('lokasi_kerja', 'Luar Negeri')->count();

        return view('admin.tracer.industri', compact('data', 'totalIndustri', 'dalamNegeri', 'luarNegeri'));
    }
}