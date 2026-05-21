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
        $query = TracerStudy::with('student');

        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }

        if ($request->filled('year')) {
            $query->whereHas('student', fn($q) =>
                $q->where('graduation_year', $request->year)
            );
        }

        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) =>
                $q->where('full_name', 'like', '%' . $request->search . '%')
            );
        }

        // 1. Ambil data untuk pagination terlebih dahulu 
        // (Status 'is_read' di memori $tracerStudies masih membawa nilai aslinya dari DB)
        $tracerStudies = $query->latest()->paginate(15)->withQueryString();

        // 2. Hitung statistik total data global untuk card/widget informasi
        $total      = TracerStudy::count();
        $working    = TracerStudy::where('status_saat_ini', 'Bekerja')->count();
        $studying   = TracerStudy::where('status_saat_ini', 'Kuliah')->count();
        $entrepren  = TracerStudy::where('status_saat_ini', 'Wirausaha')->count();
        $unemployed = TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count();

        $chartData = [
            'Bekerja'       => $working,
            'Kuliah'        => $studying,
            'Wirausaha'     => $entrepren,
            'Belum Bekerja' => $unemployed,
        ];

        $graduationYears = DB::table('students')
            ->select('graduation_year')
            ->whereNotNull('graduation_year')
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year');

        // 3. SEBELUM view dirender, tandai semua data yang 'false' menjadi 'true' di database.
        // Dengan begini, saat halaman dirender, $tracerStudies di halaman ini masih sempat 
        // membawa flag is_read = false untuk dicheck di blade (di-highlight), 
        // tapi di query berikutnya (atau saat refresh/pindah halaman), badge di sidebar sudah hilang.
        TracerStudy::where('is_read', false)->update(['is_read' => true]);

        return view('admin.tracer.index', compact(
            'tracerStudies', 'total', 'working', 'studying',
            'entrepren', 'unemployed', 'chartData', 'graduationYears'
        ));
    }

    /**
     * Menampilkan daftar semua alumni yang mengisi tracer study.
     */
    public function alumni(Request $request)
    {
        // 1. Ambil data dengan filter pencarian, status, dan tahun lulus agar fitur filter di Blade berfungsi
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
        
        // 2. Menghitung statistik global menggunakan nama variabel sesuai request kamu
        $total     = \App\Models\TracerStudy::count();
        $working   = \App\Models\TracerStudy::where('status_saat_ini', 'Bekerja')->count();
        $kuliah    = \App\Models\TracerStudy::whereIn('status_saat_ini', ['Kuliah', 'Melanjutkan Pendidikan'])->count();
        $wirausaha = \App\Models\TracerStudy::where('status_saat_ini', 'Wirausaha')->count();
        $belum     = \App\Models\TracerStudy::where('status_saat_ini', 'Belum Bekerja')->count();

        // 3. Mengambil daftar tahun kelulusan secara dinamis untuk dropdown filter di blade
        $graduationYears = \App\Models\TracerStudy::select('tahun_lulus')
            ->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        // 4. Return ke view beserta variabel yang sudah disesuaikan
        return view('admin.tracer.alumni', compact(
            'tracerStudies', 'total', 'working', 'kuliah', 'wirausaha', 'belum', 'graduationYears'
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
                'Nama Instansi', 'Tgl Mulai', 'Pendapatan (Rp)',
                'Kesesuaian Jurusan', 'Tanggal Isi',
            ]);

            foreach ($data as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row->student->full_name       ?? '-',
                    $row->student->graduation_year ?? '-',
                    $row->status_saat_ini,
                    $row->nama_instansi            ?? '-',
                    $row->tgl_mulai_masuk          ?? '-',
                    $row->pendapatan_bulanan
                        ? number_format($row->pendapatan_bulanan, 0, ',', '.')
                        : '-',
                    $row->keselarasan_jurusan      ?? '-',
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
}