@extends('layouts.admin')

@section('title', 'Detail Penilaian Industri')
@section('page_title', 'Detail Penilaian Industri')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.tracer.industri') }}" class="text-blue-600 hover:text-blue-700 font-bold inline-flex items-center">
        <i class="fas fa-chevron-left mr-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- User / Akun Perusahaan -->
<div class="lg:col-span-1 bg-white rounded-2xl shadow-lg p-6 border-t-4 border-blue-600">
    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
        <i class="fas fa-user text-blue-600 mr-2"></i> Akun Perusahaan
    </h3>
    <div class="space-y-4">
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase">Nama Akun</p>
            <p class="text-slate-800 font-semibold">{{ $industryTracer->user->name ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase">Email</p>
            <p class="text-slate-700">{{ $industryTracer->user->email ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase">Tanggal Daftar</p>
            <p class="text-slate-700">
                {{ $industryTracer->user->created_at?->format('d M Y') ?? '-' }}
            </p>
        </div>
    </div>
</div>

    <!-- Company & Respondent Info Card -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border-t-4 border-green-600">
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-building text-green-600 mr-2"></i> Informasi Perusahaan & Responden</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Nama Perusahaan</p>
                <p class="text-slate-800 font-semibold">{{ $industryTracer->nama_perusahaan }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Jenis Perusahaan</p>
                <span class="inline-block bg-slate-100 text-slate-700 text-sm font-bold px-3 py-1 rounded-lg">
                    {{ $industryTracer->jenis_perusahaan }}
                </span>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-bold text-slate-500 uppercase">Alamat Perusahaan</p>
                <p class="text-slate-700">{{ $industryTracer->alamat_perusahaan }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-bold text-slate-500 uppercase">Bisnis Utama</p>
                <p class="text-slate-700">{{ $industryTracer->bisnis_utama }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Nama Responden</p>
                <p class="text-slate-800 font-semibold">{{ $industryTracer->nama_responden }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Jabatan Responden</p>
                <p class="text-slate-700">{{ $industryTracer->jabatan_responden }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-bold text-slate-500 uppercase">Email Responden</p>
                <p class="text-slate-700">{{ $industryTracer->email_responden }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Ratings Section -->
<div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-star text-yellow-500 mr-3"></i> Penilaian Kemampuan Lulusan</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @php
            $ratings = [
                ['label' => 'Integritas (Etika dan Moral)', 'value' => $industryTracer->nilai_integritas],
                ['label' => 'Keahlian Berdasarkan Bidang Ilmu', 'value' => $industryTracer->nilai_keahlian],
                ['label' => 'Keterampilan Bahasa Inggris', 'value' => $industryTracer->nilai_bahasa_inggris],
                ['label' => 'Penggunaan Teknologi & TIK', 'value' => $industryTracer->nilai_teknologi],
                ['label' => 'Keterampilan Komunikasi', 'value' => $industryTracer->nilai_komunikasi],
                ['label' => 'Kerja Sama Tim', 'value' => $industryTracer->nilai_kerjasama],
                ['label' => 'Pemikiran Analitis & Inovasi', 'value' => $industryTracer->nilai_analitis],
                ['label' => 'Kemampuan Kepemimpinan', 'value' => $industryTracer->nilai_kepemimpinan],
                ['label' => 'Kemampuan Bekerja Dibawah Tekanan', 'value' => $industryTracer->nilai_tekanan],
            ];
        @endphp

        @foreach($ratings as $rating)
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-slate-800">{{ $rating['label'] }}</span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-bold text-sm
                        {{ $rating['value'] >= 4 ? 'bg-green-100 text-green-700' : ($rating['value'] >= 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        <i class="fas fa-star text-xs"></i> {{ $rating['value'] }}/5
                    </span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: {{ ($rating['value']/5)*100 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Average Score Section -->
<div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-slate-600 uppercase">Rata-rata Penilaian Keseluruhan</p>
            <p class="text-4xl font-black text-blue-600">{{ $industryTracer->rata_rata }}/5</p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-sm
                {{ $industryTracer->rata_rata >= 4 ? 'bg-green-100 text-green-700' : ($industryTracer->rata_rata >= 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                <i class="fas fa-check-circle"></i>
                @if($industryTracer->rata_rata >= 4)
                    Sangat Baik
                @elseif($industryTracer->rata_rata >= 3)
                    Baik
                @else
                    Perlu Peningkatan
                @endif
            </span>
        </div>
    </div>
</div>

<!-- Suggestions Section -->
@if($industryTracer->saran)
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-lightbulb text-amber-500 mr-2"></i> Masukan & Saran</h3>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-slate-800">
            {{ $industryTracer->saran }}
        </div>
    </div>
@endif

<!-- Metadata Section -->
<div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase">Tanggal Pengisian</p>
            <p class="text-slate-800 font-semibold">{{ $industryTracer->created_at->format('d F Y H:i') }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase">Terakhir Diperbarui</p>
            <p class="text-slate-800 font-semibold">{{ $industryTracer->updated_at->format('d F Y H:i') }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase">Aksi</p>
            <div class="flex gap-2 mt-2">
                <form action="{{ route('admin.tracer.industri.destroy', $industryTracer) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg font-bold transition">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
