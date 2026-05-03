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

    {{-- Poster / Banner --}}
    @if ($job->image)
        <div class="mb-6">
            <img src="{{ asset('storage/' . $job->image) }}" alt="Poster {{ $job->title }}" class="w-full max-h-64 object-cover rounded-3xl border border-slate-200 shadow-sm">
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Sidebar Kiri --}}
        <div class="space-y-4">
            {{-- Info Perusahaan --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Perusahaan</h3>
                <p class="text-lg font-bold text-slate-900">{{ $job->company->company_name ?? 'Tidak Diketahui' }}</p>
                <p class="text-sm text-slate-500">{{ $job->company->industry ?? 'Industri belum diisi' }}</p>
            </div>

            {{-- Informasi Singkat --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Informasi Singkat</h3>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Status</p>
                    <p class="font-semibold {{ $job->status === 'active' ? 'text-green-600' : 'text-red-500' }}">{{ ucfirst($job->status) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Visibility</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $job->visibility)) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Jenis Pekerjaan</p>
                    <p class="font-semibold">{{ $job->job_type ?? '-' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Gaji</p>
                    <p class="font-semibold">{{ $job->salary ?? 'Kompetitif' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Lokasi</p>
                    <p class="font-semibold">{{ $job->location ?? 'Belum diisi' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Pengalaman</p>
                    <p class="font-semibold">{{ $job->experience ?? '-' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Batas Akhir</p>
                    <p class="font-semibold">{{ $job->expired_at ? $job->expired_at->format('d M Y') : 'Belum diisi' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Dibuat Oleh</p>
                    <p class="font-semibold">{{ $job->admin->name ?? 'Admin' }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-400">Diposting Pada</p>
                    <p class="font-semibold">{{ $job->posted_at ? $job->posted_at->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Deskripsi --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Deskripsi Pekerjaan</h3>
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $job->description }}</p>
            </div>

            {{-- Tanggung Jawab --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Tanggung Jawab</h3>
                @if ($job->responsibilities)
                    <ul class="space-y-2">
                        @foreach (explode("\n", $job->responsibilities) as $item)
                            @if (trim($item))
                                <li class="flex items-start gap-2 text-slate-700 text-sm">
                                    <i class="fas fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                                    <span>{{ trim($item) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-400 italic text-sm">Belum ada informasi tanggung jawab.</p>
                @endif
            </div>

            {{-- Persyaratan --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Persyaratan</h3>
                @if ($job->requirements)
                    <ul class="space-y-2">
                        @foreach (explode("\n", $job->requirements) as $item)
                            @if (trim($item))
                                <li class="flex items-start gap-2 text-slate-700 text-sm">
                                    <i class="fas fa-check text-green-500 mt-0.5 flex-shrink-0"></i>
                                    <span>{{ trim($item) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-400 italic text-sm">Tidak ada persyaratan khusus.</p>
                @endif
            </div>

            {{-- Benefit & Tunjangan --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Benefit & Tunjangan</h3>
                @if ($job->benefits)
                    <ul class="space-y-2">
                        @foreach (explode("\n", $job->benefits) as $item)
                            @if (trim($item))
                                <li class="flex items-start gap-2 text-slate-700 text-sm">
                                    <i class="fas fa-gift text-purple-500 mt-0.5 flex-shrink-0"></i>
                                    <span>{{ trim($item) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-400 italic text-sm">Belum ada informasi benefit.</p>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection