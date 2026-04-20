@extends('layouts.admin')

@section('title', 'Laporan & Monitoring - Admin BKK')
@section('page_title', 'Laporan & Monitoring')

@section('content')
<div class="space-y-6">
    {{-- Statistik Atas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Alumni</p>
            <div class="text-3xl font-bold text-slate-800">{{ $totalAlumni }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Lowongan</p>
            <div class="text-3xl font-bold text-slate-800">{{ $totalJobs }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Lamaran</p>
            <div class="text-3xl font-bold text-slate-800">{{ $totalApplications }}</div>
        </div>
    </div>

    {{-- BAGIAN EXPORT DATA --}}
    {{-- Beri id="export-data" agar bisa di-scroll otomatis dari Dashboard --}}
    <div id="export-data" class="bg-white rounded-xl border border-slate-200 p-6 scroll-mt-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Export Data</h3>
                <p class="text-sm text-slate-500">Unduh atau cetak laporan alumni dan lowongan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.export.alumni.csv') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition">Export Alumni</a>
                <a href="{{ route('admin.reports.export.jobs.csv') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition">Export Lowongan</a>
                <a href="{{ route('admin.reports.export.alumni.print') }}" target="_blank" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 text-sm font-medium transition">Cetak Alumni</a>
                <a href="{{ route('admin.reports.export.jobs.print') }}" target="_blank" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 text-sm font-medium transition">Cetak Lowongan</a>
            </div>
        </div>
        
    </div>

    {{-- BAGIAN LAPORAN BMW --}}
    {{-- Beri id="bmw-report" agar bisa di-scroll otomatis dari Dashboard --}}
    <div id="bmw-report" class="bg-white rounded-xl border border-slate-200 p-6 scroll-mt-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Laporan BMW</h3>
                <p class="text-sm text-slate-500">Statistik alumni berdasarkan status karir saat ini.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.export.alumni.csv') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition">Export CSV</a>
                <a href="{{ route('admin.reports.export.alumni.print') }}" target="_blank" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 text-sm font-medium transition">Cetak PDF</a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50/50">
                <p class="text-sm text-slate-500 mb-1">Bekerja</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['bekerja'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50/50">
                <p class="text-sm text-slate-500 mb-1">Melanjutkan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['melanjutkan'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50/50">
                <p class="text-sm text-slate-500 mb-1">Wirausaha</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['wirausaha'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- REKAPITULASI LAMARAN --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Rekapitulasi Lamaran</h3>
                <p class="text-sm text-slate-500">Jumlah lamaran dan diterima per bulan.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.export.jobs.csv') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition">Export CSV</a>
                <a href="{{ route('admin.reports.export.jobs.print') }}" target="_blank" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 text-sm font-medium transition">Cetak PDF</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-sm">
                        <th class="p-4 font-semibold border-b border-slate-100">Bulan</th>
                        <th class="p-4 font-semibold border-b border-slate-100">Total Lamaran</th>
                        <th class="p-4 font-semibold border-b border-slate-100">Diterima</th>
                        <th class="p-4 font-semibold border-b border-slate-100">Perusahaan</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse($applicationMonthly as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="p-4">{{ \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->format('F Y') }}</td>
                            <td class="p-4"><span class="font-bold text-slate-800">{{ $row['total'] }}</span></td>
                            <td class="p-4"><span class="text-green-600 font-bold">{{ $row['accepted'] }}</span></td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    @foreach($row['by_company'] as $company => $count)
                                        <div class="text-xs bg-white border border-slate-200 px-2 py-1 rounded-md inline-block mr-1">
                                            <span class="font-semibold">{{ $company }}</span>: {{ $count }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-slate-400 italic">Belum ada data lamaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection