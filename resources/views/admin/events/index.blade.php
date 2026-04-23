@extends('layouts.admin')

@section('title', 'Manajemen Acara / Event')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i> Manajemen Acara
                </h1>
                <p class="text-slate-600 mt-1">Kelola daftar acara unggulan, workshop, dan seminar untuk siswa.</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold transition flex items-center shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Acara Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 uppercase text-xs font-bold border-b border-slate-200">
                            <th class="px-6 py-4">Judul Acara</th>
                            <th class="px-6 py-4">Kategori & Lokasi</th>
                            <th class="px-6 py-4">Pelaksanaan</th>
                            <th class="px-6 py-4">Kapasitas</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $event->title }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $event->organizer }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">{{ $event->category }}</span><br>
                                <span class="mt-1 block text-xs"><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i>{{ Str::limit($event->location, 20) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $event->start_date->format('d M Y, H:i') }}<br>
                                <span class="text-xs text-slate-400">s/d {{ $event->end_date->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $event->capacity }} Orang</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $event->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $event->is_published ? 'Publik' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="bg-amber-100 text-amber-700 p-2 rounded hover:bg-amber-200 transition" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-700 p-2 rounded hover:bg-red-200 transition" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-500">Belum ada acara yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection