<?php

namespace App\Http\Controllers\Admin; // Perhatikan ada tambahan \Admin

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Menampilkan semua loker di halaman Admin
    public function index(Request $request)
    {
        $query = Job::with('company');

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('job_type', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($visibility = $request->query('visibility')) {
            $query->where('visibility', $visibility);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $jobs = $query->latest()->get();
        return view('admin.jobs.index', compact('jobs', 'search', 'visibility', 'status'));
    }

    // Form Tambah Loker
    public function create(Request $request)
    {
        $companies = Company::all();
        $selectedCompanyId = $request->query('company_id');
        return view('admin.jobs.create', compact('companies', 'selectedCompanyId'));
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

    public function show(Job $job)
    {
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $companies = Company::all();
        return view('admin.jobs.edit', compact('job', 'companies'));
    }

    public function update(Request $request, Job $job)
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

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    // Hapus Loker (DELETE)
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus!');
    }
}