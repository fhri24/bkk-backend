<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\User;
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

        // Query dasar dengan relasi
        $query = JobApplication::with(['student.user', 'job.major', 'job.company']);

        // Jika user adalah student/siswa/publik, filter berdasarkan ID profilnya
        if ($user->role && (in_array($user->role->name, ['student', 'siswa', 'publik', 'alumni']))) {
            $profile = $user->userable;

            // Logika ambil ID: prioritaskan userable_id dari tabel users
            $myId = $user->userable_id ?? ($profile ? ($profile->student_id ?? $profile->id) : null);

            if ($myId) {
                $query->where('student_id', $myId);
            } else {
                $query->where('student_id', 0);
            }
        }

        $applications = $query->latest('application_date')->get()->map(function ($app) {
            // ANTI-TUKAR: Cari nama user asli berdasarkan student_id
            $actualUser = User::where('userable_id', $app->student_id)->first();
            $app->applicant_name = $actualUser ? $actualUser->name : ($app->student->full_name ?? 'Pelamar');
            return $app;
        });

        return response()->json([
            'status' => 'success',
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
            $application = JobApplication::with(['student.user', 'job.major', 'job.company'])
                ->findOrFail($id);

            $user = Auth::user();
            $myId = $user->userable_id;

            if ($user->role && (in_array($user->role->name, ['student', 'siswa', 'publik', 'alumni']))) {
                if ($application->student_id != $myId) {
                    return response()->json(['status' => 'error', 'message' => 'Akses ditolak'], 403);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Detail lamaran berhasil diambil',
                'data' => $application
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Lamaran tidak ditemukan'], 404);
        }
    }

    /**
     * Simpan lamaran baru
     */
    public function store(Request $request, $id)
{
    // Validasi
    $request->validate([
        'cv' => 'required|file|mimes:pdf|max:2048',
    ], [
        'cv.required' => 'CV wajib diupload Mang!',
        'cv.mimes' => 'Harus format PDF ya!',
    ]);

    try {
        $user = Auth::user();

        $application = new JobApplication();
        $application->job_id = $id;
        $application->student_id = $user->userable_id; // Pake ID Wahyu/Isal
        $application->status = 'pending';
        $application->application_date = now();
        $application->cover_letter = $request->notes;

        if ($request->hasFile('cv')) {
            $application->additional_file = $request->file('cv')->store('applications/cvs', 'public');
        }

        $application->save();

        return response()->json([
            'status' => 'success',
            'message' => 'MANTAP! Lamaran berhasil dikirim.'
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Update lamaran
     */
    public function update(Request $request, $id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $user = Auth::user();

            if ($user->role && !in_array($user->role->name, ['student', 'siswa', 'publik', 'alumni'])) {
                $request->validate([
                    'status' => 'required|in:pending,review,accepted,rejected',
                    'admin_notes' => 'nullable|string',
                ]);

                $application->update([
                    'status' => $request->status,
                    'admin_notes' => $request->admin_notes
                ]);
            } else {
                $myId = $user->userable_id;
                if ($application->student_id != $myId) {
                    return response()->json(['status' => 'error', 'message' => 'Akses ditolak'], 403);
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

            return response()->json(['status' => 'success', 'message' => 'Berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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
            return response()->json(['status' => 'success', 'message' => 'Lamaran dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus'], 500);
        }
    }
}
