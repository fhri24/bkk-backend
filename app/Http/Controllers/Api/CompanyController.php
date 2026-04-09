<?php

namespace App\Http\Controllers\Api; // Cek huruf besar kecilnya!

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
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

    public function show($id)
    {
        $company = Company::where('is_verified', true)
            ->select('company_id', 'company_name', 'industry', 'address', 'contact')
            ->where('company_id', $id)
            ->first();

        if (!$company) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan.'], 404);
        }

        return response()->json(['data' => $company]);
    }
}