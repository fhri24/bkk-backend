@extends('layouts.admin')

@section('title', 'Tambah Lowongan Kerja Baru')
@section('page_title', 'Tambah Lowongan')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Tambah Lowongan Kerja Baru</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.jobs.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="mb-4">
            <label for="company_id" class="block text-sm font-medium text-gray-700">Perusahaan</label>
            <select name="company_id" id="company_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
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

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Judul Posisi</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Developer PHP" value="{{ old('title') }}" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Jelaskan tugas dan tanggung jawab..." required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="requirements" class="block text-sm font-medium text-gray-700">Persyaratan</label>
            <textarea name="requirements" id="requirements" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Daftar persyaratan...">{{ old('requirements') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
            <input type="text" name="location" id="location" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Jakarta, Bandung" value="{{ old('location') }}">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="job_type" class="block text-sm font-medium text-gray-700">Jenis Pekerjaan</label>
                <select name="job_type" id="job_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Full-time" {{ old('job_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('job_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('job_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Internship" {{ old('job_type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>

            <div>
                <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                <select name="visibility" id="visibility" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="alumni_only" {{ old('visibility') == 'alumni_only' ? 'selected' : '' }}>Alumni Only</option>
                    <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                    <option value="internal" {{ old('visibility') == 'internal' ? 'selected' : '' }}>Internal</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label for="expired_at" class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa</label>
            <input type="date" name="expired_at" id="expired_at" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('expired_at') }}" required>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Simpan Lowongan
            </button>
            <a href="{{ route('admin.jobs.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
