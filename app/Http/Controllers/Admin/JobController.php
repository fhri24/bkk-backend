<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\Major;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Menampilkan daftar lowongan di dashboard admin
     */
    public function index(Request $request)
    {
        // Load relasi company dan major agar nama perusahaan & jurusan muncul di tabel
        $query = Job::with(['company', 'major']);

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('job_type', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('major', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $jobs = $query->latest()->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Form tambah lowongan
     */
    public function create(Request $request)
    {
        $companies = Company::all(); // Mengambil semua data perusahaan
        $majors = Major::all();     // Mengambil semua data jurusan
        $selectedCompanyId = $request->query('company_id');

        return view('admin.jobs.create', compact('companies', 'majors', 'selectedCompanyId'));
    }

    /**
     * Simpan lowongan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Sesuaikan 'company_id' di bawah jika primary key di tabel companies lu adalah 'id'
            'company_id'   => 'required|exists:companies,company_id',
            'major_id'     => 'nullable|exists:majors,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'location'     => 'nullable|string',
            'job_type'     => 'required|string',
            'visibility'   => 'required|in:public,alumni_only,private,internal',
            'expired_at'   => 'required|date',
        ]);

        // Tambahkan data otomatis
        $validated['admin_id']  = auth()->id();
        $validated['status']    = 'active';
        $validated['is_active'] = true;
        $validated['posted_at'] = now();

        // Eksekusi Simpan
        Job::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil dipublikasikan!');
    }

    /**
     * Form edit lowongan
     */
    public function edit(Job $job)
    {
        $companies = Company::all();
        $majors = Major::all();
        return view('admin.jobs.edit', compact('job', 'companies', 'majors'));
    }

    /**
     * Update data lowongan
     */
    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'company_id'   => 'required|exists:companies,company_id',
            'major_id'     => 'nullable|exists:majors,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'requirements' => 'nullable|string',
            'location'     => 'nullable|string',
            'job_type'     => 'required|string',
            'visibility'   => 'required|in:public,alumni_only,private,internal',
            'expired_at'   => 'required|date',
        ]);

        // Pastikan status tetap aktif saat update (opsional)
        $validated['is_active'] = $request->has('is_active') ? true : $job->is_active;

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    /**
     * Hapus lowongan
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus!');
    }
}
