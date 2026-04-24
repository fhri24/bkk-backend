@extends('layouts.app')
@php
    $isStudent = auth()->check() && auth()->user()->role && auth()->user()->role->name === 'siswa' && request()->is('student/*');
@endphp
@section('title', $job->title . ' - BKK SMKN 1 Garut')

@section('extra_css')
<style>
  .detail-header {
    background: linear-gradient(135deg, rgba(0, 31, 63, 0.95), rgba(37, 99, 235, 0.8)),
                url("https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80");
    background-size: cover;
    background-position: center;
    color: white;
    padding: 60px 0;
    margin-top: -80px;
    padding-top: 100px;
  }

  .company-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100px;
    height: 100px;
    background: white;
    border-radius: 12px;
    font-size: 48px;
    font-weight: bold;
    color: #001f3f;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .detail-section {
    margin-bottom: 40px;
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .detail-section h2 {
    font-size: 24px;
    font-weight: 700;
    color: #001f3f;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
  }

  .detail-section h2 i {
    margin-right: 12px;
    color: #3b82f6;
  }

  .requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }

  .requirement-box {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #3b82f6;
  }

  .requirement-box h4 {
    font-weight: 700;
    color: #001f3f;
    margin-bottom: 12px;
  }

  .requirement-box ul {
    list-style: none;
    padding: 0;
  }

  .requirement-box li {
    padding: 6px 0;
    color: #64748b;
    font-size: 14px;
  }

  .requirement-box li:before {
    content: "✓ ";
    color: #3b82f6;
    font-weight: bold;
    margin-right: 8px;
  }

  .benefits-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
  }

  .benefit-item {
    background: #eff6ff;
    padding: 12px 16px;
    border-radius: 8px;
    color: #1e40af;
    font-size: 14px;
    display: flex;
    align-items: center;
  }

  .benefit-item i {
    margin-right: 8px;
    color: #3b82f6;
  }

  .sidebar-box {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
  }

  .sidebar-box.sticky-info {
    position: sticky;
    top: 100px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
  }

  .sidebar-box h3 {
    font-size: 18px;
    font-weight: 700;
    color: #001f3f;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
  }

  .sidebar-box h3 i {
    margin-right: 8px;
    color: #3b82f6;
  }

  .info-item {
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
  }

  .info-item:last-child {
    border-bottom: none;
  }

  .info-item .label {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
  }

  .info-item .value {
    font-weight: 700;
    color: #001f3f;
    text-align: right;
  }

  .status-open {
    display: inline-block;
    background: #dcfce7;
    color: #166534;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
  }

  .similar-item {
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 12px;
    transition: all 0.3s;
  }

  .similar-item:hover {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
  }

  .similar-item h4 {
    font-weight: 700;
    color: #001f3f;
    font-size: 14px;
    margin-bottom: 4px;
  }

  .similar-item p {
    color: #64748b;
    font-size: 12px;
    margin-bottom: 8px;
  }

  .similar-item a {
    color: #3b82f6;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    transition: all 0.3s;
  }

  .responsibilities-list {
    list-style: none;
    padding: 0;
  }

  .responsibilities-list li {
    padding: 12px 0;
    color: #64748b;
    font-size: 15px;
    border-bottom: 1px solid #e2e8f0;
  }

  .responsibilities-list li:before {
    content: "→ ";
    color: #3b82f6;
    font-weight: bold;
    margin-right: 8px;
  }

  @media (max-width: 768px) {
    .sidebar-box.sticky-info {
      position: static;
      top: auto;
    }
    .detail-header {
      padding: 40px 0;
      padding-top: 60px;
    }
  }
</style>
@endsection

@section('content')

