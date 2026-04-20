<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\JobApplication;
use Exception;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $q = $request->query('q');

            if (empty($q)) {
                return response()->json([
                    'success' => true,
                    'students' => [],
                    'applications' => []
                ]);
            }

            // PERBAIKAN: Pakai 'full_name' sesuai migration, bukan 'nama_lengkap'
            $students = Student::where('full_name', 'like', "%$q%")
                        ->limit(5)
                        ->get(['student_id', 'full_name', 'major']); // Ambil kolom yang ada di DB

            // PERBAIKAN: Cari lamaran berdasarkan status
            $applications = JobApplication::where('status', 'like', "%$q%")
                        ->with(['student']) // Pastikan relasi di model sudah benar
                        ->limit(5)
                        ->get();

            return response()->json([
                'success' => true,
                'students' => $students,
                'applications' => $applications
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
