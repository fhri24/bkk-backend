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
                    })
                    ->orWhereHas('major', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $jobs = $query->latest()->get();
        return view('admin.jobs.index', compact('jobs'));
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

        $validated['admin_id']  = auth()->id();
        $validated['status']    = 'active';
        $validated['is_active'] = true;
        $validated['posted_at'] = now();

        unset($validated['image']);

        Job::create($validated);

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

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus!');
    }
}
