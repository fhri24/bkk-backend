@extends('layouts.admin')

@section('title', 'Laporan & Monitoring - Admin BKK')
@section('page_title', 'Laporan & Monitoring')

@section('content')
<div class="space-y-6">
    {{-- Statistik Atas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Total Alumni</p>
            <div class="text-3xl font-bold text-slate-800">{{ $totalAlumni }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Total Lowongan</p>
            <div class="text-3xl font-bold text-slate-800">{{ $totalJobs }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Total Lamaran</p>
            <div class="text-3xl font-bold text-slate-800">{{ $totalApplications }}</div>
        </div>
    </div>

    {{-- BAGIAN EXPORT DATA --}}
    <div id="export-data" class="bg-white rounded-xl border border-slate-200 p-6 scroll-mt-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Export Data Utama</h3>
                <p class="text-sm text-slate-500">Unduh data mentah atau cetak laporan ringkas.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.export.alumni.csv') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> Alumni CSV
                </a>
                <a href="{{ route('admin.reports.export.jobs.csv') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> Lowongan CSV
                </a>
                <a href="{{ route('admin.reports.export.alumni.print') }}" target="_blank" class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium transition flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak Alumni
                </a>
                <a href="{{ route('admin.reports.export.jobs.print') }}" target="_blank" class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium transition flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak Lowongan
                </a>
            </div>
        </div>
    </div>

    {{-- BAGIAN LAPORAN BMW --}}
    <div id="bmw-report" class="bg-white rounded-xl border border-slate-200 p-6 scroll-mt-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Laporan BMW (Bekerja, Melanjutkan, Wirausaha)</h3>
                <p class="text-sm text-slate-500">Persentase keterserapan alumni di dunia kerja.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.export.alumni.print') }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium transition">
                    <i class="fas fa-file-pdf mr-2"></i> Cetak Laporan BMW
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-blue-100 p-4 bg-blue-50/30">
                <p class="text-sm text-blue-600 font-semibold mb-1">Bekerja</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['bekerja'] ?? 0 }} <span class="text-sm font-normal text-slate-500">Orang</span></p>
            </div>
            <div class="rounded-2xl border border-emerald-100 p-4 bg-emerald-50/30">
                <p class="text-sm text-emerald-600 font-semibold mb-1">Melanjutkan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['melanjutkan'] ?? 0 }} <span class="text-sm font-normal text-slate-500">Orang</span></p>
            </div>
            <div class="rounded-2xl border border-amber-100 p-4 bg-amber-50/30">
                <p class="text-sm text-amber-600 font-semibold mb-1">Wirausaha</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['wirausaha'] ?? 0 }} <span class="text-sm font-normal text-slate-500">Orang</span></p>
            </div>
        </div>
    </div>

    {{-- REKAPITULASI LAMARAN --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Rekapitulasi Lamaran Bulanan</h3>
                <p class="text-sm text-slate-500">Monitoring aktivitas pelamaran siswa per periode.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-sm">
                        <th class="p-4 font-semibold border-b border-slate-100">Bulan</th>
                        <th class="p-4 font-semibold border-b border-slate-100">Total Lamaran</th>
                        <th class="p-4 font-semibold border-b border-slate-100">Diterima</th>
                        <th class="p-4 font-semibold border-b border-slate-100">Distribusi Perusahaan</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    @forelse($applicationMonthly as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="p-4 font-medium">{{ \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->translatedFormat('F Y') }}</td>
                            <td class="p-4"><span class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold bg-blue-100 text-blue-700 rounded-full">{{ $row['total'] }}</span></td>
                            <td class="p-4"><span class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold bg-green-100 text-green-700 rounded-full">{{ $row['accepted'] }}</span></td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($row['by_company'] as $company => $count)
                                        <span class="text-[11px] bg-slate-100 border border-slate-200 px-2 py-1 rounded-md text-slate-600 shadow-sm">
                                            <span class="font-bold text-slate-800">{{ $company }}</span> ({{ $count }})
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-slate-400 italic">Belum ada aktivitas lamaran yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection