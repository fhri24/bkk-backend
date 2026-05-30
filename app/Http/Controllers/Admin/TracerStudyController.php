<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TracerStudyController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.tracer.alumni');
    }

    /**
     * Menampilkan daftar semua alumni yang mengisi tracer study.
     */
    public function alumni(Request $request)
    {
        // 1. Ambil data dengan filter pencarian, status, dan tahun lulus
        $query = \App\Models\TracerStudy::with('student')->latest();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }

        if ($request->filled('year')) {
            $query->where('tahun_lulus', $request->year);
        }

        $tracerStudies = $query->paginate(15)->withQueryString();

        // 2. Menghitung statistik global
        $total     = \App\Models\TracerStudy::count();
        $working   = \App\Models\TracerStudy::where('status_saat_ini', 'Bekerja')->count();
        $kuliah    = \App\Models\TracerStudy::whereIn('status_saat_ini', ['Kuliah', 'Melanjutkan Pendidikan'])->count();
        $wirausaha = \App\Models\TracerStudy::where('status_saat_ini', 'Wirausaha')->count();
        $belum     = \App\Models\TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count();

        // Mengambil daftar tahun kelulusan secara dinamis
        $graduationYears = \App\Models\TracerStudy::select('tahun_lulus')
            ->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        DB::table('tracer_studies')->where('is_read', 0)->update(['is_read' => 1]);

        return view('admin.tracer.alumni', compact(
            'tracerStudies', 'total', 'working', 'kuliah', 'wirausaha', 'belum', 'graduationYears'
        ));
    }

    /**
     * Menampilkan laporan pemetaan tempat kerja / penilaian kepuasan industri penyerapan alumni.
     */
    public function industri(Request $request)
    {
        $query = \App\Models\IndustryTracer::query();

        if ($request->filled('search')) {
            $query->where('nama_perusahaan', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_perusahaan', $request->jenis);
        }

        // Hitung rata-rata per baris
        $nilaiKolom = [
            'nilai_integritas', 'nilai_keahlian', 'nilai_bahasa_inggris',
            'nilai_teknologi', 'nilai_komunikasi', 'nilai_kerjasama',
            'nilai_analitis', 'nilai_kepemimpinan', 'nilai_tekanan',
        ];

        $data = $query->latest()->paginate(10)->through(function ($row) use ($nilaiKolom) {
            $values = collect($nilaiKolom)->map(fn($k) => $row->$k)->filter()->values();
            $row->rata_rata = $values->count() > 0 ? round($values->avg(), 1) : 0;
            return $row;
        });

        // Stat cards
        $all           = \App\Models\IndustryTracer::all();
        $totalIndustri = $all->count();
        $withCompany   = $totalIndustri;

        // Rata-rata per aspek (untuk semua data)
        $avgValues = [
            'Integritas'      => round($all->avg('nilai_integritas'), 1),
            'Keahlian'        => round($all->avg('nilai_keahlian'), 1),
            'Bahasa Inggris'  => round($all->avg('nilai_bahasa_inggris'), 1),
            'Teknologi'       => round($all->avg('nilai_teknologi'), 1),
            'Komunikasi'      => round($all->avg('nilai_komunikasi'), 1),
            'Kerjasama'       => round($all->avg('nilai_kerjasama'), 1),
            'Analitis'        => round($all->avg('nilai_analitis'), 1),
            'Kepemimpinan'    => round($all->avg('nilai_kepemimpinan'), 1),
            'Kerja di Tekanan'=> round($all->avg('nilai_tekanan'), 1),
        ];

        // Perusahaan dengan rata-rata >= 4
        $matching = $all->filter(function ($row) use ($nilaiKolom) {
            $values = collect($nilaiKolom)->map(fn($k) => $row->$k)->filter();
            return $values->count() > 0 && round($values->avg(), 1) >= 4;
        })->count();

        return view('admin.tracer.industri', compact(
            'data', 'totalIndustri', 'withCompany', 'avgValues', 'matching'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = TracerStudy::with('student');

        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereHas('student', fn($q) =>
                $q->where('graduation_year', $request->year)
            );
        }

        $data     = $query->latest()->get();
        $filename = 'tracer-study-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No', 'Nama Alumni', 'Angkatan', 'Status',
                'Instansi / PT / Usaha', 'Posisi / Jurusan', 'Tgl Mulai',
                'Penghasilan / Omzet', 'Tanggal Isi',
            ]);

            foreach ($data as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row->student->full_name       ?? ($row->nama_lengkap ?? '-'),
                    $row->student->graduation_year ?? ($row->tahun_lulus ?? '-'),
                    $row->status_saat_ini,
                    $row->nama_instansi            ?? ($row->nama_pt ?? ($row->nama_usaha ?? '-')),
                    $row->posisi_jabatan           ?? ($row->jurusan_pt ?? ($row->detail_kegiatan ?? '-')),
                    $row->tmt_bekerja              ?? ($row->tmt_kuliah ?? ($row->tmt_wirausaha ?? '-')),
                    $row->range_gaji               ?? ($row->omzet_per_bulan ?? '-'),
                    $row->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = TracerStudy::with('student');

        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereHas('student', fn($q) =>
                $q->where('graduation_year', $request->year)
            );
        }

        $tracerStudies = $query->latest()->get();

        $total      = $tracerStudies->count();
        $working    = $tracerStudies->where('status_saat_ini', 'Bekerja')->count();
        $studying   = $tracerStudies->where('status_saat_ini', 'Kuliah')->count();
        $entrepren  = $tracerStudies->where('status_saat_ini', 'Wirausaha')->count();
        $unemployed = $tracerStudies->where('status_saat_ini', 'Belum Bekerja')->count();

        return view('admin.tracer.print', compact(
            'tracerStudies', 'total', 'working', 'studying', 'entrepren', 'unemployed'
        ));
    }

    public function show(\App\Models\TracerStudy $tracerStudy)
{
    return view('admin.tracer.show', compact('tracerStudy'));
}

public function destroy(\App\Models\TracerStudy $tracerStudy)
{
    $tracerStudy->delete();
    return redirect()->route('admin.tracer.index')
        ->with('success', 'Data tracer study berhasil dihapus.');
}

    /**
     * Show detail industri tracer
     */
    public function industryShow(\App\Models\IndustryTracer $industryTracer)
    {
        return view('admin.tracer.industri-show', compact('industryTracer'));
    }

    /**
     * Delete industri tracer
     */
    public function industryDestroy(\App\Models\IndustryTracer $industryTracer)
    {
        $industryTracer->delete();
        return redirect()->route('admin.tracer.industri')
            ->with('success', 'Data industri tracer berhasil dihapus.');
    }
}
