@extends('layouts.app')

@section('title', 'Tracer Study - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-extrabold text-[#001f3f] mb-8">Tracer Study Alumni</h1>

        <div class="bg-white rounded-2xl shadow-lg p-12">
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <div class="text-center p-6 bg-blue-50 rounded-2xl">
                    <div class="text-4xl font-extrabold text-blue-600 mb-2">85%</div>
                    <p class="text-slate-700 font-semibold">Terserap di Dunia Kerja</p>
                </div>
                <div class="text-center p-6 bg-green-50 rounded-2xl">
                    <div class="text-4xl font-extrabold text-green-600 mb-2">1200+</div>
                    <p class="text-slate-700 font-semibold">Alumni Terdata</p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-[#001f3f] mb-6">Isi Tracer Study Anda</h2>

            <form method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Saat Ini</label>
                    <select class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="working">Bekerja</option>
                        <option value="studying">Melanjutkan Studi</option>
                        <option value="both">Bekerja & Studi</option>
                        <option value="unemployed">Mencari Kerja</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Perusahaan / Institusi</label>
                    <input type="text" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold" placeholder="Masukkan nama">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Posisi / Program</label>
                    <input type="text" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold" placeholder="Masukkan posisi">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gaji / Kompetensi</label>
                    <input type="text" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold" placeholder="Optional">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition">
                    Simpan Tracer Study
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
