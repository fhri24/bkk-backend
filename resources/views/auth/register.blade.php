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
    .role-card { transition: all 0.3s ease; cursor: pointer; }
    .role-card:hover { transform: translateY(-2px); }
    .role-card.active { border-color: #2563eb !important; background: #eff6ff !important; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
    .form-section { transition: all 0.4s ease; }
</style>
@endsection

@section('content')
<div class="page-transition flex flex-col items-center justify-center py-12 md:py-20 bg-gradient-to-br from-slate-50 to-blue-50 px-4 md:px-6 min-h-screen pt-24">
  
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
    <div class="md:w-1/2 p-10 overflow-y-auto max-h-[90vh]">
      <div class="flex border-b border-slate-100 mb-6">
        <a href="{{ route('login') }}" class="flex-1 pb-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 text-center">Masuk</a>
        <button type="button" class="flex-1 pb-4 text-sm font-bold border-b-2 border-blue-600 text-blue-600">Daftar</button>
      </div>

      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4 mb-5">
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

      <!-- ===== STEP 1: PILIH ROLE ===== -->
      <div id="step-role">
        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-4">Daftar Sebagai</p>
        <div class="grid grid-cols-2 gap-4 mb-6">
          <div class="role-card border-2 border-slate-200 rounded-2xl p-5 text-center {{ old('role') == 'alumni' ? 'active' : '' }}" onclick="selectRole('alumni')">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
              <i class="fas fa-user-graduate text-2xl text-blue-600"></i>
            </div>
            <p class="font-extrabold text-slate-700 text-sm">Alumni</p>
            <p class="text-xs text-slate-400 mt-1">Lulusan SMKN 1 Garut</p>
          </div>

          <div class="role-card border-2 border-slate-200 rounded-2xl p-5 text-center {{ old('role') == 'publik' ? 'active' : '' }}" onclick="selectRole('publik')">
            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
              <i class="fas fa-users text-2xl text-green-600"></i>
            </div>
            <p class="font-extrabold text-slate-700 text-sm">Publik</p>
            <p class="text-xs text-slate-400 mt-1">Pencari kerja umum</p>
          </div>
        </div>
      </div>

      <form method="POST" action="{{ route('register.process') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="role" id="input_role" value="{{ old('role', '') }}" />

        <!-- Username & Nama -->
        <div class="space-y-4">
            <div class="space-y-1">
              <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">👤 Username <span class="text-red-500">*</span></label>
              <input type="text" name="name" placeholder="Username untuk login" value="{{ old('name') }}"
                class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
            </div>

            <div class="space-y-1">
              <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📝 Nama Lengkap <span class="text-red-500">*</span></label>
              <input type="text" name="nama_lengkap" placeholder="Sesuai Ijazah" value="{{ old('nama_lengkap') }}"
                class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
            </div>
        </div>

        <!-- NISN & Kelamin -->
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🎓 NISN/NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nisn" placeholder="Nomor Identitas" value="{{ old('nisn') }}"
                  class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">⚧ Kelamin <span class="text-red-500">*</span></label>
                <select name="jenis_kelamin" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>

        <!-- TTL -->
        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📍 Tempat Lahir</label>
            <input type="text" name="tempat_lahir" placeholder="Kota" value="{{ old('tempat_lahir') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📅 Tgl Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          </div>
        </div>

        <!-- Tahun Lulus & Jurusan (Dynamic) -->
        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🏫 Tahun Lulus</label>
            <select name="tahun_lulus" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required>
              @foreach($years as $year)
                <option value="{{ $year->year }}" {{ old('tahun_lulus') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
              @endforeach
            </select>
          </div>
          <div id="field_jurusan" class="space-y-1" style="display: {{ old('role') == 'alumni' ? 'block' : 'none' }};">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📚 Jurusan <span class="text-red-500">*</span></label>
            <select name="jurusan" id="input_jurusan" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100">
              <option value="">Pilih Jurusan</option>
              @foreach($majors as $major)
                <option value="{{ $major->name }}" {{ old('jurusan') == $major->name ? 'selected' : '' }}>{{ $major->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📧 Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" placeholder="anda@gmail.com" value="{{ old('email') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📱 No. WhatsApp <span class="text-red-500">*</span></label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-bold">+62</span>
              <input type="tel" name="phone" id="phoneInput" placeholder="812xxxxxxxx" 
                value="{{ old('phone') }}" oninput="formatPhone(this)"
                class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl pl-14 pr-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
            </div>
        </div>

        <!-- Passwords -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🔐 Sandi</label>
            <div class="relative">
                <input type="password" name="password" id="regPassword" placeholder="Min 8 Karakter"
                  class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white" required />
                <button type="button" onclick="toggleRegPwd('regPassword','eye1')" class="absolute right-3 top-3 text-slate-400"><i id="eye1" class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">✔️ Konfirmasi</label>
            <div class="relative">
                <input type="password" name="password_confirmation" id="regPasswordConf" placeholder="Ulangi Sandi"
                  class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white" required />
                <button type="button" onclick="toggleRegPwd('regPasswordConf','eye2')" class="absolute right-3 top-3 text-slate-400"><i id="eye2" class="fas fa-eye"></i></button>
            </div>
          </div>
        </div>
        <p id="pwdMatchHint" class="text-xs mt-1 hidden"></p>

        <button type="submit" id="btn-submit" 
          class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-xl hover:shadow-2xl transition transform active:scale-95 mt-4 disabled:opacity-50"
          {{ old('role') ? '' : 'disabled' }}>
          <i class="fas fa-user-plus mr-2"></i>DAFTAR SEKARANG
        </button>

        <div class="text-center text-slate-400 text-xs py-2">Atau daftar dengan</div>
        <div class="grid grid-cols-2 gap-4">
          <a href="{{ route('auth.google') }}" class="flex items-center justify-center border border-slate-200 py-3 rounded-xl hover:bg-red-50 transition font-semibold text-sm text-slate-600">
            <i class="fab fa-google mr-2 text-red-500"></i> Google
          </a>
          <a href="{{ route('auth.facebook') }}" class="flex items-center justify-center border border-slate-200 py-3 rounded-xl hover:bg-blue-50 transition font-semibold text-sm text-slate-600">
            <i class="fab fa-facebook-f mr-2 text-blue-600"></i> Facebook
          </a>
        </div>
      </form>

      <p class="text-center text-slate-500 text-sm mt-6">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk</a>
      </p>
    </div>
  </div>
</div>

<script>
    function selectRole(role) {
        document.getElementById('input_role').value = role;
        document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
        event.currentTarget.classList.add('active');

        const jurusanField = document.getElementById('field_jurusan');
        const jurusanInput = document.getElementById('input_jurusan');

        if (role === 'alumni') {
            jurusanField.style.display = 'block';
            jurusanInput.setAttribute('required', 'required');
        } else {
            jurusanField.style.display = 'none';
            jurusanInput.removeAttribute('required');
            jurusanInput.value = '';
        }
        document.getElementById('btn-submit').disabled = false;
    }

    function formatPhone(input) {
        let val = input.value.replace(/\D/g, '');
        if (val.startsWith('0')) val = val.substring(1);
        if (val.startsWith('62')) val = val.substring(2);
        input.value = val;
    }

    function toggleRegPwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    document.getElementById('regPasswordConf').addEventListener('input', function() {
        const pwd = document.getElementById('regPassword').value;
        const hint = document.getElementById('pwdMatchHint');
        hint.classList.remove('hidden');
        if (this.value === pwd) {
            hint.innerHTML = '<span class="text-green-600">✓ Kata sandi cocok</span>';
        } else {
            hint.innerHTML = '<span class="text-red-600">✗ Kata sandi tidak cocok</span>';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const oldRole = document.getElementById('input_role').value;
        if (oldRole) selectRole(oldRole);
    });
</script>
@endsection