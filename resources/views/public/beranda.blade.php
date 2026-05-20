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

        /* ── Kisah Sukses Alumni ── */
        .story-card {
            animation: zoomInUp 0.8s ease-out backwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .story-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.10);
        }

        .story-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .story-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .story-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .story-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .story-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .story-card:nth-child(6) {
            animation-delay: 0.6s;
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

        /* Quote decoration */
        .quote-mark {
            font-family: Georgia, serif;
            font-size: 80px;
            line-height: 0.6;
            color: #dbeafe;
            user-select: none;
        }

        /* Submit success alert */
        .story-success-alert {
            animation: zoomInUp 0.5s ease-out;
        }

        /* ── Marquee Infinite Scroll + Popup Hover Layout ── */
        .marquee-row {
            overflow: hidden;
            width: 100%;
            position: relative;
        }

        .marquee-track {
            display: flex;
            gap: 20px;
            width: max-content;
            align-items: flex-start;
            will-change: transform;
            /* Akselerasi hardware browser agar gerakan mulus */
        }

        .marquee-card {
            width: 300px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            overflow: hidden;
        }

        /* Popup overlay yang mengikuti kursor mouse */
        .marquee-card-popup {
            display: none;
            position: fixed;
            z-index: 9999;
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.18);
            border: 1px solid #e2e8f0;
            width: 360px;
            max-width: 90vw;
            pointer-events: none;
            /* Mencegah kedip / memblokir gerakan pointer */
            animation: popupIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .marquee-card-popup.active {
            display: block;
        }

        @keyframes popupIn {
            from {
                opacity: 0;
                transform: scale(0.92) translateY(8px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
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
    <section class="hero-bg h-[600px] flex items-center justify-center text-center text-white relative">
        <div class="container mx-auto px-6 z-10">

            {{-- Hero Title & Description Dinamis --}}
            <h1 class="text-5xl md:text-6xl font-black leading-tight mb-6 whitespace-pre-line break-words">
                {{ $schoolProfile->site_title ?? 'Sistem Informasi' }}
            </h1>
            <p class="text-lg md:text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                "{{ $schoolProfile->site_description ?? 'Menghubungkan Talenta Alumni SMKN 1 Garut dengan Peluang Karir Masa Depan di Industri Global' }}"
            </p>

            <div class="bg-white rounded-2xl p-2 flex flex-col md:flex-row shadow-2xl max-w-3xl mx-auto overflow-hidden">
                <div class="flex items-center flex-1 px-4 py-2 border-b md:border-b-0 md:border-r border-slate-100">
                    <i class="fas fa-search text-slate-400 mr-3"></i>
                    <input type="text" placeholder="Posisi kerja..."
                        class="w-full text-slate-800 focus:outline-none font-medium" />
                </div>
                <div class="flex items-center flex-1 px-4 py-2">
                    <i class="fas fa-location-arrow text-slate-400 mr-3"></i>
                    <input type="text" placeholder="Lokasi..."
                        class="w-full text-slate-800 focus:outline-none font-medium" />
                </div>
                <a href="{{ $routeLowongan }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold transition text-center flex items-center justify-center">CARI
                    KERJA</a>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-6 -mt-16 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            {{-- 1. Alumni Terserap --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
                <div class="text-4xl font-extrabold text-blue-600 mb-2">
                    {{ $alumniTerserap > 999 ? number_format($alumniTerserap / 1000, 1) . 'K+' : $alumniTerserap . '+' }}
                </div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Alumni Terserap</div>
                <div class="text-[10px] text-slate-400 mt-1">Bekerja & Wirausaha</div>
            </div>

            {{-- 2. Tingkat Penyaluran --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
                <div class="text-4xl font-extrabold text-blue-600 mb-2">
                    {{ $tingkatPenyaluran }}%
                </div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Tingkat Penyaluran</div>
                <div class="text-[10px] text-slate-400 mt-1">Berdasarkan data tracer</div>
            </div>

            {{-- 3. Lowongan Aktif --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
                <div class="text-4xl font-extrabold text-blue-600 mb-2">
                    {{ $lowonganAktif }}+
                </div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Lowongan Aktif</div>
                <div class="text-[10px] text-slate-400 mt-1">Masih tersedia & terbuka</div>
            </div>

            {{-- 4. MoU Industri --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
                <div class="text-4xl font-extrabold text-blue-600 mb-2">
                    {{ $totalPerusahaan }}
                </div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">MoU Industri</div>
                <div class="text-[10px] text-slate-400 mt-1">Perusahaan terdaftar</div>
            </div>

        </div>
    </section>

    <section class="container mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div class="section-header">
                <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Lowongan Unggulan</h2>
                <p class="text-slate-500 mt-2 pl-6">Peluang kerja terbaru khusus untuk Anda</p>
            </div>
            <a href="{{ $routeLowongan }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i
                    class="fas fa-arrow-right ml-2 text-xs"></i></a>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @forelse($featured_jobs as $job)
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center border group-hover:bg-blue-50 transition overflow-hidden">
                            @if ($job->company && $job->company->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-industry text-blue-600 text-2xl"></i>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-lg truncate w-48">{{ $job->title }}</h4>
                            <p class="text-xs text-slate-500">{{ $job->company->company_name ?? 'Perusahaan' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3 mb-8 text-sm text-slate-600 font-medium">
                        <div class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-slate-400"></i>
                            {{ $job->location }}</div>
                        <div class="flex items-center"><i class="fas fa-graduation-cap w-5 text-slate-400"></i>
                            {{ $job->job_type }}</div>
                        <div class="flex items-center"><i class="fas fa-calendar-alt w-5 text-slate-400"></i> Tutup:
                            {{ \Carbon\Carbon::parse($job->expired_at)->format('d M Y') }}</div>
                    </div>
                    <a href="{{ route($isStudent ? 'student.lowongan.detail' : 'public.lowongan.detail', $job->job_id ?? $job->id) }}"
                        class="w-full bg-slate-100 py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center block">Lamar
                        Sekarang</a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-600">Belum ada lowongan unggulan</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="bg-gradient-to-b from-slate-50 to-white py-20">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div class="section-header">
                    <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Acara Unggulan</h2>
                    <p class="text-slate-500 mt-2 pl-6">Bergabunglah dalam kegiatan pengembangan karir</p>
                </div>
                <a href="{{ $routeAcara }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i
                        class="fas fa-arrow-right ml-2 text-xs"></i></a>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($featured_events as $event)
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center border group-hover:bg-blue-100 transition">
                                <i class="fas fa-briefcase text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-lg truncate w-48">{{ $event->title }}</h4>
                                <p class="text-xs text-slate-500">{{ $event->category }}</p>
                            </div>
                        </div>
                        <div class="space-y-3 mb-8 text-sm text-slate-600 font-medium">
                            <div class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-slate-400"></i>
                                {{ $event->location }}</div>
                            <div class="flex items-center"><i class="fas fa-calendar-alt w-5 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</div>
                            <div class="flex items-center"><i class="fas fa-users w-5 text-slate-400"></i>
                                {{ $event->capacity }} Peserta</div>
                        </div>

                        <a href="{{ route($isStudent ? 'student.acara.detail' : 'public.acara.detail', $event->id) }}"
                            class="w-full bg-slate-100 py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center block">Detail
                            Acara</a>

                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-slate-600">Belum ada acara unggulan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div class="section-header">
                    <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Berita Unggulan</h2>
                    <p class="text-slate-500 mt-2 pl-6">Informasi terkini dari dunia karir dan industri</p>
                </div>
                <a href="{{ $routeBerita }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i
                        class="fas fa-arrow-right ml-2 text-xs"></i></a>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($news as $item)
                    <div
                        class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition card-zoom job-card">
                        <div class="h-48 overflow-hidden relative">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-white text-5xl opacity-20"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <span
                                    class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-3">{{ $item->category ?? 'Warta' }}</span>
                                <h4 class="font-bold text-lg text-slate-800 leading-tight line-clamp-2 h-14">
                                    {{ $item->title }}</h4>
                            </div>
                            <p class="text-xs text-slate-500 mb-4">{{ $item->created_at->translatedFormat('d M Y') }}</p>

                            <a href="{{ route($isStudent ? 'student.berita.detail' : 'public.berita.detail', $item->slug) }}"
                                class="w-full mt-6 bg-slate-100 py-2.5 rounded-lg font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-sm text-center block">Baca
                                Selengkapnya</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-slate-600">Belum ada berita unggulan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- KISAH SUKSES ALUMNI                                                  --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <section class="py-20 bg-gradient-to-b from-slate-50 to-white overflow-hidden">
        <div class="container mx-auto px-6">

            <div class="section-header mb-12">
                <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">
                    Kisah Sukses Alumni
                </h2>
                <p class="text-slate-500 mt-2 pl-6">
                    Inspirasi karir dari para lulusan terbaik kami
                </p>
            </div>

            @if (isset($alumni_stories) && $alumni_stories->count() > 0)

                {{-- ═══════════════════════════════════════ --}}
                {{-- DUAL ROW MARQUEE --}}
                {{-- ═══════════════════════════════════════ --}}
                <div class="relative mb-16 overflow-hidden">

                    {{-- ====================================================== --}}
                    {{-- BARIS 1 --}}
                    {{-- ====================================================== --}}
                    <div class="marquee-row mb-5">
                        <div class="marquee-track" id="track-1">

                            @foreach ($alumni_stories->take(ceil($alumni_stories->count() / 2)) as $index => $story)
                                @php
                                    $colorClass = $avatarColors[$index % count($avatarColors)];
                                    $avatarUrl = null;

                                    if ($story->student && $story->student->profile_picture) {
                                        $avatarUrl = \Illuminate\Support\Facades\Storage::url(
                                            $story->student->profile_picture,
                                        );
                                    } elseif ($story->photo) {
                                        $avatarUrl = asset('storage/' . $story->photo);
                                    }

                                    $gradientMap = [
                                        'bg-gradient-to-br from-blue-500 to-blue-700' => '#3b82f6, #1d4ed8',
                                        'bg-gradient-to-br from-indigo-500 to-indigo-700' => '#6366f1, #4338ca',
                                        'bg-gradient-to-br from-violet-500 to-violet-700' => '#8b5cf6, #6d28d9',
                                        'bg-gradient-to-br from-sky-500 to-sky-700' => '#0ea5e9, #0369a1',
                                        'bg-gradient-to-br from-cyan-500 to-cyan-700' => '#06b6d4, #0e7490',
                                        'bg-gradient-to-br from-teal-500 to-teal-700' => '#14b8a6, #0f766e',
                                    ];

                                    $gradientColor = $gradientMap[$colorClass] ?? '#3b82f6, #1d4ed8';
                                @endphp

                                <div class="marquee-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex-shrink-0"
                                    data-story="{{ $story->story }}" data-name="{{ $story->name }}"
                                    data-job="{{ $story->job_title }}" data-avatar="{{ $avatarUrl ?? '' }}"
                                    data-initials="{{ $story->initials }}" data-color="{{ $gradientColor }}">

                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">
                                        {{ $story->story }}
                                    </p>

                                    <div class="divider-line mb-3"></div>

                                    <div class="flex items-center gap-3">

                                        @if ($avatarUrl)
                                            <img src="{{ $avatarUrl }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-white shadow flex-shrink-0"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">

                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                                                style="display:none;">
                                                {{ $story->initials }}
                                            </div>
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                {{ $story->initials }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">
                                                {{ $story->name }}
                                            </p>

                                            <p class="text-xs text-slate-500">
                                                {{ $story->job_title }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- ====================================================== --}}
                    {{-- BARIS 2 --}}
                    {{-- ====================================================== --}}
                    <div class="marquee-row">
                        <div class="marquee-track" id="track-2">

                            @foreach ($alumni_stories->skip(ceil($alumni_stories->count() / 2)) as $index => $story)
                                @php
                                    $colorClass = $avatarColors[$index % count($avatarColors)];
                                    $avatarUrl = null;

                                    if ($story->student && $story->student->profile_picture) {
                                        $avatarUrl = \Illuminate\Support\Facades\Storage::url(
                                            $story->student->profile_picture,
                                        );
                                    } elseif ($story->photo) {
                                        $avatarUrl = asset('storage/' . $story->photo);
                                    }

                                    $gradientMap = [
                                        'bg-gradient-to-br from-blue-500 to-blue-700' => '#3b82f6, #1d4ed8',
                                        'bg-gradient-to-br from-indigo-500 to-indigo-700' => '#6366f1, #4338ca',
                                        'bg-gradient-to-br from-violet-500 to-violet-700' => '#8b5cf6, #6d28d9',
                                        'bg-gradient-to-br from-sky-500 to-sky-700' => '#0ea5e9, #0369a1',
                                        'bg-gradient-to-br from-cyan-500 to-cyan-700' => '#06b6d4, #0e7490',
                                        'bg-gradient-to-br from-teal-500 to-teal-700' => '#14b8a6, #0f766e',
                                    ];

                                    $gradientColor = $gradientMap[$colorClass] ?? '#3b82f6, #1d4ed8';
                                @endphp

                                <div class="marquee-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex-shrink-0"
                                    data-story="{{ $story->story }}" data-name="{{ $story->name }}"
                                    data-job="{{ $story->job_title }}" data-avatar="{{ $avatarUrl ?? '' }}"
                                    data-initials="{{ $story->initials }}" data-color="{{ $gradientColor }}">

                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">
                                        {{ $story->story }}
                                    </p>

                                    <div class="divider-line mb-3"></div>

                                    <div class="flex items-center gap-3">

                                        @if ($avatarUrl)
                                            <img src="{{ $avatarUrl }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-white shadow flex-shrink-0"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">

                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                                                style="display:none;">
                                                {{ $story->initials }}
                                            </div>
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                {{ $story->initials }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">
                                                {{ $story->name }}
                                            </p>

                                            <p class="text-xs text-slate-500">
                                                {{ $story->job_title }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- Gradient Overlay --}}
                    <div
                        class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-slate-50 to-transparent pointer-events-none z-10">
                    </div>

                    <div
                        class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-white to-transparent pointer-events-none z-10">
                    </div>

                </div>

            @endif

        </div>
    </section>

    {{-- ───────────────────────────────────────── --}}
    {{-- FORM KISAH SUKSES (hanya tampil saat sudah login) --}}
    {{-- ───────────────────────────────────────── --}}
    @auth
        <div class="max-w-2xl mx-auto relative mb-10">
            {{-- Background Blur --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 rounded-[40px] blur-3xl"></div>

            <div
                class="relative bg-white rounded-[40px] custom-shadow p-10 md:p-12 animate-zoom-in border border-slate-100/50">
                {{-- Success Alert --}}
                @if (session('story_success'))
                    <div
                        class="story-success-alert flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 mb-8">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-sm font-medium">{{ session('story_success') }}</p>
                    </div>
                @endif

                {{-- Error Alert --}}
                @if (session('error') || $errors->any())
                    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 mb-8">
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
                <div class="text-center mb-8">
                    <div class="inline-block">
                        <span
                            class="bg-gradient-to-r from-blue-100 to-purple-100 px-5 py-1.5 rounded-full text-blue-700 font-bold text-[10px] uppercase tracking-widest">
                            Berbagi Pengalaman
                        </span>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-6 tracking-tight">
                        "Bagikan kisah suksesmu"
                    </h3>
                    <p class="text-slate-500 text-sm mt-2">
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
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($profilePic) }}"
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
        document.addEventListener("DOMContentLoaded", function() {

            // ── Counter Karakter Textarea Cerita Singkat ──
            const textarea = document.getElementById('storyTextarea');
            const charCount = document.getElementById('charCount');

            if (textarea && charCount) {
                charCount.textContent = textarea.value.length;

                textarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }

            // ── Marquee Infinite Scroll + Popup Hover ──
            (function() {
                const SPEED = 0.45;
                const GAP = 20;

                // Buat satu popup element global jika belum ada
                let popup = document.querySelector('.marquee-card-popup');
                if (!popup) {
                    popup = document.createElement('div');
                    popup.className = 'marquee-card-popup';
                    popup.innerHTML = `
                        <p class="popup-story text-slate-700 text-sm leading-relaxed mb-4"></p>
                        <div style="height:1px;background:linear-gradient(90deg,transparent,#e2e8f0,transparent);margin-bottom:14px;"></div>
                        <div class="flex items-center gap-3">
                            <div class="popup-avatar-img" style="display:none;">
                                <img class="w-11 h-11 rounded-full object-cover border-2 border-white shadow flex-shrink-0" src="" alt="">
                            </div>
                            <div class="popup-avatar-initials w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="display:none;"></div>
                            <div>
                                <p class="popup-name font-bold text-slate-800 text-sm"></p>
                                <p class="popup-job text-xs text-slate-500"></p>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(popup);
                }

                function showPopup(card, e) {
                    const story = card.dataset.story;
                    const name = card.dataset.name;
                    const job = card.dataset.job;
                    const avatar = card.dataset.avatar;
                    const initials = card.dataset.initials;
                    const color = card.dataset.color;

                    popup.querySelector('.popup-story').textContent = story;
                    popup.querySelector('.popup-name').textContent = name;
                    popup.querySelector('.popup-job').textContent = job;

                    const imgWrap = popup.querySelector('.popup-avatar-img');
                    const initWrap = popup.querySelector('.popup-avatar-initials');

                    if (avatar && avatar.trim() !== '') {
                        imgWrap.querySelector('img').src = avatar;
                        imgWrap.style.display = 'block';
                        initWrap.style.display = 'none';
                    } else {
                        initWrap.textContent = initials;
                        initWrap.style.cssText =
                            `display:flex; background: linear-gradient(to bottom right, ${color});`;
                        imgWrap.style.display = 'none';
                    }

                    // Sembunyikan dulu menggunakan visibility, pasang di DOM, baru ukur tingginya agar tidak terpotong
                    popup.style.visibility = 'hidden';
                    popup.classList.add('active');

                    // Posisi berdasarkan card rect — muncul presisi di atas card
                    const rect = card.getBoundingClientRect();
                    const pw = 360;
                    const margin = 10;
                    const scrollY = window.scrollY || document.documentElement.scrollTop;
                    const scrollX = window.scrollX || document.documentElement.scrollLeft;

                    let x = rect.left + scrollX;
                    let y = rect.top + scrollY - popup.offsetHeight - margin;

                    // Kalau kurang ruang di atas viewport, munculkan di bawah card
                    if (rect.top - popup.offsetHeight - margin < 0) {
                        y = rect.bottom + scrollY + margin;
                    }

                    // Jangan sampai keluar kanan layar
                    if (x + pw > window.innerWidth + scrollX - 10) {
                        x = window.innerWidth + scrollX - pw - 10;
                    }

                    // Jangan sampai keluar kiri layar      
                    if (x < scrollX + 10) {
                        x = scrollX + 10;
                    }

                    popup.style.left = x + 'px';
                    popup.style.top = y + 'px';
                    popup.style.visibility = 'visible';
                }

                function positionPopup(e) {
                    // Sesuai request: Tidak dipakai lagi — posisi sudah presisi mengikuti bounding box card
                }

                function hidePopup() {
                    popup.classList.remove('active');
                }

                function setupMarquee(trackId, direction) {
                    const track = document.getElementById(trackId);
                    if (!track) return;

                    const originalCards = Array.from(track.querySelectorAll('.marquee-card'));
                    if (originalCards.length === 0) return;

                    const viewW = window.innerWidth;
                    const cardW = 300 + GAP;
                    const origW = originalCards.length * cardW;
                    const clonesets = Math.max(5, Math.ceil((viewW * 5) / origW));

                    for (let i = 0; i < clonesets; i++) {
                        originalCards.forEach(card => {
                            track.appendChild(card.cloneNode(true));
                        });
                    }

                    const oneSetW = originalCards.length * cardW;
                    let pos = direction === 'left' ? 0 : -oneSetW;
                    let paused = false;
                    let currentActiveCard = null; // Menjaga state pencegah kedip (flicker)

                    // Event delegation di level track
                    track.addEventListener('mouseover', (e) => {
                        const card = e.target.closest('.marquee-card');
                        if (card && card !== currentActiveCard) {
                            currentActiveCard = card;
                            paused = true;
                            showPopup(card, e);
                        }
                    });

                    track.addEventListener('mouseout', (e) => {
                        const card = e.target.closest('.marquee-card');
                        if (card) {
                            const related = e.relatedTarget;
                            // Pastikan kursor benar-benar keluar dari komponen card, bukan sekadar pindah ke elemen anak
                            if (!related || !card.contains(related)) {
                                currentActiveCard = null;
                                paused = false;
                                hidePopup();
                            }
                        }
                    });

                    function tick() {
                        if (!paused) {
                            if (direction === 'left') {
                                pos -= SPEED;
                                if (Math.abs(pos) >= oneSetW) pos = 0;
                            } else {
                                pos += SPEED;
                                if (pos >= 0) pos = -oneSetW;
                            }
                            track.style.transform = `translateX(${pos}px)`;
                        }
                        requestAnimationFrame(tick);
                    }

                    tick();
                }

                setupMarquee('track-1', 'left');
                setupMarquee('track-2', 'right');
            })();

        });
    </script>
@endsection
