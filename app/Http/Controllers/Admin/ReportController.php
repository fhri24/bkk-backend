<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SchoolProfile;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index()
    {
        $totalAlumni = Student::where('alumni_flag', 1)->count();
        $totalJobs = Job::count();
        $totalApplications = JobApplication::count();

        $alumniCareerCounts = [
            'bekerja' => 0,
            'melanjutkan' => 0,
            'wirausaha' => 0,
            'belum' => 0,
        ];

        if (Schema::hasColumn('students', 'career_path')) {
            $alumniCareerCounts = Student::where('alumni_flag', 1) // ✅ fix
                ->selectRaw('career_path, COUNT(*) as total')
                ->groupBy('career_path')
                ->pluck('total', 'career_path')
                ->toArray();
        }

        $applications = JobApplication::with(['job.company'])->get();
        $applicationMonthly = $applications->groupBy(function ($item) {
            return $item->application_date->format('Y-m');
        })->map(function ($group, $month) {
            return [
                'month' => $month,
                'total' => $group->count(),
                'accepted' => $group->where('status', 'accepted')->count(),
                'by_company' => $group->groupBy(fn ($item) => $item->job->company->company_name ?? 'Tidak Diketahui')
                    ->map(fn ($items) => $items->count())
                    ->toArray(),
            ];
        })->values();

        return view('admin.reports.index', compact(
            'totalAlumni',
            'totalJobs',
            'totalApplications',
            'alumniCareerCounts',
            'applicationMonthly'
        ));
    }

    public function exportAlumniCsv()
    {
        $alumni = Student::where('alumni_flag', 1)->with('user')->get(); // ✅ fix
        $filename = 'alumni_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = ['NIS', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat/Tgl Lahir', 'Jurusan', 'Tahun Lulus', 'No HP', 'Alamat', 'Karir', 'Email'];

        $callback = function () use ($alumni, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($alumni as $student) {
                fputcsv($handle, [
                    $student->nis,
                    $student->full_name,
                    $student->gender,
                    $student->birth_info,
                    $student->major,
                    $student->graduation_year,
                    $student->phone,
                    $student->address,
                    $student->career_path ?? 'belum',
                    $student->user->email ?? '-',
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportJobsCsv()
    {
        $jobs = Job::with('company')->get();
        $filename = 'lowongan_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = ['Judul Lowongan', 'Perusahaan', 'Lokasi', 'Kategori', 'Status', 'Tanggal Dibuat'];

        $callback = function () use ($jobs, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($jobs as $job) {
                fputcsv($handle, [
                    $job->title,
                    $job->company->company_name ?? '-',
                    $job->location ?? '-',
                    $job->category ?? '-',
                    $job->status ?? '-',
                    $job->created_at?->format('Y-m-d') ?? '-',
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function printAlumni(Request $request)
    {
        $query = Student::where('alumni_flag', 1)->with('user'); // ✅ fix

        if ($request->filled('year')) {
            $query->where('graduation_year', $request->year);
        }

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        $alumni = $query->orderBy('full_name')->get();

        $availableYears = Student::where('alumni_flag', 1) // ✅ fix
            ->select('graduation_year')->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year');

        $availableMajors = Student::where('alumni_flag', 1) // ✅ fix
            ->select('major')->distinct()
            ->orderBy('major')
            ->pluck('major');

        $profile = SchoolProfile::latest()->first();

        return view('admin.reports.print-alumni', compact(
            'alumni', 'profile', 'availableYears', 'availableMajors'
        ));
    }

    public function printJobs()
    {
        $jobs = Job::with('company')->get();
        $profile = SchoolProfile::latest()->first();

        return view('admin.reports.print-jobs', compact('jobs', 'profile'));
    }
}
