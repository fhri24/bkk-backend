@extends('layouts.app')

@section('title', 'Daftar | BKK SMKN 1 Garut')

@section('extra_css')
<style>
    body { font-family: 'Poppins', sans-serif; }
    .page-transition { animation: fadeInUp 0.5s ease-out; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .form-input { transition: all 0.3s ease; }
    .form-input:focus { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15); }
    .btn-submit { transition: all 0.3s ease; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(37, 99, 235, 0.3); }
</style>
@endsection

@section('content')
<div class="page-transition flex items-center justify-center py-12 md:py-20 bg-gradient-to-br from-slate-50 to-blue-50 px-4 md:px-6 min-h-screen pt-24">
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

    <!-- Right Side - Register Form -->
    <div class="md:w-1/2 p-12 overflow-y-auto max-h-[90vh]">
      <div class="flex border-b border-slate-100 mb-8">
        <a href="{{ route('login') }}" class="flex-1 pb-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 text-center">Masuk</a>
        <button type="button" class="flex-1 pb-4 text-sm font-bold border-b-2 border-blue-600 text-blue-600">Daftar</button>
      </div>

      <form method="POST" action="{{ route('register.process') }}" class="space-y-4">
        @csrf
        
        {{-- Input Hidden Role (Penting agar Controller menerima role) --}}
        <input type="hidden" name="role" value="siswa">

        @if ($errors->any())
          <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4 mb-6 animate-pulse">
            <div class="flex items-start">
              <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3 text-lg"></i>
              <div>
                <h3 class="font-bold text-red-800 mb-2">Terjadi Kesalahan</h3>
                <ul class="text-sm text-red-700 space-y-1">
                  @foreach ($errors->all() as $error)
                    <li><i class="fas fa-times-circle mr-1"></i>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        @endif

        <!-- Nama Lengkap -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">👤 Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="name" placeholder="Nama Anda" value="{{ old('name') }}"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('name') border-red-500 @enderror" required />
          @error('name')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
          <!-- NIS -->
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">🎓 NIS</label>
            <input type="text" name="nis" placeholder="2024001" value="{{ old('nis') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('nis') border-red-500 @enderror" />
            @error('nis')
              <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>

          <!-- Jurusan -->
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">📚 Jurusan <span class="text-red-500">*</span></label>
            <select name="major"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('major') border-red-500 @enderror" required>
              <option value="">Pilih Jurusan</option>
              @foreach($majors as $major)
                <option value="{{ $major->name }}" {{ old('major') == $major->name ? 'selected' : '' }}>{{ $major->name }}</option>
              @endforeach
            </select>
            @error('major')
              <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">⚧ Jenis Kelamin <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-100 @error('gender') border-red-500 @enderror">
              <input type="radio" name="gender" value="L" class="accent-blue-600" {{ old('gender') == 'L' ? 'checked' : '' }} required />
              <span class="text-sm font-semibold text-slate-700">Laki-laki</span>
            </label>
            <label class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-100 @error('gender') border-red-500 @enderror">
              <input type="radio" name="gender" value="P" class="accent-blue-600" {{ old('gender') == 'P' ? 'checked' : '' }} />
              <span class="text-sm font-semibold text-slate-700">Perempuan</span>
            </label>
          </div>
          @error('gender')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
          <!-- Email -->
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">📧 Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" placeholder="anda@gmail.com" value="{{ old('email') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('email') border-red-500 @enderror" required />
            @error('email')
              <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>

          <!-- Tahun Lulus -->
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">📅 Tahun Lulus <span class="text-red-500">*</span></label>
            <select name="graduation_year"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('graduation_year') border-red-500 @enderror" required>
              <option value="">Pilih Tahun</option>
              @foreach($years as $year)
                <option value="{{ $year->year }}" {{ old('graduation_year') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
              @endforeach
            </select>
            @error('graduation_year')
              <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- NO. HP (WhatsApp) -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">
            📱 No. WhatsApp <span class="text-red-500">*</span>
            <span class="text-blue-500 font-normal normal-case tracking-normal ml-1">(untuk verifikasi & lupa sandi)</span>
          </label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-bold">+62</span>
            <input type="tel" name="phone" id="phoneInput"
              placeholder="812xxxxxxxx"
              value="{{ old('phone') ? ltrim(preg_replace('/^62/', '', preg_replace('/^0/', '', old('phone'))), '0') : '' }}"
              oninput="formatPhone(this)"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl pl-14 pr-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('phone') border-red-500 @enderror" required />
          </div>
          <p class="text-slate-400 text-xs mt-1">
            <i class="fab fa-whatsapp text-green-500 mr-1"></i>Pastikan nomor aktif di WhatsApp
          </p>
          @error('phone')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">🔐 Kata Sandi <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="password" name="password" id="regPassword" placeholder="Minimal 8 karakter"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('password') border-red-500 @enderror" required />
            <button type="button" onclick="toggleRegPwd('regPassword','regEye')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <i id="regEye" class="fas fa-eye text-sm"></i>
            </button>
          </div>
          @error('password')
            <span class="text-red-600 text-xs mt-2 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">✔️ Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="password" name="password_confirmation" id="regPasswordConf" placeholder="Ulangi kata sandi"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
            <button type="button" onclick="toggleRegPwd('regPasswordConf','regEye2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <i id="regEye2" class="fas fa-eye text-sm"></i>
            </button>
          </div>
          <p id="pwdMatchHint" class="text-xs mt-1 hidden"></p>
        </div>

        <!-- Syarat & Ketentuan -->
        <div class="flex items-start text-xs font-bold pt-2">
          <input type="checkbox" id="agreeCheck" class="mr-3 mt-1" required />
          <label for="agreeCheck" class="text-slate-700">
            Saya setuju dengan <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>
          </label>
        </div>

        <button type="submit" class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-xl hover:shadow-2xl transition transform active:scale-95 mt-2">
          <i class="fas fa-user-plus mr-2"></i>DAFTAR SEKARANG
        </button>

        <div class="text-center text-slate-400 text-xs py-4">Atau daftar dengan</div>
        <div class="grid grid-cols-2 gap-4">
          <a href="{{ route('auth.google') }}" class="flex items-center justify-center border border-slate-200 py-3 rounded-xl hover:bg-red-50 hover:border-red-300 transition font-semibold text-sm text-slate-600 hover:text-red-600">
            <i class="fab fa-google mr-2 text-red-500"></i> Google
          </a>
          <a href="{{ route('auth.facebook') }}" class="flex items-center justify-center border border-slate-200 py-3 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition font-semibold text-sm text-slate-600 hover:text-blue-700">
            <i class="fab fa-facebook-f mr-2 text-blue-600"></i> Facebook
          </a>
        </div>
      </form>

      <p class="text-center text-slate-500 text-sm mt-8">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
      </p>
    </div>
  </div>
</div>

<script>
  // Format nomor HP - hapus karakter non-digit, buang awalan 0/62
  function formatPhone(input) {
    let val = input.value.replace(/\D/g, '');
    if (val.startsWith('0'))  val = val.substring(1);
    if (val.startsWith('62')) val = val.substring(2);
    input.value = val;
  }

  // Toggle show/hide password
  function toggleRegPwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }

  // Real-time cek kecocokan password
  document.getElementById('regPasswordConf').addEventListener('input', function () {
    const pwd  = document.getElementById('regPassword').value;
    const hint = document.getElementById('pwdMatchHint');
    hint.classList.remove('hidden');
    if (this.value === pwd) {
      hint.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i><span class="text-green-600">Kata sandi cocok</span>';
    } else {
      hint.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i><span class="text-red-600">Kata sandi tidak cocok</span>';
    }
  });
</script>
@endsection