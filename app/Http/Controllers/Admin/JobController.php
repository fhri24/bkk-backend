<?php

namespace App\Http\Controllers\Admin; // Perhatikan ada tambahan \Admin

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Menampilkan semua loker di halaman Admin
    public function index()
    {
        $jobs = Job::latest()->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    // Form Tambah Loker
    public function create()
    {
        return view('admin.jobs.create');
    }

    // Simpan Loker Baru (POST)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,company_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string',
            'job_type' => 'required|string',
            'visibility' => 'required|in:public,alumni_only,private,internal',
            'expired_at' => 'required|date',
        ]);

        // Tambahkan admin_id dari user yang login
        $validated['admin_id'] = auth()->id();
        $validated['status'] = 'active';
        $validated['is_active'] = true;
        $validated['posted_at'] = now();

        Job::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil dipublikasikan!');
    }

    // Hapus Loker (DELETE)
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus!');
    }
}