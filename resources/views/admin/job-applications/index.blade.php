@extends('layouts.admin')

@section('title', 'Kelola Lamaran - Admin BKK')
@section('page_title', 'Kelola Lamaran')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-file-alt text-blue-600 mr-3"></i> Daftar Lamaran
        </h3>
    </div>
    <div class="table-custom">
        <table class="w-full">
            <thead>
                <tr>
                    <th>Lowongan</th>
                    <th>Siswa</th>
                    <th>Tgl Melamar</th>
                    <th>File Pendukung</th>
                    <th>Status Lamaran</th>
                    <th>Catatan Admin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->job->title ?? '-' }} ({{ $app->job->company->company_name ?? '-' }})</td>
                        <td>{{ $app->student->full_name ?? '-' }}</td>
                        <td>{{ $app->application_date->format('d M Y') }}</td>
                        <td>
                            @if($app->additional_file)
                                <a href="{{ asset('storage/' . $app->additional_file) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.job-applications.update-status', $app->job_application_id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="badge-pill {{ 
                                    $app->status == 'accepted' ? 'badge-success' : 
                                    ($app->status == 'rejected' ? 'badge-danger' : 
                                    ($app->status == 'review' ? 'badge-warning' : 'badge-info'))
                                }}">
                                    <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="review" {{ $app->status == 'review' ? 'selected' : '' }}>Seleksi Administrasi</option>
                                    <option value="accepted" {{ $app->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <textarea name="admin_notes" form="notes-{{ $app->job_application_id }}" class="w-full text-xs" rows="2">{{ $app->admin_notes }}</textarea>
                        </td>
                        <td>
                            <form id="notes-{{ $app->job_application_id }}" method="POST" action="{{ route('admin.job-applications.update-status', $app->job_application_id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $app->status }}">
                                <button type="submit" class="btn-action" title="Simpan Catatan">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.job-applications.show', $app->job_application_id) }}" class="btn-action" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-500 py-8">
                            <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                            Belum ada lamaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-200">
        {{ $applications->links() }}
    </div>
</div>
@endsection