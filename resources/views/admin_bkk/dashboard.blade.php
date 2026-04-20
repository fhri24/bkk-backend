@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    {{-- Header Dashboard --}}
    <div>
        <h3 class="text-2xl font-bold text-slate-800">Dashboard Utama</h3>
        <p class="text-slate-500">Ringkasan aktivitas sistem BKK hari ini.</p>
    </div>

    {{-- Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 transition-colors">
            <p class="text-slate-500 text-sm font-medium">Perusahaan Mitra</p>
            <h4 class="text-3xl font-bold text-slate-800 mt-2">124</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 transition-colors">
            <p class="text-slate-500 text-sm font-medium">Lowongan Aktif</p>
            <h4 class="text-3xl font-bold text-blue-600 mt-2">45</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 transition-colors">
            <p class="text-slate-500 text-sm font-medium">Pelamar Kerja</p>
            <h4 class="text-3xl font-bold text-slate-800 mt-2">1,202</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 transition-colors">
            <p class="text-slate-500 text-sm font-medium">Alumni Terserap</p>
            <h4 class="text-3xl font-bold text-green-500 mt-2">85%</h4>
        </div>
    </div>

    {{-- Tombol Aksi Cepat (Quick Actions) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.export') }}" class="flex items-center justify-center bg-green-600 text-white p-6 rounded-2xl font-bold hover:bg-green-700 shadow-md transition-all active:scale-95">
            <i class="fas fa-file-export mr-3 text-2xl"></i> 
            <div>
                <p class="text-lg">Export Data</p>
                <p class="text-xs font-normal opacity-80 text-left">Unduh data CSV/Excel</p>
            </div>
        </a>

        <a href="{{ route('admin.laporan') }}" class="flex items-center justify-center bg-purple-600 text-white p-6 rounded-2xl font-bold hover:bg-purple-700 shadow-md transition-all active:scale-95">
            <i class="fas fa-chart-line mr-3 text-2xl"></i> 
            <div>
                <p class="text-lg">Laporan</p>
                <p class="text-xs font-normal opacity-80 text-left">Lihat statistik lengkap</p>
            </div>
        </a>

        <a href="{{ route('admin.broadcast') }}" class="flex items-center justify-center bg-slate-700 text-white p-6 rounded-2xl font-bold hover:bg-slate-800 shadow-md transition-all active:scale-95">
            <i class="fas fa-bullhorn mr-3 text-2xl"></i> 
            <div>
                <p class="text-lg">Broadcast</p>
                <p class="text-xs font-normal opacity-80 text-left">Kirim info ke Alumni</p>
            </div>
        </a>
    </div>

    {{-- Aktivitas Terakhir --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100">
        <h4 class="font-bold text-slate-800 mb-4 text-lg text-left">Aktivitas Terakhir</h4>
        <div class="h-40 flex items-center justify-center border-2 border-dashed border-slate-100 rounded-xl text-slate-400">
            <p>Tabel log aktivitas atau grafik Chart.js akan muncul di sini</p>
        </div>
    </div>
</div>
@endsection