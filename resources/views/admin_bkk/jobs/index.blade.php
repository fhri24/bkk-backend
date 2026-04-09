@extends('layouts.admin')

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
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-left">Judul</th>
                        <th class="px-4 py-2 border text-left">Jenis</th>
                        <th class="px-4 py-2 border text-left">Visibility</th>
                        <th class="px-4 py-2 border text-left">Status</th>
                        <th class="px-4 py-2 border text-left">Kadaluarsa</th>
                        <th class="px-4 py-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $job->title }}</td>
                            <td class="px-4 py-2 border">{{ $job->job_type ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($job->visibility) }}</td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded text-white {{ $job->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">{{ $job->expired_at ? $job->expired_at->format('d-m-Y') : '-' }}</td>
                            <td class="px-4 py-2 border text-center">
                                <form action="{{ route('admin.jobs.destroy', $job->job_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 bg-gray-100 border border-gray-300 rounded text-center">
            Belum ada lowongan kerja. <a href="{{ route('admin.jobs.create') }}" class="text-blue-500 underline">Buat sekarang</a>
        </div>
    @endif
</div>
@endsection
