@extends('layouts.app')

@section('title', 'Survey Kepuasan Pengguna Lulusan - BKK SMKN 1 Garut')

@section('content')
    <div class="page-transition container mx-auto px-6 py-12">
        <div class="mb-12">
            <a href="{{ route('public.tracer') }}" class="text-green-600 hover:text-green-700 font-bold mb-4 inline-flex items-center">
                <i class="fas fa-chevron-left mr-2"></i>Kembali ke Tracer Study
            </a>
            <h1 class="text-4xl font-extrabold text-[#001f3f] mb-4">Survey Kepuasan Pengguna Lulusan</h1>
            <p class="text-slate-600 text-lg">Formulir evaluasi performa lulusan SMKN 1 Garut dari perspektif industri.</p>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-8 rounded-2xl mb-12">
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-info-circle text-green-600 mr-3"></i>Pengantar Survei</h2>
            <div class="space-y-4 text-slate-700">
                <p>Kepada Yth. Pimpinan/Atasan Langsung dan Pengguna Lulusan SMKN 1 Garut,</p>
                <p>
                    Dalam rangka peningkatan mutu lulusan dan memenuhi kebutuhan untuk kelengkapan akreditasi serta penyiapan kompetensi lulusan agar lebih relevan dengan kebutuhan industri, Bursa Kerja Khusus SMKN 1 Garut mengundang Bapak/Ibu
                    untuk mengisi formulir survey kepuasan pengguna lulusan.
                </p>
                <p>
                    Kami dengan hormat memohon bantuan Bapak/Ibu sebagai atasan langsung untuk memberikan penilaian, masukan, dan saran terhadap kinerja lulusan kami yang bekerja di perusahaan Bapak/Ibu. Data isian tersebut sangat kami perlukan
                    sebagai feedback bagi pengembangan sekolah dan program studi.
                </p>
            </div>
        </div>

        <form id="surveyForm" class="space-y-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-building text-green-600 mr-3"></i>Informasi Perusahaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Perusahaan *</label>
                        <select required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100">
                            <option value="">Pilih Nama Perusahaan</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->company_id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Industri *</label>
                        <input type="text" placeholder="Masukkan Nama Industri" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Alamat Perusahaan *</label>
                        <input type="text" placeholder="Masukkan Alamat Perusahaan" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Bisnis Utama Perusahaan *</label>
                        <input type="text" placeholder="Contoh: Manufaktur, Teknologi, Keuangan, dll" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-user-tie text-green-600 mr-3"></i>Informasi Responden</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Responden *</label>
                        <input type="text" placeholder="Masukkan Nama Responden" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Jabatan Responden *</label>
                        <input type="text" placeholder="Contoh: HRD Manager, Pimpinan, Owner, dll" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Email Responden *</label>
                        <input type="email" placeholder="Masukkan Email Responden" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-star text-green-600 mr-3"></i>Penilaian Kemampuan Lulusan</h3>
                <p class="text-sm text-slate-600 mb-6 bg-slate-50 p-4 rounded-xl">
                    Silahkan isi penilaian kemampuan lulusan SMKN 1 Garut yang bekerja di perusahaan Bapak/Ibu dengan skala:
                    <strong>1 = Sangat Kurang</strong>, <strong>2 = Kurang</strong>, <strong>3 = Cukup</strong>, <strong>4 = Baik</strong>, <strong>5 = Sangat Baik</strong>
                </p>

                <div class="space-y-4">
                    @foreach([
                        'Integritas (Etika dan Moral)' => 'integritas',
                        'Keahlian Berdasarkan Bidang Ilmu' => 'keahlian',
                        'Keterampilan Bahasa Inggris' => 'bahasa',
                        'Penggunaan Teknologi & TIK' => 'teknologi',
                        'Keterampilan Komunikasi' => 'komunikasi',
                        'Kerja Sama Tim' => 'teamwork',
                        'Pemikiran Analitis & Inovasi' => 'analitis',
                        'Kemampuan Kepemimpinan' => 'kepemimpinan',
                        'Kemampuan Bekerja Dibawah Tekanan' => 'tekanan',
                    ] as $label => $name)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-4 border-b border-slate-200 hover:bg-slate-50 px-3 rounded">
                            <span class="font-semibold text-slate-800 flex-1">{{ $label }}</span>
                            <div class="flex gap-3 flex-wrap md:flex-nowrap">
                                @for($score = 1; $score <= 5; $score++)
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="{{ $name }}" value="{{ $score }}" class="mr-2" />
                                        <span class="text-sm">{{ $score }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center"><i class="fas fa-lightbulb text-green-600 mr-3"></i>Masukan & Saran</h3>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-4">Berikan saran untuk peningkatan kualitas lulusan SMKN 1 Garut</label>
                <textarea id="saran" name="saran" placeholder="Tulis masukan dan saran Bapak/Ibu di sini..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 h-32 resize-none"></textarea>
            </div>

            <div class="flex gap-4 pb-8">
                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-4 rounded-xl font-bold hover:bg-green-700 transition transform hover:scale-105 active:scale-95 shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Survey
                </button>
                <a href="{{ route('public.tracer') }}" class="flex-1 bg-slate-200 text-slate-800 px-6 py-4 rounded-xl font-bold hover:bg-slate-300 transition text-center">Batal</a>
            </div>
        </form>
    </div>
@endsection

@section('extra_js')
    <script>
        document.getElementById('surveyForm').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Terima kasih atas partisipasi Bapak/Ibu dalam survey kepuasan pengguna lulusan SMKN 1 Garut. Data Anda telah kami terima dan akan digunakan untuk meningkatkan kualitas pendidikan kami. Hormat kami.');
            this.reset();
            window.location.href = '{{ route('public.tracer') }}';
        });
    </script>
@endsection
