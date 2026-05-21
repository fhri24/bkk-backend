<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use App\Models\IndustryTracer;
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
        $unemployed      = TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count();
        $graduationYears = TracerStudy::distinct()->pluck('tahun_lulus')->sort();
        $chartData       = [
            'Bekerja'       => $working,
            'Kuliah'        => $studying,
            'Wirausaha'     => $entrepren,
            'Belum Bekerja' => $unemployed,
        ];

        return view('admin.tracer.alumni', compact(
            'tracerStudies', 'total', 'working', 'studying',
            'entrepren', 'unemployed', 'graduationYears', 'chartData'
        ));
    }

    public function industri(Request $request)
    {
        // ✅ Fix: hanya with('user'), tidak ada relasi student
        $query = IndustryTracer::with('user')->latest();

        if ($request->filled('search')) {
            // ✅ Fix: hanya search by nama_perusahaan, tidak ada orWhereHas student
            $query->where('nama_perusahaan', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('jenis')) {
            $query->where('jenis_perusahaan', $request->jenis);
        }

        $data          = $query->paginate(15)->withQueryString();
        $totalIndustri = IndustryTracer::count();
        $withCompany   = IndustryTracer::count();
        $matching      = IndustryTracer::whereRaw(
            '(nilai_integritas + nilai_keahlian + nilai_bahasa_inggris +
              nilai_teknologi + nilai_komunikasi + nilai_kerjasama +
              nilai_analitis + nilai_kepemimpinan + nilai_tekanan) / 9 >= 4'
        )->count();

        $avgValues = [
            'Integritas'       => round(IndustryTracer::avg('nilai_integritas') ?? 0, 1),
            'Keahlian'         => round(IndustryTracer::avg('nilai_keahlian') ?? 0, 1),
            'Bahasa Inggris'   => round(IndustryTracer::avg('nilai_bahasa_inggris') ?? 0, 1),
            'Teknologi'        => round(IndustryTracer::avg('nilai_teknologi') ?? 0, 1),
            'Komunikasi'       => round(IndustryTracer::avg('nilai_komunikasi') ?? 0, 1),
            'Kerja Sama'       => round(IndustryTracer::avg('nilai_kerjasama') ?? 0, 1),
            'Analitis'         => round(IndustryTracer::avg('nilai_analitis') ?? 0, 1),
            'Kepemimpinan'     => round(IndustryTracer::avg('nilai_kepemimpinan') ?? 0, 1),
            'Kerja di Tekanan' => round(IndustryTracer::avg('nilai_tekanan') ?? 0, 1),
        ];

        return view('admin.tracer.industri', compact(
            'data', 'totalIndustri', 'withCompany', 'matching', 'avgValues'
        ));
    }

    public function industryShow(IndustryTracer $industryTracer)
    {
        $industryTracer->load('user');
        return view('admin.tracer.industri-show', compact('industryTracer'));
    }

    public function industryDestroy(IndustryTracer $industryTracer)
    {
        $industryTracer->delete();
        return redirect()->route('admin.tracer.industri')
            ->with('success', 'Data penilaian industri berhasil dihapus.');
    }
}