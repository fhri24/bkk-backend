@extends('layouts.admin')

@section('title', 'Daftar Peserta Acara')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                Daftar Peserta Acara
            </h1>
            <p class="text-slate-600 mt-2">Kelola pendaftaran peserta acara</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
            <form method="GET" class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Acara</label>
                    <select name="event_slug" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Acara --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_slug') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Status --</option>
                        <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Hadir</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </form>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Total Registrasi</div>
                <div class="text-3xl font-bold text-blue-600 mt-2">{{ $registrations->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Terdaftar</div>
                <div class="text-3xl font-bold text-blue-600 mt-2">{{ $registrations->where('status', 'registered')->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Dikonfirmasi</div>
                <div class="text-3xl font-bold text-green-600 mt-2">{{ $registrations->where('status', 'confirmed')->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="text-slate-600 text-sm font-medium">Hadir</div>
                <div class="text-3xl font-bold text-purple-600 mt-2">{{ $registrations->where('status', 'attended')->count() }}</div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Telepon</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Institusi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Tgl Daftar</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($registrations as $reg)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $reg->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $reg->email }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $reg->phone }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $reg->institution ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($reg->status === 'registered') bg-blue-100 text-blue-800
                                        @elseif($reg->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($reg->status === 'attended') bg-purple-100 text-purple-800
                                        @elseif($reg->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-slate-100 text-slate-800 @endif
                                    ">
                                        @if($reg->status === 'registered') Terdaftar
                                        @elseif($reg->status === 'confirmed') Dikonfirmasi
                                        @elseif($reg->status === 'attended') Hadir
                                        @elseif($reg->status === 'cancelled') Dibatalkan
                                        @else {{ $reg->status }} @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $reg->registered_at ? $reg->registered_at->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="openEditModal({{ $reg->event_registration_id }}, '{{ $reg->status }}', '{{ addslashes($reg->admin_notes ?? '') }}')" class="text-blue-600 hover:text-blue-800 font-bold" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.event-registrations.destroy', $reg->event_registration_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-600 hover:text-red-800 font-bold" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                    <i class="fas fa-inbox text-3xl opacity-50 mb-2 block"></i>
                                    Tidak ada data registrasi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold">Edit Registrasi</h2>
            <button onclick="closeEditModal()" class="text-white text-2xl font-bold hover:text-blue-200">&times;</button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" id="statusSelect" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="registered">Terdaftar</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="attended">Hadir</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Admin</label>
                <textarea name="admin_notes" id="adminNotesInput" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, status, notes) {
        document.getElementById('editForm').action = `/admin/event-registrations/${id}`;
        document.getElementById('statusSelect').value = status;
        document.getElementById('adminNotesInput').value = notes;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }
</script>
@endsection
