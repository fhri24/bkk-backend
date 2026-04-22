@extends('layouts.app')

@section('title', 'Tambah Lowongan Kerja Baru')
@section('page_title', 'Tambah Lowongan')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6 text-slate-900">Tambah Lowongan Kerja Baru</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TAMBAHAN: enctype="multipart/form-data" WAJIB ADA --}}
    <form action="{{ route('admin.jobs.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        @csrf

        <div class="mb-5">
            <label for="company_id" class="block text-sm font-bold text-slate-700 mb-2">Perusahaan</label>
            <select name="company_id" id="company_id" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">-- Pilih Perusahaan --</option>
                @forelse ($companies as $company)
                    <option value="{{ $company->company_id }}"
                        {{ old('company_id') == $company->company_id || $selectedCompanyId == $company->company_id ? 'selected' : '' }}>
                        {{ $company->company_name ?? $company->name }}
                    </option>
                @empty
                    <option value="">Tidak ada perusahaan, silakan tambahkan perusahaan terlebih dahulu</option>
                @endforelse
            </select>
            @if ($companies->isEmpty())
                <p class="text-sm text-red-600 mt-2">Belum ada perusahaan terdaftar. <a href="{{ route('admin.companies.create') }}" class="text-blue-600 underline">Tambah perusahaan baru</a>.</p>
            @endif
        </div>

        <div class="mb-5">
            <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Judul Posisi</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Developer PHP" value="{{ old('title') }}" required>
        </div>

        {{-- TAMBAHAN: INPUT FILE GAMBAR LOWONGAN --}}
        <div class="mb-5">
            <label for="image" class="block text-sm font-bold text-slate-700 mb-2">Poster / Banner Lowongan (Opsional)</label>
            <input type="file" name="image" id="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl p-2">
            <p class="text-[10px] text-slate-400 mt-2 italic">*Format: JPG, PNG (Max 2MB)</p>
        </div>

        <div class="mb-5">
            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Pekerjaan</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan tugas dan tanggung jawab..." required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-5">
            <label for="requirements" class="block text-sm font-bold text-slate-700 mb-2">Persyaratan</label>
            <textarea name="requirements" id="requirements" rows="3" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Daftar persyaratan...">{{ old('requirements') }}</textarea>
        </div>

        <div class="mb-5">
            <label for="location" class="block text-sm font-bold text-slate-700 mb-2">Lokasi</label>
            <input type="text" name="location" id="location" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Jakarta, Bandung" value="{{ old('location') }}">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label for="job_type" class="block text-sm font-bold text-slate-700 mb-2">Jenis Pekerjaan</label>
                <select name="job_type" id="job_type" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Full-time" {{ old('job_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('job_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('job_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Internship" {{ old('job_type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>

            <div>
                <label for="visibility" class="block text-sm font-bold text-slate-700 mb-2">Visibility</label>
                <select name="visibility" id="visibility" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="alumni_only" {{ old('visibility') == 'alumni_only' ? 'selected' : '' }}>Alumni Only</option>
                    <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    <option value="internal" {{ old('visibility') == 'internal' ? 'selected' : '' }}>Internal</option>
                </select>
            </div>
        </div>

        <div class="mb-8">
            <label for="expired_at" class="block text-sm font-bold text-slate-700 mb-2">Tanggal Kadaluarsa</label>
            <input type="date" name="expired_at" id="expired_at" class="mt-1 block w-full border-slate-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('expired_at') }}" required>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition">
                Simpan Lowongan
            </button>
            <a href="{{ route('admin.jobs.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
