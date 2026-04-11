@extends('layouts.admin')

@section('title', 'Admin Dashboard - BKK SMKN 1 Garut')
@section('page_title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Jobs -->
    <div class="stat-box">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium mb-1">Total Lowongan</p>
                <div class="text-3xl font-bold text-slate-800">{{ $total_jobs ?? 0 }}</div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-arrow-up text-green-500"></i> Bulan Ini</p>
            </div>
            <div class="stat-icon bg-blue-100 text-blue-600">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Applications -->
    <div class="stat-box">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium mb-1">Total Lamaran</p>
                <div class="text-3xl font-bold text-slate-800">{{ $total_applications ?? 0 }}</div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-arrow-up text-green-500"></i> Pending</p>
            </div>
            <div class="stat-icon bg-green-100 text-green-600">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Students -->
    <div class="stat-box">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium mb-1">Total Alumni</p>
                <div class="text-3xl font-bold text-slate-800">{{ $total_students ?? 0 }}</div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-flag"></i> Terserap</p>
            </div>
            <div class="stat-icon bg-purple-100 text-purple-600">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Companies -->
    <div class="stat-box">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium mb-1">Total Perusahaan</p>
                <div class="text-3xl font-bold text-slate-800">{{ $total_companies ?? 0 }}</div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-handshake"></i> Terdata</p>
            </div>
            <div class="stat-icon bg-orange-100 text-orange-600">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Quick Actions + Recent Jobs -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                <i class="fas fa-lightning-bolt text-yellow-500 mr-3"></i> Aksi Cepat
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.jobs.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-plus mb-2 block text-lg"></i>
                    Tambah Lowongan
                </a>
                <button class="bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-download mb-2 block text-lg"></i>
                    Export Data
                </button>
                <button class="bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-chart-line mb-2 block text-lg"></i>
                    Laporan
                </button>
                <button class="bg-gradient-to-br from-slate-500 to-slate-600 hover:from-slate-600 hover:to-slate-700 text-white p-4 rounded-lg text-center font-semibold transition">
                    <i class="fas fa-envelope mb-2 block text-lg"></i>
                    Broadcast
                </button>
            </div>
        </div>
        
        <!-- Recent Job Listings -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-blue-600 mr-3"></i> Lowongan Terbaru
                </h3>
            </div>
            <div class="table-custom">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Posisi</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_jobs ?? [] as $job)
                            <tr>
                                <td class="font-semibold">{{ $job->title ?? '-' }}</td>
                                <td>{{ $job->company->company_name ?? '-' }}</td>
                                <td>
                                    <span class="badge-pill {{ $job->status == 'active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($job->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500 py-8">
                                    <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                    Belum ada lowongan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Right Sidebar -->
    <div>
        <!-- Stats Overview -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6">
                <i class="fas fa-chart-pie text-green-600 mr-3"></i> Ringkasan
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Lamaran Pending</span>
                        <span class="text-sm font-bold text-slate-800">{{ $pending_applications ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Lowongan Aktif</span>
                        <span class="text-sm font-bold text-slate-800">{{ $active_jobs ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 72%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Tingkat Penyaluran</span>
                        <span class="text-sm font-bold text-slate-800">85%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6">
                <i class="fas fa-building text-blue-600 mr-3"></i> Perusahaan Teratas
            </h3>
            <div class="space-y-3">
                @forelse($topCompanies ?? [] as $company)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $company->company_name }}</p>
                                <p class="text-xs text-slate-500">{{ $company->industry ?? 'Industri belum diisi' }}</p>
                            </div>
                            <span class="badge-pill badge-info">{{ $company->jobs_count }} lowongan</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada data perusahaan.</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6">
                <i class="fas fa-calendar text-red-600 mr-3"></i> Acara Mendatang
            </h3>
            <div class="space-y-3">
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <p class="font-semibold text-slate-800 text-sm">Job Fair 2026</p>
                    <p class="text-xs text-slate-500 mt-1">15-17 Maret 2026</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4 py-2">
                    <p class="font-semibold text-slate-800 text-sm">Workshop Interview</p>
                    <p class="text-xs text-slate-500 mt-1">22 Februari 2026</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4 py-2">
                    <p class="font-semibold text-slate-800 text-sm">Sertifikasi BNSP</p>
                    <p class="text-xs text-slate-500 mt-1">01-30 Maret 2026</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-file-alt text-green-600 mr-3"></i> Lamaran Terbaru
        </h3>
    </div>
    <div class="table-custom">
        <table class="w-full">
            <thead>
                <tr>
                    <th>Pelamar</th>
                    <th>Posisi</th>
                    <th>Tanggal Lamar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_applications ?? [] as $app)
                    <tr>
                        <td class="font-semibold">{{ $app->student->user->email ?? $app->student->full_name ?? '-' }}</td>
                        <td>{{ $app->job->title ?? '-' }}</td>
                        <td>{{ ($app->application_date ?? now())->format('d M Y') }}</td>
                        <td>
                            <span class="badge-pill {{ 
                                $app->status == 'accepted' ? 'badge-success' : 
                                ($app->status == 'rejected' ? 'badge-danger' : 'badge-warning')
                            }}">
                                {{ ucfirst($app->status ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <button class="btn-action" title="Review">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-500 py-8">
                            <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                            Belum ada lamaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
