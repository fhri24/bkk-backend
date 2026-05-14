{{-- ============================================================
     FILE: resources/views/company/lowongan/index.blade.php
     ============================================================ --}}
@extends('layouts.company')

@section('title', 'Lowongan Saya')
@section('page_title', 'Lowongan Saya')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-slate-500 text-sm">Kelola semua lowongan yang telah Anda buat</p>
    <a href="{{ route('company.lowongan.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah Lowongan
    </a>
</div>

@if($jobs->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 font-bold text-slate-600">Posisi</th>
                    <th class="text-left px-6 py-3 font-bold text-slate-600">Tipe</th>
                    <th class="text-left px-6 py-3 font-bold text-slate-600">Kadaluarsa</th>
                    <th class="text-center px-6 py-3 font-bold text-slate-600">Status BKK</th>
                    <th class="text-center px-6 py-3 font-bold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($jobs as $job)
                    @php $approval = $job->approval_status ?? 'approved'; @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800">{{ $job->title }}</p>
                            <p class="text-xs text-slate-400">{{ $job->location ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $job->job_type }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $job->expired_at ? $job->expired_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold px-3 py-1 rounded-full
                                {{ $approval === 'approved' ? 'bg-green-100 text-green-700' :
                                   ($approval === 'pending'  ? 'bg-yellow-100 text-yellow-700' :
                                   'bg-red-100 text-red-700') }}">
                                {{ $approval === 'approved' ? '✓ Disetujui' :
                                   ($approval === 'pending'  ? '⏳ Menunggu' : '✗ Ditolak') }}
                            </span>
                            @if($approval === 'rejected' && $job->approval_notes)
                                <p class="text-xs text-red-400 mt-1">{{ $job->approval_notes }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($approval !== 'approved')
                                    <a href="{{ route('company.lowongan.edit', $job->job_id) }}"
                                       class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                @endif
                                <form action="{{ route('company.lowongan.destroy', $job->job_id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Yakin hapus lowongan ini?')"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <i class="fas fa-briefcase text-4xl text-slate-200 mb-3 block"></i>
        <p class="text-slate-500 mb-4">Belum ada lowongan. Buat lowongan pertama Anda!</p>
        <a href="{{ route('company.lowongan.create') }}"
           class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 inline-block transition">
            <i class="fas fa-plus mr-2"></i> Tambah Lowongan
        </a>
    </div>
@endif
@endsection
