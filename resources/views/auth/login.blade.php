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
        <button type="button" onclick="switchTab('login')" class="flex-1 pb-4 text-sm font-bold border-b-2 border-blue-600 text-blue-600">Masuk</button>
        <a href="{{ route('register') }}" class="flex-1 pb-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 text-center">Daftar</a>
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
                <p class="text-red-700 text-sm">Email atau password salah. Silakan coba lagi.</p>
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
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-3">📧 Email</label>
          <input type="email" name="email" placeholder="nama@example.com" value="{{ old('email') }}" class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          @error('email')
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

        <div class="flex justify-between items-center text-xs font-bold">
          <label class="flex items-center text-slate-500">
            <input type="checkbox" name="remember" class="mr-2" /> Ingat Saya
          </label>
          {{-- Tombol buka modal lupa sandi --}}
          <button type="button" onclick="openForgotModal()" class="text-blue-600 hover:underline">Lupa Sandi?</button>
        </div>

        <button type="submit" class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-xl hover:shadow-2xl transition transform active:scale-95">
          <i class="fas fa-sign-in-alt mr-2"></i>MASUK SEKARANG
        </button>

        <div class="text-center text-slate-400 text-xs py-4">Atau masuk dengan</div>
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
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar di sini</a>
      </p>
    </div>
  </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: LUPA SANDI                                            -->
