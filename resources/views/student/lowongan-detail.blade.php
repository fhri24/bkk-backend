@extends('layouts.app')

@section('title', 'Detail Lowongan - BKK SMKN 1 Garut')

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

  .similar-item:last-child {
    margin-bottom: 0;
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

  .similar-item a:hover {
    color: #1d4ed8;
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

  .responsibilities-list li:last-child {
    border-bottom: none;
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

    .detail-section {
      padding: 20px;
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
            <i class="fas fa-clock mr-2"></i>
            <span>Baru</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-6 py-16">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2">
      <!-- Job Description -->
      <div class="detail-section">
        <h2><i class="fas fa-briefcase"></i> Deskripsi Pekerjaan</h2>
        <p class="text-slate-600 leading-relaxed">
          {!! nl2br(e($job->description ?? 'Deskripsi pekerjaan tidak tersedia')) !!}
        </p>
      </div>

      <!-- Responsibilities -->
      <div class="detail-section">
        <h2><i class="fas fa-tasks"></i> Tanggung Jawab</h2>
        <ul class="responsibilities-list">
          <li>Mengelola dan memelihara sistem mesin produksi</li>
          <li>Melakukan inspeksi harian dan perawatan preventif</li>
          <li>Monitoring performa mesin dan sistem otomasi</li>
          <li>Troubleshooting dan problem solving</li>
          <li>Dokumentasi dan pelaporan maintenance</li>
          <li>Koordinasi dengan tim produksi dan engineering</li>
          <li>Memastikan keselamatan kerja dan standar kualitas</li>
        </ul>
      </div>

      <!-- Requirements -->
      <div class="detail-section">
        <h2><i class="fas fa-graduation-cap"></i> Persyaratan</h2>
        <div class="requirements-grid">
          <div class="requirement-box">
            <h4>Pendidikan</h4>
            <ul>
              <li>Minimal SMK Teknik Mesin</li>
              <li>S1 Teknik Mesin lebih diutamakan</li>
            </ul>
          </div>
          <div class="requirement-box">
            <h4>Pengalaman</h4>
            <ul>
              <li>Minimal 2 tahun di bidang yang sama</li>
              <li>Pengalaman dengan sistem PLC</li>
            </ul>
          </div>
          <div class="requirement-box">
            <h4>Skill Teknis</h4>
            <ul>
              <li>PLC dan sistem hidrolik</li>
              <li>Membaca drawing teknis</li>
              <li>Penguasaan alat ukur</li>
            </ul>
          </div>
          <div class="requirement-box">
            <h4>Soft Skills</h4>
            <ul>
              <li>Problem solving yang kuat</li>
              <li>Team player</li>
              <li>Disiplin dan detail-oriented</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Benefits -->
      <div class="detail-section">
        <h2><i class="fas fa-gift"></i> Benefit & Tunjangan</h2>
        <div class="benefits-list">
          <span class="benefit-item"><i class="fas fa-check"></i> Gaji kompetitif 5-7.5 juta</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Tunjangan kesehatan lengkap</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Asuransi jiwa</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Tunjangan transportasi</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Bonus kinerja tahunan</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Training & pengembangan karir</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Flexible working hours</span>
          <span class="benefit-item"><i class="fas fa-check"></i> Career advancement</span>
        </div>
      </div>

      <!-- Company Info -->
      <div class="detail-section">
        <h2><i class="fas fa-building"></i> Tentang Perusahaan</h2>
        <p class="text-slate-600 leading-relaxed mb-4">
          {{ $job->company->description ?? 'Informasi perusahaan tidak tersedia' }}
        </p>
        <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold transition">
          <i class="fas fa-external-link-alt mr-2"></i> Lihat Profil Perusahaan
        </a>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
      <!-- Quick Info Box - STICKY -->
      <div class="sidebar-box sticky-info">
        <h3><i class="fas fa-info-circle"></i> Informasi Singkat</h3>
        <div class="info-item">
          <span class="label">Gaji:</span>
          <span class="value">{{ $job->salary_range ?? 'Kompetitif' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Tipe Pekerjaan:</span>
          <span class="value">{{ $job->job_type ?? 'Full Time' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Lokasi:</span>
          <span class="value">{{ $job->location ?? 'Lokasi' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Pengalaman:</span>
          <span class="value">{{ $job->experience_required ?? '2+ Tahun' }}</span>
        </div>
        <div class="info-item">
          <span class="label">Status:</span>
          <span class="value"><span class="status-open">Buka</span></span>
        </div>

        <!-- Action Buttons -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
          <form action="{{ route('student.lowongan.apply', $job->job_id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition mb-3 flex items-center justify-center">
              <i class="fas fa-paper-plane mr-2"></i> Apply Sekarang
            </button>
          </form>
          <form action="{{ route('student.lowongan.save', $job->job_id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="w-full bg-amber-400 hover:bg-amber-500 text-slate-900 px-6 py-3 rounded-xl font-bold transition mb-3 flex items-center justify-center">
              <i class="fas fa-bookmark mr-2"></i> Simpan Lowongan
            </button>
          </form>
          <button onclick="shareVacancy()" class="w-full border-2 border-slate-200 hover:border-slate-300 text-slate-600 px-6 py-3 rounded-xl font-bold transition flex items-center justify-center">
            <i class="fas fa-share-alt mr-2"></i> Bagikan
          </button>
        </div>
      </div>

      <!-- Similar Vacancies -->
      <div class="sidebar-box">
        <h3><i class="fas fa-list"></i> Lowongan Serupa</h3>
        @if($similarJobs && count($similarJobs) > 0)
          @foreach($similarJobs->take(2) as $similar)
            <div class="similar-item">
              <h4>{{ $similar->title }}</h4>
              <p>{{ $similar->company->company_name ?? 'Perusahaan' }}</p>
              <a href="{{ route('student.lowongan.detail', $similar->job_id) }}">Lihat <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
          @endforeach
        @else
          <p class="text-slate-500 text-sm">Tidak ada lowongan serupa</p>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
  function shareVacancy() {
    const text = "{{ $job->title }} - {{ $job->company->company_name ?? 'Perusahaan' }}";
    const url = window.location.href;

    if (navigator.share) {
      navigator.share({
        title: text,
        url: url,
      });
    } else {
      alert("Link lowongan:\n" + url);
    }
  }
</script>

@endsection