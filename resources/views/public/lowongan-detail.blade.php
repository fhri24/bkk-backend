@extends('layouts.app')

@section('title', 'Detail Lowongan - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-12">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-[#001f3f] mb-2">{{ $job->title }}</h1>
                <p class="text-2xl text-slate-600 font-semibold">{{ $job->company->company_name ?? 'Perusahaan' }}</p>
            </div>
            {{-- Tombol simpan/bookmark dimatikan untuk publik karena butuh login --}}
            @auth
                <button class="text-4xl text-slate-300 hover:text-red-500 transition">
                    <i class="fas fa-bookmark"></i>
                </button>
            @endauth
        </div>

        <div class="grid grid-cols-3 gap-6 mb-12">
            <div class="p-6 bg-blue-50 rounded-2xl text-center">
                <p class="text-sm text-slate-600 font-semibold">Jenis Pekerjaan</p>
                <p class="text-2xl font-bold text-blue-600">{{ $job->job_type }}</p>
            </div>
            <div class="p-6 bg-green-50 rounded-2xl text-center">
                <p class="text-sm text-slate-600 font-semibold">Status</p>
                <p class="text-2xl font-bold text-green-600">Aktif</p>
            </div>
            <div class="p-6 bg-purple-50 rounded-2xl text-center">
                <p class="text-sm text-slate-600 font-semibold">Kadaluarsa</p>
                {{-- Gunakan format tanggal yang aman --}}
                <p class="text-2xl font-bold text-purple-600">{{ $job->expired_at ? $job->expired_at->format('d M Y') : '-' }}</p>
            </div>
        </div>

        <div class="divider-line mb-8"></div>

        <h2 class="text-2xl font-bold text-[#001f3f] mb-6">Deskripsi Pekerjaan</h2>
        <div class="prose prose-sm mb-12">
            <p>{!! nl2br(e($job->description)) !!}</p>
        </div>

        @if($job->requirements)
            <h2 class="text-2xl font-bold text-[#001f3f] mb-6">Persyaratan</h2>
            <div class="prose prose-sm mb-12">
                <p>{!! nl2br(e($job->requirements)) !!}</p>
            </div>
        @endif

        @if($job->location)
            <div class="mb-12 p-6 bg-slate-100 rounded-2xl">
                <h3 class="font-bold text-slate-700 mb-2"><i class="fas fa-map-marker-alt mr-2"></i>Lokasi</h3>
                <p class="text-slate-600">{{ $job->location }}</p>
            </div>
        @endif

        <div class="flex gap-4">
            {{-- Tombol Lamar mengarahkan ke login jika belum masuk --}}
            @auth
                <button class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition">
                    <i class="fas fa-envelope mr-2"></i>Lamar Sekarang
                </button>
            @else
                <a href="{{ route('login') }}" class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition text-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Melamar
                </a>
            @endauth
            
            {{-- Link kembali diarahkan ke rute publik --}}
            <a href="{{ route('public.lowongan') }}" class="flex-1 bg-slate-200 text-slate-900 py-4 rounded-xl font-bold hover:bg-slate-300 transition text-center">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection