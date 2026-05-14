{{-- ============================================================
     FILE: resources/views/company/lamaran/show.blade.php
     ============================================================ --}}
@extends('layouts.company')
@section('title', 'Detail Lamaran')
@section('page_title', 'Detail Lamaran')

@section('content')
<div class="max-w-3xl space-y-6">

    <a href="{{ route('company.lamaran.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Lamaran
    </a>

    {{-- Info Pelamar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-user text-blue-500"></i> Informasi Pelamar
        </h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-400 font-semibold mb-1">Nama Lengkap</p>
                <p class="font-bold text-slate-800">{{ $application->full_name }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold mb-1">Email</p>
                <p class="font-bold text-slate-800">{{ $application->email }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold mb-1">No. HP</p>
                <p class="font-bold text-slate-800">{{ $application->phone_number ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold mb-1">Posisi Dilamar</p>
                <p class="font-bold text-slate-800">{{ $application->job->title ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold mb-1">Tanggal Melamar</p>
                <p class="font-bold text-slate-800">
                    {{ $application->application_date ? \Carbon\Carbon::parse($application->application_date)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold mb-1">CV / Resume</p>
                @if($application->additional_file)
                    <a href="{{ asset('storage/cv_applications/' . $application->additional_file) }}" target="_blank"
                       class="inline-flex items-center gap-2 text-blue-600 font-bold hover:underline">
                        <i class="fas fa-file-pdf"></i> Unduh CV
                    </a>
                @else
                    <p class="text-slate-400">Tidak ada file</p>
                @endif
            </div>
        </div>

        @if($application->cover_letter)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-slate-400 font-semibold mb-2">Pesan / Motivasi</p>
                <p class="text-slate-700 text-sm leading-relaxed bg-slate-50 rounded-xl p-4">{{ $application->cover_letter }}</p>
            </div>
        @endif
    </div>

    {{-- Update Status --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-tasks text-blue-500"></i> Update Status Lamaran
        </h3>
        <form action="{{ route('company.lamaran.update-status', $application->job_application_id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status Saat Ini</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                    <option value="pending"  {{ $application->status === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Sedang Ditinjau</option>
                    <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan <span class="text-slate-400">(Opsional)</span></label>
                <textarea name="admin_notes" rows="3" placeholder="Tulis catatan untuk pelamar..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ $application->admin_notes }}</textarea>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition">
                <i class="fas fa-save mr-2"></i> Simpan Status
            </button>
        </form>
    </div>
</div>
@endsection
