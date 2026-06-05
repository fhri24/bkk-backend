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
                                <button
                                    onclick="showDetail(
                                        '{{ addslashes($event->title) }}',
                                        '{{ addslashes($event->organizer) }}',
                                        '{{ addslashes($event->category) }}',
                                        '{{ addslashes($event->location) }}',
                                        '{{ $event->start_date->format('d M Y, H:i') }}',
                                        '{{ $event->end_date->format('d M Y, H:i') }}',
                                        {{ $event->capacity }},
                                        {{ $event->is_published ? 'true' : 'false' }},
                                        '{{ addslashes($event->description) }}'
                                    )"
                                    class="bg-blue-100 text-blue-700 p-2 rounded hover:bg-blue-200 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
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

{{-- Modal Detail Acara --}}
<div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">

        {{-- Header --}}
        <div class="flex justify-between items-start p-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-alt text-blue-400 text-lg"></i>
                </div>
                <div>
                    <h2 id="m-title" class="text-base font-bold text-slate-900"></h2>
                    <p id="m-organizer" class="text-xs text-slate-500 mt-0.5"></p>
                </div>
            </div>
            <button onclick="closeDetail()" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Rows --}}
        <div class="divide-y divide-slate-100">
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-tag w-4 text-slate-400"></i> Kategori</span>
                <span id="m-category" class="text-sm font-bold text-slate-700"></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-building w-4 text-slate-400"></i> Penyelenggara</span>
                <span id="m-organizer-row" class="text-sm font-bold text-slate-700"></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-map-marker-alt w-4 text-slate-400"></i> Lokasi</span>
                <span id="m-location" class="text-sm font-bold text-slate-700"></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-play-circle w-4 text-slate-400"></i> Mulai</span>
                <span id="m-start" class="text-sm font-bold text-slate-700"></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-stop-circle w-4 text-slate-400"></i> Selesai</span>
                <span id="m-end" class="text-sm font-bold text-slate-700"></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-users w-4 text-slate-400"></i> Kapasitas</span>
                <span id="m-capacity" class="text-sm font-bold text-slate-700"></span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-sm text-slate-500 flex items-center gap-2"><i class="fas fa-globe w-4 text-slate-400"></i> Status</span>
                <span id="m-status" class="text-sm font-bold"></span>
            </div>
            <div class="px-5 py-3">
                <p class="text-sm text-slate-500 flex items-center gap-2 mb-2"><i class="fas fa-align-left w-4 text-slate-400"></i> Deskripsi</p>
                <p id="m-description" class="text-sm text-slate-700 leading-relaxed whitespace-pre-line"></p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('extra_js')
<script>
function showDetail(title, organizer, category, location, start, end, capacity, isPublished, description) {
    document.getElementById('m-title').textContent        = title;
    document.getElementById('m-organizer').textContent    = organizer;
    document.getElementById('m-organizer-row').textContent = organizer;
    document.getElementById('m-category').textContent     = category || 'Umum';
    document.getElementById('m-location').textContent     = location || '-';
    document.getElementById('m-start').textContent        = start + ' WIB';
    document.getElementById('m-end').textContent          = end + ' WIB';
    document.getElementById('m-capacity').textContent     = capacity + ' Orang';
    document.getElementById('m-description').textContent  = description || 'Tidak ada deskripsi.';

    const statusEl = document.getElementById('m-status');
    if (isPublished) {
        statusEl.textContent = '🌐 Publik';
        statusEl.className   = 'text-sm font-bold px-2 py-1 rounded-full bg-green-100 text-green-700';
    } else {
        statusEl.textContent = '🔒 Draft';
        statusEl.className   = 'text-sm font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-600';
    }

    const modal = document.getElementById('modalDetail');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDetail() {
    const modal = document.getElementById('modalDetail');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('modalDetail').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
</script>
@endsection
