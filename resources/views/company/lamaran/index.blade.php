{{-- ============================================================
     FILE: resources/views/company/lamaran/index.blade.php
     ============================================================ --}}
@extends('layouts.company')
@section('title', 'Lamaran Masuk')
@section('page_title', 'Lamaran Masuk')

@section('content')
{{-- Filter --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <select name="job_id" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition">
        <option value="">Semua Lowongan</option>
        @foreach($myJobs as $job)
            <option value="{{ $job->job_id }}" {{ request('job_id') == $job->job_id ? 'selected' : '' }}>
                {{ $job->title }}
            </option>
        @endforeach
    </select>
    <select name="status" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition">
        <option value="">Semua Status</option>
        <option value="pending"  {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
        <option value="reviewed" {{ request('status') === 'reviewed'  ? 'selected' : '' }}>Ditinjau</option>
        <option value="accepted" {{ request('status') === 'accepted'  ? 'selected' : '' }}>Diterima</option>
        <option value="rejected" {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">Filter</button>
    @if(request('job_id') || request('status'))
        <a href="{{ route('company.lamaran.index') }}" class="border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">Reset</a>
    @endif
</form>

@if($applications->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 font-bold text-slate-600">Pelamar</th>
                    <th class="text-left px-6 py-3 font-bold text-slate-600">Posisi</th>
                    <th class="text-left px-6 py-3 font-bold text-slate-600">Tanggal</th>
                    <th class="text-center px-6 py-3 font-bold text-slate-600">Status</th>
                    <th class="text-center px-6 py-3 font-bold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($applications as $app)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-800">{{ $app->full_name }}</p>
                            <p class="text-xs text-slate-400">{{ $app->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $app->job->title ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $app->application_date ? \Carbon\Carbon::parse($app->application_date)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold px-3 py-1 rounded-full
                                {{ $app->status === 'accepted' ? 'bg-green-100 text-green-700' :
                                   ($app->status === 'rejected' ? 'bg-red-100 text-red-700' :
                                   ($app->status === 'reviewed' ? 'bg-blue-100 text-blue-700' :
                                   'bg-yellow-100 text-yellow-700')) }}">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('company.lamaran.show', $app->job_application_id) }}"
                               class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition mx-auto" title="Lihat Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <i class="fas fa-inbox text-4xl text-slate-200 mb-3 block"></i>
        <p class="text-slate-500">Belum ada lamaran masuk.</p>
    </div>
@endif
@endsection
