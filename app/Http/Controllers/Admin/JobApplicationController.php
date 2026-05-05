<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index()
    {

        $applications = JobApplication::with(['student', 'job.company'])->latest()->paginate(10);

        return view('admin.job-applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = JobApplication::with(['student', 'job.company'])->findOrFail($id);
        return view('admin.job-applications.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,review,accepted,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $application = JobApplication::findOrFail($id);
        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => sprintf('Memperbarui status lamaran ID %s menjadi %s', $application->job_application_id, $application->status),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['job_application_id' => $application->job_application_id],
        ]);

        return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui');
    }
}
