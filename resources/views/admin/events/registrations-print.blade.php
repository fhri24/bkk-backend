@extends('layouts.admin')

@section('title', 'Export Peserta Acara')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Export Peserta Acara</h1>
                    <p class="text-slate-600 mt-2">Cetak atau simpan daftar peserta sesuai event yang dipilih.</p>
                    @if($selectedEvent)
                        <p class="text-sm text-slate-500 mt-2">Acara: <span class="font-semibold">{{ $selectedEvent->title }}</span></p>
                    @endif
                </div>
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-print"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Nama</th>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Email</th>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Telepon</th>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Institusi</th>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Posisi</th>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Status</th>
                            <th class="px-5 py-4 text-left font-semibold text-slate-700">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                            <tr class="border-b border-slate-200">
                                <td class="px-5 py-4 text-slate-800">{{ $reg->name }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ $reg->email }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ $reg->phone }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ $reg->institution ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ $reg->position ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ ucfirst($reg->status) }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ $reg->registered_at?->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500">Tidak ada data registrasi untuk acara ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        if (window.location.search.includes('print=true')) {
            window.print();
        }
    });
</script>
@endsection
