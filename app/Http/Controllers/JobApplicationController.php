<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    // ... fungsi lainnya ...

    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'status' => 'required|in:pending,review,accepted,rejected',
            'admin_notes' => 'nullable|string', // Notes hanya diisi jika perlu (terutama jika ditolak)
        ]);

        // 2. Cari data lamarannya
        $application = JobApplication::findOrFail($id);

        // 3. Update status dan notes
        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui ke: ' . $request->status);
    }
}