<!-- Detail Header -->
<div class="detail-header">
  <div class="container mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row gap-8 items-start">
      <div class="company-badge">
        {{ strtoupper(substr($job->company->company_name ?? 'P', 0, 1)) }}
      </div>
      <div class="flex-1">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3">
          {{ $job->title }}
        </h1>
        <p class="text-2xl text-blue-100 font-bold mb-6">
          {{ $job->company->company_name ?? 'Nama Perusahaan' }}
        </p>
        <div class="flex flex-wrap gap-4">
          <span class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm flex items-center">
            <i class="fas fa-map-marker-alt mr-2"></i>
            <span>{{ $job->location ?? 'Lokasi' }}</span>
          </span>
          <span class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm flex items-center">
            <i class="fas fa-briefcase mr-2"></i>
            <span>{{ $job->job_type ?? 'Full Time' }}</span>
          </span>
          <span class="bg-white/20 px-4 py-2 rounded-lg backdrop-blur-sm flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i>
            <span>Post: {{ $job->created_at->diffForHumans() }}</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-6 py-16">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Bagian Kiri: Deskripsi & Detail -->
    <div class="lg:col-span-2">
      <div class="detail-section">
        <h2><i class="fas fa-align-left"></i> Deskripsi Pekerjaan</h2>
        <div class="text-slate-600 leading-relaxed space-y-4">
          {!! nl2br(e($job->description ?? 'Deskripsi pekerjaan tidak tersedia')) !!}
        </div>
      </div>

      <!-- Jika ada kolom persyaratan/tanggung jawab di DB, tampilkan. Jika tidak, pakai placeholder -->
      <div class="detail-section">
        <h2><i class="fas fa-tasks"></i> Tanggung Jawab & Persyaratan</h2>
        <p class="text-slate-600 mb-6">Berikut adalah gambaran umum kualifikasi untuk posisi ini:</p>
        <div class="requirements-grid">
          <div class="requirement-box">
            <h4>Kualifikasi Utama</h4>
            <ul>
              <li>{{ $job->experience_required ?? 'Terbuka untuk lulusan baru' }}</li>
              <li>Pendidikan minimal SMK/Sederajat</li>
              <li>Mampu bekerja dalam tim</li>
            </ul>
          </div>
          <div class="requirement-box">
            <h4>Kemampuan Teknis</h4>
            <ul>
              <li>Memahami dasar bidang {{ $job->title }}</li>
              <li>Disiplin dan bertanggung jawab</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="detail-section">
        <h2><i class="fas fa-building"></i> Tentang Perusahaan</h2>
        <p class="text-slate-600 leading-relaxed mb-6">
          {{ $job->company->description ?? 'Informasi perusahaan tidak tersedia' }}
        </p>
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
           <p class="text-sm text-slate-500 font-medium">
             <i class="fas fa-info-circle mr-2 text-blue-500"></i>
             Lowongan ini telah diverifikasi oleh tim BKK SMKN 1 Garut.
           </p>
        </div>
      </div>
    </div>

    <!-- Bagian Kanan: Sidebar Info -->
    <div class="lg:col-span-1">
      <div class="sidebar-box sticky-info">
        <h3><i class="fas fa-info-circle"></i> Informasi Singkat</h3>
        <div class="info-item">
          <span class="label">Gaji:</span>
          <span class="value text-green-600">{{ $job->salary_range ?? 'Kompetitif' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Tipe:</span>
          <span class="value">{{ $job->job_type ?? 'Full Time' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Lokasi:</span>
          <span class="value">{{ $job->location ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Status:</span>
          <span class="value"><span class="status-open">Open</span></span>
        </div>

        <div class="mt-8 space-y-3">
          @auth
            @if(auth()->user()->role && auth()->user()->role->name === 'siswa')
              <form action="{{ route('student.lowongan.apply', $job->job_id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl font-bold transition flex items-center justify-center shadow-lg shadow-blue-200">
                  <i class="fas fa-paper-plane mr-2"></i> Lamaran Cepat
                </button>
              </form>
              
              <form action="{{ route('student.lowongan.save', $job->job_id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full border-2 border-slate-200 hover:border-blue-400 hover:text-blue-600 text-slate-600 px-6 py-3 rounded-xl font-bold transition flex items-center justify-center mt-3">
                  <i class="{{ auth()->user()->savedJobs->contains('job_id', $job->job_id) ? 'fas text-blue-600' : 'far' }} fa-bookmark mr-2"></i>
                  {{ auth()->user()->savedJobs->contains('job_id', $job->job_id) ? 'Tersimpan' : 'Simpan' }}
                </button>
              </form>
            @endif
          @else
            <!-- Tombol untuk Guest -->
            <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl font-bold transition flex items-center justify-center shadow-lg shadow-blue-200">
              <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Melamar
            </a>
            <p class="text-[11px] text-center text-slate-400 mt-2 italic">
              *Khusus Siswa & Alumni SMKN 1 Garut
            </p>
          @endauth
          
          <button onclick="shareVacancy()" class="w-full border-2 border-slate-200 hover:bg-slate-50 text-slate-600 px-6 py-3 rounded-xl font-bold transition flex items-center justify-center">
            <i class="fas fa-share-alt mr-2"></i> Bagikan Lowongan
          </button>
        </div>
      </div>

      <!-- Lowongan Serupa -->
      <div class="sidebar-box">
        <h3><i class="fas fa-list"></i> Lowongan Serupa</h3>
        @forelse($similarJobs->take(3) as $similar)
          <div class="similar-item">
            <h4>{{ $similar->title }}</h4>
            <p>{{ $similar->company->company_name ?? 'Perusahaan' }}</p>
            <!-- Arahkan ke route public juga -->
            <a href="{{ route($isStudent ? 'student.lowongan.detail' : 'public.lowongan.detail', $similar->job_id) }}" class="font-bold text-blue-600 hover:text-blue-800">
              Detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        @empty
          <p class="text-slate-400 text-sm italic">Belum ada lowongan serupa.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<script>
  function shareVacancy() {
    if (navigator.share) {
      navigator.share({
        title: "{{ $job->title }}",
        text: "Cek lowongan kerja {{ $job->title }} di {{ $job->company->company_name ?? 'BKK SMKN 1 Garut' }}",
        url: window.location.href,
      });
    } else {
      const el = document.createElement('textarea');
      el.value = window.location.href;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
      alert("Link lowongan berhasil disalin ke clipboard!");
    }
  }
</script>

@endsection