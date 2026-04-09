<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // 1. GET ALL COMPANIES (Hanya yang terverifikasi)
    public function index()
    {
        $companies = Company::where('is_verified', true)
            ->select('company_id', 'company_name', 'industry', 'address', 'contact')
            ->get();

        return response()->json([
            'message' => 'Daftar perusahaan terverifikasi berhasil dimuat.',
            'data' => $companies
        ]);
    }

    // 2. CREATE (Tambah Perusahaan Baru)
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string',
            'industry' => 'required|string',
            'address' => 'required|string',
            'contact' => 'required|string', // Sesuaikan nama kolom dengan database kamu
        ]);

        $data['is_verified'] = false; // Default belum terverifikasi

        $company = Company::create($data);

        return response()->json([
            'message' => 'Perusahaan berhasil didaftarkan.',
            'data' => $company
        ], 201);
    }

    // 3. SHOW (Detail Perusahaan)
    public function show($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan.'], 404);
        }

        return response()->json(['data' => $company]);
    }

    // 4. UPDATE
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json([
            'message' => 'Data perusahaan berhasil diperbarui.',
            'data' => $company
        ]);
    }

    // 5. DELETE
    public function destroy($id)
    {
        Company::destroy($id);
        return response()->json(['message' => 'Perusahaan berhasil dihapus.']);
    }

    // 6. TOGGLE VERIFIED (Admin memverifikasi perusahaan)
    public function toggleVerify($id)
    {
        $company = Company::findOrFail($id);
        $company->is_verified = !$company->is_verified;
        $company->save();

        return response()->json([
            'message' => 'Status verifikasi diperbarui.',
            'is_verified' => $company->is_verified
        ]);
    }

    // 7. ASSIGN COMPANY TO JOB
    public function assignToJob(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'job_id' => 'required|exists:job_listings,job_id'
        ]);

        $job = Job::findOrFail($request->job_id);
        $job->company_id = $company->company_id;
        $job->save();

        return response()->json([
            'message' => 'Perusahaan berhasil dikaitkan dengan lowongan.',
            'job' => $job
        ]);
    }
}