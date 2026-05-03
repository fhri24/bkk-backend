@extends('layouts.app')

@section('title', 'Lowongan Tersimpan - BKK SMKN 1 Garut')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 md:px-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-[#001f3f]">Lowongan Tersimpan</h2>
                <p class="text-gray-500 mt-1">Kelola daftar lowongan yang menarik perhatianmu di sini.</p>
            </div>
            <a href="{{ route('public.lowongan') }}"
               class="text-blue-600 hover:underline font-semibold flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Lowongan
            </a>
        </div>

        @if($savedJobs->isEmpty())
            {{-- State Kosong --}}
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                <div class="w-20 h-20 bg-blue-50 text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bookmark text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada lowongan tersimpan</h3>
                <p class="text-gray-500 mb-6">Cari lowongan yang sesuai dengan keahlianmu dan simpan untuk dilihat nanti.</p>
                <a href="{{ route('public.lowongan') }}"
                   class="bg-[#001f3f] text-white px-8 py-3 rounded-full font-bold hover:bg-blue-900 transition">
                    Jelajahi Lowongan
                </a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($savedJobs as $job)
                    @php
                        $companyName = $job->company->company_name ?? 'Perusahaan';
                        if ($job->logo)
                            $logoUrl = Storage::url($job->logo);
                        elseif ($job->company && $job->company->logo)
                            $logoUrl = Storage::url($job->company->logo);
                        else
                            $logoUrl = null;
                    @endphp

                    {{-- ID Card disesuaikan agar bisa dihapus real-time --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4 group hover:shadow-md transition"
                         id="saved-card-{{ $job->job_id }}">

                        {{-- Logo + Info --}}
                        <div class="flex items-center gap-4 w-full">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" class="object-contain w-full h-full" alt="Logo">
                                @else
                                    <span class="text-2xl font-black text-gray-400">
                                        {{ strtoupper(substr($companyName, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition">
                                    {{ $job->title }}
                                </h4>
                                <p class="text-gray-600 font-medium text-sm">{{ $companyName }}</p>
                                <div class="flex flex-wrap items-center gap-4 mt-2 text-xs text-gray-400 font-semibold">
                                    <span><i class="fas fa-map-marker-alt mr-1 text-blue-400"></i> {{ $job->location ?? '-' }}</span>
                                    <span><i class="fas fa-briefcase mr-1 text-blue-400"></i> {{ $job->job_type ?? '-' }}</span>
                                    @if($job->status === 'active')
                                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Aktif</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Ditutup</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center gap-3 w-full md:w-auto justify-end shrink-0">
                            <a href="{{ route('public.lowongan.detail', $job->job_id) }}"
                               class="bg-blue-50 text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-100 transition whitespace-nowrap">
                                Lihat Detail
                            </a>
                            {{-- Tombol Hapus memanggil fungsi global removeSavedJob --}}
                            <button
                                onclick="removeSavedJob({{ $job->job_id }})"
                                class="flex items-center gap-2 text-red-500 hover:text-red-700 font-bold px-4 py-2 rounded-xl border border-red-100 hover:bg-red-50 transition whitespace-nowrap">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('extra_js')
<script>
    // Fungsi Toast untuk notifikasi
    function showToast(msg) {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 right-6 bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-xl text-sm font-bold z-50 animate-zoom-in';
        t.innerText = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }
</script>
@endpush