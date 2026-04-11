@extends('layouts.admin')

@section('title', 'Detail Lamaran - Admin BKK')
@section('page_title', 'Detail Lamaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Detail Lamaran #{{ $application->job_application_id }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-slate-700 mb-4">Informasi Lamaran</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Lowongan:</label>
                        <p class="text-slate-800">{{ $application->job->title ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ $application->job->company->company_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Siswa:</label>
                        <p class="text-slate-800">{{ $application->student->full_name ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ $application->student->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Tanggal Melamar:</label>
                        <p class="text-slate-800">{{ $application->application_date->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Status:</label>
                        <span class="badge-pill {{ 
                            $application->status == 'accepted' ? 'badge-success' : 
                            ($application->status == 'rejected' ? 'badge-danger' : 
                            ($application->status == 'review' ? 'badge-warning' : 'badge-info'))
                        }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-slate-700 mb-4">Dokumen</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Cover Letter:</label>
                        <p class="text-slate-800">{{ $application->cover_letter ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">File Pendukung:</label>
                        @if($application->additional_file)
                            <a href="{{ asset('storage/' . $application->additional_file) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                        @else
                            <p class="text-slate-500">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <label class="text-sm font-medium text-slate-600">Catatan Admin:</label>
            <form method="POST" action="{{ route('admin.job-applications.update-status', $application->job_application_id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ $application->status }}">
                <textarea name="admin_notes" class="w-full mt-2 p-3 border border-slate-300 rounded-lg" rows="4">{{ $application->admin_notes }}</textarea>
                <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan Catatan</button>
            </form>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('admin.job-applications.index') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Kembali ke Daftar</a>
    </div>
</div>
@endsection