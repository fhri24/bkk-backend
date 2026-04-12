@extends('layouts.app')

@section('title', 'BKK SMKN 1 Garut - Beranda')

@section('extra_css')
<style>
    body { 
        font-family: 'Poppins', sans-serif; 
    }
    
    @keyframes zoomInUp {
        from { opacity: 0; transform: scale(0.85) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-zoom-in { animation: zoomInUp 0.6s ease-out forwards; }
    .card-zoom { transition: transform 0.3s ease-out; }
    .card-zoom:hover { transform: scale(1.02); }
    .stat-card { animation: zoomInUp 0.8s ease-out backwards; }
    .stat-card:nth-child(1) { animation-delay: 0s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.3s; }
    .job-card { animation: zoomInUp 0.8s ease-out backwards; }
    .job-card:nth-child(1) { animation-delay: 0.1s; }
    .job-card:nth-child(2) { animation-delay: 0.2s; }
    .job-card:nth-child(3) { animation-delay: 0.3s; }
    
    .section-header { position: relative; padding: 16px 0; }
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
        background: linear-gradient(rgba(0, 31, 63, 0.85), rgba(0, 31, 63, 0.85)), url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
    }

    .custom-shadow {
        box-shadow: 0 15px 40px rgba(0,0,0,0.06);
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
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-bg h-[600px] flex items-center justify-center text-center text-white relative">
    <div class="container mx-auto px-6 z-10">
        <h2 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">Sistem Informasi <br /><span class="text-blue-500">Bursa Kerja</span></h2>
        <p class="text-lg md:text-xl opacity-90 mb-10 max-w-2xl mx-auto font-medium">"Menghubungkan Talenta Alumni SMKN 1 Garut dengan Peluang Karir Masa Depan di Industri Global"</p>
        <div class="bg-white rounded-2xl p-2 flex flex-col md:flex-row shadow-2xl max-w-3xl mx-auto overflow-hidden">
            <div class="flex items-center flex-1 px-4 py-2 border-b md:border-b-0 md:border-r border-slate-100">
                <i class="fas fa-search text-slate-400 mr-3"></i>
                <input type="text" placeholder="Posisi kerja..." class="w-full text-slate-800 focus:outline-none font-medium" />
            </div>
            <div class="flex items-center flex-1 px-4 py-2">
                <i class="fas fa-location-arrow text-slate-400 mr-3"></i>
                <input type="text" placeholder="Lokasi..." class="w-full text-slate-800 focus:outline-none font-medium" />
            </div>
            <a href="{{ route('student.lowongan') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold transition">CARI KERJA</a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="container mx-auto px-6 -mt-16 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
            <div class="text-4xl font-extrabold text-blue-600 mb-2">1.2K+</div>
            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Alumni Terserap</div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
            <div class="text-4xl font-extrabold text-blue-600 mb-2">85%</div>
            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Tingkat Penyaluran</div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
            <div class="text-4xl font-extrabold text-blue-600 mb-2">150+</div>
            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Lowongan Baru</div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
            <div class="text-4xl font-extrabold text-blue-600 mb-2">45</div>
            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">MoU Industri</div>
        </div>
    </div>
</section>

<!-- Lowongan Unggulan Section -->
<section class="container mx-auto px-6 py-20">
    <div class="flex justify-between items-end mb-12">
        <div class="section-header">
            <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Lowongan Unggulan</h2>
            <p class="text-slate-500 mt-2 pl-6">Peluang kerja terbaru khusus untuk Anda</p>
        </div>
        <a href="{{ route('student.lowongan') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
        @forelse($featured_jobs as $job)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card">
            <div class="flex items-center mb-6">
                <div class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center border group-hover:bg-blue-50 transition">
                    <i class="fas fa-industry text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="font-bold text-lg">{{ $job->title }}</h4>
                    <p class="text-xs text-slate-500">{{ $job->company->company_name ?? 'Perusahaan' }}</p>
                </div>
            </div>
            <div class="space-y-3 mb-8 text-sm text-slate-600 font-medium">
                <div class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-slate-400"></i> {{ $job->location }}</div>
                <div class="flex items-center"><i class="fas fa-graduation-cap w-5 text-slate-400"></i> {{ $job->job_type }}</div>
                <div class="flex items-center"><i class="fas fa-calendar-alt w-5 text-slate-400"></i> Tutup: {{ $job->expired_at->format('d M Y') }}</div>
            </div>
            <a href="{{ route('student.lowongan.detail', $job->job_id) }}" class="w-full bg-slate-100 py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center block">Lamar Sekarang</a>
        </div>
        @empty
        <div class="col-span-full text-center py-12"><p class="text-slate-600">Belum ada lowongan unggulan</p></div>
        @endforelse
    </div>
</section>

<!-- Divider -->
<div class="container mx-auto px-6 py-8">
    <div class="divider-line"></div>
</div>

<!-- Acara Unggulan Section -->
<section class="bg-gradient-to-b from-slate-50 to-white py-20">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div class="section-header">
                <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Acara Unggulan</h2>
                <p class="text-slate-500 mt-2 pl-6">Bergabunglah dalam kegiatan pengembangan karir</p>
            </div>
            <a href="{{ route('student.acara') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @forelse($featured_events as $event)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card">
                <div class="flex items-center mb-6">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center border group-hover:bg-blue-100 transition">
                        <i class="fas fa-briefcase text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-lg">{{ $event->title }}</h4>
                        <p class="text-xs text-slate-500">{{ $event->category }}</p>
                    </div>
                </div>
                <div class="space-y-3 mb-8 text-sm text-slate-600 font-medium">
                    <div class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-slate-400"></i> {{ $event->location }}</div>
                    <div class="flex items-center"><i class="fas fa-calendar-alt w-5 text-slate-400"></i> {{ $event->start_date->format('d M Y') }}</div>
                    <div class="flex items-center"><i class="fas fa-users w-5 text-slate-400"></i> {{ $event->capacity }} Peserta</div>
                </div>
                <a href="{{ route('student.acara') }}" class="w-full bg-slate-100 py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center block">Daftar Peserta</a>
            </div>
            @empty
            <div class="col-span-full text-center py-12"><p class="text-slate-600">Belum ada acara unggulan</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- Divider -->
<div class="container mx-auto px-6 py-8">
    <div class="divider-line"></div>
</div>

<!-- Berita Unggulan Section -->
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div class="section-header">
                <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Berita Unggulan</h2>
                <p class="text-slate-500 mt-2 pl-6">Informasi terkini dari dunia karir dan industri</p>
            </div>
            <a href="{{ route('student.berita') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @forelse($featured_news as $news)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition card-zoom job-card">
                <div class="h-40 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-newspaper text-white text-5xl opacity-20"></i>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-3">{{ $news->category }}</span>
                        <h4 class="font-bold text-lg text-slate-800 leading-tight">{{ $news->title }}</h4>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">{{ $news->published_at->format('d M Y') }}</p>
                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">{{ $news->excerpt }}</p>
                    <a href="{{ route('student.berita.detail', $news->id) }}" class="w-full mt-6 bg-slate-100 py-2.5 rounded-lg font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-sm text-center block">Baca Selengkapnya</a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12"><p class="text-slate-600">Belum ada berita unggulan</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- Divider -->
<div class="container mx-auto px-6 py-8">
    <div class="divider-line"></div>
</div>

<!-- Partner Companies Section -->
<section class="bg-gradient-to-b from-white to-slate-100 py-20">
    <div class="container mx-auto px-6 text-center">
        <div class="section-header inline-block mb-10">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-slate-400 pl-6">Bekerjasama dengan Industri Ternama</p>
        </div>
        <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-40 grayscale hover:grayscale-0 transition duration-700">
            <span class="text-2xl font-black">TOYOTA</span>
            <span class="text-2xl font-black">HONDA</span>
            <span class="text-2xl font-black">ASTRA</span>
            <span class="text-2xl font-black">EPSON</span>
            <span class="text-2xl font-black">TELKOM</span>
            <span class="text-2xl font-black">POLYTRON</span>
        </div>
    </div>
</section>

<!-- Divider -->
<div class="container mx-auto px-6 py-8">
    <div class="divider-line"></div>
</div>

<!-- CTA Section -->
<section class="container mx-auto px-6 py-24">
    <div class="bg-gradient-to-br from-[#1e3a8a] to-[#001f3f] rounded-[60px] p-12 md:p-24 text-center text-white shadow-2xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/15 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Siap Memulai Karir Profesional?</h2>
            <p class="text-blue-100 mb-12 max-w-2xl mx-auto text-lg leading-relaxed">Daftarkan diri Anda sebagai alumni untuk mendapatkan notifikasi lowongan terbaru yang sesuai dengan jurusan Anda.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 items-center">
                @auth
                    <a href="{{ route('student.home') }}" class="inline-flex items-center justify-center gap-2 bg-white text-[#1e3a8a] px-10 py-4 rounded-full font-semibold shadow-2xl hover:shadow-[0_25px_75px_rgba(15,23,42,0.18)] transition transform hover:-translate-y-0.5 active:translate-y-0">Dashboard</a>
                    <a href="{{ route('public.tutorial') }}" class="inline-flex items-center justify-center gap-2 border border-white bg-white/10 text-white px-10 py-4 rounded-full font-semibold hover:bg-white/20 transition transform hover:-translate-y-0.5 active:translate-y-0">Panduan Pendaftaran</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-white text-[#1e3a8a] px-10 py-4 rounded-full font-semibold shadow-2xl hover:shadow-[0_25px_75px_rgba(15,23,42,0.18)] transition transform hover:-translate-y-0.5 active:translate-y-0">Daftar Sebagai Alumni</a>
                    <a href="{{ route('public.tutorial') }}" class="inline-flex items-center justify-center gap-2 border border-white bg-white/10 text-white px-10 py-4 rounded-full font-semibold hover:bg-white/20 transition transform hover:-translate-y-0.5 active:translate-y-0">Panduan Pendaftaran</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<script>
    // Scripts untuk alumni stories telah dihapus
</script>
@endsection
