<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\Major;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company', 'major']);

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('job_type', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($visibility = $request->query('visibility')) {
            $query->where('visibility', $visibility);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($approval = $request->query('approval')) {
            $query->where('approval_status', $approval);
        }

        $jobs = $query->latest()->get();

        return view('admin.jobs.index', compact('jobs'))->with([
            'search'     => $request->query('search', ''),
            'visibility' => $request->query('visibility', ''),
            'status'     => $request->query('status', ''),
            'approval'   => $request->query('approval', ''),
        ]);
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        $majors = Major::all();
        $selectedCompanyId = $request->query('company_id');
        return view('admin.jobs.create', compact('companies', 'majors', 'selectedCompanyId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'       => 'required|exists:companies,company_id',
            'major_id'         => 'nullable|exists:majors,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits'         => 'nullable|string',
            'location'         => 'nullable|string',
            'salary'           => 'nullable|string',
            'skill_required'   => 'nullable|string',
            'job_type'         => 'required|string',
            'visibility'       => 'required|in:public,alumni_only,private,internal',
            'expired_at'       => 'required|date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['logo'] = $request->file('image')->store('logos', 'public');
        }

        unset($validated['image']);

        // Gunakan new Job + save() agar casting boolean berjalan dengan benar di PostgreSQL
        $job = new Job($validated);
        $job->admin_id        = auth()->id();
        $job->status          = 'active';
        $job->approval_status = 'approved';
        $job->is_active       = true;
        $job->posted_at       = now();
        $job->save();

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil dipublikasikan!');
    }

    public function edit(Job $job)
    {
        $companies = Company::all();
        $majors = Major::all();
        return view('admin.jobs.edit', compact('job', 'companies', 'majors'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'company_id'       => 'required|exists:companies,company_id',
            'major_id'         => 'nullable|exists:majors,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits'         => 'nullable|string',
            'location'         => 'nullable|string',
            'salary'           => 'nullable|string',
            'skill_required'   => 'nullable|string',
            'job_type'         => 'required|string',
            'visibility'       => 'required|in:public,alumni_only,private,internal',
            'expired_at'       => 'required|date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['logo'] = $request->file('image')->store('logos', 'public');
        }

        unset($validated['image']);

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function approve(Job $job)
    {
        $job->update([
            'approval_status' => 'approved',
            'status'          => 'active',
            'is_active'       => true,
            'approval_notes'  => null,
        ]);

        return redirect()->back()->with('success', "Lowongan \"{$job->title}\" berhasil disetujui dan sekarang tampil ke publik.");
    }

    public function reject(Request $request, Job $job)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:500',
        ]);

        $job->update([
            'approval_status' => 'rejected',
            'status'          => 'inactive',
            'is_active'       => false,
            'approval_notes'  => $request->approval_notes,
        ]);

        return redirect()->back()->with('success', "Lowongan \"{$job->title}\" ditolak. Perusahaan akan melihat alasan penolakan.");
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus!');
    }
}