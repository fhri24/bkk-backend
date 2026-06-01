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
                                <img src="{{ Storage::disk('public')->url($job->company->logo) }}" class="w-full h-full object-cover">
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
                                <img src="{{ Storage::disk('public')->url($item->image) }}"
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

        <div class="section-header mb-12 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">
                    Kisah Sukses Alumni
                </h2>
                <p class="text-slate-500 mt-2 pl-6">
                    Inspirasi karir dari para lulusan terbaik kami
                </p>
            </div>
            <a href="{{ route('public.alumni-stories') }}"
                class="inline-flex items-center justify-center rounded-full border border-blue-500 bg-white px-6 py-3 text-sm font-semibold text-blue-600 shadow-sm transition hover:bg-blue-50">
                Semua Kisah
            </a>
        </div>

        @if (isset($alumni_stories) && $alumni_stories->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($alumni_stories as $index => $story)
                    @php
                        $colorClass = $avatarColors[$index % count($avatarColors)];
                        $avatarUrl = null;

                        if ($story->student && $story->student->profile_picture) {
                            $avatarUrl = \Illuminate\Support\Facades\Storage::url($story->student->profile_picture);
                        } elseif ($story->photo) {
                            $avatarUrl = Storage::disk('public')->url($story->photo);
                        }
                    @endphp

                    <article class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 transition hover:-translate-y-1 hover:shadow-md">
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-5">
                            {{ Str::limit($story->story, 180) }}
                        </p>

                        <div class="flex items-center gap-4">
                            @if ($avatarUrl)
                                <img src="{{ $avatarUrl }}"
                                    class="w-14 h-14 rounded-full object-cover border border-slate-200 shadow-sm"
                                    alt="{{ $story->name }}" />
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-lg">
                                    {{ $story->initials }}
                                </div>
                            @endif

                            <div>
                                <p class="font-bold text-slate-900">{{ $story->name }}</p>
                                <p class="text-xs text-slate-500">{{ $story->job_title }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center">
                <p class="text-slate-600 text-lg">Belum ada kisah alumni yang dipublikasikan.</p>
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
    </script>
@endsection

