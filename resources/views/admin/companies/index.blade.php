@extends('layouts.admin')

@section('title', 'Manajemen Perusahaan')
@section('page_title', 'Daftar Perusahaan')

@section('content')
<div class="p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Manajemen Perusahaan</h2>
            <p class="text-sm text-slate-500">Kelola perusahaan mitra dan tambahkan perusahaan baru sebelum membuat lowongan.</p>
        </div>
        <a href="{{ route('admin.companies.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Perusahaan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Data Perusahaan</h3>
        </div>
        <div class="p-6">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" class="mb-6 grid gap-4 lg:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Cari Perusahaan</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nama atau industri">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Status Lowongan</label>
                    <select name="has_jobs" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Perusahaan</option>
                        <option value="1" {{ ($hasJobs ?? '') === '1' ? 'selected' : '' }}>Dengan Lowongan</option>
                        <option value="0" {{ ($hasJobs ?? '') === '0' ? 'selected' : '' }}>Belum Ada Lowongan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Terapkan Filter</button>
                </div>
            </form>
            @if ($companies->isEmpty())
                <div class="text-center py-16 text-slate-500">
                    <p class="text-lg font-semibold">Belum ada perusahaan terdaftar.</p>
                    <p class="mt-3">Silakan tambahkan perusahaan baru terlebih dahulu untuk membuat lowongan kerja.</p>
                    <a href="{{ route('admin.companies.create') }}" class="mt-6 inline-flex rounded-lg bg-blue-600 px-6 py-3 text-white hover:bg-blue-700 transition">Tambah Perusahaan</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Perusahaan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Industri</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Lowongan</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach ($companies as $company)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900">{{ $company->company_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $company->address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $company->industry ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                        {{ $company->contact_person ?? '—' }}<br>
                                        <span class="text-xs text-slate-500">{{ $company->phone ?? '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $company->jobs->count() }} lowongan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex flex-wrap items-center justify-center gap-2">
                                            <a href="{{ route('admin.companies.show', $company->company_id) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-slate-700 hover:bg-slate-200 transition">
                                                <i class="fas fa-eye mr-2"></i> Detail
                                            </a>
                                            <a href="{{ route('admin.companies.edit', $company->company_id) }}" class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700 transition">
                                                <i class="fas fa-edit mr-2"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.companies.destroy', $company->company_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700 transition" onclick="return confirm('Yakin ingin menghapus perusahaan ini? Seluruh lowongan terkait mungkin akan hilang juga.')">
                                                    <i class="fas fa-trash mr-2"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
