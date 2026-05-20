@extends('layouts.app')

@section('title', 'Tracer Study Report - BKK SMKN 1 Garut')

@section('extra_css')
    <style>
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="page-transition container mx-auto px-6 py-12">
        <div id="surveyIntroHeader" class="{{ session()->has('success') || session()->has('error') || (isset($hasSubmitted) && $hasSubmitted) ? '' : 'hidden' }}">
            <div class="mb-12">
                <a href="{{ route('public.tracer') }}" class="text-blue-600 hover:text-blue-700 font-bold mb-4 inline-flex items-center">
                    <i class="fas fa-chevron-left mr-2"></i> Kembali ke Tracer Study
                </a>
                <h1 class="text-4xl font-extrabold text-[#001f3f] mb-4">Form Penelusuran Alumni</h1>
                <p class="text-slate-600 text-lg">Isi survei alumni SMKN 1 Garut sesuai petunjuk untuk membantu evaluasi dan pengembangan sekolah.</p>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-8 rounded-2xl mb-12">
                <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-info-circle text-blue-600 mr-3"></i>Pengantar Program</h2>
                <div class="space-y-4 text-slate-700">
                    <p>Kepada Yth. Alumni SMKN 1 Garut,</p>
                    <p>Dalam rangka evaluasi keberhasilan lulusan dan pengembangan kurikulum yang lebih sesuai dengan kebutuhan dunia kerja, kami mengundang Anda untuk mengisi survei tracer study ini.</p>
                    <p>Data yang Anda berikan akan digunakan secara internal untuk evaluasi dan pengembangan SMKN 1 Garut.</p>
                </div>
            </div>
        </div>

       <div id="surveyIntro" class="{{ session()->has('success') || session()->has('error') || (isset($hasSubmitted) && $hasSubmitted) ? 'hidden' : '' }} bg-white rounded-[32px] shadow-xl border border-slate-200 p-8 mb-10 max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-full bg-blue-100 mx-auto flex items-center justify-center text-blue-700 text-2xl mb-4">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-3">FORM PENELUSURAN ALUMNI</h1>
                <p class="text-slate-600">SMKN 1 Garut</p>
            </div>
            <div class="bg-slate-50 rounded-[28px] p-8 space-y-4 border border-slate-200">
                <h2 class="text-xl font-bold text-slate-900">Petunjuk Pengisian:</h2>
                <ol class="list-decimal list-inside space-y-3 text-slate-700">
                    <li>Isi identitas diri lulusan / alumni secara lengkap.</li>
                    <li>Bagi yang sudah bekerja, silakan mengisi bagian informasi pekerjaan.</li>
                    <li>Bagi yang kuliah / studi lanjut, silakan sesuaikan pengisian data.</li>
                    <li>Bagi yang berwirausaha, silakan isi kolom nama bisnis / bidang usaha.</li>
                    <li>Mohon isi masukan dan saran untuk pengembangan kurikulum sekolah di masa depan.</li>
                </ol>
                <div class="text-center pt-4">
                    <button type="button" onclick="startSurvey()" class="inline-flex items-center justify-center bg-blue-600 text-white px-8 py-4 rounded-full font-bold shadow-lg hover:bg-blue-700 transition">MULAI SURVEI</button>
                </div>
            </div>
        </div>

       <div id="surveyFormContainer" class="{{ session()->has('success') || session()->has('error') || (isset($hasSubmitted) && $hasSubmitted) ? '' : 'hidden' }} max-w-5xl mx-auto">
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5 text-green-900 shadow-sm">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-900 shadow-sm">
                    <strong>Gagal:</strong> {{ session('error') }}
                </div>
            @endif
            @if(isset($hasSubmitted) && $hasSubmitted)
                <div class="mb-6 rounded-2xl border border-yellow-300 bg-yellow-50 p-5 text-yellow-900 shadow-sm">
                    <strong>Perhatian:</strong>
                    @if(isset($myTracer) && $myTracer && $myTracer->created_at)
                        Anda sudah mengirim survei pada {{ $myTracer->created_at->format('d F Y') }}.
                    @else
                        Anda sudah mengirim survei sebelumnya.
                    @endif
                    Anda dapat memperbarui data dengan mengirim ulang formulir.
                </div>
            @endif
            <form action="{{ route('student.tracer.store') }}" method="POST" id="alumniForm" class="space-y-8">
                @csrf

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-user text-blue-600 mr-3"></i>Identitas Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Lengkap *</label>
                            <input name="nama" type="text" value="{{ old('nama', $authStudent->full_name ?? '') }}" placeholder="Nama Lengkap sesuai Ijazah" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">NIK *</label>
                            <input name="nik" type="text" value="{{ old('nik', $authStudent->nis ?? '') }}" placeholder="16 Digit Nomor Induk Kependudukan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" required />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Tempat Lahir</label>
                                <input name="tempat_lahir" type="text" placeholder="Kota Lahir" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Tanggal Lahir</label>
                                <input name="tgl_lahir" type="date" value="{{ old('tgl_lahir') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" />
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Alamat Lengkap</label>
                            <textarea name="alamat" placeholder="Alamat Domisili Saat Ini" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 h-20">{{ old('alamat', $authStudent->address ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">No. HP / WhatsApp Aktif *</label>
                            <input name="telepon" type="tel" value="{{ old('telepon') }}" placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Email (Aktif) *</label>
                            <input name="email" type="email" value="{{ old('email') }}" placeholder="Alamat Email Aktif" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" required />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-graduation-cap text-blue-600 mr-3"></i>Data Sekolah</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Tahun Lulus *</label>
                            <input name="tahun_lulus" type="number" value="{{ old('tahun_lulus', $authStudent->graduation_year ?? '') }}" placeholder="Contoh: 2024" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Konsentrasi Keahlian / Jurusan *</label>
                            <select name="jurusan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->name }}" {{ (old('jurusan', $authStudent->major ?? '') == $major->name) ? 'selected' : '' }}>{{ $major->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center"><i class="fas fa-question-circle text-blue-200 mr-3"></i>Status Kegiatan Setelah Lulus</h3>
                    <div>
                        <label class="block text-xs font-bold text-blue-200 uppercase mb-4">Apa kegiatan Anda saat ini? *</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 bg-white/10 border-2 border-white/20 rounded-xl cursor-pointer hover:bg-white/20 transition group">
                                <input type="radio" name="kegiatan_utama" value="Bekerja" class="mr-3 w-5 h-5" onclick="showSection('section-bekerja')" required />
                                <span class="font-bold text-white group-hover:translate-x-1 transition">Bekerja</span>
                            </label>
                            <label class="flex items-center p-4 bg-white/10 border-2 border-white/20 rounded-xl cursor-pointer hover:bg-white/20 transition group">
                                <input type="radio" name="kegiatan_utama" value="Kuliah" class="mr-3 w-5 h-5" onclick="showSection('section-kuliah')" required />
                                <span class="font-bold text-white group-hover:translate-x-1 transition">Melanjutkan Kuliah</span>
                            </label>
                            <label class="flex items-center p-4 bg-white/10 border-2 border-white/20 rounded-xl cursor-pointer hover:bg-white/20 transition group">
                                <input type="radio" name="kegiatan_utama" value="Wirausaha" class="mr-3 w-5 h-5" onclick="showSection('section-wirausaha')" required />
                                <span class="font-bold text-white group-hover:translate-x-1 transition">Wirausaha</span>
                            </label>
                            <label class="flex items-center p-4 bg-white/10 border-2 border-white/20 rounded-xl cursor-pointer hover:bg-white/20 transition group">
                                <input type="radio" name="kegiatan_utama" value="Lainnya" class="mr-3 w-5 h-5" onclick="showSection('section-lainnya')" required />
                                <span class="font-bold text-white group-hover:translate-x-1 transition">Belum Bekerja / Lainnya</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="section-bekerja" class="hidden dynamic-section bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-briefcase text-blue-600 mr-3"></i>Detail Pekerjaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Lokasi Kerja</label>
                            <select name="lokasi_kerja" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="Dalam Negeri">Dalam Negeri</option>
                                <option value="Luar Negeri">Luar Negeri</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Perusahaan / Industri</label>
                            <input type="text" name="nama_pt" placeholder="PT. ABC Indonesia" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Alamat Perusahaan</label>
                            <input type="text" name="alamat_pt" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Posisi / Jabatan</label>
                            <input type="text" name="jabatan" placeholder="Staff IT / Teknisi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">TMT Bekerja</label>
                            <input type="date" name="tmt_kerja" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Penghasilan per Bulan</label>
                            <select name="gaji_bekerja" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="">Pilih Range Gaji</option>
                                <option value="< Rp2 Juta">< Rp2 Juta</option>
                                <option value="Rp2 Juta - Rp5 Juta">Rp2 Juta - Rp5 Juta</option>
                                <option value="Rp5 Juta - Rp10 Juta">Rp5 Juta - Rp10 Juta</option>
                                <option value="> Rp10 Juta">> Rp10 Juta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="section-kuliah" class="hidden dynamic-section bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-university text-blue-600 mr-3"></i>Detail Perguruan Tinggi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Status Perguruan Tinggi</label>
                            <select name="status_pt" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="PTN (Negeri)">PTN (Negeri)</option>
                                <option value="PTS (Swasta)">PTS (Swasta)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama PT & Fakultas</label>
                            <input type="text" name="nama_univ" placeholder="Contoh: UNPAD - Teknik" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Jurusan / Prodi</label>
                            <input type="text" name="prodi_univ" placeholder="Informatika" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Jenjang Kuliah</label>
                            <select name="jenjang_univ" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="D4 / S1">D4 / S1</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">TMT Mulai Kuliah</label>
                            <input type="date" name="tmt_kuliah" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                    </div>
                </div>

                <div id="section-wirausaha" class="hidden dynamic-section bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-store text-blue-600 mr-3"></i>Detail Wirausaha</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Usaha & Bidang Usaha</label>
                            <input type="text" name="nama_usaha" placeholder="Contoh: Kedai Kopi (Kuliner)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Status Usaha</label>
                            <select name="status_usaha" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="Milik Pribadi">Milik Pribadi</option>
                                <option value="Milik Keluarga">Milik Keluarga</option>
                                <option value="Milik Bersama">Milik Bersama</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">TMT Berwirausaha</label>
                            <input type="date" name="tmt_wirausaha" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Omzet Per Bulan</label>
                            <input type="text" name="omzet_usaha" placeholder="Masukkan nominal omzet (Contoh: Rp 5.000.000)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100" />
                        </div>
                    </div>
                </div>

                <div id="section-lainnya" class="hidden dynamic-section bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-clock text-blue-600 mr-3"></i>Detail Kegiatan</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-4">Detail Kegiatan Saat Ini</label>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="detail_lainnya" value="Mencari pekerjaan" />
                                <span>Mencari pekerjaan</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="detail_lainnya" value="Mencari tempat kuliah" />
                                <span>Mencari tempat kuliah</span>
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="detail_lainnya" value="Lainnya" id="radio-is-lainnya" />
                                <input type="text" name="is_lainnya_teks" placeholder="Lainnya (Sebutkan...)" class="flex-1 bg-slate-50 border-b border-slate-200 py-1 px-2 text-sm focus:outline-none focus:border-blue-600" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pb-8">
                    <button type="reset" class="flex-1 bg-slate-200 text-slate-700 px-6 py-4 rounded-xl font-bold hover:bg-slate-300 transition transform active:scale-95 flex items-center justify-center space-x-2">
                        <i class="fas fa-undo-alt"></i>
                        <span>Kosongkan Survey</span>
                    </button>

                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-4 rounded-xl font-bold hover:bg-blue-700 transition transform hover:scale-105 active:scale-95 shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ (isset($hasSubmitted) && $hasSubmitted) ? 'Perbarui Survey' : 'Kirim Survey' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function startSurvey() {
            document.getElementById('surveyIntro').classList.add('hidden');
            document.getElementById('surveyIntroHeader').classList.remove('hidden');
            document.getElementById('surveyFormContainer').classList.remove('hidden');
            document.getElementById('surveyFormContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function showSection(sectionId) {
            document.querySelectorAll('.dynamic-section').forEach((sec) => {
                sec.classList.add('hidden');
            });
            const target = document.getElementById(sectionId);
            if (target) {
                target.classList.remove('hidden');
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Show a simple modal notification with an OK button. The page will NOT refresh
        // until the user clicks OK.
        function showNotificationModal(status, message, onOk) {
            // remove existing if any
            const existing = document.getElementById('ajax-notification-modal');
            if (existing) existing.remove();

            const modal = document.createElement('div');
            modal.id = 'ajax-notification-modal';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative max-w-lg w-full bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-2">${status === 'success' ? 'Berhasil' : 'Perhatian'}</h3>
                    <div class="text-sm text-slate-700 mb-4">${message}</div>
                    <div class="text-right">
                        <button id="ajax-notif-ok" class="bg-blue-600 text-white px-4 py-2 rounded-md font-bold">OK</button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            document.getElementById('ajax-notif-ok').focus();
            document.getElementById('ajax-notif-ok').addEventListener('click', function() {
                modal.remove();
                if (typeof onOk === 'function') onOk();
            });
        }

        // Intercept form submit and send via AJAX. Only refresh/redirect after user clicks OK.
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('alumniForm');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalHtml = submitBtn ? submitBtn.innerHTML : null;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="ml-2">Mengirim...</span>';
                }

                const formData = new FormData(form);
                const token = formData.get('_token') || (document.querySelector('input[name="_token"]') || {}).value || '';

                let fetchResponse = null;
                try {
                    fetchResponse = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData,
                        redirect: 'manual'
                    });

                    let msg = 'Survei berhasil dikirim. Klik OK untuk melanjutkan.';
                    let status = 'success';
                    let redirectUrl = null;

                    if (fetchResponse.redirected) {
                        // server redirected (likely to a success page)
                        redirectUrl = fetchResponse.url;
                    } else {
                        const data = await fetchResponse.json().catch(() => null);
                        if (fetchResponse.ok) {
                            if (data && (data.message || data.success)) msg = data.message || data.success;
                            if (data && data.redirect) redirectUrl = data.redirect;
                        } else {
                            status = 'error';
                            msg = (data && (data.message || data.error)) ? (data.message || data.error) : 'Gagal mengirim survei.';
                        }
                    }

                    showNotificationModal(status, msg, function() {
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        } else if (status === 'success') {
                            window.location.reload();
                        }
                    });

                } catch (err) {
                    showNotificationModal('error', 'Gagal mengirim survei. Silakan coba lagi.', function() {});
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHtml;
                    }
                }
            });
        });
    </script>
@endsection
