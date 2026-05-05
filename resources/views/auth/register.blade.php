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
  
  <!-- ===== BANNER INFORMASI ROLE (Hanya tampil jika user sudah login) ===== -->
  @if(auth()->check())
  <div class="w-full max-w-4xl mb-6 bg-white/95 backdrop-blur rounded-2xl p-5 shadow-lg border border-blue-100 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm">
      @can('alumni')
          <p class="font-extrabold text-blue-800 m-0"><i class="fas fa-user-graduate mr-2"></i>Jurusan: {{ auth()->user()->userable->jurusan }}</p>
      @endcan

      @can('publik')
          <p class="font-extrabold text-green-800 m-0"><i class="fas fa-users mr-2"></i>Anda terdaftar sebagai pengguna publik.</p>
      @endcan
    </div>

    @can('any_admin')
        <a href="{{ route('admin.dashboard') }}" class="bg-[#001f3f] text-white px-5 py-2.5 rounded-xl text-sm font-extrabold hover:bg-blue-900 transition shadow-md">
          <i class="fas fa-cogs mr-2"></i>Admin Panel
        </a>
    @endcan
  </div>
  @endif
  <!-- ===== END BANNER INFORMASI ROLE ===== -->

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

          <!-- Card Alumni -->
          <div class="role-card border-2 border-slate-200 rounded-2xl p-5 text-center {{ old('role') == 'alumni' ? 'active' : '' }}"
               onclick="selectRole('alumni')">
            <!-- PERBAIKAN DI SINI: ganti justify-content-center jadi justify-center -->
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
              <i class="fas fa-user-graduate text-2xl text-blue-600"></i>
            </div>
            <p class="font-extrabold text-slate-700 text-sm">Alumni</p>
            <p class="text-xs text-slate-400 mt-1">Lulusan SMKN 1 Garut</p>
          </div>

          <!-- Card Publik -->
          <div class="role-card border-2 border-slate-200 rounded-2xl p-5 text-center {{ old('role') == 'publik' ? 'active' : '' }}"
               onclick="selectRole('publik')">
            <!-- PERBAIKAN DI SINI: ganti justify-content-center jadi justify-center -->
            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
              <i class="fas fa-users text-2xl text-green-600"></i>
            </div>
            <p class="font-extrabold text-slate-700 text-sm">Publik</p>
            <p class="text-xs text-slate-400 mt-1">Pencari kerja umum</p>
          </div>

        </div>
      </div>
      <!-- ===== END STEP 1 ===== -->

      <!-- ===== FORM ===== -->
      <form method="POST" action="{{ route('register.process') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="role" id="input_role" value="{{ old('role', '') }}" />

        <!-- Username -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">👤 Username <span class="text-red-500">*</span></label>
          <input type="text" name="name" placeholder="Nama panggilan / Username" value="{{ old('name') }}"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('name') border-red-500 @enderror" required />
          @error('name')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Nama Lengkap -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📝 Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="nama_lengkap" placeholder="Nama lengkap sesuai identitas/ijazah" value="{{ old('nama_lengkap') }}"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('nama_lengkap') border-red-500 @enderror" required />
          @error('nama_lengkap')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- NISN -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🎓 NISN <span class="text-red-500">*</span></label>
          <input type="text" name="nisn" placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn') }}"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('nisn') border-red-500 @enderror" required />
          @error('nisn')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Jenis Kelamin -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">⚧ Jenis Kelamin <span class="text-red-500">*</span></label>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-100">
              <input type="radio" name="jenis_kelamin" value="L" class="accent-blue-600" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required />
              <span class="text-sm font-semibold text-slate-700">Laki-laki</span>
            </label>
            <label class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-100">
              <input type="radio" name="jenis_kelamin" value="P" class="accent-blue-600" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} />
              <span class="text-sm font-semibold text-slate-700">Perempuan</span>
            </label>
          </div>
          @error('jenis_kelamin')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Tempat & Tanggal Lahir -->
        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📍 Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir" placeholder="Kota kelahiran" value="{{ old('tempat_lahir') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('tempat_lahir') border-red-500 @enderror" required />
            @error('tempat_lahir')
              <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📅 Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('tanggal_lahir') border-red-500 @enderror" required />
            @error('tanggal_lahir')
              <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Tahun Lulus & No HP -->
        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🏫 Tahun Lulus <span class="text-red-500">*</span></label>
            <select name="tahun_lulus"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('tahun_lulus') border-red-500 @enderror" required>
              <option value="">Pilih Tahun</option>
              @foreach($years as $year)
                <option value="{{ $year->year }}" {{ old('tahun_lulus') == $year->year ? 'selected' : '' }}>{{ $year->year }}</option>
              @endforeach
            </select>
            @error('tahun_lulus')
              <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📱 No. HP <span class="text-red-500">*</span></label>
            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('no_hp') border-red-500 @enderror" required />
            @error('no_hp')
              <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- ===== JURUSAN — hanya tampil untuk Alumni ===== -->
        <div id="field_jurusan" class="form-section space-y-1" style="display: {{ old('role') == 'alumni' ? 'block' : 'none' }};">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📚 Jurusan <span class="text-red-500">*</span></label>
          <select name="jurusan" id="input_jurusan"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('jurusan') border-red-500 @enderror">
            <option value="">Pilih Jurusan</option>
            @foreach($majors as $major)
              <option value="{{ $major->name }}" {{ old('jurusan') == $major->name ? 'selected' : '' }}>{{ $major->name }}</option>
            @endforeach
          </select>
          @error('jurusan')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>
        <!-- ===== END JURUSAN ===== -->

        <!-- Alamat -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🏠 Alamat <span class="text-red-500">*</span></label>
          <textarea name="alamat" rows="2" placeholder="Alamat lengkap Anda"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('alamat') border-red-500 @enderror" required>{{ old('alamat') }}</textarea>
          @error('alamat')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Foto Profil -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🖼️ Foto Profil</label>
          <input type="file" name="foto_profile" accept="image/*"
            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('foto_profile') border-red-500 @enderror" />
          @error('foto_profile')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Email -->
        <div class="space-y-1">
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📧 Email <span class="text-red-500">*</span></label>
          <input type="email" name="email" placeholder="anda@gmail.com" value="{{ old('email') }}"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('email') border-red-500 @enderror" required />
          @error('email')
            <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
          @enderror
        </div>

        <!-- Password -->
        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🔐 Kata Sandi <span class="text-red-500">*</span></label>
            <input type="password" name="password" placeholder="Minimal 6 karakter"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('password') border-red-500 @enderror" required />
            @error('password')
              <span class="text-red-600 text-xs mt-1 block"><i class="fas fa-times-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">✔️ Konfirmasi <span class="text-red-500">*</span></label>
            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          </div>
        </div>

        <!-- Syarat -->
        <div class="flex items-start text-xs font-bold pt-2">
          <input type="checkbox" class="mr-3 mt-1" required />
          <label class="text-slate-700">
            Saya setuju dengan <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>
          </label>
        </div>

        <!-- Submit -->
        <button type="submit" id="btn-submit"
          class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-xl hover:shadow-2xl transition transform active:scale-95 mt-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
          {{ old('role') ? '' : 'disabled' }}>
          <i class="fas fa-user-plus mr-2"></i>DAFTAR SEKARANG
        </button>

      </form>

      <p class="text-center text-slate-500 text-sm mt-6">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
      </p>
    </div>
  </div>
</div>

<script>
  function selectRole(role) {
    // Update hidden input
    document.getElementById('input_role').value = role;

    // Update visual state kartu
    document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Tampil/sembunyikan field jurusan
    const jurusanField = document.getElementById('field_jurusan');
    const jurusanInput = document.getElementById('input_jurusan');

    if (role === 'alumni') {
      jurusanField.style.display = 'block';
      jurusanInput.required = true;
    } else {
      jurusanField.style.display = 'none';
      jurusanInput.required = false;
      jurusanInput.value = '';
    }

    // Aktifkan tombol submit
    document.getElementById('btn-submit').disabled = false;
  }

  // Restore state saat ada old() value (misal validasi gagal)
  document.addEventListener('DOMContentLoaded', function () {
    const oldRole = document.getElementById('input_role').value;
    if (oldRole === 'alumni' || oldRole === 'publik') {
      // Highlight kartu yang sesuai
      const cards = document.querySelectorAll('.role-card');
      const idx = oldRole === 'alumni' ? 0 : 1;
      cards[idx].classList.add('active');
      document.getElementById('btn-submit').disabled = false;

      if (oldRole === 'alumni') {
        document.getElementById('field_jurusan').style.display = 'block';
        document.getElementById('input_jurusan').required = true;
      }
    }
  });
</script>
@endsection