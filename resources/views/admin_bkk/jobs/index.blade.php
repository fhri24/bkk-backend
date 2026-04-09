@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manajemen Lowongan Kerja</h1>
        <a href="{{ route('admin.jobs.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Tambah Lowongan
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ $message }}
        </div>
    @endif

    @if ($jobs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded">
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
