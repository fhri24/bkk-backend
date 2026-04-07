@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div>
        <h3 class="text-2xl font-bold text-slate-800">Dashboard Utama</h3>
        <p class="text-slate-500">Ringkasan aktivitas sistem BKK hari ini.</p>
    </div>

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

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100">
        <h4 class="font-bold text-slate-800 mb-4 text-lg">Aktivitas Terakhir</h4>
        <div class="h-40 flex items-center justify-center border-2 border-dashed border-slate-100 rounded-xl text-slate-400">
            [ Tempat Tabel atau Chart.js ]
        </div>
    </div>
</div>
@endsection