<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Menampilkan daftar semua lamaran yang masuk (Sisi Admin)
     */
    public function index()
    {
        // Mengambil semua lamaran beserta data siswa (student) dan lowongan (job)
        // Pastikan di Model JobApplication sudah ada function student() dan job()
        $applications = JobApplication::with(['student', 'job.company'])
            ->latest()
            ->get();
        
        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Update status lamaran (Pending, Review, Accepted, Rejected)
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'status' => 'required|in:pending,review,accepted,rejected',
            'admin_notes' => 'nullable|string|max:1000', 
        ]);

        try {
            // 2. Cari data lamarannya
            $application = JobApplication::findOrFail($id);

            // 3. Update status dan catatan dari admin
            $application->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);

            return redirect()->back()->with('success', 'Status lamaran siswa berhasil diperbarui menjadi: ' . ucfirst($request->status));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data lamaran jika diperlukan
     */
    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);
        $application->delete();

        return redirect()->back()->with('success', 'Data lamaran berhasil dihapus.');
    }
}