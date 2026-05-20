<?php

namespace App\Http\Controllers;

use App\Models\TracerStudy;
use App\Models\Major;
use App\Models\GraduationYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TracerStudyController extends Controller
{
    // Form tracer study untuk publik/alumni/siswa
    public function index()
    {
        $user    = Auth::user();
        $majors  = Major::orderBy('name')->get();
        $years   = GraduationYear::orderByDesc('year')->get();

        // Cek apakah sudah pernah isi
        $existing = TracerStudy::where('user_id', $user->id)->first();

        return view('public.tracer', compact('majors', 'years', 'existing'));
    }

    // Simpan / update tracer study
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nik'            => 'nullable|string|max:20',
            'no_hp'          => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'tahun_lulus'    => 'required|digits:4',
            'jurusan'        => 'required|string|max:100',
            'status_saat_ini'=> 'required|in:Bekerja,Kuliah,Wirausaha,Belum Bekerja',
        ], [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi.',
            'no_hp.required'           => 'No. HP wajib diisi.',
            'email.required'           => 'Email wajib diisi.',
            'tahun_lulus.required'     => 'Tahun lulus wajib diisi.',
            'jurusan.required'         => 'Jurusan wajib dipilih.',
            'status_saat_ini.required' => 'Status kegiatan wajib dipilih.',
        ]);

        $user = Auth::user();

        // Ambil student_id kalau ada
        $studentId = null;
        if ($user->role->name === 'siswa' && $user->userable) {
            $studentId = $user->userable->student_id ?? null;
        }

        $data = [
            'user_id'      => $user->id,
            'student_id'   => $studentId,
            'nama_lengkap' => $request->nama_lengkap,
            'nik'          => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir'=> $request->tanggal_lahir,
            'alamat_lengkap'=> $request->alamat_lengkap,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
            'tahun_lulus'  => $request->tahun_lulus,
            'jurusan'      => $request->jurusan,
            'status_saat_ini' => $request->status_saat_ini,
        ];

        // Tambah field sesuai status
        match($request->status_saat_ini) {
            'Bekerja' => $data = array_merge($data, [
                'lokasi_kerja'       => $request->lokasi_kerja,
                'nama_instansi'      => $request->nama_instansi,
                'alamat_perusahaan'  => $request->alamat_perusahaan,
                'posisi_jabatan'     => $request->posisi_jabatan,
                'tmt_bekerja'        => $request->tmt_bekerja,
                'range_gaji'         => $request->range_gaji,
                'pendapatan_bulanan' => $request->pendapatan_bulanan,
                'keselarasan_jurusan'=> $request->keselarasan_jurusan,
            ]),
            'Kuliah' => $data = array_merge($data, [
                'status_pt'    => $request->status_pt,
                'nama_pt'      => $request->nama_pt,
                'jurusan_pt'   => $request->jurusan_pt,
                'jenjang_kuliah'=> $request->jenjang_kuliah,
                'tmt_kuliah'   => $request->tmt_kuliah,
            ]),
            'Wirausaha' => $data = array_merge($data, [
                'nama_usaha'    => $request->nama_usaha,
                'status_usaha'  => $request->status_usaha,
                'tmt_wirausaha' => $request->tmt_wirausaha,
                'omzet_per_bulan'=> $request->omzet_per_bulan,
            ]),
            'Belum Bekerja' => $data = array_merge($data, [
                'detail_kegiatan'        => $request->detail_kegiatan,
                'detail_kegiatan_lainnya'=> $request->detail_kegiatan_lainnya,
            ]),
            default => null,
        };

        // Update kalau sudah ada, insert kalau belum
        TracerStudy::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->back()->with('success', 'Terima kasih! Data tracer study berhasil disimpan.');
    }
}