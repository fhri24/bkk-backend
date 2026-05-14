{{-- ============================================================
     FILE: resources/views/company/lowongan/create.blade.php
     ============================================================ --}}
@extends('layouts.company')
@section('title', 'Tambah Lowongan')
@section('page_title', 'Tambah Lowongan Baru')

@section('content')
<div class="max-w-3xl">
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex gap-3 text-sm text-blue-700">
        <i class="fas fa-info-circle mt-0.5 shrink-0"></i>
        <p>Lowongan yang Anda buat akan <strong>menunggu persetujuan Admin BKK</strong> sebelum ditampilkan ke publik. Proses ini biasanya memakan waktu 1x24 jam.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('company.lowongan.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Posisi <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Staff Kasir"
                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Poster / Banner <span class="text-slate-400">(Opsional)</span></label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png"
                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG (Maks 2MB)</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Pekerjaan <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" placeholder="Jelaskan gambaran umum pekerjaan..."
                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none" required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggung Jawab</label>
            <textarea name="responsibilities" rows="4" placeholder="Pisahkan tiap poin dengan Enter baru..."
                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ old('responsibilities') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Persyaratan</label>
            <textarea name="requirements" rows="3" placeholder="Pisahkan tiap poin dengan Enter baru..."
                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ old('requirements') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Benefit & Tunjangan</label>
            <textarea name="benefits" rows="3" placeholder="Contoh: BPJS, Tunjangan Makan..."
                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ old('benefits') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Garut, Jawa Barat"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Gaji</label>
                <input type="text" name="salary" value="{{ old('salary') }}" placeholder="Contoh: Rp 3.000.000"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Pekerjaan <span class="text-red-500">*</span></label>
                <select name="job_type" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" required>
                    <option value="">-- Pilih --</option>
                    @foreach(['Full-time','Part-time','Contract','Internship'] as $type)
                        <option value="{{ $type }}" {{ old('job_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Lamaran <span class="text-red-500">*</span></label>
                <input type="date" name="expired_at" value="{{ old('expired_at') }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition" required>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Kirim untuk Disetujui
            </button>
            <a href="{{ route('company.lowongan.index') }}" class="border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
