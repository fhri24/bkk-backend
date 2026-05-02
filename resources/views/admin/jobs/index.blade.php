@extends('layouts.admin')

@section('title', 'Manajemen Lowongan Kerja - Admin Dashboard')
@section('page_title', 'Manajemen Lowongan Kerja')

@section('content')
<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
    <div>
        <p class="text-slate-600">Kelola semua lowongan kerja yang telah dipublikasikan</p>
    </div>
    <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah Lowongan
    </a>
</div>

<form method="GET" class="mb-6 grid gap-4 lg:grid-cols-4">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Cari</label>
        <input type="text" name="search" value="{{ $search ?? '' }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Judul, perusahaan, atau jenis">
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Visibility</label>
        <select name="visibility" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">Semua Visibility</option>
            <option value="public" {{ ($visibility ?? '') === 'public' ? 'selected' : '' }}>Public</option>
            <option value="alumni_only" {{ ($visibility ?? '') === 'alumni_only' ? 'selected' : '' }}>Alumni Only</option>
            <option value="private" {{ ($visibility ?? '') === 'private' ? 'selected' : '' }}>Private</option>
            <option value="internal" {{ ($visibility ?? '') === 'internal' ? 'selected' : '' }}>Internal</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Status</label>
        <select name="status" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">Semua Status</option>
            <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="flex items-end">
        <button type="submit" class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Terapkan Filter</button>
    </div>
</form>

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
                    <th class="text-center">Judul</th>
                    <th class="text-center">Perusahaan</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Visibility</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Kadaluarsa</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                    <tr>
                        <td class="text-center font-semibold">{{ $job->title }}</td>
                        <td class="text-center">{{ $job->company->company_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $job->job_type ?? '-' }}</td>
                        <td class="text-center"><span class="badge-pill badge-info">{{ ucfirst(str_replace('_', ' ', $job->visibility)) }}</span></td>
                        <td class="text-center">
                            <span class="badge-pill {{ $job->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td class="text-center">{{ $job->expired_at ? $job->expired_at->format('d M Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.jobs.show', $job->job_id) }}" class="btn-action text-slate-700 hover:bg-slate-100" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.jobs.edit', $job->job_id) }}" class="btn-action text-blue-600 hover:bg-blue-50" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.jobs.destroy', $job->job_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action text-red-600 hover:bg-red-50" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
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
    <div class="bg-white rounded-lg border border-slate-200 p-12 text-center">
        <i class="fas fa-inbox text-4xl text-slate-300 mb-3 block"></i>
        <p class="text-slate-600 mb-4">Belum ada lowongan kerja</p>
        <a href="{{ route('admin.jobs.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
            <i class="fas fa-plus mr-2"></i>Buat Lowongan Baru
        </a>
    </div>
@endif
@endsection 