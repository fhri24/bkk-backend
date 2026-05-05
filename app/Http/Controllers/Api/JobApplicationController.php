<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Menampilkan daftar lamaran
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filter berdasarkan role
        if ($user->role->name === 'student') {
            // Siswa lihat lamaran sendiri + data lowongan & jurusannya
            $student = $user->userable;
            $applications = JobApplication::where('student_id', $student->student_id)
                ->with(['student', 'job.major', 'job.company']) // Ditambah job.major
                ->latest('application_date')
                ->get();
        } else {
            // Admin lihat semua + data lowongan & jurusannya
            $applications = JobApplication::with(['student.major', 'job.major', 'job.company'])
                ->latest('application_date')
                ->get();
        }

        return response()->json([
            'message' => 'Daftar lamaran berhasil diambil',
            'data' => $applications
        ], 200);
    }

    /**
     * Menampilkan detail satu lamaran
     */
    public function show($id)
    {
        try {
            // Load relasi major di dalam job
            $application = JobApplication::with(['student.major', 'job.major', 'job.company'])
                ->findOrFail($id);

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
     * Siswa melamar ke sebuah lowongan
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:job_listings,job_id',
            'cover_letter' => 'nullable|string|max:1000',
            'additional_file' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $student = $user->userable;

        // Cek apakah user benar-benar student
        if (!$student || !($student instanceof \App\Models\Student)) {
            return response()->json(['message' => 'Hanya siswa yang bisa melamar.'], 403);
        }

        // Cek duplikasi lamaran
        $alreadyApplied = JobApplication::where('student_id', $student->student_id)
            ->where('job_id', $request->job_id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json([
                'message' => 'Kamu sudah melamar di lowongan ini sebelumnya.'
            ], 400);
        }

        // Simpan lamaran
        $application = JobApplication::create([
            'job_id' => $request->job_id,
            'student_id' => $student->student_id,
            'cover_letter' => $request->cover_letter,
            'additional_file' => $request->additional_file,
            'application_date' => now(),
            'status' => 'pending',
        ]);

        // Load data lengkap untuk respon
        $application->load(['job.major', 'job.company']);

        return response()->json([
            'message' => 'Lamaran berhasil dikirim!',
            'data' => $application
        ], 201);
    }

    /**
     * Update lamaran (Status oleh Admin, File oleh Student)
     */
    public function update(Request $request, $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $user = Auth::user();

            if ($user->role->name === 'student') {
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json(['message' => 'Akses ditolak'], 403);
                }

                $request->validate([
                    'cover_letter' => 'nullable|string|max:1000',
                    'additional_file' => 'nullable|string|max:255',
                ]);

                $application->update($request->only(['cover_letter', 'additional_file']));

            } else {
                // Admin/Kepala BKK update status
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
                'data' => $application->load(['job.major', 'job.company'])
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Lamaran tidak ditemukan'], 404);
        }
    }

    /**
     * Hapus lamaran
     */
    public function destroy($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $user = Auth::user();

            if ($user->role->name === 'student') {
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json(['message' => 'Akses ditolak'], 403);
                }
            }

            $application->delete();
            return response()->json(['message' => 'Lamaran berhasil dihapus'], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Lamaran tidak ditemukan'], 404);
        }
    }
}
