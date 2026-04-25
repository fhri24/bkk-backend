@extends('layouts.app')

@section('title', $job->title . ' - BKK SMKN 1 Garut')

@section('content')
    {{-- Breadcrumb --}}
    <div class="bg-slate-50 border-b border-slate-100">
        <div class="container mx-auto px-6 py-4 text-sm font-medium text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a> 
            <span class="mx-2 text-slate-300">/</span>
            <a href="{{ route('public.lowongan') }}" class="hover:text-blue-600 transition">Lowongan</a> 
            <span class="mx-2 text-slate-300">/</span>
            <span class="text-slate-800">{{ $job->title }}</span>
        </div>
    </div>

    {{-- ===== DETAIL HEADER ===== --}}
    <div class="relative overflow-hidden bg-[#001f3f] py-16 md:py-20">
        {{-- Hero Background with Overlay --}}
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80" class="w-full h-full object-cover">
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-left">
                {{-- Company Logo --}}
                <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-3xl shadow-2xl flex items-center justify-center p-4 shrink-0">
                    @if($job->logo)
                        <img src="{{ Storage::url($job->logo) }}" alt="{{ $job->company }}" class="max-w-full max-h-full object-contain" />
                    @else
                        <span class="text-5xl font-black text-blue-600">{{ strtoupper(substr($job->company ?? 'B', 0, 1)) }}</span>
                    @endif
                </div>

                <div class="flex-1 text-white">
                    <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight">{{ $job->title }}</h1>
                    <p class="text-xl md:text-2xl text-blue-300 font-bold mb-8">{{ $job->company }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <span class="bg-white/10 px-5 py-2.5 rounded-xl backdrop-blur-md flex items-center gap-2 border border-white/10">
                            <i class="fas fa-map-marker-alt text-blue-400"></i> {{ $job->location }}
                        </span>
                        <span class="bg-white/10 px-5 py-2.5 rounded-xl backdrop-blur-md flex items-center gap-2 border border-white/10">
                            <i class="fas fa-briefcase text-blue-400"></i> {{ $job->type }}
                        </span>
                        <span class="bg-white/10 px-5 py-2.5 rounded-xl backdrop-blur-md flex items-center gap-2 border border-white/10">
                            <i class="fas fa-clock text-blue-400"></i>
                            @if($job->created_at && $job->created_at->diffInDays(now()) < 7)
                                Baru Saja
                            @else
                                {{ $job->created_at ? $job->created_at->diffForHumans() : '' }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Kolom Kiri: Detail Deskripsi --}}
            <div class="lg:col-span-2 space-y-12">
                {{-- Deskripsi --}}
                <section>
                    <h2 class="text-2xl font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center"><i class="fas fa-align-left text-sm"></i></span>
                        Deskripsi Pekerjaan
                    </h2>
                    <div class="text-slate-600 leading-relaxed text-lg prose max-w-none">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </section>

                {{-- Tanggung Jawab --}}
                @if($job->responsibilities)
                <section>
                    <h2 class="text-2xl font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center"><i class="fas fa-tasks text-sm"></i></span>
                        Tanggung Jawab Utama
                    </h2>
                    <ul class="grid gap-4">
                        @foreach(explode("\n", $job->responsibilities) as $responsibility)
                            @if(trim($responsibility))
                            <li class="flex items-start gap-4 p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                <i class="fas fa-check-circle text-blue-500 mt-1"></i>
                                <span class="text-slate-600 font-medium">{{ trim($responsibility) }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </section>
                @endif

                {{-- Benefit --}}
                @if($job->benefits)
                <section>
                    <h2 class="text-2xl font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center"><i class="fas fa-gift text-sm"></i></span>
                        Benefit & Tunjangan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(explode("\n", $job->benefits) as $benefit)
                            @if(trim($benefit))
                            <div class="flex items-center gap-3 bg-green-50/50 text-green-700 px-5 py-4 rounded-2xl border border-green-100 font-bold">
                                <i class="fas fa-star"></i> {{ trim($benefit) }}
                            </div>
                            @endif
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

            {{-- Kolom Kanan: Sidebar --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Quick Info Card --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 sticky top-24">
                    <h3 class="text-xl font-extrabold text-slate-800 mb-8">Detail Tambahan</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span class="text-slate-400 font-bold text-sm">Gaji</span>
                            <span class="text-slate-800 font-extrabold">{{ $job->salary ?? 'Kompetitif' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span class="text-slate-400 font-bold text-sm">Status</span>
                            @if($job->is_published && (!$job->deadline || \Carbon\Carbon::parse($job->deadline)->isFuture()))
                                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-xs font-black uppercase">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-xs font-black uppercase">Ditutup</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-4 border-b border-slate-50">
                            <span class="text-slate-400 font-bold text-sm">Batas Akhir</span>
                            <span class="text-slate-800 font-extrabold">{{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-10 space-y-4">
                        @auth
                            <button onclick="openApplicationForm()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-black shadow-xl shadow-blue-200 transition transform hover:-translate-y-1">
                                <i class="fas fa-paper-plane mr-2"></i> LAMAR SEKARANG
                            </button>
                            <button id="saveBtn" onclick="toggleSaveJob()" class="w-full bg-amber-100 text-amber-700 py-4 rounded-2xl font-black transition hover:bg-amber-200">
                                <i class="fas fa-bookmark mr-2"></i> SIMPAN DULU
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="block text-center w-full bg-blue-600 text-white py-4 rounded-2xl font-black shadow-xl shadow-blue-200">
                                LOGIN UNTUK MELAMAR
                            </a>
                        @endauth
                        
                        <button onclick="shareVacancy()" class="w-full border-2 border-slate-100 text-slate-500 py-4 rounded-2xl font-black hover:bg-slate-50 transition">
                            <i class="fas fa-share-alt mr-2"></i> BAGIKAN
                        </button>
                    </div>
                </div>

                {{-- Lowongan Serupa --}}
                @if(isset($similarJobs) && $similarJobs->isNotEmpty())
                    <div class="bg-slate-900 p-8 rounded-3xl text-white">
                        <h3 class="text-lg font-extrabold mb-6">Lowongan Serupa</h3>
                        <div class="space-y-6">
                            @foreach($similarJobs as $similar)
                            <a href="{{ route('public.lowongan.detail', $similar->job_id ?? $similar->id) }}" class="block group">
                                <h4 class="font-bold group-hover:text-blue-400 transition">{{ $similar->title }}</h4>
                                <p class="text-slate-400 text-xs mt-1">{{ $similar->company }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Aplikasi diletakkan di luar kontainer utama agar tidak terpotong --}}
    @include('components.job-apply-modal')
@endsection

@push('extra_js')
<script>
    // Logic untuk Modal dan Save Job (Sama seperti sebelumnya namun lebih rapi)
    function openApplicationForm() {
        document.getElementById('applicationModal').classList.add('show');
    }
    
    function toggleSaveJob() {
        // ... Logic fetch seperti sebelumnya ...
    }

    function shareVacancy() {
        if (navigator.share) {
            navigator.share({ title: '{{ $job->title }}', url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link disalin!');
        }
    }
</script>
@endpush