<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar semua alumni.
     */
    public function index(Request $request)
    {
        $query = Student::with('user')->alumniFilter()->latest();

        // Filter per tahun lulus
        if ($request->filled('year')) {
            $query->where('graduation_year', $request->year);
        }

        $students = $query->paginate(15);

        // Ambil semua tahun lulus yang tersedia untuk dropdown filter
        $availableYears = Student::alumniFilter()
            ->select('graduation_year')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year');

        return view('admin.students.index', compact('students', 'availableYears'));
    }

    /**
     * Menampilkan detail spesifik alumni.
     */
    public function show($id)
    { 
        $student = Student::with('user')->findOrFail($id);
        return view('admin.students.show', compact('student')); 
    }

    /**
     * Proses import data alumni dari Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file'      => 'required|file|mimes:xlsx,xls',
            'graduation_year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ], [
            'excel_file.required'      => 'File Excel wajib diupload.',
            'excel_file.mimes'         => 'Format file harus .xlsx atau .xls.',
            'graduation_year.required' => 'Tahun lulus wajib diisi.',
        ]);

        try {
            $year = (int) $request->graduation_year;

            Excel::import(new StudentsImport($year), $request->file('excel_file'));

            $count = Student::where('graduation_year', $year)
                ->where('alumni_flag', true)
                ->count();

            return redirect()->route('admin.students.index', ['year' => $year])
                ->with('success', "Import berhasil! Total alumni angkatan {$year}: {$count} siswa.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}