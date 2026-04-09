@extends('layouts.app')

@section('title', 'Detail Lowongan - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-12">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-[#001f3f] mb-2">{{ $job->title }}</h1>
                <p class="text-2xl text-slate-600 font-semibold">{{ $job->company->company_name ?? 'Company' }}</p>
            </div>
            <button class="text-4xl text-slate-300 hover:text-red-500 transition">
                <i class="fas fa-bookmark"></i>
            </button>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-12">
            <div class="p-6 bg-blue-50 rounded-2xl text-center">
                <p class="text-sm text-slate-600 font-semibold">Jenis Pekerjaan</p>
                <p class="text-2xl font-bold text-blue-600">{{ $job->job_type }}</p>
            </div>
            <div class="p-6 bg-green-50 rounded-2xl text-center">
                <p class="text-sm text-slate-600 font-semibold">Visibilitas</p>
                <p class="text-2xl font-bold text-green-600">{{ ucfirst($job->visibility) }}</p>
            </div>
            <div class="p-6 bg-purple-50 rounded-2xl text-center">
                <p class="text-sm text-slate-600 font-semibold">Kadaluarsa</p>
                <p class="text-2xl font-bold text-purple-600">{{ $job->expired_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="divider-line mb-8"></div>

        <h2 class="text-2xl font-bold text-[#001f3f] mb-6">Deskripsi Pekerjaan</h2>
        <div class="prose prose-sm mb-12">
            <p>{{ $job->description }}</p>
        </div>

        @if($job->requirements)
            <h2 class="text-2xl font-bold text-[#001f3f] mb-6">Persyaratan</h2>
            <div class="prose prose-sm mb-12">
                <p>{{ $job->requirements }}</p>
            </div>
        @endif

        @if($job->location)
            <div class="mb-12 p-6 bg-slate-100 rounded-2xl">
                <h3 class="font-bold text-slate-700 mb-2"><i class="fas fa-map-marker-alt mr-2"></i>Lokasi</h3>
                <p class="text-slate-600">{{ $job->location }}</p>
            </div>
        @endif

        <div class="flex gap-4">
            <button class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition">
                <i class="fas fa-envelope mr-2"></i>Lamar Sekarang
            </button>
            <a href="{{ route('student.lowongan') }}" class="flex-1 bg-slate-200 text-slate-900 py-4 rounded-xl font-bold hover:bg-slate-300 transition text-center">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
