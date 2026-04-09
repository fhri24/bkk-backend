@extends('layouts.admin')

@section('title', 'Manajemen Lowongan Kerja - Admin Dashboard')
@section('page_title', 'Manajemen Lowongan Kerja')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-slate-600">Kelola semua lowongan kerja yang telah dipublikasikan</p>
    </div>
    <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah Lowongan
    </a>
</div>

@if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

    @if ($jobs->count() > 0)
        <div class="table-custom">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Perusahaan</th>
                        <th>Jenis</th>
                        <th>Visibility</th>
                        <th>Status</th>
                        <th>Kadaluarsa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td class="font-semibold">{{ $job->title }}</td>
                            <td>{{ $job->company->company_name ?? 'N/A' }}</td>
                            <td>{{ $job->job_type ?? '-' }}</td>
                            <td><span class="badge-pill badge-info">{{ ucfirst($job->visibility) }}</span></td>
                            <td>
                                <span class="badge-pill {{ $job->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td>{{ $job->expired_at ? $job->expired_at->format('d M Y') : '-' }}</td>
                            <td>
                                <form action="{{ route('admin.jobs.destroy', $job->job_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action text-red-600 hover:bg-red-50" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-lg border border-slate-200 p-12 text-center">
            <i class="fas fa-inbox text-4xl text-slate-300 mb-3 block"></i>
            <p class="text-slate-600 mb-4">Belum ada lowongan kerja</p>
            <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                <i class="fas fa-plus mr-2"></i>Buat Lowongan Baru
            </a>
        </div>
    @endif
@endsection
