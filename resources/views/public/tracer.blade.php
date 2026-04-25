@extends('layouts.app')

@section('title', 'Tracer Study - BKK SMKN 1 Garut')

@section('extra_css')
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in-out;
  }

  .modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-content {
    background: white;
    border-radius: 24px;
    width: 90%;
    max-width: 800px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
  }

  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
@endsection

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
  {{-- Header & Statistik --}}
  <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
    <div>
      <h2 class="text-4xl font-extrabold text-[#001f3f] mb-6">Tracer Study & <br />Laporan Karir</h2>
      <p class="text-slate-500 text-lg leading-relaxed mb-8">Sistem pelacakan jejak alumni untuk memetakan kualitas pendidikan dan kebutuhan dunia industri.</p>
      
      @auth
        @if(auth()->user()->role === 'student')
          <button onclick="openTracerForm()" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 mb-8">
            <i class="fas fa-edit mr-2"></i>Isi Data Tracer Study Anda
          </button>
        @endif
      @else
        <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl mb-8">
          <p class="text-amber-700 text-sm italic">Silahkan <a href="{{ route('login') }}" class="font-bold underline">Login</a> sebagai Siswa/Alumni untuk mengisi data tracer.</p>
        </div>
      @endauth

      <div class="grid grid-cols-2 gap-4">
        <div class="bg-blue-600 p-6 rounded-2xl text-white shadow-lg">
          <div class="text-3xl font-bold mb-1">92%</div>
          <div class="text-[10px] font-bold uppercase tracking-widest opacity-80">Data Terverifikasi</div>
        </div>
        <div class="bg-slate-800 p-6 rounded-2xl text-white shadow-lg">
          <div class="text-3xl font-bold mb-1">450+</div>
          <div class="text-[10px] font-bold uppercase tracking-widest opacity-80">User Surveyed</div>
        </div>
      </div>
    </div>

    <div class="bg-white p-8 rounded-[40px] shadow-2xl border border-slate-100 h-[360px]">
      <canvas id="tracerChart" width="400" height="300"></canvas>
    </div>
  </div>

  {{-- Opsi Laporan --}}
  <div class="grid md:grid-cols-2 gap-8">
    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100 group">
      <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition duration-500">
        <i class="fas fa-user-graduate"></i>
      </div>
      <h3 class="text-2xl font-bold text-slate-800 mb-4">Tracer Study Report</h3>
      <p class="text-slate-500 mb-8 leading-relaxed">Laporan lengkap hasil pelacakan alumni mencakup masa tunggu kerja dan relevansi kurikulum.</p>
      <button class="bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition">Lihat Laporan Alumni</button>
    </div>

    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100 group">
      <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-8 group-hover:bg-green-600 group-hover:text-white transition duration-500">
        <i class="fas fa-building-user"></i>
      </div>
      <h3 class="text-2xl font-bold text-slate-800 mb-4">User Study Report (DUDI)</h3>
      <p class="text-slate-500 mb-8 leading-relaxed">Survei tingkat kepuasan mitra industri terhadap performa kerja alumni SMKN 1 Garut.</p>
      <button onclick="openIndustryReport()" class="block bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-green-600 hover:text-white transition text-center w-full">Lihat Laporan Industri</button>
    </div>
  </div>
</div>

{{-- MODAL 1: Form Input Tracer (Untuk Siswa) --}}
@auth
<div id="tracerFormModal" class="modal">
  <div class="modal-content max-h-[90vh] overflow-y-auto">
    <div class="bg-blue-600 p-8 text-white sticky top-0 flex justify-between items-center z-10">
      <h2 class="text-2xl font-extrabold">Isi Tracer Study Alumni</h2>
      <button onclick="closeTracerForm()" class="text-white text-2xl">&times;</button>
    </div>
    <form method="POST" action="{{ route('student.tracer.store') }}" class="p-8 space-y-6">
      @csrf
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Saat Ini</label>
        <select name="status" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
          <option value="">-- Pilih Status --</option>
          <option value="working">Bekerja</option>
          <option value="studying">Melanjutkan Studi</option>
          <option value="both">Bekerja & Studi</option>
          <option value="unemployed">Mencari Kerja</option>
        </select>
      </div> 
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Perusahaan / Institusi</label>
        <input type="text" name="institution" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none" placeholder="Masukkan nama tempat bekerja/studi">
      </div> 
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Posisi / Program Studi</label>
        <input type="text" name="position" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none" placeholder="Masukkan jabatan/jurusan">
      </div>
      <div class="flex gap-3">
        <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 transition">Simpan Data</button>
        <button type="button" onclick="closeTracerForm()" class="flex-1 bg-slate-100 text-slate-800 py-4 rounded-xl font-bold">Batal</button>
      </div>
    </form>
  </div>
</div>
@endauth

{{-- MODAL 2: Survey DUDI (Tetap Ada) --}}
<div id="industryModal" class="modal">
  {{-- ... (Isi Modal Industry/DUDI Anda yang lama tetap di sini) ... --}}
</div>

<script>
  function openTracerForm() {
    document.getElementById('tracerFormModal').classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeTracerForm() {
    document.getElementById('tracerFormModal').classList.remove('show');
    document.body.style.overflow = 'auto';
  }

  function openIndustryReport() {
    document.getElementById('industryModal').classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeIndustryReport() {
    document.getElementById('industryModal').classList.remove('show');
    document.body.style.overflow = 'auto';
  }

  // Chart Init
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('tracerChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Bekerja', 'Kuliah', 'Wirausaha', 'Mencari Kerja'],
          datasets: [{
            data: [65, 15, 12, 8],
            backgroundColor: ['#2563eb', '#9333ea', '#f59e0b', '#94a3b8'],
            borderWidth: 0,
          }],
        },
        options: {
          maintainAspectRatio: false,
          plugins: { legend: { position: 'bottom' } }
        }
      });
    } 
  });
</script>
@endsection 