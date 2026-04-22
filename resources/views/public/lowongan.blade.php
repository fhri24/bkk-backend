@extends('layouts.app')

@section('title', 'Lowongan Kerja - BKK SMKN 1 Garut')

@section('extra_css')
<style>
    /* Hero Section dengan Gradient Overlay yang lebih smooth */
    .search-hero {
        background: linear-gradient(135deg, rgba(0, 31, 63, 0.9), rgba(37, 99, 235, 0.8)), 
                    url("https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1920&q=80");
        background-size: cover;
        background-position: center;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Efek kartu agar seragam dengan desain modern */
    .job-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .job-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        border-color: #3b82f6;
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="search-hero">
    <div class="container mx-auto px-6 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight">Eksplorasi Karir Impianmu</h1>
        <p class="text-xl opacity-90 mb-10 max-w-2xl mx-auto">Temukan peluang terbaik dari mitra industri terpercaya SMKN 1 Garut.</p>
        
        <form action="{{ route('student.lowongan') }}" method="GET" class="flex max-w-3xl mx-auto gap-3 p-2 glass-input rounded-2xl shadow-2xl">
            <div class="flex-1 flex items-center px-4">
                <i class="fas fa-search text-slate-400 mr-3"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi atau perusahaan..." 
                    class="w-full py-4 bg-transparent text-slate-900 focus:outline-none font-medium">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold transition-all flex items-center gap-2">
                <span>Cari</span>
            </button>
        </form>
    </div>
</div>

<div class="container mx-auto px-6 py-16">
    <!-- Filter & Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div class="section-header">
            <h2 class="text-3xl font-bold text-[#001f3f]">Lowongan Tersedia</h2>
            <p class="text-slate-500 mt-1">Menampilkan lowongan terbaru untuk alumni dan siswa.</p>
        </div>

        <div class="flex gap-4">
            <form action="{{ route('student.lowongan') }}" method="GET" id="filterForm" class="flex gap-3">
                <select name="type" onchange="this.form.submit()" class="px-6 py-3 rounded-xl border border-slate-200 font-semibold text-slate-600 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Tipe Kerja</option>
                    <option value="Full-time" {{ request('type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Internship" {{ request('type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                    <option value="Contract" {{ request('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Jobs Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($jobs as $job)
            <div class="bg-white rounded-3xl p-6 job-card relative flex flex-col justify-between">
                
                {{-- Bookmark Button --}}
                <div class="absolute top-6 right-6 z-10">
                    <form action="{{ route('student.lowongan.save', $job->job_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full flex items-center justify-center transition-all {{ Auth::user()->savedJobs->contains('job_id', $job->job_id) ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-300 hover:text-blue-600 hover:bg-blue-50' }}">
                            <i class="fas fa-bookmark text-lg"></i>
                        </button>
                    </form>
                </div>

                <div>
                    {{-- Logo Perusahaan (Placeholder) --}}
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 border border-slate-100">
                        <i class="fas fa-briefcase text-3xl text-blue-600"></i>
                    </div>

                    <h3 class="text-xl font-extrabold text-slate-900 mb-1 leading-tight">{{ $job->title }}</h3>
                    <p class="text-blue-600 font-bold text-sm mb-4">{{ $job->company->company_name ?? 'Mitra Industri' }}</p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            <i class="fas fa-clock mr-1"></i> {{ $job->job_type }}
                        </span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $job->location ?? 'Garut' }}
                        </span>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3">
                        {{ Str::limit(strip_tags($job->description), 120) }}
                    </p>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <div class="text-xs">
                        <p class="text-slate-400">Batas Lamaran:</p>
                        <p class="font-bold text-slate-700">{{ $job->expired_at ? $job->expired_at->format('d M Y') : 'Selamanya' }}</p>
                    </div>
                    <a href="{{ route('student.lowongan.detail', $job->job_id) }}" 
                       class="bg-[#001f3f] hover:bg-blue-900 text-white px-5 py-3 rounded-xl font-bold text-sm transition-colors shadow-lg shadow-blue-900/10">
                        Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Lowongan tidak ditemukan</h3>
                <p class="text-slate-500 mt-2">Coba gunakan kata kunci lain atau filter yang berbeda.</p>
                <a href="{{ route('student.lowongan') }}" class="mt-6 inline-block text-blue-600 font-bold hover:underline">Reset Filter</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination Modern -->
    @if($jobs->hasPages())
        <div class="mt-16 flex justify-center">
            {{ $jobs->links() }}
        </div>
    @endif
</div>


@endsection
