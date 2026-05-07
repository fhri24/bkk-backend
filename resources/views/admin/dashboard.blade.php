@extends('layouts.admin')

@section('title', 'Admin Dashboard - BKK SMKN 1 Garut')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header Dashboard --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Dashboard Utama</h3>
            <p class="text-slate-500">Ringkasan aktivitas sistem BKK hari ini.</p>
        </div>
        <div class="text-sm text-slate-400 bg-white px-4 py-2 rounded-lg border border-slate-200">
            <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d F Y') }}
        </div>
    </div>

    {{-- Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-300 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Lowongan</p>
                    <div class="text-3xl font-bold text-slate-800">{{ $total_jobs ?? 0 }}</div>
                </div>
                <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                    <i class="fas fa-briefcase text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-green-300 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Lamaran</p>
                    <div class="text-3xl font-bold text-slate-800">{{ $total_applications ?? 0 }}</div>
                </div>
                <div class="p-3 rounded-xl bg-green-50 text-green-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-purple-300 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Alumni</p>
                    <div class="text-3xl font-bold text-slate-800">{{ $total_students ?? 0 }}</div>
                </div>
                <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-orange-300 transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Total Perusahaan</p>
                    <div class="text-3xl font-bold text-slate-800">{{ $total_companies ?? 0 }}</div>
                </div>
                <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Aksi Cepat --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-3"></i> Aksi Cepat
                </h3>

                <div class="grid grid-cols-2 gap-4">

                    <a href="{{ route('admin.jobs.create') }}"
                       class="flex flex-col items-center justify-center p-6 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition active:scale-95 shadow-sm">
                        <i class="fas fa-plus text-2xl mb-2"></i>
                        <span class="font-bold text-center text-sm">Tambah Lowongan</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}#export-data"
                       class="flex flex-col items-center justify-center p-6 bg-green-600 text-white rounded-xl hover:bg-green-700 transition active:scale-95 shadow-sm">
                        <i class="fas fa-file-export text-2xl mb-2"></i>
                        <span class="font-bold text-center text-sm">Export Data</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}#bmw-report"
                       class="flex flex-col items-center justify-center p-6 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition active:scale-95 shadow-sm">
                        <i class="fas fa-chart-line text-2xl mb-2"></i>
                        <span class="font-bold text-center text-sm">Laporan BMW</span>
                    </a>

                    <a href="{{ route('admin.broadcast.index') }}"
                       class="flex flex-col items-center justify-center p-6 bg-slate-700 text-white rounded-xl hover:bg-slate-800 transition active:scale-95 shadow-sm">
                        <i class="fas fa-bullhorn text-2xl mb-2"></i>
                        <span class="font-bold text-center text-sm">Broadcast</span>
                    </a>

                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-8">

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6">Ringkasan</h3>

                <div class="space-y-6">

                    <div>
                        <div class="flex justify-between mb-2 text-sm">
                            <span class="text-slate-600">Lamaran Pending</span>
                            <span class="font-bold text-slate-800">{{ $pending_applications ?? 0 }}</span>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 text-sm">
                            <span class="text-slate-600">Lowongan Aktif</span>
                            <span class="font-bold text-slate-800">{{ $active_jobs ?? 0 }}</span>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6">Perusahaan Teratas</h3>

                <div class="space-y-4">

                    @forelse($topCompanies ?? [] as $company)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $company->company_name }}</p>
                            <p class="text-xs text-slate-500">{{ $company->industry ?? 'Industri' }}</p>
                        </div>

                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-lg font-bold">
                            {{ $company->jobs_count }} Lowongan
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400">Tidak ada data.</p>
                    @endforelse

                </div>
            </div>
 
        </div>
    </div>
</div>
@endsection