<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // GET all companies
    public function index()
    {
        return Company::all();
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required',
            'industry' => 'required',
            'address' => 'required',
            'contact_person' => 'required',
            'phone' => 'required',
        ]);

        $data['is_verified'] = false;

        return Company::create($data);
    }

    // SHOW
    public function show($id)
    {
        return Company::findOrFail($id);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $company->update($request->all());

        return $company;
    }

    // DELETE
    public function destroy($id)
    {
        Company::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }

    // TOGGLE VERIFIED
    public function toggleVerify($id)
    {
        $company = Company::findOrFail($id);

        $company->is_verified = !$company->is_verified;
        $company->save();

        return response()->json([
            'message' => 'Status updated',
            'is_verified' => $company->is_verified
        ]);
    }

    // ASSIGN COMPANY TO JOB
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
            'message' => 'Company assigned to job successfully',
            'job' => $job
        ]);
    }
}