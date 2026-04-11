<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::withCount('jobs');

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        if ($hasJobs = $request->query('has_jobs')) {
            if ($hasJobs === '1') {
                $query->has('jobs');
            } elseif ($hasJobs === '0') {
                $query->doesntHave('jobs');
            }
        }

        $companies = $query->latest()->get();
        return view('admin.companies.index', compact('companies', 'search', 'hasJobs'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
        ]);

        $company = Company::create(array_merge($validated, ['is_verified' => false]));

        return redirect()->route('admin.jobs.create', ['company_id' => $company->company_id])
            ->with('success', 'Perusahaan berhasil ditambahkan. Sekarang lanjut tambah lowongan untuk perusahaan ini.');
    }

    public function show(Company $company)
    {
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')->with('success', 'Perusahaan berhasil diperbarui!');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'Perusahaan berhasil dihapus!');
    }
}
