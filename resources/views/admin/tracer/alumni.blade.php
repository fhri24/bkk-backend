@extends('layouts.admin')

@section('title', 'Laporan Alumni - Tracer Study - BKK SMKN 1 Garut')
@section('page_title', 'Laporan Alumni')

@section('extra_css')
<style>
    .badge-bekerja  { background:#dcfce7; color:#166534; }
    .badge-kuliah   { background:#dbeafe; color:#1e40af; }
    .badge-wirausaha{ background:#fef3c7; color:#92400e; }
    .badge-belum    { background:#f1f5f9; color:#475569; }
    .badge-status {
        display:inline-flex; align-items:center; gap:4px;
        padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;
    }
</style>
@endsection

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-users text-slate-500 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Total</p>
            <p class="text-2xl font-black text-slate-800">{{ $total }}</p>
        </div>
    </div>
    <div class="bg-white border-l-4 border-green-500 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <i class="fas fa-briefcase text-green-600 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Bekerja</p>
            <p class="text-2xl font-black text-green-600">{{ $working }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($working/$total)*100) : 0 }}%</p>
        </div>
    </div>
    <div class="bg-white border-l-4 border-blue-500 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Kuliah</p>
            <p class="text-2xl font-black text-blue-600">{{ $studying }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($studying/$total)*100) : 0 }}%</p>
        </div>
    </div>
    <div class="bg-white border-l-4 border-amber-500 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
            <i class="fas fa-store text-amber-600 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Wirausaha</p>
            <p class="text-2xl font-black text-amber-600">{{ $entrepren }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($entrepren/$total)*100) : 0 }}%</p>
        </div>
    </div>
    <div class="bg-white border-l-4 border-slate-400 border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-clock text-slate-500 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold">Belum Bekerja</p>
            <p class="text-2xl font-black text-slate-600">{{ $unemployed }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($unemployed/$total)*100) : 0 }}%</p>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6">
    <form method="GET" action="{{ route('admin.tracer.index') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Cari Nama</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama alumni..."
                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
            <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="Bekerja"       {{ request('status')==='Bekerja' ? 'selected' : '' }}>Bekerja</option>
                <option value="Kuliah"        {{ request('status')==='Kuliah' ? 'selected' : '' }}>Kuliah</option>
                <option value="Wirausaha"     {{ request('status')==='Wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                <option value="Belum Bekerja" {{ request('status')==='Belum Bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tahun Lulus</label>
            <select name="year" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                <option value="">Semua Tahun</option>
                @foreach($graduationYears as $year)
                    <option value="{{ $year }}" {{ request('year')==$year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.tracer.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold transition">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- TABEL --}}
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800">Data Tracer Study</h3>
            <p class="text-xs text-slate-400 mt-0.5">
                {{ $tracerStudies->firstItem() ?? 0 }}–{{ $tracerStudies->lastItem() ?? 0 }}
                dari {{ $tracerStudies->total() }} data
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-4 text-left">#</th>
                    <th class="px-4 py-4 text-left">Nama</th>
                    <th class="px-4 py-4 text-left">No. HP</th>
                    <th class="px-4 py-4 text-left">Jurusan</th>
                    <th class="px-4 py-4 text-left">Tahun Lulus</th>
                    <th class="px-4 py-4 text-left">Status</th>
                    <th class="px-4 py-4 text-left">Instansi / PT / Usaha</th>
                    <th class="px-4 py-4 text-left">Tanggal Isi</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($tracerStudies as $i => $row)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-4 text-slate-400 font-medium">{{ $tracerStudies->firstItem() + $i }}</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                {{ strtoupper(substr($row->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $row->nama_lengkap }}</p>
                                <p class="text-xs text-slate-400">{{ $row->email ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-slate-600">{{ $row->no_hp ?? '-' }}</td>
                    <td class="px-4 py-4 text-slate-600">{{ $row->jurusan ?? '-' }}</td>
                    <td class="px-4 py-4 font-semibold text-slate-700">{{ $row->tahun_lulus ?? '-' }}</td>
                    <td class="px-4 py-4">
                        @switch($row->status_saat_ini)
                            @case('Bekerja')
                                <span class="badge-status badge-bekerja"><i class="fas fa-circle" style="font-size:6px"></i> Bekerja</span>
                                @break
                            @case('Kuliah')
                                <span class="badge-status badge-kuliah"><i class="fas fa-circle" style="font-size:6px"></i> Kuliah</span>
                                @break
                            @case('Wirausaha')
                                <span class="badge-status badge-wirausaha"><i class="fas fa-circle" style="font-size:6px"></i> Wirausaha</span>
                                @break
                            @default
                                <span class="badge-status badge-belum"><i class="fas fa-circle" style="font-size:6px"></i> Belum Bekerja</span>
                        @endswitch
                    </td>
                    <td class="px-4 py-4 text-slate-700 font-medium">
                        {{ $row->nama_instansi ?? $row->nama_pt ?? $row->nama_usaha ?? '-' }}
                    </td>
                    <td class="px-4 py-4 text-xs text-slate-400">{{ $row->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.tracer.show', $row) }}"
                               class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center transition"
                               title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <form action="{{ route('admin.tracer.destroy', $row) }}" method="POST"
                                  onsubmit="return confirm('Hapus data tracer study {{ addslashes($row->nama_lengkap) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition"
                                    title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-16 text-center text-slate-400">
                        <i class="fas fa-folder-open text-4xl text-slate-200 block mb-3"></i>
                        <p class="font-semibold">Belum ada data tracer study</p>
                        <p class="text-xs mt-1">Data akan muncul setelah alumni mengisi form</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tracerStudies->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $tracerStudies->links() }}
        </div>
    @endif
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection