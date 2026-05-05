@extends('layouts.admin')

@section('title', 'Daftar Publik | Admin BKK')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Publik</h1>
            <p class="text-sm text-gray-500 mt-1">Data pengguna publik yang terdaftar</p>
        </div>
        <div class="bg-green-100 text-green-700 text-sm font-semibold px-4 py-2 rounded-xl">
            Total: {{ $dataPublik->total() }} pengguna
        </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-4">#</th>
                        <th class="px-4 py-4">Foto</th>
                        <th class="px-4 py-4">NISN</th>
                        <th class="px-4 py-4">Nama Lengkap</th>
                        <th class="px-4 py-4">Jenis Kelamin</th>
                        <th class="px-4 py-4">Tempat, Tgl Lahir</th>
                        <th class="px-4 py-4">Tahun Lulus</th>
                        <th class="px-4 py-4">No. HP</th>
                        <th class="px-4 py-4">Alamat</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($dataPublik as $i => $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 text-gray-400 font-medium">
                            {{ $dataPublik->firstItem() + $i }}
                        </td>

                        {{-- Foto --}}
                        <td class="px-4 py-4">
                            @if($p->foto_profile)
                                <img src="{{ asset('storage/' . $p->foto_profile) }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100"
                                    alt="foto {{ $p->nama_lengkap }}">
                            @else
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold text-sm ring-2 ring-gray-100">
                                    {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-4 font-mono text-gray-700">{{ $p->nisn }}</td>

                        {{-- Nama + email --}}
                        <td class="px-4 py-4">
                            <p class="font-semibold text-gray-800">{{ $p->nama_lengkap }}</p>
                            @if($p->user)
                                <p class="text-xs text-gray-400">{{ $p->user->email }}</p>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            @if($p->jenis_kelamin === 'L')
                                <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                    Laki-laki
                                </span>
                            @else
                                <span class="bg-pink-100 text-pink-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                    Perempuan
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-gray-600">
                            {{ $p->tempat_lahir }},
                            {{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') }}
                        </td>

                        <td class="px-4 py-4">
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                {{ $p->tahun_lulus }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-gray-600">{{ $p->no_hp }}</td>

                        <td class="px-4 py-4 text-gray-600 max-w-[160px]">
                            <p class="truncate" title="{{ $p->alamat }}">{{ $p->alamat }}</p>
                        </td>

                        {{-- Aksi Hapus --}}
                        <td class="px-4 py-4 text-center">
                            <form action="{{ route('admin.publik.destroy', $p->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data {{ addslashes($p->nama_lengkap) }}? Akun terkait juga akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-2 rounded-lg transition">
                                    <i class="fas fa-trash text-xs"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i class="fas fa-users text-4xl"></i>
                                <p class="font-medium">Belum ada data publik terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($dataPublik->hasPages())
            <div class="px-4 py-4 border-t border-gray-100">
                {{ $dataPublik->links() }}
            </div>
        @endif
    </div>

</div>
@endsection