<!-- ============================================================ -->
<div id="forgotModal" class="modal-overlay hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
  <div class="modal-box bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 relative">

    <!-- Tombol Tutup -->
    <button onclick="closeForgotModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-xl">
      <i class="fas fa-times"></i>
    </button>

    <!-- Step 1: Masukkan No. HP / Email -->
    <div id="step1">
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-lock text-blue-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800">Lupa Kata Sandi?</h3>
        <p class="text-slate-500 text-sm mt-2">Masukkan nomor HP atau email akunmu. Kami akan kirimkan kode verifikasi.</p>
      </div>

      <form id="sendOtpForm" onsubmit="sendOtp(event)" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📱 No. HP / Email</label>
          <input type="text" id="otpTarget" name="contact" placeholder="0812xxxxxxxx atau email@gmail.com"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          <p id="step1Error" class="text-red-600 text-xs mt-2 hidden"></p>
        </div>

        <!-- Pilihan metode kirim -->
        <div>
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">📤 Kirim Kode Via</label>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-green-400 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
              <input type="radio" name="send_via" value="whatsapp" class="accent-green-600" checked />
              <span class="text-sm font-semibold text-slate-700"><i class="fab fa-whatsapp text-green-500 mr-1"></i>WhatsApp</span>
            </label>
            <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
              <input type="radio" name="send_via" value="email" class="accent-blue-600" />
              <span class="text-sm font-semibold text-slate-700"><i class="fas fa-envelope text-blue-500 mr-1"></i>Email</span>
            </label>
          </div>
        </div>

        <button type="submit" id="sendOtpBtn" class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-lg transition transform active:scale-95">
          <i class="fas fa-paper-plane mr-2"></i>Kirim Kode Verifikasi
        </button>
      </form>
    </div>

    <!-- Step 2: Masukkan Kode OTP -->
    <div id="step2" class="hidden">
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800">Masukkan Kode OTP</h3>
        <p id="otpSentDesc" class="text-slate-500 text-sm mt-2">Kode 6 digit telah dikirim ke <span id="otpDestDisplay" class="font-bold text-blue-600"></span></p>
      </div>

      <form id="verifyOtpForm" onsubmit="verifyOtp(event)" class="space-y-4">
        <div>
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🔑 Kode OTP</label>
          <input type="text" id="otpCode" name="otp" maxlength="6" placeholder="_ _ _ _ _ _"
            class="form-input w-full text-center text-2xl font-extrabold tracking-[0.5em] bg-slate-50 border border-slate-200 rounded-xl px-4 py-4 focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          <p id="step2Error" class="text-red-600 text-xs mt-2 hidden text-center"></p>
        </div>

        <!-- Countdown + Resend -->
        <div class="text-center text-sm text-slate-500">
          Tidak terima kode? 
          <button type="button" id="resendBtn" onclick="resendOtp()" class="text-blue-600 font-bold hover:underline hidden">Kirim Ulang</button>
          <span id="countdownText">Kirim ulang dalam <span id="countdown" class="font-bold text-blue-600">60</span>s</span>
        </div>

        <button type="submit" id="verifyOtpBtn" class="btn-submit w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 rounded-xl font-extrabold shadow-lg transition transform active:scale-95">
          <i class="fas fa-check-circle mr-2"></i>Verifikasi Kode
        </button>
      </form>

      <button onclick="goBackStep1()" class="w-full text-center text-slate-400 text-xs mt-4 hover:text-slate-600">
        <i class="fas fa-arrow-left mr-1"></i>Kembali
      </button>
    </div>

    <!-- Step 3: Reset Password -->
    <div id="step3" class="hidden">
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-key text-yellow-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-800">Buat Kata Sandi Baru</h3>
        <p class="text-slate-500 text-sm mt-2">Buat kata sandi baru yang kuat dan mudah kamu ingat.</p>
      </div>

      <form id="resetPasswordForm" onsubmit="resetPassword(event)" class="space-y-4">
        <div>
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">🔐 Kata Sandi Baru</label>
          <div class="relative">
            <input type="password" id="newPassword" name="password" placeholder="Minimal 6 karakter"
              class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required minlength="6" />
            <button type="button" onclick="toggleNewPwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <i id="newPwdEye" class="fas fa-eye text-sm"></i>
            </button>
          </div>
        </div>
        <div>
          <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-widest mb-2">✔️ Konfirmasi Kata Sandi</label>
          <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Ulangi kata sandi"
            class="form-input w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-100" required />
          <p id="step3Error" class="text-red-600 text-xs mt-2 hidden"></p>
        </div>

        <button type="submit" class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-lg transition transform active:scale-95">
          <i class="fas fa-save mr-2"></i>Simpan Kata Sandi Baru
        </button>
      </form>
    </div>

    <!-- Step 4: Sukses -->
    <div id="step4" class="hidden text-center py-4">
      <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-check-circle text-green-500 text-4xl"></i>
      </div>
      <h3 class="text-xl font-extrabold text-slate-800 mb-2">Berhasil!</h3>
      <p class="text-slate-500 text-sm mb-6">Kata sandi kamu berhasil diubah. Silakan masuk dengan kata sandi baru.</p>
      <button onclick="closeForgotModal()" class="btn-submit w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-xl font-extrabold shadow-lg transition transform active:scale-95">
        <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sekarang
      </button>
    </div>

  </div>
</div>

