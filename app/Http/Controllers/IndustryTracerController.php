<?php

namespace App\Http\Controllers;

use App\Models\IndustryTracer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndustryTracerController extends Controller
{
    public function index()
    {
        $existing = IndustryTracer::where('user_id', Auth::id())->first();
        return view('public.tracer-industri', compact('existing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan'      => 'required|string|max:255',
            'jenis_perusahaan'     => 'required|string|max:100',
            'alamat_perusahaan'    => 'required|string',
            'bisnis_utama'         => 'required|string|max:255',
            'nama_responden'       => 'required|string|max:255',
            'jabatan_responden'    => 'required|string|max:255',
            'email_responden'      => 'required|email|max:255',
            'nilai_integritas'     => 'required|integer|min:1|max:5',
            'nilai_keahlian'       => 'required|integer|min:1|max:5',
            'nilai_bahasa_inggris' => 'required|integer|min:1|max:5',
            'nilai_teknologi'      => 'required|integer|min:1|max:5',
            'nilai_komunikasi'     => 'required|integer|min:1|max:5',
            'nilai_kerjasama'      => 'required|integer|min:1|max:5',
            'nilai_analitis'       => 'required|integer|min:1|max:5',
            'nilai_kepemimpinan'   => 'required|integer|min:1|max:5',
            'nilai_tekanan'        => 'required|integer|min:1|max:5',
            'saran'                => 'nullable|string',
        ], [
            'nama_perusahaan.required'      => 'Nama perusahaan wajib diisi.',
            'jenis_perusahaan.required'     => 'Jenis perusahaan wajib dipilih.',
            'alamat_perusahaan.required'    => 'Alamat perusahaan wajib diisi.',
            'bisnis_utama.required'         => 'Bisnis utama wajib diisi.',
            'nama_responden.required'       => 'Nama responden wajib diisi.',
            'jabatan_responden.required'    => 'Jabatan responden wajib diisi.',
            'email_responden.required'      => 'Email responden wajib diisi.',
            'nilai_integritas.required'     => 'Penilaian integritas wajib diisi.',
            'nilai_keahlian.required'       => 'Penilaian keahlian wajib diisi.',
            'nilai_bahasa_inggris.required' => 'Penilaian bahasa Inggris wajib diisi.',
            'nilai_teknologi.required'      => 'Penilaian teknologi wajib diisi.',
            'nilai_komunikasi.required'     => 'Penilaian komunikasi wajib diisi.',
            'nilai_kerjasama.required'      => 'Penilaian kerja sama wajib diisi.',
            'nilai_analitis.required'       => 'Penilaian analitis wajib diisi.',
            'nilai_kepemimpinan.required'   => 'Penilaian kepemimpinan wajib diisi.',
            'nilai_tekanan.required'        => 'Penilaian kerja di bawah tekanan wajib diisi.',
        ]);

        IndustryTracer::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge($request->except('_token'), ['user_id' => Auth::id()])
        );

        return redirect()->back()
            ->with('success', 'Terima kasih! Penilaian industri berhasil disimpan.');
    }
}