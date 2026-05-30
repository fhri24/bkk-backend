@extends('layouts.admin')

@section('title', 'Detail Laporan Alumni - BKK SMKN 1 Garut')
@section('page_title', 'Detail Laporan Alumni')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Detail Data Alumni</h2>
            <p class="text-slate-500 text-sm mt-1">Informasi lengkap terkait status karir dan data diri alumni</p>
        </div>
        <a href="{{ route('admin.tracer.alumni') }}" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Data Pribadi & Akademik -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">
                    <i class="fas fa-user-circle text-blue-500 mr-2"></i>Data Pribadi & Akademik
                </h3>
                
                <table class="w-full text-sm">
                    <tbody>
                        <tr>
                            <td class="py-3 text-slate-500 w-1/3">Nama Lengkap</td>
                            <td class="py-3 font-semibold text-slate-800">{{ $tracerStudy->nama_lengkap }}</td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">NIK</td>
                            <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->nik ?? '-' }}</td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">Tempat, Tanggal Lahir</td>
                            <td class="py-3 font-medium text-slate-700">
                                {{ $tracerStudy->tempat_lahir ?? '-' }}, 
                                {{ $tracerStudy->tanggal_lahir ? \Carbon\Carbon::parse($tracerStudy->tanggal_lahir)->format('d F Y') : '-' }}
                            </td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">Alamat Lengkap</td>
                            <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->alamat_lengkap ?? '-' }}</td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">No. HP / Email</td>
                            <td class="py-3 font-medium text-slate-700">
                                {{ $tracerStudy->no_hp ?? '-' }} / {{ $tracerStudy->email ?? '-' }}
                            </td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">Tahun Lulus</td>
                            <td class="py-3 font-semibold text-slate-800">{{ $tracerStudy->tahun_lulus ?? '-' }}</td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">Jurusan</td>
                            <td class="py-3 font-semibold text-slate-800">{{ $tracerStudy->jurusan ?? '-' }}</td>
                        </tr>
                        <tr class="border-t border-slate-50">
                            <td class="py-3 text-slate-500">Tanggal Mengisi</td>
                            <td class="py-3 font-medium text-slate-700">
                                {{ $tracerStudy->created_at->format('d F Y H:i') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Status Karir -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex justify-between items-center">
                    <span>
                        <i class="fas fa-briefcase text-green-500 mr-2"></i>Status Saat Ini
                    </span>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $tracerStudy->status_saat_ini }}
                    </span>
                </h3>
                
                <table class="w-full text-sm">
                    <tbody>
                        @if($tracerStudy->status_saat_ini === 'Bekerja')
                            <tr>
                                <td class="py-3 text-slate-500 w-1/3">Lokasi Kerja</td>
                                <td class="py-3 font-semibold text-slate-800">{{ $tracerStudy->lokasi_kerja ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Nama Perusahaan</td>
                                <td class="py-3 font-semibold text-blue-600">{{ $tracerStudy->nama_instansi ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Alamat Perusahaan</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->alamat_perusahaan ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Posisi / Jabatan</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->posisi_jabatan ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Tanggal Mulai</td>
                                <td class="py-3 font-medium text-slate-700">
                                    {{ $tracerStudy->tmt_bekerja ? \Carbon\Carbon::parse($tracerStudy->tmt_bekerja)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Range Gaji</td>
                                <td class="py-3 font-semibold text-green-600">
                                    {{ $tracerStudy->range_gaji ?? ($tracerStudy->pendapatan_bulanan ? 'Rp ' . number_format($tracerStudy->pendapatan_bulanan, 0, ',', '.') : '-') }}
                                </td>
                            </tr>
                        @elseif($tracerStudy->status_saat_ini === 'Kuliah')
                            <tr>
                                <td class="py-3 text-slate-500 w-1/3">Status PT</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->status_pt ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Nama Perguruan Tinggi</td>
                                <td class="py-3 font-semibold text-blue-600">{{ $tracerStudy->nama_pt ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Jurusan / Prodi</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->jurusan_pt ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Jenjang</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->jenjang_kuliah ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Tanggal Mulai</td>
                                <td class="py-3 font-medium text-slate-700">
                                    {{ $tracerStudy->tmt_kuliah ? \Carbon\Carbon::parse($tracerStudy->tmt_kuliah)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                        @elseif($tracerStudy->status_saat_ini === 'Wirausaha')
                            <tr>
                                <td class="py-3 text-slate-500 w-1/3">Nama Usaha</td>
                                <td class="py-3 font-semibold text-blue-600">{{ $tracerStudy->nama_usaha ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Status Usaha</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->status_usaha ?? '-' }}</td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Tanggal Mulai</td>
                                <td class="py-3 font-medium text-slate-700">
                                    {{ $tracerStudy->tmt_wirausaha ? \Carbon\Carbon::parse($tracerStudy->tmt_wirausaha)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Omzet Bulanan</td>
                                <td class="py-3 font-semibold text-green-600">{{ $tracerStudy->omzet_per_bulan ?? '-' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="py-3 text-slate-500 w-1/3">Detail Kegiatan</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->detail_kegiatan ?? '-' }}</td>
                            </tr>
                            @if($tracerStudy->detail_kegiatan_lainnya)
                            <tr class="border-t border-slate-50">
                                <td class="py-3 text-slate-500">Keterangan Tambahan</td>
                                <td class="py-3 font-medium text-slate-700">{{ $tracerStudy->detail_kegiatan_lainnya }}</td>
                            </tr>
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
