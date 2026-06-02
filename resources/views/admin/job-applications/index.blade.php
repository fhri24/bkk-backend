@extends('layouts.admin')

@section('title', 'Kelola Lamaran - Admin BKK')
@section('page_title', 'Kelola Lamaran')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded shadow-md flex items-center">
        <i class="fas fa-check-circle mr-3 text-xl text-green-600"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded shadow-md flex items-center">
        <i class="fas fa-exclamation-triangle mr-3 text-xl text-red-600"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
@endif

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
    <div class="p-6 border-b border-slate-200 bg-slate-50">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-file-alt text-blue-600 mr-3"></i> Daftar Lamaran Masuk
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 text-slate-600 uppercase text-xs font-bold">
                    <th class="p-4 border-b">Lowongan</th>
                    <th class="p-4 border-b">Nama Pelamar</th>
                    <th class="p-4 border-b">Tgl Melamar</th>
                    <th class="p-4 border-b">File Pendukung</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b">Catatan Admin</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-700">
                @forelse($applications as $app)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="p-4 text-sm">
                            <div class="font-bold text-slate-800">{{ $app->job->title ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ $app->job->company->company_name ?? '-' }}</div>
                        </td>

                        <td class="p-4">
                            <span class="font-bold text-blue-700 px-2 py-1 bg-blue-50 rounded text-sm">
                                {{-- AMBIL DARI FULL_NAME SESUAI DATABASE --}}
                                {{ $app->full_name ?? ($app->student->full_name ?? 'Tanpa Nama') }}
                            </span>
                        </td>

                        <td class="p-4 text-sm">{{ $app->application_date->format('d M Y') }}</td>

                        <td class="p-4">
                            @if($app->additional_file)
                                {{-- Gunakan method getCvUrl() dari model untuk generate URL yang benar --}}
                                <a href="{{ $app->getCvUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center font-medium">
                                    <i class="fas fa-file-pdf mr-2 text-lg"></i> CV
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.job-applications.update-status', $app->job_application_id) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-full px-3 py-1 border outline-none {{
                                    $app->status == 'accepted' ? 'bg-green-100 text-green-700 border-green-200' :
                                    ($app->status == 'rejected' ? 'bg-red-100 text-red-700 border-red-200' :
                                    ($app->status == 'review' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 'bg-blue-100 text-blue-700 border-blue-200'))
                                }}">
                                    <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="review" {{ $app->status == 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="accepted" {{ $app->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </form>
                        </td>

                        <td class="p-4">
                            <textarea name="admin_notes" form="notes-{{ $app->job_application_id }}" class="w-full text-xs border rounded p-2 focus:ring-1 focus:ring-blue-400" rows="2">{{ $app->admin_notes }}</textarea>
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <form id="notes-{{ $app->job_application_id }}" method="POST" action="{{ route('admin.job-applications.update-status', $app->job_application_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $app->status }}">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded shadow-sm transition" title="Simpan Catatan">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.job-applications.show', $app->job_application_id) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded shadow-sm transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 py-12 italic">Belum ada lamaran yang masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-slate-50 border-t border-slate-200">
        {{ $applications->links() }}
    </div>
</div>
@endsection

