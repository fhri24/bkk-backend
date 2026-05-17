@extends('layouts.app')

@section('title', 'Tracer Study Report - BKK SMKN 1 Garut')

@section('extra_css')
    <style>
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="page-transition container mx-auto px-6 py-16">

        {{-- Header --}}
        <div class="text-center mb-12">
            <a href="{{ route('public.tracer') }}" class="text-blue-600 font-bold mb-4 inline-flex items-center">
                <i class="fas fa-chevron-left mr-2"></i> Kembali ke Tracer Study
            </a>
            <h1 class="text-4xl font-bold text-slate-900">Laporan Lengkap Tracer Study</h1>
            <p class="text-slate-500 mt-2 text-lg">Analisis mendalam hasil pelacakan alumni SMKN 1 Garut</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="stat-card glass-morphism p-8 rounded-3xl shadow-sm border-l-4 border-l-blue-500">
                <div class="bg-blue-50 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <span class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Responden</span>
                <p class="text-5xl font-black text-slate-900 mt-2">{{ $totalRespondents }}</p>
            </div>
            <div class="stat-card glass-morphism p-8 rounded-3xl shadow-sm border-l-4 border-l-emerald-500">
                <div class="bg-emerald-50 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-briefcase text-emerald-600 text-xl"></i>
                </div>
                <span class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Sudah Bekerja</span>
                <p class="text-5xl font-black text-emerald-600 mt-2">{{ $workingPercentage }}%</p>
            </div>
            <div class="stat-card glass-morphism p-8 rounded-3xl shadow-sm border-l-4 border-l-amber-500">
                <div class="bg-amber-50 w-12 h-12 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-store text-amber-600 text-xl"></i>
                </div>
                <span class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Berwirausaha</span>
                <p class="text-5xl font-black text-amber-600 mt-2">{{ $entrepreneurPercentage }}%</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-8 flex items-center text-lg">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-pie text-indigo-600"></i>
                    </div>
                    Penyebaran Status Alumni
                </h3>
                <div class="h-80 w-full flex items-center justify-center">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-8 flex items-center text-lg">
                    <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap text-sky-600"></i>
                    </div>
                    Kesesuaian dengan Pendidikan
                </h3>
                <div class="h-80 w-full flex items-center justify-center">
                    <canvas id="chartAlignment"></canvas>
                </div>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-xl">Database Alumni Lengkap</h3>
                <p class="text-slate-500 mt-1">Detail responden tracer study dan informasi karir mereka</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Alumni</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Angkatan</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Instansi</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">
                                Kesesuaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($tracerStudies as $tracer)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 mr-4">
                                            {{-- FIX: pakai full_name bukan name --}}
                                            {{ strtoupper(substr($tracer->student->full_name ?? 'N', 0, 1)) }}
                                        </div>
                                        {{-- FIX: pakai full_name bukan name --}}
                                        <span
                                            class="font-bold text-slate-800">{{ $tracer->student->full_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                    {{ $tracer->student->graduation_year ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{-- FIX: enum check pakai nilai yang benar sesuai database --}}
                                    <span
                                        class="px-4 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider
                  {{ $tracer->status_saat_ini === 'Bekerja'
                      ? 'bg-emerald-100 text-emerald-700'
                      : ($tracer->status_saat_ini === 'Kuliah'
                          ? 'bg-indigo-100 text-indigo-700'
                          : ($tracer->status_saat_ini === 'Wirausaha'
                              ? 'bg-amber-100 text-amber-700'
                              : 'bg-slate-100 text-slate-600')) }}">
                                        {{ $tracer->status_saat_ini ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-semibold">
                                    {{ $tracer->nama_instansi ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- FIX: enum check pakai nilai yang benar sesuai database --}}
                                    @if ($tracer->keselarasan_jurusan === 'Sesuai')
                                        <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold">✓
                                            Sesuai</span>
                                    @elseif($tracer->keselarasan_jurusan === 'Tidak Sesuai')
                                        <span class="bg-red-100 text-red-600 px-4 py-1.5 rounded-full text-xs font-bold">✕
                                            Tidak Sesuai</span>
                                    @else
                                        <span
                                            class="bg-gray-100 text-gray-400 px-4 py-1.5 rounded-full text-xs font-bold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <i class="fas fa-folder-open text-slate-200 text-4xl mb-4 block"></i>
                                    Belum ada data tracer study
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener('load', function() {
            const statusCtx = document.getElementById('chartStatus');
            const alignmentCtx = document.getElementById('chartAlignment');

            if (statusCtx) {
                new Chart(statusCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja'],
                        datasets: [{
                            data: @json(array_values($chartData)),
                            backgroundColor: ['#10b981', '#6366f1', '#f59e0b', '#94a3b8'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        cutout: '75%',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 25
                                }
                            }
                        }
                    }
                });
            }

            if (alignmentCtx) {
                new Chart(alignmentCtx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: ['Sesuai', 'Tidak Sesuai'],
                        datasets: [{
                            data: @json(array_values($alignmentData)),
                            backgroundColor: ['#3b82f6', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 25
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
