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
            $query->whereHas(
                'student',
                fn($q) =>
                $q->where('graduation_year', $request->year)
            );
        }

        if ($request->filled('search')) {
            // FIX: cari berdasarkan full_name bukan name
            $query->whereHas(
                'student',
                fn($q) =>
                $q->where('full_name', 'like', '%' . $request->search . '%')
            );
        }

        $tracerStudies = $query->latest()->paginate(15)->withQueryString();

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

        return view('admin.tracer.index', compact(
            'tracerStudies',
            'total',
            'working',
            'studying',
            'entrepren',
            'unemployed',
            'chartData',
            'graduationYears'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = TracerStudy::with('student');

        if ($request->filled('status')) {
            $query->where('status_saat_ini', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereHas(
                'student',
                fn($q) =>
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
                'No',
                'Nama Alumni',
                'Angkatan',
                'Status',
                'Nama Instansi',
                'Tgl Mulai',
                'Pendapatan (Rp)',
                'Kesesuaian Jurusan',
                'Tanggal Isi',
            ]);

            foreach ($data as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    // FIX: pakai full_name bukan name
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
            $query->whereHas(
                'student',
                fn($q) =>
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
            'tracerStudies',
            'total',
            'working',
            'studying',
            'entrepren',
            'unemployed'
        ));
    }
}
