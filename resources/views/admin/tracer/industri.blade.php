@extends('layouts.admin')

@section('title', 'Laporan Industri')
@section('page_title', 'Laporan Industri')

@section('extra_css')
<style>
    .nilai-bar { height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden; }
    .nilai-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8); transition: width 0.6s ease; }
</style>
@endsection

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-building text-slate-500 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Total Perusahaan</p>
            <p class="text-2xl font-black text-slate-800">{{ $totalIndustri }}</p>
        </div>
    </div>
    <div class="bg-white border-l-4 border-green-500 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Nilai Rata-rata ≥ 4</p>
            <p class="text-2xl font-black text-green-600">{{ $matching }}</p>
            <p class="text-xs text-slate-400">Perusahaan dengan nilai baik</p>
        </div>
    </div>
    <div class="bg-white border-l-4 border-blue-500 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
            <i class="fas fa-star text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Rata-rata Penilaian</p>
            <p class="text-2xl font-black text-blue-600">
                {{ $withCompany > 0 ? round(collect($avgValues)->avg(), 1) : 0 }}/5
            </p>
        </div>
    </div>
</div>

{{-- RATA-RATA PER ASPEK --}}
<div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6">
    <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
        <i class="fas fa-chart-bar text-blue-500"></i> Rata-rata Penilaian Per Aspek
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($avgValues as $label => $nilai)
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
                <span class="text-sm font-black text-blue-600">{{ $nilai }}/5</span>
            </div>
            <div class="nilai-bar">
                <div class="nilai-fill" style="width: {{ ($nilai/5)*100 }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- FILTER --}}
<div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6">
    <form method="GET" action="{{ route('admin.tracer.industri') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Cari Perusahaan</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama perusahaan..."
                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div class="min-w-[180px]">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Perusahaan</label>
            <select name="jenis" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                <option value="">Semua Jenis</option>
                <option value="PT"                {{ request('jenis')==='PT' ? 'selected' : '' }}>PT</option>
                <option value="CV"                {{ request('jenis')==='CV' ? 'selected' : '' }}>CV</option>
                <option value="BUMN"              {{ request('jenis')==='BUMN' ? 'selected' : '' }}>BUMN</option>
                <option value="Instansi Pemerintah" {{ request('jenis')==='Instansi Pemerintah' ? 'selected' : '' }}>Instansi Pemerintah</option>
                <option value="Lainnya"           {{ request('jenis')==='Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.tracer.industri') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- TABEL --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-800">Data Penilaian Industri</h3>
        <p class="text-xs text-slate-400 mt-0.5">
            {{ $data->firstItem() ?? 0 }}–{{ $data->lastItem() ?? 0 }}
            dari {{ $data->total() }} data
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-4 text-left">#</th>
                    <th class="px-4 py-4 text-left">Perusahaan</th>
                    <th class="px-4 py-4 text-left">Jenis</th>
                    <th class="px-4 py-4 text-left">Bisnis Utama</th>
                    <th class="px-4 py-4 text-left">Responden</th>
                    <th class="px-4 py-4 text-center">Rata-rata Nilai</th>
                    <th class="px-4 py-4 text-left">Tanggal Isi</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($data as $i => $row)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-4 text-slate-400">{{ $data->firstItem() + $i }}</td>

                    {{-- Perusahaan --}}
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm shrink-0">
                                {{ strtoupper(substr($row->nama_perusahaan, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $row->nama_perusahaan }}</p>
                                <p class="text-xs text-slate-400 truncate max-w-[180px]">{{ $row->alamat_perusahaan }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Jenis --}}
                    <td class="px-4 py-4">
                        <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                            {{ $row->jenis_perusahaan }}
                        </span>
                    </td>

                    {{-- Bisnis Utama --}}
                    <td class="px-4 py-4 text-slate-600 text-sm">{{ $row->bisnis_utama }}</td>

                    {{-- Responden --}}
                    <td class="px-4 py-4">
                        <p class="font-semibold text-slate-700">{{ $row->nama_responden }}</p>
                        <p class="text-xs text-slate-400">{{ $row->jabatan_responden }}</p>
                    </td>

                    {{-- Rata-rata Nilai --}}
                    <td class="px-4 py-4 text-center">
                        @php $avg = $row->rata_rata; @endphp
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl font-bold text-sm
                            {{ $avg >= 4 ? 'bg-green-100 text-green-700' : ($avg >= 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            <i class="fas fa-star text-xs"></i> {{ $avg }}/5
                        </span>
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-4 py-4 text-xs text-slate-400">{{ $row->created_at->format('d M Y') }}</td>

                    {{-- Aksi --}}
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.tracer.industri.show', $row) }}"
                           class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg inline-flex items-center justify-center transition"
                           title="Detail">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-16 text-center text-slate-400">
                        <i class="fas fa-building text-4xl text-slate-200 block mb-3"></i>
                        <p class="font-semibold">Belum ada data penilaian industri</p>
                        <p class="text-xs mt-1">Data akan muncul setelah perusahaan mengisi form</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($data->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $data->links() }}
        </div>
    @endif
</div>

@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection