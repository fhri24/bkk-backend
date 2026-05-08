@extends('layouts.admin')

@section('title', 'Daftar Alumni - Admin BKK')
@section('page_title', 'Daftar Alumni')

@section('content')

{{-- Alert Success / Error --}}
@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-2">
    <i class="fas fa-times-circle"></i> {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

    {{-- Header: judul + tombol import + filter tahun --}}
    <div class="p-6 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-users text-green-600 mr-3"></i> Daftar Alumni
        </h3>
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Filter Tahun Lulus --}}
            <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()"
                    class="text-sm border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Angkatan</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                            Angkatan {{ $yr }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Tombol Import --}}
            <button onclick="document.getElementById('modalImport').classList.remove('hidden')"
                class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-excel"></i> Import Excel
            </button>
        </div>
    </div>

    {{-- Tabel Alumni --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">NIPD</th>
                    <th class="px-4 py-3 text-left">JK</th>
                    <th class="px-4 py-3 text-left">NISN</th>
                    <th class="px-4 py-3 text-left">Jurusan</th>
                    <th class="px-4 py-3 text-left">Tahun Lulus</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($students as $i => $student)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 text-slate-500">
                        {{ $students->firstItem() + $i }}
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-800">
                        {{ $student->full_name }}
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        {{-- NIPD = NIS di sistem --}}
                        {{ $student->nis ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($student->gender === 'L')
                            <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">L</span>
                        @elseif($student->gender === 'P')
                            <span class="px-2 py-0.5 text-xs rounded-full bg-pink-100 text-pink-700">P</span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        {{-- NISN disimpan di kolom nisn --}}
                        {{ $student->nisn ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        {{ $student->major ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 font-medium">
                            {{ $student->graduation_year }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.students.show', $student->student_id) }}"
                           class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-slate-400 py-12">
                        <i class="fas fa-inbox text-3xl mb-2 block opacity-40"></i>
                        Belum ada data alumni
                        @if(request('year'))
                            <span class="block text-sm mt-1">untuk angkatan {{ request('year') }}</span>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-slate-200">
        {{ $students->appends(request()->query())->links() }}
    </div>
</div>

{{-- ===== MODAL IMPORT EXCEL ===== --}}
<div id="modalImport"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-file-excel text-green-600"></i> Import Data Alumni
            </h4>
            <button onclick="document.getElementById('modalImport').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Tahun Lulus --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Tahun Lulus <span class="text-red-500">*</span>
                </label>
                <input type="number" name="graduation_year"
                    placeholder="Contoh: 2025"
                    min="2000" max="{{ date('Y') + 1 }}"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    required>
                <p class="text-xs text-slate-400 mt-1">Isi tahun lulus angkatan yang akan diimport.</p>
            </div>

            {{-- Upload File --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    File Excel (.xlsx / .xls) <span class="text-red-500">*</span>
                </label>
                <input type="file" name="excel_file" accept=".xlsx,.xls"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    required>
                <p class="text-xs text-slate-400 mt-1">
                    Format: file Excel dari Dapodik (kolom: No, Nama, NIPD, JK, NISN, Rombel Saat Ini).
                </p>
            </div>

            {{-- Info Box --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-700 space-y-1">
                <p class="font-semibold"><i class="fas fa-info-circle mr-1"></i>Catatan:</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    <li>Data lama angkatan lain <strong>tidak akan terhapus</strong>.</li>
                    <li>Data duplikat (NISN + tahun sama) akan otomatis dilewati.</li>
                    <li>Kolom "Rombel Saat Ini" akan diambil nama jurusannya (RPL, TKJ, dll).</li>
                </ul>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-2">
                <button type="button"
                    onclick="document.getElementById('modalImport').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 text-sm bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-upload"></i> Import Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@endsection