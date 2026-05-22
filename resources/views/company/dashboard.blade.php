@extends('layouts.company')

@section('title', 'Dashboard - ' . ($company->company_name ?? 'Panel Perusahaan'))
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Welcome --}}
    <div class="bg-gradient-to-r from-[#001f3f] to-[#003d6b] rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h2 class="text-2xl font-bold mb-1">Selamat Datang, {{ $company->company_name }}!</h2>
                <p class="text-blue-200 text-sm">Kelola lowongan dan pantau lamaran masuk dari panel ini.</p>
            </div>
            <a href="{{ route('public.beranda') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
        @if($lowonganPending > 0)
            <div class="mt-3 inline-flex items-center gap-2 bg-yellow-400/20 border border-yellow-400/40 text-yellow-300 px-4 py-2 rounded-xl text-sm font-semibold">
                <i class="fas fa-clock"></i>
                {{ $lowonganPending }} lowongan menunggu persetujuan Admin BKK
            </div>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-briefcase"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalLowongan }}</p>
            <p class="text-xs text-slate-500 font-semibold mt-1">Total Lowongan</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-check-circle"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $lowonganAktif }}</p>
            <p class="text-xs text-slate-500 font-semibold mt-1">Lowongan Aktif</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-users"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalLamaran }}</p>
            <p class="text-xs text-slate-500 font-semibold mt-1">Total Pelamar</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mb-3">
                <i class="fas fa-bell"></i>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $lamaranBaru }}</p>
            <p class="text-xs text-slate-500 font-semibold mt-1">Lamaran Baru</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">

        {{-- Lowongan Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Lowongan Terbaru</h3>
                <a href="{{ route('company.lowongan.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentJobs as $job)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $job->title }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $job->job_type }} · {{ $job->location ?? '-' }}</p>
                        </div>
                        @php
                            $approvalStatus = $job->approval_status ?? 'approved';
                        @endphp
                        <span class="text-xs font-bold px-3 py-1 rounded-full
                            {{ $approvalStatus === 'approved' ? 'bg-green-100 text-green-700' :
                               ($approvalStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               'bg-red-100 text-red-700') }}">
                            {{ $approvalStatus === 'approved' ? 'Aktif' : ($approvalStatus === 'pending' ? 'Menunggu' : 'Ditolak') }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada lowongan</div>
                @endforelse
            </div>
        </div>

        {{-- Lamaran Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Lamaran Terbaru</h3>
                <a href="{{ route('company.lamaran.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentApplications as $app)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $app->full_name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $app->job->title ?? '-' }}</p>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full
                            {{ $app->status === 'accepted' ? 'bg-green-100 text-green-700' :
                               ($app->status === 'rejected' ? 'bg-red-100 text-red-700' :
                               ($app->status === 'reviewed' ? 'bg-blue-100 text-blue-700' :
                               'bg-yellow-100 text-yellow-700')) }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada lamaran masuk</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
