<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Menampilkan daftar lamaran (Admin/Kepala BKK lihat semua, Student lihat punya dia)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filter berdasarkan role
        if ($user->role->name === 'student') {
            // Siswa hanya bisa lihat lamaran mereka sendiri
            $student = $user->userable;
            $applications = JobApplication::where('student_id', $student->student_id)
                ->with(['student', 'job'])
                ->latest('application_date')
                ->get();
        } else {
            // Admin/Kepala BKK bisa lihat semua lamaran
            $applications = JobApplication::with(['student', 'job.company'])
                ->latest('application_date')
                ->get();
        }

        return response()->json([
            'message' => 'Daftar lamaran berhasil diambil',
            'data' => $applications
        ], 200);
    }

    /**
     * Menampilkan detail satu lamaran (GET /applications/{id})
     */
    public function show($id)
    {
        try {
            $application = JobApplication::with(['student', 'job.company'])
                ->findOrFail($id);

            // Cek otorisasi: hanya pemilik lamaran atau admin/kepala bkk
            $user = Auth::user();
            if ($user->role->name === 'student') {
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json([
                        'message' => 'Anda tidak berhak mengakses lamaran ini'
                    ], 403);
                }
            }

            return response()->json([
                'message' => 'Detail lamaran berhasil diambil',
                'data' => $application
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Siswa melamar ke sebuah lowongan (POST /applications)
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'job_id' => 'required|exists:job_listings,job_id',
            'cover_letter' => 'nullable|string|max:1000',
            'additional_file' => 'nullable|string|max:255',
        ]);

        // 2. Ambil data siswa yang sedang login
        $user = Auth::user();
        $student = $user->userable;

        if (!$student || get_class($student) !== 'App\Models\Student') {
            return response()->json(['message' => 'Hanya siswa yang bisa melamar.'], 403);
        }

        // 3. CEK: Sudah pernah apply ke job yang sama belum?
        $alreadyApplied = JobApplication::where('student_id', $student->student_id)
            ->where('job_id', $request->job_id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json([
                'message' => 'Kamu sudah melamar di lowongan ini sebelumnya.'
            ], 400);
        }

        // 4. Simpan lamaran baru
        $application = JobApplication::create([
            'job_id' => $request->job_id,
            'student_id' => $student->student_id,
            'cover_letter' => $request->cover_letter,
            'additional_file' => $request->additional_file,
            'application_date' => now(),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Lamaran berhasil dikirim!',
            'data' => $application
        ], 201);
    }

    /**
     * Update lamaran (Admin/Kepala BKK ubah status, Student edit lampiran)
     */
    public function update(Request $request, $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $user = Auth::user();

            if ($user->role->name === 'student') {
                // Siswa hanya bisa edit lamaran mereka sendiri dan hanya field tertentu
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json([
                        'message' => 'Anda tidak berhak mengedit lamaran ini'
                    ], 403);
                }

                $request->validate([
                    'cover_letter' => 'nullable|string|max:1000',
                    'additional_file' => 'nullable|string|max:255',
                ]);

                $application->update([
                    'cover_letter' => $request->cover_letter ?? $application->cover_letter,
                    'additional_file' => $request->additional_file ?? $application->additional_file,
                ]);

            } else {
                // Admin/Kepala BKK bisa ubah status dan catatan
                $request->validate([
                    'status' => 'required|in:pending,review,accepted,rejected',
                    'admin_notes' => 'nullable|string|max:1000',
                ]);

                $application->update([
                    'status' => $request->status,
                    'admin_notes' => $request->admin_notes ?? $application->admin_notes,
                ]);
            }

            return response()->json([
                'message' => 'Lamaran berhasil diperbarui',
                'data' => $application
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Hapus lamaran (Admin/Kepala BKK atau Student pemilik lamaran)
     */
    public function destroy($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $user = Auth::user();

            // Cek otorisasi: hanya pemilik atau admin/kepala bkk
            if ($user->role->name === 'student') {
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json([
                        'message' => 'Anda tidak berhak menghapus lamaran ini'
                    ], 403);
                }
            }

            $application->delete();

            return response()->json([
                'message' => 'Lamaran berhasil dihapus'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }
    }
}