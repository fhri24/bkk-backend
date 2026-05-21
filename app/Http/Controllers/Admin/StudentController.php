<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan scope bawaan model Anda yaitu alumniFilter()
        $query = Student::with('user')->where('alumni_flag', 1)->orderBy('full_name', 'asc');

        if ($request->filled('year')) {
            $query->where('graduation_year', $request->year);
        }

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        $students = $query->paginate(15);

        $availableYears = Student::alumniFilter()
            ->select('graduation_year')
            ->distinct()
            ->orderBy('graduation_year', 'desc')
            ->pluck('graduation_year');

        $availableMajors = Student::alumniFilter()
            ->select('major')
            ->distinct()
            ->orderBy('major')
            ->pluck('major');

        // Ringkasan per jurusan untuk tampilan grup widget card
        $summaryByMajor = Student::alumniFilter()
            ->select('major', DB::raw('count(*) as total'))
            ->groupBy('major')
            ->orderBy('major')
            ->get();

        // Ringkasan per angkatan untuk tampilan grup widget card
        $summaryByYear = Student::alumniFilter()
            ->select('graduation_year', DB::raw('count(*) as total'))
            ->groupBy('graduation_year')
            ->orderBy('graduation_year', 'desc')
            ->get();

        return view('admin.students.index', compact(
            'students', 'availableYears', 'availableMajors',
            'summaryByMajor', 'summaryByYear'
        ));
    }

    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }

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

            // FIX 1: Menggunakan whereRaw untuk kompatibilitas tipe data boolean PostgreSQL
            $count = Student::where('graduation_year', $year)
                ->whereRaw('"alumni_flag" = true')
                ->count();

            return redirect()->route('admin.students.index', ['year' => $year])
                ->with('success', "Import berhasil! Total alumni angkatan {$year}: {$count} siswa.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Hapus satu alumni beserta akunnya (Aman menggunakan ID Unik)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $student = Student::findOrFail($id);
            $userId  = $student->user_id;

            $student->delete();

            if ($userId) {
                User::where('id', $userId)->delete();
            }

            DB::commit(); // Memastikan state tersimpan dengan aman

            return redirect()->back()->with('success', 'Data alumni berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua alumni berdasarkan jurusan
     */
    public function destroyByMajor(Request $request)
    {
        $request->validate([
            'major' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $students = Student::alumniFilter()
                ->where('major', $request->major)
                ->get();

            $count   = $students->count();
            $userIds = $students->pluck('user_id')->filter()->toArray();

            Student::alumniFilter()
                ->where('major', $request->major)
                ->delete();

            if (!empty($userIds)) {
                User::whereIn('id', $userIds)->delete();
            }

            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', "Berhasil menghapus {$count} alumni jurusan {$request->major} beserta akun mereka.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua alumni berdasarkan angkatan
     */
    public function destroyByYear(Request $request)
    {
        $request->validate([
            'graduation_year' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $students = Student::alumniFilter()
                ->where('graduation_year', $request->graduation_year)
                ->get();

            $count   = $students->count();
            $userIds = $students->pluck('user_id')->filter()->toArray();

            Student::alumniFilter()
                ->where('graduation_year', $request->graduation_year)
                ->delete();

            if (!empty($userIds)) {
                User::whereIn('id', $userIds)->delete();
            }

            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', "Berhasil menghapus {$count} alumni angkatan {$request->graduation_year} beserta akun mereka.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