<script>
  // ─── Tab Switch ───────────────────────────────────────────
  function switchTab(tab) {
    if (tab === 'register') window.location.href = '{{ route('register') }}';
  }

  // ─── Toggle Password Visibility ───────────────────────────
  function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }
  function toggleNewPwd() {
    const input = document.getElementById('newPassword');
    const icon  = document.getElementById('newPwdEye');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }

  // ─── Modal ────────────────────────────────────────────────
  function openForgotModal()  { document.getElementById('forgotModal').classList.remove('hidden'); }
  function closeForgotModal() {
    document.getElementById('forgotModal').classList.add('hidden');
    resetModalSteps();
  }

  function showStep(n) {
    ['step1','step2','step3','step4'].forEach(id => document.getElementById(id).classList.add('hidden'));
    document.getElementById('step'+n).classList.remove('hidden');
  }

  function resetModalSteps() {
    showStep(1);
    document.getElementById('sendOtpForm').reset();
    document.getElementById('verifyOtpForm').reset();
    document.getElementById('resetPasswordForm').reset();
  }

  // ─── State ────────────────────────────────────────────────
  let otpToken   = null;
  let countdownInterval = null;

  // ─── Step 1: Kirim OTP ───────────────────────────────────
  async function sendOtp(e) {
    e.preventDefault();
    const btn     = document.getElementById('sendOtpBtn');
    const contact = document.getElementById('otpTarget').value.trim();
    const via     = document.querySelector('input[name="send_via"]:checked').value;
    const errEl   = document.getElementById('step1Error');

    errEl.classList.add('hidden');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

    try {
      const res = await fetch('{{ route('password.otp.send') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ contact, send_via: via })
      });
      const data = await res.json();

      if (data.success) {
        otpToken = data.token;
        document.getElementById('otpDestDisplay').textContent = data.destination;
        showStep(2);
        startCountdown();
      } else {
        errEl.textContent = data.message || 'Terjadi kesalahan. Coba lagi.';
        errEl.classList.remove('hidden');
      }
    } catch (err) {
      errEl.textContent = 'Koneksi gagal. Periksa jaringan kamu.';
      errEl.classList.remove('hidden');
    }

    btn.disabled  = false;
    btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Kode Verifikasi';
  }

  // ─── Countdown Resend ────────────────────────────────────
  function startCountdown() {
    let secs = 60;
    document.getElementById('resendBtn').classList.add('hidden');
    document.getElementById('countdownText').classList.remove('hidden');
    clearInterval(countdownInterval);
    countdownInterval = setInterval(() => {
      secs--;
      document.getElementById('countdown').textContent = secs;
      if (secs <= 0) {
        clearInterval(countdownInterval);
        document.getElementById('resendBtn').classList.remove('hidden');
        document.getElementById('countdownText').classList.add('hidden');
      }
    }, 1000);
  }

  function resendOtp() {
    document.getElementById('sendOtpForm').dispatchEvent(new Event('submit', { cancelable: true }));
  }

  function goBackStep1() {
    clearInterval(countdownInterval);
    showStep(1);
  }

  // ─── Step 2: Verifikasi OTP ──────────────────────────────
  async function verifyOtp(e) {
    e.preventDefault();
    const btn    = document.getElementById('verifyOtpBtn');
    const code   = document.getElementById('otpCode').value.trim();
    const errEl  = document.getElementById('step2Error');

    errEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memverifikasi...';

    try {
      const res = await fetch('{{ route('password.otp.check') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ otp: code, token: otpToken })
      });
      const data = await res.json();

      if (data.success) {
        otpToken = data.reset_token;   // ganti token untuk step reset
        clearInterval(countdownInterval);
        showStep(3);
      } else {
        errEl.textContent = data.message || 'Kode salah atau kadaluarsa.';
        errEl.classList.remove('hidden');
      }
    } catch (err) {
      errEl.textContent = 'Koneksi gagal. Coba lagi.';
      errEl.classList.remove('hidden');
    }

    btn.disabled  = false;
    btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Verifikasi Kode';
  }

  // ─── Step 3: Reset Password ──────────────────────────────
  async function resetPassword(e) {
    e.preventDefault();
    const pwd    = document.getElementById('newPassword').value;
    const conf   = document.getElementById('confirmPassword').value;
    const errEl  = document.getElementById('step3Error');

    errEl.classList.add('hidden');
    if (pwd !== conf) {
      errEl.textContent = 'Kata sandi tidak cocok.';
      errEl.classList.remove('hidden');
      return;
    }

    try {
      const res = await fetch('{{ route('password.reset.update') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ password: pwd, password_confirmation: conf, token: otpToken })
      });
      const data = await res.json();

      if (data.success) {
        showStep(4);
      } else {
        errEl.textContent = data.message || 'Gagal mengubah kata sandi.';
        errEl.classList.remove('hidden');
      }
    } catch (err) {
      errEl.textContent = 'Koneksi gagal. Coba lagi.';
      errEl.classList.remove('hidden');
    }
  }
</script>
@endsection