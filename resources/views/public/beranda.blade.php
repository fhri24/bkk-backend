@extends('layouts.app')

@section('title', 'BKK SMKN 1 Garut - Beranda')

@section('extra_css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        @keyframes zoomInUp {
            from {
                opacity: 0;
                transform: scale(0.85) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-zoom-in {
            animation: zoomInUp 0.6s ease-out forwards;
        }

        .card-zoom {
            transition: transform 0.3s ease-out;
        }

        .card-zoom:hover {
            transform: scale(1.02);
        }

        .stat-card {
            animation: zoomInUp 0.8s ease-out backwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.3s;
        }

        .job-card {
            animation: zoomInUp 0.8s ease-out backwards;
        }

        .job-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .job-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .job-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .section-header {
            position: relative;
            padding: 16px 0;
        }

        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 4px;
            height: 30px;
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
            border-radius: 2px;
            transform: translateY(-50%);
        }

        .hero-bg {
            background: linear-gradient(rgba(0, 31, 63, 0.85), rgba(0, 31, 63, 0.85)),
                url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }

        .custom-shadow {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
        }

        .custom-input {
            background-color: #f8fafc;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .divider-line {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        .avatar-initials {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }

        .quote-mark {
            font-family: Georgia, serif;
            font-size: 80px;
            line-height: 0.6;
            color: #dbeafe;
            user-select: none;
        }

        .story-success-alert {
            animation: zoomInUp 0.5s ease-out;
        }
    </style>
@endsection

@section('content')
    @php
        // Logic deteksi role siswa untuk penentuan route
        $isStudent = auth()->check() && auth()->user()->role && auth()->user()->role->name === 'siswa';

        $routeLowongan = $isStudent ? route('student.lowongan') : route('public.lowongan');
        $routeAcara = $isStudent ? route('student.acara') : route('public.acara');
        $routeBerita = $isStudent ? route('student.berita') : route('public.berita');

        // Warna avatar bergantian
        $avatarColors = [
            'bg-gradient-to-br from-blue-500 to-blue-700',
            'bg-gradient-to-br from-indigo-500 to-indigo-700',
            'bg-gradient-to-br from-violet-500 to-violet-700',
            'bg-gradient-to-br from-sky-500 to-sky-700',
            'bg-gradient-to-br from-cyan-500 to-cyan-700',
            'bg-gradient-to-br from-teal-500 to-teal-700',
        ];
    @endphp
    <section class="hero-bg min-h-[260px] md:h-[600px] py-12 md:py-0 flex items-center justify-center text-center text-white relative">
        <div class="container mx-auto px-6 z-10">

            {{-- Hero Title & Description Dinamis --}}
            <h1 class="text-4xl md:text-6xl font-black leading-tight mb-4 md:mb-6 whitespace-pre-line break-words mt-10 md:mt-0">
                {{ $schoolProfile->site_title ?? 'SISTEM INFORMASI BURSA KERJA KHUSUS' }}
            </h1>
            <p class="text-sm md:text-xl text-blue-100 mb-8 md:mb-10 max-w-2xl mx-auto leading-relaxed">
                Wujudkan karir impian Anda melalui portal resmi Bursa Kerja Khusus SMKN 1 Garut.
            </p>

            {{-- Unified Search Bar --}}
            <div class="flex bg-white rounded-full p-1.5 md:p-2 shadow-2xl w-full max-w-xl mx-auto items-center mt-6">
                <i class="fas fa-search text-slate-400 ml-4 mr-3 md:text-lg"></i>
                <input type="text" placeholder="Cari posisi atau perusahaan..."
                    class="w-full text-slate-800 text-sm md:text-base focus:outline-none font-medium bg-transparent" />
                <a href="{{ $routeLowongan }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold transition text-xs md:text-sm flex-shrink-0 flex items-center justify-center">
                    CARI
                </a>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 -mt-8 md:-mt-16 relative z-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">

            {{-- 1. Alumni Terserap --}}
            <div class="bg-white p-5 md:p-8 rounded-2xl shadow-xl text-left md:text-center border border-slate-100 stat-card flex flex-row md:flex-col items-center md:justify-center gap-4 md:gap-0">
                <div class="w-10 h-10 md:w-auto md:h-auto bg-green-50 md:bg-transparent rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-green-500 md:text-blue-600 text-lg md:text-transparent md:hidden"></i>
                    <div class="text-4xl font-extrabold text-blue-600 mb-2 hidden md:block">
                        {{ $alumniTerserap > 999 ? number_format($alumniTerserap / 1000, 1) . 'K+' : $alumniTerserap . '+' }}
                    </div>
                </div>
                <div>
                    <div class="text-lg md:hidden font-extrabold text-slate-800 leading-none mb-1">
                        {{ $alumniTerserap > 999 ? number_format($alumniTerserap / 1000, 1) . 'K+' : $alumniTerserap . '+' }}
                    </div>
                    <div class="text-slate-500 font-bold text-[9px] md:text-xs uppercase tracking-wider leading-tight">Alumni<br class="md:hidden"/>Terserap</div>
                    <div class="text-[10px] text-slate-400 mt-1 hidden md:block">Bekerja & Wirausaha</div>
                </div>
            </div>

            {{-- 2. Tingkat Penyaluran --}}
            <div class="bg-white p-5 md:p-8 rounded-2xl shadow-xl text-left md:text-center border border-slate-100 stat-card flex flex-row md:flex-col items-center md:justify-center gap-4 md:gap-0">
                <div class="w-10 h-10 md:w-auto md:h-auto bg-blue-50 md:bg-transparent rounded-lg md:rounded-none flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-chart-line text-blue-500 md:text-blue-600 text-lg md:text-transparent md:hidden"></i>
                    <div class="text-4xl font-extrabold text-blue-600 mb-2 hidden md:block">
                        {{ $tingkatPenyaluran }}%
                    </div>
                </div>
                <div>
                    <div class="text-lg md:hidden font-extrabold text-slate-800 leading-none mb-1">
                        {{ $tingkatPenyaluran }}%
                    </div>
                    <div class="text-slate-500 font-bold text-[9px] md:text-xs uppercase tracking-wider leading-tight">Tingkat<br class="md:hidden"/>Penyaluran</div>
                    <div class="text-[10px] text-slate-400 mt-1 hidden md:block">Berdasarkan data tracer</div>
                </div>
            </div>

            {{-- 3. Lowongan Aktif --}}
            <div class="bg-white p-5 md:p-8 rounded-2xl shadow-xl text-left md:text-center border border-slate-100 stat-card flex flex-row md:flex-col items-center md:justify-center gap-4 md:gap-0">
                <div class="w-10 h-10 md:w-auto md:h-auto bg-purple-50 md:bg-transparent rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-briefcase text-purple-500 md:text-blue-600 text-lg md:text-transparent md:hidden"></i>
                    <div class="text-4xl font-extrabold text-blue-600 mb-2 hidden md:block">
                        {{ $lowonganAktif }}+
                    </div>
                </div>
                <div>
                    <div class="text-lg md:hidden font-extrabold text-slate-800 leading-none mb-1">
                        {{ $lowonganAktif }}+
                    </div>
                    <div class="text-slate-500 font-bold text-[9px] md:text-xs uppercase tracking-wider leading-tight">Lowongan<br class="md:hidden"/>Aktif</div>
                    <div class="text-[10px] text-slate-400 mt-1 hidden md:block">Masih tersedia & terbuka</div>
                </div>
            </div>

            {{-- 4. MoU Industri --}}
            <div class="bg-white p-5 md:p-8 rounded-2xl shadow-xl text-left md:text-center border border-slate-100 stat-card flex flex-row md:flex-col items-center md:justify-center gap-4 md:gap-0">
                <div class="w-10 h-10 md:w-auto md:h-auto bg-orange-50 md:bg-transparent rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-handshake text-orange-500 md:text-blue-600 text-lg md:text-transparent md:hidden"></i>
                    <div class="text-4xl font-extrabold text-blue-600 mb-2 hidden md:block">
                        {{ $totalPerusahaan }}
                    </div>
                </div>
                <div>
                    <div class="text-lg md:hidden font-extrabold text-slate-800 leading-none mb-1">
                        {{ $totalPerusahaan }}
                    </div>
                    <div class="text-slate-500 font-bold text-[9px] md:text-xs uppercase tracking-wider leading-tight">Mitra<br class="md:hidden"/>Industri</div>
                    <div class="text-[10px] text-slate-400 mt-1 hidden md:block">Perusahaan terdaftar</div>
                </div>
            </div>

        </div>
    </section>

    <section class="container mx-auto px-6 py-12 md:py-20 overflow-hidden relative">
        <div class="flex justify-between items-center mb-6 md:mb-12">
            <h2 class="text-xl md:text-3xl font-extrabold text-[#001f3f]">Lowongan Unggulan</h2>
            <a href="{{ $routeLowongan }}" class="text-blue-600 font-bold text-xs md:text-base hover:underline uppercase md:capitalize">Lihat Semua <i class="fas fa-arrow-right ml-1 md:ml-2 text-[10px] md:text-xs"></i></a>
        </div>
        
        <div class="relative group">
            {{-- Carousel Navigation (Sides) - Mobile Only --}}
            <button onclick="scrollContainer('jobs-carousel', -1)" class="flex md:hidden absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full border border-slate-200 items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 shadow-md transition z-10 focus:outline-none opacity-80 hover:opacity-100">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </button>
            <button onclick="scrollContainer('jobs-carousel', 1)" class="flex md:hidden absolute right-0 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full border border-slate-200 items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 shadow-md transition z-10 focus:outline-none opacity-80 hover:opacity-100">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </button>

            <div id="jobs-carousel" class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-4 pb-6 md:pb-0 md:grid md:grid-cols-3 md:gap-8 no-scrollbar -mx-6 px-6 md:mx-0 md:px-0">
                @forelse($featured_jobs as $job)
                    <div class="w-[85vw] md:w-auto shrink-0 snap-center bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4 md:mb-6">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-slate-50 rounded-xl flex items-center justify-center border group-hover:bg-blue-50 transition overflow-hidden flex-shrink-0">
                                    @if ($job->company && $job->company->logo)
                                        <img src="{{ Storage::disk('public')->url($job->company->logo) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-industry text-blue-600 text-xl md:text-2xl"></i>
                                    @endif
                                </div>
                                <span class="text-[9px] md:text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-md uppercase">{{ $job->job_type }}</span>
                            </div>
                            <div class="mb-4">
                                <h4 class="font-bold text-base md:text-lg text-slate-800 line-clamp-2 md:leading-snug">{{ $job->title }}</h4>
                                <p class="text-xs md:text-sm text-slate-500 mt-1 line-clamp-1">{{ $job->company->company_name ?? 'Perusahaan' }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center text-[11px] md:text-sm text-slate-500 mb-3 md:mb-6 font-medium">
                                <i class="fas fa-map-marker-alt w-5 text-slate-400"></i>
                                <span class="truncate">{{ $job->location }}</span>
                            </div>
                            <div class="flex flex-col space-y-2 md:space-y-3 mb-6 md:mb-8 text-xs md:text-sm text-slate-600 font-medium">
                                <div class="flex items-center"><i class="fas fa-graduation-cap w-5 text-slate-400"></i> {{ $job->job_type }}</div>
                                <div class="flex items-center"><i class="fas fa-calendar-alt w-5 text-slate-400"></i> Tutup: {{ \Carbon\Carbon::parse($job->expired_at)->format('d M Y') }}</div>
                            </div>
                            <a href="{{ route($isStudent ? 'student.lowongan.detail' : 'public.lowongan.detail', $job->job_id ?? $job->id) }}"
                                class="w-full bg-slate-100 py-2.5 md:py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center block text-sm md:text-base">Lamar Sekarang</a>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-12">
                        <p class="text-slate-600">Belum ada lowongan unggulan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-b from-slate-50 to-white py-12 md:py-20">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mb-6 md:mb-12">
                <h2 class="text-xl md:text-3xl font-extrabold text-[#001f3f]">Acara Mendatang</h2>
                <a href="{{ $routeAcara }}" class="text-blue-600 font-bold text-xs md:text-base hover:underline uppercase md:capitalize">Arsip <span class="hidden md:inline">Semua Acara</span> <i class="fas fa-arrow-right ml-1 md:ml-2 text-[10px] md:text-xs"></i></a>
            </div>
            <div class="flex flex-col md:grid md:grid-cols-3 gap-4 md:gap-8">
                @forelse($featured_events as $event)
                    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card flex flex-row md:flex-col items-center md:items-start gap-4 md:gap-0">
                        <div class="w-[72px] h-[72px] md:w-14 md:h-14 bg-[#001f3f] md:bg-blue-50 rounded-[20px] md:rounded-xl flex flex-col md:flex-row items-center justify-center border md:group-hover:bg-blue-100 transition flex-shrink-0 text-white md:text-slate-800 md:mb-6">
                            <span class="text-[10px] md:hidden font-bold uppercase tracking-widest text-blue-200 mt-1">{{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('M') }}</span>
                            <span class="text-[28px] md:hidden font-black leading-none mt-0">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                            <i class="fas fa-briefcase text-blue-600 text-2xl hidden md:block"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm md:text-lg truncate md:w-48 text-slate-800">{{ $event->title }}</h4>
                            
                            <div class="flex items-center text-[10px] md:text-sm text-slate-500 mb-2 md:mb-8 font-medium mt-1">
                                <i class="far fa-clock w-3.5 md:w-4 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_date ?? $event->start_date)->format('H:i') }} WIB
                            </div>
                            
                            <div class="md:hidden">
                                <span class="text-[9px] font-bold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-full"><i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}</span>
                            </div>

                            <div class="hidden md:flex flex-col space-y-3 text-sm text-slate-600 font-medium mb-8">
                                <div class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-slate-400"></i> {{ $event->location }}</div>
                                <div class="flex items-center"><i class="fas fa-users w-5 text-slate-400"></i> {{ $event->capacity }} Peserta</div>
                            </div>

                            <a href="{{ route($isStudent ? 'student.acara.detail' : 'public.acara.detail', $event->id) }}"
                                class="hidden md:block w-full bg-slate-100 py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center">Detail Acara</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-slate-600">Belum ada acara unggulan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20 overflow-hidden relative">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mb-6 md:mb-12">
                <h2 class="text-xl md:text-3xl font-extrabold text-[#001f3f]">Berita Unggulan</h2>
                <a href="{{ $routeBerita }}" class="text-blue-600 font-bold text-xs md:text-base hover:underline uppercase md:capitalize">Lihat Semua <i class="fas fa-arrow-right ml-1 md:ml-2 text-[10px] md:text-xs"></i></a>
            </div>
            
            <div class="relative group">
                <button onclick="scrollContainer('news-carousel', -1)" class="flex md:hidden absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full border border-slate-200 items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 shadow-md transition z-10 focus:outline-none opacity-80 hover:opacity-100">
                    <i class="fas fa-chevron-left text-[10px]"></i>
                </button>
                <button onclick="scrollContainer('news-carousel', 1)" class="flex md:hidden absolute right-0 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full border border-slate-200 items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 shadow-md transition z-10 focus:outline-none opacity-80 hover:opacity-100">
                    <i class="fas fa-chevron-right text-[10px]"></i>
                </button>

                <div id="news-carousel" class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-4 pb-6 md:pb-0 md:grid md:grid-cols-3 md:gap-8 no-scrollbar -mx-6 px-6 md:mx-0 md:px-0">
                    @forelse($news as $item)
                        <div class="w-[85vw] md:w-auto shrink-0 snap-center bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition card-zoom job-card flex flex-col justify-between">
                            <div>
                                <div class="h-40 md:h-48 overflow-hidden relative">
                                    @if ($item->image)
                                        <img src="{{ Storage::disk('public')->url($item->image) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-white text-5xl opacity-20"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5 md:p-6">
                                    <div class="mb-2 md:mb-4">
                                        <span class="inline-block bg-blue-100 text-blue-700 text-[10px] md:text-xs font-bold px-2.5 py-1 rounded-md md:rounded-full mb-2 md:mb-3 uppercase">{{ $item->category ?? 'Warta' }}</span>
                                        <h4 class="font-bold text-base md:text-lg text-slate-800 leading-tight line-clamp-2 h-auto md:h-14">
                                            {{ $item->title }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 pb-5 md:px-6 md:pb-6">
                                <p class="text-[11px] md:text-xs text-slate-500 mb-0 md:mb-4"><i class="far fa-calendar-alt mr-1"></i> {{ $item->created_at->translatedFormat('d M Y') }}</p>

                                <a href="{{ route($isStudent ? 'student.berita.detail' : 'public.berita.detail', $item->slug) }}"
                                    class="block w-full mt-4 md:mt-6 bg-slate-100 py-2.5 rounded-lg font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-sm text-center">Baca
                                    Selengkapnya</a>
                            </div>
                        </div>
                    @empty
                        <div class="w-full text-center py-12">
                            <p class="text-slate-600">Belum ada berita unggulan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

   {{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- KISAH SUKSES ALUMNI                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section class="py-12 md:py-20 bg-gradient-to-b from-slate-50 to-white overflow-hidden relative">
    <div class="container mx-auto px-6">

        <div class="flex items-center justify-between mb-6 md:mb-12">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-1 h-6 bg-blue-600 rounded-full md:hidden"></div>
                    <h2 class="text-xl md:text-3xl font-extrabold text-[#001f3f] md:pl-6">
                        Kisah Sukses Alumni
                    </h2>
                </div>
                <p class="text-slate-500 mt-2 pl-6 hidden md:block">
                    Inspirasi karir dari para lulusan terbaik kami
                </p>
            </div>
            <a href="{{ route('public.alumni-stories') }}" class="text-blue-600 font-bold text-xs md:text-base hover:underline uppercase md:capitalize md:border md:border-blue-500 md:bg-white md:px-6 md:py-3 md:rounded-full md:shadow-sm md:hover:bg-blue-50 md:transition">
                <span class="md:hidden">Lihat Semua <i class="fas fa-arrow-right ml-1 text-[10px]"></i></span>
                <span class="hidden md:inline">Semua Kisah <i class="fas fa-arrow-right ml-1 md:ml-2 text-[10px] md:text-xs"></i></span>
            </a>
        </div>

        @if (isset($alumni_stories) && $alumni_stories->count() > 0)
            <div class="relative group">
                <button onclick="scrollContainer('alumni-carousel', -1)" class="flex md:hidden absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full border border-slate-200 items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 shadow-md transition z-10 focus:outline-none opacity-80 hover:opacity-100">
                    <i class="fas fa-chevron-left text-[10px]"></i>
                </button>
                <button onclick="scrollContainer('alumni-carousel', 1)" class="flex md:hidden absolute right-0 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full border border-slate-200 items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-600 shadow-md transition z-10 focus:outline-none opacity-80 hover:opacity-100">
                    <i class="fas fa-chevron-right text-[10px]"></i>
                </button>
                
                <div id="alumni-carousel" class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-4 pb-6 md:pb-0 md:grid md:gap-6 md:grid-cols-2 xl:grid-cols-3 no-scrollbar -mx-6 px-6 md:mx-0 md:px-0">
                    @foreach ($alumni_stories as $index => $story)
                        @php
                            $colorClass = $avatarColors[$index % count($avatarColors)];
                            $avatarUrl = null;

                            if ($story->student && $story->student->profile_picture) {
                                $avatarUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($story->student->profile_picture);
                            } elseif ($story->photo) {
                                $avatarUrl = Storage::disk('public')->url($story->photo);
                            }
                        @endphp

                        <article class="w-[85vw] md:w-auto shrink-0 snap-center bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 transition hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                            <p class="text-slate-600 text-sm italic leading-relaxed mb-6 line-clamp-5">
                                "{{ Str::limit($story->story, 180) }}"
                            </p>

                            <div class="flex items-center gap-4">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}"
                                        class="w-12 h-12 md:w-14 md:h-14 rounded-full object-cover border border-slate-200 shadow-sm"
                                        alt="{{ $story->name }}" />
                                @else
                                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-base md:text-lg">
                                        {{ $story->initials }}
                                    </div>
                                @endif

                                <div>
                                    <p class="font-bold text-slate-900 text-sm md:text-base">{{ $story->name }}</p>
                                    <p class="text-[11px] md:text-xs text-slate-500">{{ $story->job_title }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="w-full py-12 md:py-16 text-center">
                <p class="text-slate-600 text-sm md:text-lg">Belum ada kisah alumni yang dipublikasikan.</p>
            </div>
        @endif

    </div>
</section>

                

    {{-- ───────────────────────────────────────── --}}
    {{-- FORM KISAH SUKSES (hanya tampil saat sudah login) --}}
    {{-- ───────────────────────────────────────── --}}
    @auth
        <div class="max-w-2xl mx-auto relative mb-10 px-4 md:px-0">
            {{-- Background Blur --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 rounded-[40px] blur-3xl"></div>

            <div
                class="relative bg-white rounded-[24px] md:rounded-[40px] custom-shadow p-6 md:p-12 animate-zoom-in border border-slate-100/50">
                {{-- Success Alert --}}
                @if (session('story_success'))
                    <div
                        class="story-success-alert flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl md:rounded-2xl p-4 mb-6 md:mb-8">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm font-medium">{{ session('story_success') }}</p>
                    </div>
                @endif

                {{-- Error Alert --}}
                @if (session('error') || $errors->any())
                    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl md:rounded-2xl p-4 mb-6 md:mb-8">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                        <ul class="text-sm font-medium space-y-1">
                            @if (session('error'))
                                <li>{{ session('error') }}</li>
                            @endif
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Header --}}
                <div class="text-center mb-6 md:mb-8">
                    <div class="inline-block">
                        <span
                            class="bg-gradient-to-r from-blue-100 to-purple-100 px-4 py-1.5 rounded-full text-blue-700 font-bold text-[9px] md:text-[10px] uppercase tracking-widest">
                            Berbagi Pengalaman
                        </span>
                    </div>
                    <h3 class="text-xl md:text-3xl font-bold text-slate-800 mt-4 md:mt-6 tracking-tight">
                        "Bagikan kisah suksesmu"
                    </h3>
                    <p class="text-slate-500 text-xs md:text-sm mt-2">
                        Inspirasi bagi alumni lain untuk meraih karir impian
                    </p>
                </div>

                <form action="{{ route('alumni-stories.store') }}" method="POST"
                    class="space-y-4 max-w-md mx-auto text-left">
                    @csrf

                    {{-- Nama & Foto Profil (Otomatis & Read-Only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2 uppercase tracking-wider">
                            Nama Lengkap
                        </label>
                        @php
                            $authStudent = \App\Models\Student::where('user_id', auth()->id())->first();
                            $profileName = $authStudent->full_name ?? auth()->user()->name;
                            $profilePic = $authStudent->profile_picture ?? null;
                        @endphp

                        <div class="flex items-center gap-3 px-5 py-3.5 bg-slate-50 rounded-xl border border-slate-200">
                            @if ($profilePic)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($profilePic) }}"
                                    class="w-9 h-9 rounded-full object-cover border-2 border-blue-300 flex-shrink-0"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                    style="display:none;">
                                    {{ strtoupper(substr($profileName, 0, 2)) }}
                                </div>
                            @else
                                <div
                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                    {{ strtoupper(substr($profileName, 0, 2)) }}
                                </div>
                            @endif
                            <span class="text-sm font-bold text-slate-700">{{ $profileName }}</span>
                        </div>
                        <input type="hidden" name="name" value="{{ $profileName }}">
                    </div>

                    {{-- Pekerjaan & Instansi --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2 uppercase tracking-wider">
                            Pekerjaan & Instansi
                        </label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}"
                            placeholder="Contoh: Staff IT - PT. Maju Jaya"
                            class="w-full px-5 py-3.5 custom-input rounded-xl focus:outline-none text-sm font-medium @error('job_title') border-red-400 @enderror"
                            required>
                    </div>

                    {{-- Cerita Singkat --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2 uppercase tracking-wider">
                            Cerita Singkat
                        </label>
                        <textarea name="story" id="storyTextarea" placeholder="Bagikan pengalaman menarik Anda..." rows="4"
                            maxlength="2000"
                            class="w-full px-5 py-3.5 custom-input rounded-xl focus:outline-none text-sm font-medium resize-none @error('story') border-red-400 @enderror"
                            required>{{ old('story') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">
                            <span id="charCount">0</span>/2000 karakter
                        </p>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#001f3f] to-blue-700 text-white py-4 rounded-xl font-bold flex items-center justify-center space-x-2 hover:shadow-lg hover:-translate-y-1 transition duration-300 shadow-lg mt-6 active:translate-y-0">
                        <i class="fas fa-paper-plane"></i>
                        <span class="uppercase tracking-widest text-sm">Kirim Cerita</span>
                    </button>
                </form>
            </div>
        </div>
    @endauth

    <section class="bg-gradient-to-b from-white to-slate-100 py-20">
        <div class="container mx-auto px-6 text-center">
            <div class="section-header inline-block mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-slate-400 pl-6">Bekerjasama dengan Industri
                    Ternama</p>
            </div>
            <div
                class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-40 grayscale hover:grayscale-0 transition duration-700">
                <span class="text-2xl font-black">TOYOTA</span>
                <span class="text-2xl font-black">HONDA</span>
                <span class="text-2xl font-black">ASTRA</span>
                <span class="text-2xl font-black">EPSON</span>
                <span class="text-2xl font-black">TELKOM</span>
                <span class="text-2xl font-black">POLYTRON</span>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 py-24">
        <div
            class="bg-gradient-to-br from-[#1e3a8a] to-[#001f3f] rounded-[60px] p-12 md:p-24 text-center text-white shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/15 rounded-full -mr-48 -mt-48 blur-3xl"></div>
            <div class="relative z-10">
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Siap Memulai Karir Profesional?</h2>
                <p class="text-blue-100 mb-12 max-w-2xl mx-auto text-lg leading-relaxed">Daftarkan diri Anda sebagai alumni
                    untuk mendapatkan notifikasi lowongan terbaru yang sesuai dengan jurusan Anda.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 items-center">
                    @auth
                        <a href="{{ route('student.home') }}"
                            class="inline-flex items-center justify-center gap-2 bg-white text-[#1e3a8a] px-10 py-4 rounded-full font-semibold shadow-2xl hover:shadow-[0_25px_75px_rgba(15,23,42,0.18)] transition transform hover:-translate-y-0.5 active:translate-y-0">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 bg-white text-[#1e3a8a] px-10 py-4 rounded-full font-semibold shadow-2xl hover:shadow-[0_25px_75px_rgba(15,23,42,0.18)] transition transform hover:-translate-y-0.5 active:translate-y-0">Login
                            Sebagai Alumni</a>
                    @endauth
                    <a href="{{ route('public.tutorial') }}"
                        class="inline-flex items-center justify-center gap-2 bg-white text-[#1e3a8a] px-10 py-4 rounded-full font-semibold shadow-2xl hover:shadow-[0_25px_75px_rgba(15,23,42,0.18)] transition transform hover:-translate-y-0.5 active:translate-y-0">Tutorial
                        Pendaftaran</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra_js')
    <script>
        function scrollContainer(containerId, direction) {
            const container = document.getElementById(containerId);
            if (container) {
                const scrollAmount = container.clientWidth > 768 ? container.clientWidth / 2 : container.clientWidth * 0.85;
                container.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('storyTextarea');
            const charCount = document.getElementById('charCount');

            function updateCharCount() {
                if (textarea && charCount) {
                    charCount.textContent = textarea.value.length;
                }
            }

            if (textarea && charCount) {
                updateCharCount();
                textarea.addEventListener('input', updateCharCount);
            }
        });

        // Cek apakah halaman ini dibuka di dalam WebView aplikasi mobile
        if (window.ReactNativeWebView) {
        // Buat data yang mau dikirim ke React Native
        const loginData = {
            status: "success",
            // Generate token laravel sanctum langsung dari session web yang aktif
            token: "{{ Auth::user()->createToken('MobileToken')->plainTextToken }}",
            nama: "{{ Auth::user()->name }}"
        };

        // Lempar data ke React Native
        window.ReactNativeWebView.postMessage(JSON.stringify(loginData));
    }
    </script>
@endsection

