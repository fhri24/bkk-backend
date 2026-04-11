@extends('layouts.admin')

@section('title', 'Detail Perusahaan - ' . $company->company_name)
@section('page_title', 'Detail Perusahaan')

@section('content')
<div class="p-6 max-w-5xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">{{ $company->company_name }}</h2>
            <p class="text-sm text-slate-500">Informasi lengkap perusahaan dan daftar lowongan yang terkait.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.companies.edit', $company->company_id) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit Perusahaan
            </a>
            <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Informasi Perusahaan</h3>
            <div class="space-y-4 text-sm text-slate-700">
                <div>
                    <p class="font-semibold">Nama Perusahaan</p>
                    <p>{{ $company->company_name }}</p>
                </div>
                <div>
                    <p class="font-semibold">Industri</p>
                    <p>{{ $company->industry ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Contact Person</p>
                    <p>{{ $company->contact_person ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Telepon</p>
                    <p>{{ $company->phone ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Website</p>
                    <p>{{ $company->website ? $company->website : 'Belum diisi' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Alamat</p>
                    <p>{{ $company->address ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Total Lowongan</p>
                    <p>{{ $company->jobs->count() }} lowongan</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Lowongan Terkait</h3>
                @if ($company->jobs->isEmpty())
                    <p class="text-slate-500">Belum ada lowongan untuk perusahaan ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($company->jobs as $job)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h4 class="font-semibold text-slate-900">{{ $job->title }}</h4>
                                        <p class="text-sm text-slate-600">{{ ucfirst($job->visibility) }} • {{ $job->job_type }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.jobs.show', $job->job_id) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-white text-sm hover:bg-blue-700 transition">Detail</a>
                                        <a href="{{ route('admin.jobs.edit', $job->job_id) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-sm text-slate-700 hover:bg-slate-200 transition">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
