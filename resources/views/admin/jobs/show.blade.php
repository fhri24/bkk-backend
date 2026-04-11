@extends('layouts.admin')

@section('title', 'Detail Lowongan - ' . $job->title)
@section('page_title', 'Detail Lowongan')

@section('content')
<div class="p-6">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">{{ $job->title }}</h2>
            <p class="text-sm text-slate-500">Detail lowongan pekerjaan yang dipublikasikan oleh {{ $job->company->company_name ?? 'Perusahaan' }}.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.jobs.edit', $job->job_id) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                <i class="fas fa-edit mr-2"></i> Edit Lowongan
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Perusahaan</h3>
                <p class="text-lg font-bold text-slate-900">{{ $job->company->company_name ?? 'Tidak Diketahui' }}</p>
                <p class="text-sm text-slate-500">{{ $job->company->industry ?? 'Industri belum diisi' }}</p>
            </div>
            <div class="grid gap-3">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Status</p>
                    <p class="font-semibold">{{ ucfirst($job->status) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Visibility</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $job->visibility)) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Jenis Pekerjaan</p>
                    <p class="font-semibold">{{ $job->job_type }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Deskripsi</h3>
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $job->description }}</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 mb-3">Persyaratan</h3>
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $job->requirements ?? 'Tidak ada persyaratan khusus.' }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900 mb-3">Detail</h3>
                    <dl class="space-y-3 text-sm text-slate-700">
                        <div>
                            <dt class="font-semibold">Lokasi</dt>
                            <dd>{{ $job->location ?? 'Belum diisi' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Kadaluarsa</dt>
                            <dd>{{ $job->expired_at ? $job->expired_at->format('d M Y') : 'Belum diisi' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Dibuat oleh</dt>
                            <dd>{{ $job->admin->name ?? 'Admin' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Diposting pada</dt>
                            <dd>{{ $job->posted_at ? $job->posted_at->format('d M Y H:i') : '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
