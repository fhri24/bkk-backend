@extends('layouts.app')

@section('content')

<style>
  .search-hero {
    background: linear-gradient(135deg, rgba(30, 58, 138, 0.85), rgba(0, 31, 63, 0.85)),
      url("https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80");
    background-size: cover;
    background-position: center;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .job-item {
    display: flex;
  }

  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>

<!-- Main Content -->
<div class="search-hero">
  <div class="container mx-auto px-6 text-center text-white w-full">
    <h1 class="text-4xl md:text-5xl font-extrabold mb-3">
      Sistem Informasi Bursa Kerja
    </h1>
    <p class="text-lg md:text-xl opacity-90 mb-10">
      Temukan pekerjaan terbaik sesuai keahlianmu
    </p>
    <form action="{{ route('public.lowongan') }}" method="GET" class="flex flex-col md:flex-row justify-center items-center gap-3 max-w-xl mx-auto">
      <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari pekerjaan..."
        class="w-full md:flex-1 px-6 py-3.5 rounded-lg text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-400"
      />
      <button
        type="submit"
        class="w-full md:w-auto bg-amber-400 hover:bg-amber-500 text-slate-900 px-10 py-3.5 rounded-lg font-bold transition transform hover:scale-105 active:scale-95"
      >
        Cari
      </button>
    </form>
  </div>
</div>

<div class="page-transition container mx-auto px-6 py-16">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
    <div>
      <h2 class="text-4xl font-extrabold text-[#001f3f]">
        Lowongan Terbaru
      </h2>
      <p class="text-slate-500 mt-2 font-medium">
        Peluang karir untuk Alumni & Siswa
      </p>
    </div>
  </div>

  <div class="grid lg:grid-cols-4 gap-8">
    <!-- Sidebar Filter -->
    <aside class="space-y-8">
      <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h4 class="font-bold mb-4 flex items-center text-slate-800">
          <i class="fas fa-filter mr-2 text-blue-500"></i> Filter
        </h4>
        <form action="{{ route('public.lowongan') }}" method="GET">
          <div class="mb-4">
            <p class="font-bold mb-2 text-sm">Tipe Pekerjaan</p>
            <div class="space-y-2">
                @foreach(['Full-time', 'Kontrak', 'Magang'] as $type)
                <label class="flex items-center space-x-2 text-sm text-slate-600 cursor-pointer">
                    <input type="radio" name="type" value="{{ $type }}" {{ request('type') == $type ? 'checked' : '' }} onchange="this.form.submit()">
                    <span>{{ $type }}</span>
                </label>
                @endforeach
            </div>
          </div>

          <div class="mb-4">
            <p class="font-bold mb-2 text-sm">Bidang Keahlian</p>
            <select name="major" class="w-full p-2 border rounded-xl text-sm text-slate-600" onchange="this.form.submit()">
              <option value="">Semua Jurusan</option>
              <option value="Teknik Otomotif" {{ request('major') == 'Teknik Otomotif' ? 'selected' : '' }}>Teknik Otomotif</option>
              <option value="Teknik Komputer" {{ request('major') == 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
              <option value="Tata Boga" {{ request('major') == 'Tata Boga' ? 'selected' : '' }}>Tata Boga</option>
            </select>
          </div>

          <a href="{{ route('public.lowongan') }}" class="block text-center text-xs text-blue-600 mt-4 hover:underline">Reset Semua Filter</a>
        </form>
      </div>

      <div class="bg-blue-600 p-6 rounded-2xl text-white shadow-xl relative overflow-hidden">
        <i class="fas fa-briefcase absolute -bottom-4 -right-4 text-8xl opacity-10 rotate-12"></i>
        <h4 class="font-bold mb-2">Info Karir</h4>
        <p class="text-xs text-blue-100 leading-relaxed mb-4">
          Login sebagai siswa untuk melamar dan menyimpan lowongan favoritmu.
        </p>
        <a href="{{ route('login') }}" class="inline-block bg-white text-blue-600 px-6 py-2 rounded-xl font-bold hover:bg-gray-100 transition text-sm">
          Login Sekarang
        </a>
      </div>
    </aside>

    <!-- Job List -->
    <div class="lg:col-span-3 space-y-6" id="jobList">
      @forelse($jobs as $job)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-6 hover:border-blue-200 hover:shadow-md transition job-item">
          <div class="w-20 h-20 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border">
            <div class="text-blue-600 font-bold text-2xl">
                {{ substr($job->company->company_name ?? 'C', 0, 1) }}
            </div>
          </div>
          <div class="flex-1 relative">
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-extrabold text-xl text-slate-800">
                {{ $job->title }}
              </h3>
              <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                  Verified
              </span>
            </div>
            <p class="text-blue-600 font-bold text-sm mb-4">
              {{ $job->company->company_name ?? 'Perusahaan' }}
            </p>
            <div class="flex flex-wrap gap-4 text-sm text-slate-500 font-medium mb-6">
              <span class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> {{ $job->location ?? 'Lokasi' }}</span>
              <span class="flex items-center"><i class="fas fa-money-bill-wave mr-2"></i> {{ $job->salary_range ?? 'Kompetitif' }}</span>
              <span class="flex items-center"><i class="fas fa-briefcase mr-2"></i> {{ $job->job_type ?? 'Full-time' }}</span>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-2">
              {{ strip_tags($job->description) }}
            </p>
            <div class="flex gap-3">
              <a
                href="{{ route('public.lowongan.detail', $job->job_id) }}"
                class="bg-blue-600 text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition inline-flex items-center"
              >
                Detail Lowongan
              </a>
              @auth
              <form action="{{ route('student.lowongan.save', $job->job_id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
                  <i class="{{ Auth::user()->savedJobs->contains('job_id', $job->job_id) ? 'fas text-blue-600' : 'far' }} fa-bookmark mr-2"></i>
                  {{ Auth::user()->savedJobs->contains('job_id', $job->job_id) ? 'Tersimpan' : 'Simpan' }}
                </button>
              </form>
              @else
              <a href="{{ route('login') }}" class="border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition inline-flex items-center">
                <i class="far fa-bookmark mr-2"></i> Simpan
              </a>
              @endauth
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-dashed">
            <i class="fas fa-search text-5xl text-slate-200 mb-4"></i>
            <h3 class="text-lg font-bold text-slate-600">Tidak ada lowongan ditemukan</h3>
            <p class="text-slate-400 text-sm">Coba ubah kata kunci atau filter pencarian Anda.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>


@endsection
