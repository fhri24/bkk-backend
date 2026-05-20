@extends('layouts.admin')

@section('title', 'Daftar Alumni - Admin BKK')
@section('page_title', 'Daftar Alumni')

@section('content')

    {{-- Alert Success / Error --}}
    @if (session('success'))
        <div
            class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-2 shadow-sm animate-fade-in">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @elseif(session('error'))
        <div
            class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-2 shadow-sm animate-fade-in">
            <i class="fas fa-times-circle text-lg"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- WIDGET CARDS SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Ringkasan Per Jurusan --}}
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-slate-500"></i> Ringkasan Per Jurusan
                </h4>
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-md font-medium">Total Grup:
                    {{ $summaryByMajor->count() }}</span>
            </div>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                @forelse($summaryByMajor as $sm)
                    <div
                        class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-100 hover:border-slate-200 transition">
                        <div class="flex items-center gap-3">
                            <span
                                class="font-semibold text-slate-800 text-sm bg-white border border-slate-200 w-10 h-8 flex items-center justify-center rounded-md shadow-sm">{{ $sm->major }}</span>
                            <span class="text-xs font-medium text-slate-500">{{ $sm->total }} Alumni</span>
                        </div>
                        <form action="{{ route('admin.students.destroy.by.major') }}" method="POST"
                            onsubmit="return confirm('APAKAH ANDA YAKIN? Semua data alumni dan akun pengguna untuk JURUSAN [{{ $sm->major }}] akan dihapus permanen dari sistem BKK!')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="major" value="{{ $sm->major }}">
                            <button type="submit"
                                class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus Grup
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada kelompok data jurusan.</p>
                @endforelse
            </div>
        </div>

        {{-- Ringkasan Per Tahun Lulus (FIX: ganti dari "Angkatan") --}}
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-slate-500"></i> Ringkasan Per Tahun Lulus
                </h4>
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-md font-medium">Total Tahun Lulus:
                    {{ $summaryByYear->count() }}</span>
            </div>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                @forelse($summaryByYear as $sy)
                    <div
                        class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-100 hover:border-slate-200 transition">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-800">Lulus {{ $sy->graduation_year }}</span>
                            <span
                                class="text-xs font-medium bg-green-50 text-green-700 border border-green-100 px-2 py-0.5 rounded-full">{{ $sy->total }}
                                Alumni</span>
                        </div>
                        <form action="{{ route('admin.students.destroy.by.year') }}" method="POST"
                            onsubmit="return confirm('APAKAH ANDA YAKIN? Semua data alumni dan akun pengguna dengan TAHUN LULUS [{{ $sy->graduation_year }}] akan dihapus permanen dari sistem BKK!')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="graduation_year" value="{{ $sy->graduation_year }}">
                            <button type="submit"
                                class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus Grup
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada kelompok data tahun lulus.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MAIN DATA --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">

        <div class="p-6 border-b border-slate-200 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                <i class="fas fa-users text-green-600 mr-3 bg-green-50 p-2.5 rounded-lg border border-green-100"></i> Daftar
                Utama Alumni
            </h3>

            <div class="flex items-center gap-3 flex-wrap">
                <form method="GET" action="{{ route('admin.students.index') }}"
                    class="flex items-center gap-2 flex-wrap md:flex-nowrap">
                    {{-- Filter Tahun Lulus --}}
                    <select name="year" onchange="this.form.submit()"
                        class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 cursor-pointer text-slate-700 font-medium">
                        <option value="">Semua Tahun Lulus</option>
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                                Lulus {{ $yr }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Jurusan --}}
                    <select name="major" onchange="this.form.submit()"
                        class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 cursor-pointer text-slate-700 font-medium">
                        <option value="">Semua Jurusan</option>
                        @foreach ($availableMajors as $mj)
                            <option value="{{ $mj }}" {{ request('major') == $mj ? 'selected' : '' }}>
                                Jurusan {{ $mj }}
                            </option>
                        @endforeach
                    </select>

                    @if (request('year') || request('major'))
                        <a href="{{ route('admin.students.index') }}"
                            class="text-xs text-slate-500 hover:text-red-500 underline font-medium px-1">
                            Reset Filter
                        </a>
                    @endif
                </form>

                <button onclick="document.getElementById('modalImport').classList.remove('hidden')"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                    <i class="fas fa-file-excel text-base"></i> Import Excel
                </button>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead
                    class="bg-slate-50 text-slate-600 uppercase text-xs font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5 text-left w-12">No</th>
                        <th class="px-5 py-3.5 text-left">Nama Lengkap</th>
                        <th class="px-5 py-3.5 text-left">NIPD (NIS)</th>
                        <th class="px-5 py-3.5 text-center w-16">JK</th>
                        <th class="px-5 py-3.5 text-left">NISN</th>
                        <th class="px-5 py-3.5 text-left">Jurusan</th>
                        <th class="px-5 py-3.5 text-left">Tahun Lulus</th>
                        <th class="px-5 py-3.5 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $i => $student)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-slate-500 font-medium">
                                {{ $students->firstItem() + $i }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $student->full_name }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">
                                {{ $student->nis ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if ($student->gender === 'L')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-bold rounded-md bg-blue-50 text-blue-600 border border-blue-100">L</span>
                                @elseif($student->gender === 'P')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-bold rounded-md bg-pink-50 text-pink-600 border border-pink-100">P</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">
                                {{ $student->nisn ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-medium">
                                {{ $student->major ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="px-2.5 py-0.5 text-xs rounded-full bg-green-50 text-green-700 font-semibold border border-green-100">
                                    {{ $student->graduation_year }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.students.show', $student->student_id) }}"
                                        class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <span class="text-slate-300">|</span>
                                    <form action="{{ route('admin.students.destroy', $student->student_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus data alumni atas nama [{{ $student->full_name }}]? Seluruh data akun terkait juga akan terhapus.')"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-semibold hover:underline">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-slate-400 py-16 bg-slate-50/30">
                                <i class="fas fa-inbox text-4xl mb-3 block opacity-30 text-slate-500"></i>
                                <span class="font-medium block text-slate-500 text-sm">Tidak Menemukan Data Alumni</span>
                                @if (request('year') || request('major'))
                                    <span class="block text-xs text-slate-400 mt-1">
                                        untuk pencarian filter:
                                        @if (request('year'))
                                            <strong>[Tahun Lulus {{ request('year') }}]</strong>
                                        @endif
                                        @if (request('major'))
                                            <strong>[Jurusan {{ request('major') }}]</strong>
                                        @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- MODAL IMPORT EXCEL --}}
    <div id="modalImport"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-transform border border-slate-100">

            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h4 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-file-excel text-green-600 text-lg"></i> Import Data Alumni
                </h4>
                <button onclick="document.getElementById('modalImport').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition bg-white border border-slate-200 w-7 h-7 flex items-center justify-center rounded-full hover:shadow-sm">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Tahun Lulus <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="graduation_year" placeholder="Contoh: 2025" min="2000"
                        max="{{ date('Y') + 1 }}"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <p class="text-xs text-slate-400 mt-1">Isi tahun lulus angkatan yang akan diimport.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        File Excel (.xlsx / .xls) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="excel_file" accept=".xlsx,.xls"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer"
                        required>
                    <p class="text-xs text-slate-400 mt-1">
                        Format: file Excel dari Dapodik (kolom: No, Nama, NIPD, JK, NISN, Rombel Saat Ini).
                    </p>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-700 space-y-1">
                    <p class="font-bold flex items-center gap-1"><i class="fas fa-info-circle"></i> Catatan Penting:</p>
                    <ul class="list-disc pl-4 space-y-0.5">
                        <li>Data lama angkatan lain <strong>tidak akan terhapus</strong>.</li>
                        <li>Data duplikat (NISN + tahun sama) akan otomatis dilewati.</li>
                        <li>Kolom "Rombel Saat Ini" akan diambil nama jurusannya (RPL, TKJ, dll).</li>
                    </ul>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalImport').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition flex items-center gap-2 shadow-sm">
                        <i class="fas fa-upload"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

@endsection
