@extends('layouts.app')

@section('title', 'Beranda - Portal Siswa BKK')

@section('extra_css')
<style>
    body { font-family: 'Poppins', sans-serif; }
    
    @keyframes zoomInUp {
        from { opacity: 0; transform: scale(0.85) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-zoom-in { animation: zoomInUp 0.6s ease-out forwards; }
    .card-zoom { transition: transform 0.3s ease-out; }
    .card-zoom:hover { transform: scale(1.02); }
    .stat-card { animation: zoomInUp 0.8s ease-out backwards; }
    .job-card { animation: zoomInUp 0.8s ease-out backwards; }
    
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
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-bg h-[500px] flex items-center justify-center text-center text-white relative">
  <div class="container mx-auto px-6 z-10">
    <h2 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">Selamat Datang Kembali, <br/>{{ $user->name }}! 👋</h2>
    <p class="text-base md:text-lg opacity-90 mb-8 max-w-2xl mx-auto font-medium">Jelajahi lowongan kerja terbaru dan kembangkan karir impianmu bersama BKK SMKN 1 Garut</p>
  </div>
</section>

<!-- Stats Section -->
<section class="container mx-auto px-6 -mt-12 relative z-20">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
      <div class="text-4xl font-extrabold text-blue-600 mb-2">{{ $jobs->total() }}</div>
      <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Lowongan Tersedia</div>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card">
      <div class="text-4xl font-extrabold text-green-600 mb-2">{{ $user->studentProfile?->jobApplications?->count() ?? 0 }}</div>
      <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Lamaran Saya</div>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card cursor-pointer hover:shadow-2xl transition" onclick="window.location.href='{{ route('student.profil') }}'">
      <div class="text-4xl font-extrabold text-purple-600 mb-2">📋</div>
      <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Profil Saya</div>
      <p class="text-[11px] text-blue-600 font-semibold mt-2">Klik untuk detail</p>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-xl text-center border border-slate-100 stat-card cursor-pointer hover:shadow-2xl transition" onclick="window.location.href='{{ route('student.tracer') }}'">
      <div class="text-4xl font-extrabold text-orange-600 mb-2">📊</div>
      <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Tracer Study</div>
      <p class="text-[11px] text-blue-600 font-semibold mt-2">Isikan survey</p>
    </div>
  </div>
</section>

<!-- Lowongan Unggulan Section -->
<section class="container mx-auto px-6 py-20">
  <div class="flex justify-between items-end mb-12">
    <div class="section-header">
      <h2 class="text-3xl font-extrabold text-[#001f3f] pl-6">Lowongan Terbaru Untuk Anda</h2>
      <p class="text-slate-500 mt-2 pl-6">Peluang kerja eksklusif sesuai jurusan Anda</p>
    </div>
    <a href="{{ route('student.lowongan') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i></a>
  </div>
  
  @if($jobs->count() > 0)
    <div class="grid md:grid-cols-3 gap-8">
      @foreach($jobs->take(3) as $job)
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom job-card">
        <div class="flex items-center mb-6">
          <div class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center border group-hover:bg-blue-50 transition">
            <i class="fas fa-building text-blue-600 text-2xl"></i>
          </div>
          <div class="ml-4">
            <h4 class="font-bold text-lg">{{ $job->title }}</h4>
            <p class="text-xs text-slate-500">{{ $job->company?->company_name ?? 'Perusahaan' }}</p>
          </div>
        </div>
        <div class="space-y-3 mb-8 text-sm text-slate-600 font-medium">
          <div class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-slate-400"></i> {{ $job->location ?? 'Lokasi' }}</div>
          <div class="flex items-center"><i class="fas fa-briefcase w-5 text-slate-400"></i> {{ $job->job_type }}</div>
          <div class="flex items-center"><i class="fas fa-calendar-alt w-5 text-slate-400"></i> Tutup: {{ $job->expired_at?->format('d M Y') ?? '-' }}</div>
        </div>
        <a href="{{ route('student.lowongan.detail', $job->job_id) }}" class="w-full bg-slate-100 py-3 rounded-xl font-bold text-slate-800 hover:bg-blue-600 hover:text-white transition text-center block">Lihat Detail</a>
      </div>
      @endforeach
    </div>

    <div class="text-center mt-12">
      <a href="{{ route('student.lowongan') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-extrabold shadow-lg transition transform hover:scale-105 active:scale-95">
        Jelajahi Semua Lowongan <i class="fas fa-arrow-right ml-2"></i>
      </a>
    </div>
  @else
    <div class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-200">
      <i class="fas fa-inbox text-5xl text-slate-300 mb-4 block"></i>
      <p class="text-slate-600 font-semibold">Belum ada lowongan untuk jurusan Anda saat ini</p>
      <p class="text-slate-500 text-sm mt-2">Cek kembali nanti untuk peluang terbaru</p>
    </div>
  @endif
</section>

<!-- Divider -->
<div class="container mx-auto px-6 py-8">
  <div class="bg-gradient-to-r from-transparent via-slate-200 to-transparent h-[1px]"></div>
</div>

<!-- Quick Actions -->
<section class="container mx-auto px-6 py-20">
  <div class="text-center mb-12">
    <h2 class="text-3xl font-extrabold text-[#001f3f]">Jelajahi Portal Kami</h2>
    <p class="text-slate-500 mt-2">Akses semua informasi yang Anda butuhkan untuk karir impian</p>
  </div>
  
  <div class="grid md:grid-cols-3 gap-8">
    <a href="{{ route('student.berita') }}" class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom text-center">
      <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-100 transition">
        <i class="fas fa-newspaper text-blue-600 text-3xl"></i>
      </div>
      <h3 class="font-bold text-lg text-slate-800">Berita & Artikel</h3>
      <p class="text-slate-600 text-sm mt-2">Baca tips karir, tren industri, dan kisah sukses alumni</p>
      <div class="text-blue-600 font-bold text-sm mt-4">Kunjungi →</div>
    </a>

    <a href="{{ route('student.acara') }}" class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom text-center">
      <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-100 transition">
        <i class="fas fa-calendar text-green-600 text-3xl"></i>
      </div>
      <h3 class="font-bold text-lg text-slate-800">Acara & Event</h3>
      <p class="text-slate-600 text-sm mt-2">Ikuti workshop, job fair, dan pelatihan pengembangan karir</p>
      <div class="text-blue-600 font-bold text-sm mt-4">Kunjungi →</div>
    </a>

    <a href="{{ route('student.profil') }}" class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl transition group card-zoom text-center">
      <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-100 transition">
        <i class="fas fa-user text-purple-600 text-3xl"></i>
      </div>
      <h3 class="font-bold text-lg text-slate-800">Profil & Lamaran</h3>
      <p class="text-slate-600 text-sm mt-2">Kelola profil Anda dan pantau status lamaran pekerjaan</p>
      <div class="text-blue-600 font-bold text-sm mt-4">Buka Profil →</div>
    </a>
  </div>
</section>

<!-- Divider -->
<div class="container mx-auto px-6 py-8">
  <div class="bg-gradient-to-r from-transparent via-slate-200 to-transparent h-[1px]"></div>
</div>

<!-- CTA Section -->
<section class="container mx-auto px-6 py-24">
  <div class="bg-gradient-to-br from-[#1e3a8a] to-[#001f3f] rounded-[60px] p-12 md:p-24 text-center text-white shadow-2xl overflow-hidden relative">
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/15 rounded-full -mr-48 -mt-48 blur-3xl"></div>
    <div class="relative z-10">
      <h2 class="text-4xl md:text-5xl font-extrabold mb-6">Perluas Jaringan Profesional Anda</h2>
      <p class="text-blue-100 mb-12 max-w-2xl mx-auto text-lg leading-relaxed">Lengkapi profil Anda dengan portfolio terbaik dan terhubung dengan ribuan peluang karir di industri terkemuka.</p>
      <div class="flex flex-col sm:flex-row justify-center gap-6 items-center">
        <a href="{{ route('student.profil') }}" class="bg-white text-[#1e3a8a] px-12 py-3.5 rounded-full font-extrabold shadow-xl hover:shadow-2xl hover:bg-slate-50 transition transform hover:scale-105 active:scale-95">Edit Profil</a>
        <a href="{{ route('student.lowongan') }}" class="bg-[#2563eb] border-2 border-white text-white px-12 py-3.5 rounded-full font-extrabold hover:bg-[#1d4ed8] transition transform hover:scale-105 active:scale-95">Cari Lowongan</a>
      </div>
    </div>
  </div>
</section>
@endsection
