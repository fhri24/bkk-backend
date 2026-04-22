@extends('layouts.app')

@section('title', 'Detail Lowongan - BKK SMKN 1 Garut')

@section('extra_css')
<style>
    .detail-header {
        background: linear-gradient(135deg, #001f3f 0%, #2563eb 100%);
    }
    .requirement-list {
        list-style-type: none;
    }
    .requirement-list li::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #10b981;
        margin-right: 12px;
    }
    .sticky-card {
        position: sticky;
        top: 100px;
    }
</style>
@endsection

@section('content')
{{-- Breadcrumb --}}
<div class="bg-slate-50 border-b border-slate-200 py-4">
    <div class="container mx-auto px-6">
        <div class="flex items-center gap-2 text-sm font-semibold text-slate-500">
            <a href="{{ route('student.lowongan') }}" class="hover:text-blue-600 transition">Lowongan</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-slate-900">{{ $job->title }}</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- Kolom Kiri: Konten Utama --}}
        <div class="lg:w-2/3">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                {{-- Header Detail --}}
                <div class="p-8 md:p-12 border-b border-slate-100">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                        <div class="flex gap-6 items-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm">
                                <i class="fas fa-briefcase text-4xl text-blue-600"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001f3f] mb-2 leading-tight">{{ $job->title }}</h1>
                                <div class="flex items-center gap-2 text-blue-600 font-bold">
                                    <i class="fas fa-building"></i>
                                    <span>{{ $job->company->company_name ?? 'Mitra Industri' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Bookmark Aktif --}}
                        @auth
                            <form action="{{ route('student.lowongan.save', $job->job_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-lg {{ Auth::user()->savedJobs->contains('job_id', $job->job_id) ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-300 hover:text-blue-600 border border-slate-100' }}">
                                    <i class="fas fa-bookmark text-2xl"></i>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="p-8 md:p-12">
                    <div class="mb-10">
                        <h2 class="text-2xl font-bold text-[#001f3f] mb-6 flex items-center gap-3">
                            <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                            Deskripsi Pekerjaan
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    @if($job->requirements)
                    <div class="mb-10">
                        <h2 class="text-2xl font-bold text-[#001f3f] mb-6 flex items-center gap-3">
                            <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                            Kualifikasi & Persyaratan
                        </h2>
                        <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                            <div class="prose prose-slate max-w-none text-slate-600 font-medium">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Sidebar Info --}}
        <div class="lg:w-1/3">
            <div class="sticky-card space-y-6">
                {{-- Quick Info Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <h3 class="text-xl font-bold text-[#001f3f] mb-6">Informasi Tambahan</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Tipe Pekerjaan</p>
                                <p class="text-slate-900 font-bold">{{ $job->job_type }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Lokasi Penempatan</p>
                                <p class="text-slate-900 font-bold">{{ $job->location ?? 'Garut & Sekitarnya' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Batas Akhir</p>
                                <p class="text-slate-900 font-bold text-red-600">
                                    {{ $job->expired_at ? $job->expired_at->format('d M Y') : 'Hingga Terpenuhi' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-10 space-y-3">
                        @auth
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all transform active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i>
                                Lamar Sekarang
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold transition-all text-center block">
                                Login untuk Melamar
                            </a>
                        @endauth
                        
                        <a href="{{ route('student.lowongan') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-4 rounded-2xl font-bold transition-all text-center block">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                {{-- Safety Tip --}}
                <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                    <div class="flex gap-4">
                        <i class="fas fa-shield-alt text-amber-500 text-xl mt-1"></i>
                        <div>
                            <p class="text-sm font-bold text-amber-900 mb-1">Tips Keamanan</p>
                            <p class="text-xs text-amber-800 leading-relaxed">
                                BKK SMKN 1 Garut tidak pernah memungut biaya apapun dalam proses rekrutmen. Laporkan jika ada indikasi penipuan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection