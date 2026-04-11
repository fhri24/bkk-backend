@extends('layouts.admin')

@section('title', 'Edit Lowongan - ' . $job->title)
@section('page_title', 'Edit Lowongan')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Edit Lowongan</h2>
            <p class="text-sm text-slate-500">Perbarui informasi lowongan pekerjaan dan perusahaan terkait.</p>
        </div>
        <a href="{{ route('admin.jobs.show', $job->job_id) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
        </a>
    </div>

    <form action="{{ route('admin.jobs.update', $job->job_id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6">
            <div>
                <label for="company_id" class="block text-sm font-medium text-slate-700">Perusahaan</label>
                <select name="company_id" id="company_id" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->company_id }}" {{ old('company_id', $job->company_id) == $company->company_id ? 'selected' : '' }}>
                            {{ $company->company_name ?? $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Judul Posisi</label>
                <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                <textarea name="description" id="description" rows="5" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>{{ old('description', $job->description) }}</textarea>
            </div>

            <div>
                <label for="requirements" class="block text-sm font-medium text-slate-700">Persyaratan</label>
                <textarea name="requirements" id="requirements" rows="3" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('requirements', $job->requirements) }}</textarea>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="job_type" class="block text-sm font-medium text-slate-700">Jenis Pekerjaan</label>
                    <select name="job_type" id="job_type" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach (['Full-time','Part-time','Contract','Internship'] as $type)
                            <option value="{{ $type }}" {{ old('job_type', $job->job_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="visibility" class="block text-sm font-medium text-slate-700">Visibility</label>
                    <select name="visibility" id="visibility" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                        @foreach (['public' => 'Public', 'alumni_only' => 'Alumni Only', 'private' => 'Private', 'internal' => 'Internal'] as $value => $label)
                            <option value="{{ $value }}" {{ old('visibility', $job->visibility) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="expired_at" class="block text-sm font-medium text-slate-700">Tanggal Kadaluarsa</label>
                    <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at', $job->expired_at?->format('Y-m-d')) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan Perubahan</button>
                <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
