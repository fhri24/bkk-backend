@extends('layouts.app')

@section('title', 'Lowongan Kerja - BKK SMKN 1 Garut')

@section('extra_css')
<style>
    .search-hero {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.85), rgba(0, 31, 63, 0.85)), url("https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80");
        background-size: cover;
        background-position: center;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="search-hero">
    <div class="text-center text-white">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Temukan Lowongan Impianmu</h1>
        <p class="text-xl opacity-90 mb-8">Ribuan peluang karir menunggu alumni SMKN 1 Garut</p>
        
        <div class="flex max-w-2xl mx-auto gap-3 px-6">
            <input type="text" placeholder="Cari posisi, perusahaan, atau skill..." class="flex-1 px-6 py-4 rounded-xl text-slate-900 placeholder-slate-400 font-semibold" id="searchInput">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold transition" onclick="filterJobs()">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<div class="container mx-auto px-6 py-16">
    <!-- Filter Section -->
    <div class="mb-12 flex gap-4 flex-wrap">
        <select class="px-6 py-2 rounded-lg border border-slate-300 font-semibold text-slate-700" id="filterType">
            <option value="">Semua Jenis</option>
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Contract">Contract</option>
            <option value="Internship">Internship</option>
        </select>
    </div>

    <!-- Jobs Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($jobs as $job)
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-8 job-card group cursor-pointer" onclick="window.location.href='{{ route('student.lowongan.detail', $job->job_id) }}'">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $job->title }}</h3>
                        <p class="text-slate-600 font-semibold mt-2">{{ $job->company->company_name ?? 'Company' }}</p>
                    </div>
                    <button class="text-2xl text-slate-300 hover:text-red-500 transition" onclick="event.stopPropagation()">
                        <i class="fas fa-bookmark"></i>
                    </button>
                </div>

                <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ substr($job->description ?? '', 0, 150) }}...</p>

                <div class="flex gap-3 mb-6">
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">{{ $job->job_type }}</span>
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-xs font-bold">{{ $job->visibility }}</span>
                </div>

                <div class="text-xs text-slate-400 font-bold">
                    Kadaluarsa: {{ $job->expired_at->format('d M Y') }}
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-slate-600 text-lg">Tidak ada lowongan yang tersedia</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="mt-12">
            {{ $jobs->links() }}
        </div>
    @endif
</div>

@endsection

@section('extra_js')
<script>
    function filterJobs() {
        const search = document.getElementById('searchInput').value;
        const type = document.getElementById('filterType').value;
        // TODO: Implement client-side filtering or AJAX
    }
</script>
@endsection
