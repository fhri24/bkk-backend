@extends('layouts.app')

@section('title', 'Login | BKK SMKN 1 Garut')

@section('extra_css')
<style>
    body { font-family: 'Poppins', sans-serif; }
    .page-transition { animation: fadeInUp 0.5s ease-out; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .form-input { transition: all 0.3s ease; }
    .form-input:focus { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15); }
    .btn-submit { transition: all 0.3s ease; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(37, 99, 235, 0.3); }

    /* Modal */
    .modal-overlay { transition: opacity 0.3s ease; }
    .modal-box { transition: transform 0.3s ease, opacity 0.3s ease; }
    .modal-overlay.hidden { display: none; }
</style>
@endsection

@section('content')
<!-- Main Content -->
<div class="page-transition flex items-center justify-center py-12 md:py-20 bg-gradient-to-br from-slate-50 to-blue-50 px-4 md:px-6 min-h-screen">
  <div class="bg-white/95 backdrop-blur w-full max-w-4xl rounded-3xl md:rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/40">
    <!-- Left Side - Branding -->
    <div class="md:w-1/2 bg-[#001f3f] p-12 text-white flex flex-col justify-between relative overflow-hidden">
      <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 rounded-full -mr-20 -mt-20 blur-3xl"></div>
      <div>
        <h2 class="text-3xl font-extrabold mb-4 leading-tight">Mulai Perjalanan Karirmu Disini</h2>
        <p class="text-blue-200 text-sm leading-relaxed">Bergabunglah dengan ribuan alumni sukses SMKN 1 Garut yang telah berkarir di perusahaan-perusahaan terkemuka.</p>
      </div>
      <div class="space-y-4 pt-10">
        <div class="flex items-center space-x-4">
          <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-blue-400"><i class="fas fa-check"></i></div>
          <span class="text-sm font-medium">Info Lowongan Eksklusif</span>
        </div>
        <div class="flex items-center space-x-4">
          <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-blue-400"><i class="fas fa-check"></i></div>
          <span class="text-sm font-medium">Tes Rekrutmen Terpusat</span>
        </div>
        <div class="flex items-center space-x-4">
          <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-blue-400"><i class="fas fa-check"></i></div>
          <span class="text-sm font-medium">Konsultasi Karir Gratis</span>
        </div>
      </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="md:w-1/2 p-12">
      <div class="flex border-b border-slate-100 mb-8">
        <div class="flex-1 pb-4 text-sm font-bold border-b-2 border-blue-600 text-blue-600 text-center">Masuk</div>
      </div>

      <!-- Login Form -->
      <form action="{{ route('login.process') }}" method="POST" class="space-y-6">
        @csrf

        @if ($errors->any())
          <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4 mb-6 animate-pulse">
            <div class="flex items-start">
              <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3 text-lg"></i>
              <div>
                <h3 class="font-bold text-red-800 mb-1">Login Gagal</h3>
                <p class="text-red-700 text-sm">NIS, tahun lulus, atau password salah. Silakan coba lagi.</p>
              </div>
            </div>
          </div>
        @endif

        @if (session('status'))
          <div class="bg-green-50 border-l-4 border-green-500 rounded-xl p-4 mb-6">
            <p class="text-green-700 text-sm font-semibold"><i class="fas fa-check-circle mr-2"></i>{{ session('status') }}</p>
          </div>
        @endif

        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">📘 NIS</label>
          <input type="text" name="nis" placeholder="NIS Sekolah" value="{{ old('nis') }}" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          @error('nis')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">📅 Tahun Lulus</label>
          <select name="graduation_year" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required>
            <option value="">Pilih Tahun Lulus</option>
            @foreach($years as $year)
              <option value="{{ $year->year }}" {{ old('graduation_year') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
            @endforeach
          </select>
          @error('graduation_year')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">🎓 Jurusan</label>
          <select name="major" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required>
            <option value="">Pilih Jurusan</option>
            @foreach($majors as $major)
              <option value="{{ $major->name }}" {{ old('major') == $major->name ? 'selected' : '' }}>{{ $major->name }}</option>
            @endforeach
          </select>
          @error('major')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">🔐 Kata Sandi</label>
          <div class="relative">
            <input type="password" name="password" id="passwordInput" placeholder="••••••••" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <i id="eyeIcon" class="fas fa-eye text-sm"></i>
            </button>
          </div>
          @error('password')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="text-xs font-bold text-slate-500">
          <label class="flex items-center">
            <input type="checkbox" name="remember" class="mr-2" /> Ingat Saya
          </label>
        </div>

        <button type="submit" class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-xl hover:shadow-2xl transition transform active:scale-95">
          <i class="fas fa-sign-in-alt mr-2"></i>MASUK SEKARANG
        </button>

      </form>

      </div>
    </div>
  </div>
</div>

<script>
  function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }
</script>
@endsection