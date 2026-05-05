<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Menampilkan daftar lamaran
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Cek Role (Student vs Admin)
        if ($user->role && (in_array($user->role->name, ['student', 'siswa']))) {
            $student = $user->userable;
            $applications = JobApplication::where('student_id', $student->student_id)
                ->with(['student', 'job.major', 'job.company'])
                ->latest('application_date')
                ->get();
        } else {
            $applications = JobApplication::with(['student', 'job.major', 'job.company'])
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
            $application = JobApplication::with(['student', 'job.major', 'job.company'])
                ->findOrFail($id);

            $user = Auth::user();
            if ($user->role && (in_array($user->role->name, ['student', 'siswa']))) {
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json(['message' => 'Akses ditolak'], 403);
                }
            }

            return response()->json([
                'message' => 'Detail lamaran berhasil diambil',
                'data' => $application
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Lamaran tidak ditemukan'], 404);
        }
    }

 public function store(Request $request, $id = null)
{
    $jobId = $request->job_id ?? $id;

    $request->validate([
        'notes'  => 'nullable|string',
        'cv'     => 'required|mimes:pdf|max:2048',
    ]);

    try {
        $user = Auth::user();

        /**
         * PERBAIKAN: Kita ambil ID dari userable-nya.
         * Apapun role-nya (Student/Alumni), yang penting dia punya ID di tabel profilnya.
         */
        $profile = $user->userable;

        if (!$profile) {
            // Kalau masih NULL, kita coba cari ID manual atau kasih error yang jelas
            return redirect()->back()->with('error', 'Profil lu gak ketemu, Mang. Pastiin data profil Student/Alumni lu udah diisi.');
        }

        // Ambil student_id (biasanya tabel alumni & student pake kolom yang sama atau mirip)
        // Kalau di tabel alumni nama kolomnya beda, sesuaikan di sini.
        $studentId = $profile->student_id ?? $profile->id;

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = time() . '_' . str_replace(' ', '_', $user->name) . '_cv.pdf';
            $cvPath = $file->storeAs('applications/cvs', $filename, 'public');
        }

        // Simpan manual biar gak kena mass assignment
        $app = new \App\Models\JobApplication();
        $app->job_id = $jobId;
        $app->student_id = $studentId;
        $app->cover_letter = $request->notes ?? '-';
        $app->additional_file = $cvPath;
        $app->application_date = now();
        $app->status = 'pending';

        if($app->save()) {
            // HAPUS SEMUA DD SEBELUMNYA, KITA PAKE REDIRECT
            return redirect()->route('student.applications')->with('success', 'Lamaran berhasil dikirim!');
        }

        return redirect()->back()->with('error', 'Gagal simpan data.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Masalah: ' . $e->getMessage());
    }
}

    public function update(Request $request, $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $user = Auth::user();

            // Admin Update Status
            if ($user->role && !in_array($user->role->name, ['student', 'siswa'])) {
                $request->validate([
                    'status' => 'required|in:pending,review,accepted,rejected',
                    'admin_notes' => 'nullable|string',
                ]);

                $application->update([
                    'status' => $request->status,
                    'admin_notes' => $request->admin_notes
                ]);
            }
            // Student Update Lamaran
            else {
                $student = $user->userable;
                if ($application->student_id !== $student->student_id) {
                    return response()->json(['message' => 'Akses ditolak'], 403);
                }

                if ($request->hasFile('cv')) {
                    if ($application->additional_file) {
                        Storage::disk('public')->delete($application->additional_file);
                    }
                    $file = $request->file('cv');
                    $filename = time() . '_updated_cv.pdf';
                    $application->additional_file = $file->storeAs('applications/cvs', $filename, 'public');
                }

                if ($request->has('notes')) {
                    $application->cover_letter = $request->notes;
                }

                $application->save();
            }

            return response()->json([
                'message' => 'Berhasil diperbarui',
                'data' => $application
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            if ($application->additional_file) {
                Storage::disk('public')->delete($application->additional_file);
            }
            $application->delete();
            return response()->json(['message' => 'Lamaran dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus'], 500);
        }
    }
}
