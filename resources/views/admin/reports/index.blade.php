@extends('layouts.admin')

@section('title', 'Laporan & Monitoring - Admin BKK')
@section('page_title', 'Laporan & Monitoring')

@section('content')
<div class="space-y-6">
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

    <div id="export-actions" class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Export Data</h3>
            <div class="space-x-2">
                <a href="{{ route('admin.reports.export.alumni.csv') }}" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Export Alumni</a>
                <a href="{{ route('admin.reports.export.jobs.csv') }}" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Export Lowongan</a>
                <a href="{{ route('admin.reports.export.alumni.print') }}" target="_blank" class="btn-action bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Cetak Alumni</a>
                <a href="{{ route('admin.reports.export.jobs.print') }}" target="_blank" class="btn-action bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Cetak Lowongan</a>
            </div>
        </div>
        <p class="text-sm text-slate-500">Gunakan tombol ini untuk mengunduh dan mencetak laporan alumni serta rekapitulasi lowongan.</p>
    </div>

    <div id="bmw-report" class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Laporan BMW</h3>
                <p class="text-sm text-slate-500">Statistik alumni berdasarkan status karir saat ini.</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('admin.reports.export.alumni.csv') }}" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Export Alumni</a>
                <a href="{{ route('admin.reports.export.alumni.print') }}" target="_blank" class="btn-action bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Cetak PDF</a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Bekerja</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['bekerja'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Melanjutkan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['melanjutkan'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Wirausaha</p>
                <p class="text-2xl font-bold text-slate-800">{{ $alumniCareerCounts['wirausaha'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div id="job-report" class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Rekapitulasi Lamaran</h3>
                <p class="text-sm text-slate-500">Jumlah lamaran dan diterima per bulan, serta perusahaan tujuan.</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('admin.reports.export.jobs.csv') }}" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Export Lowongan</a>
                <a href="{{ route('admin.reports.export.jobs.print') }}" target="_blank" class="btn-action bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Cetak PDF</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="p-3">Bulan</th>
                        <th class="p-3">Total Lamaran</th>
                        <th class="p-3">Diterima</th>
                        <th class="p-3">Perusahaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicationMonthly as $row)
                        <tr class="border-t border-slate-200">
                            <td class="p-3">{{ \Carbon\Carbon::createFromFormat('Y-m', $row['month'])->format('F Y') }}</td>
                            <td class="p-3">{{ $row['total'] }}</td>
                            <td class="p-3">{{ $row['accepted'] }}</td>
                            <td class="p-3 text-sm text-slate-700">
                                @foreach($row['by_company'] as $company => $count)
                                    <div>{{ $company }}: {{ $count }}</div>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-500">Belum ada data lamaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection