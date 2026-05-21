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

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5 text-green-900 shadow-sm">
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-900 shadow-sm">
                <strong>Gagal:</strong> {{ session('error') }}
            </div>
        @endif

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-8 rounded-2xl mb-12">
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-green-600 mr-3"></i>Pengantar Survei
            </h2>
            <div class="space-y-4 text-slate-700">
                <p>Kepada Yth. Pimpinan/Atasan Langsung dan Pengguna Lulusan SMKN 1 Garut,</p>
                <p>Dalam rangka peningkatan mutu lulusan dan memenuhi kebutuhan untuk kelengkapan akreditasi serta penyiapan kompetensi lulusan agar lebih relevan dengan kebutuhan industri, Bursa Kerja Khusus SMKN 1 Garut mengundang Bapak/Ibu untuk mengisi formulir survey kepuasan pengguna lulusan.</p>
                <p>Kami dengan hormat memohon bantuan Bapak/Ibu sebagai atasan langsung untuk memberikan penilaian, masukan, dan saran terhadap kinerja lulusan kami yang bekerja di perusahaan Bapak/Ibu.</p>
            </div>
        </div>

        <form action="{{ route('company.tracer.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Informasi Perusahaan --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                    <i class="fas fa-building text-green-600 mr-3"></i>Informasi Perusahaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Perusahaan *</label>
                        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                               placeholder="Masukkan Nama Perusahaan" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('nama_perusahaan') border-red-500 @enderror" />
                        @error('nama_perusahaan')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Jenis Perusahaan (Badan Hukum) *</label>
                        <select name="jenis_perusahaan" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('jenis_perusahaan') border-red-500 @enderror">
                            <option value="">Pilih Jenis Perusahaan</option>
                            <option value="PT"       {{ old('jenis_perusahaan') == 'PT' ? 'selected' : '' }}>PT (Perseroan Terbatas)</option>
                            <option value="CV"       {{ old('jenis_perusahaan') == 'CV' ? 'selected' : '' }}>CV (Commanditaire Vennootschap)</option>
                            <option value="Koperasi" {{ old('jenis_perusahaan') == 'Koperasi' ? 'selected' : '' }}>Koperasi</option>
                            <option value="BUMN"     {{ old('jenis_perusahaan') == 'BUMN' ? 'selected' : '' }}>BUMN</option>
                            <option value="BUMD"     {{ old('jenis_perusahaan') == 'BUMD' ? 'selected' : '' }}>BUMD</option>
                            <option value="Lainnya"  {{ old('jenis_perusahaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_perusahaan')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Alamat Perusahaan *</label>
                        <input type="text" name="alamat_perusahaan" value="{{ old('alamat_perusahaan') }}"
                               placeholder="Masukkan Alamat Perusahaan" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('alamat_perusahaan') border-red-500 @enderror" />
                        @error('alamat_perusahaan')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Bisnis Utama Perusahaan *</label>
                        <input type="text" name="bisnis_utama" value="{{ old('bisnis_utama') }}"
                               placeholder="Contoh: Manufaktur, Teknologi, Keuangan, dll" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('bisnis_utama') border-red-500 @enderror" />
                        @error('bisnis_utama')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Informasi Responden --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                    <i class="fas fa-user-tie text-green-600 mr-3"></i>Informasi Responden
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Nama Responden *</label>
                        <input type="text" name="nama_responden" value="{{ old('nama_responden') }}"
                               placeholder="Masukkan Nama Responden" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('nama_responden') border-red-500 @enderror" />
                        @error('nama_responden')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Jabatan Responden *</label>
                        <input type="text" name="jabatan_responden" value="{{ old('jabatan_responden') }}"
                               placeholder="Contoh: HRD Manager, Pimpinan, Owner, dll" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('jabatan_responden') border-red-500 @enderror" />
                        @error('jabatan_responden')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Email Responden *</label>
                        <input type="email" name="email_responden" value="{{ old('email_responden') }}"
                               placeholder="Masukkan Email Responden" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 @error('email_responden') border-red-500 @enderror" />
                        @error('email_responden')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Penilaian --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                    <i class="fas fa-star text-green-600 mr-3"></i>Penilaian Kemampuan Lulusan
                </h3>
                <p class="text-sm text-slate-600 mb-6 bg-slate-50 p-4 rounded-xl">
                    Silahkan isi penilaian kemampuan lulusan SMKN 1 Garut yang bekerja di perusahaan Bapak/Ibu dengan skala:
                    <strong>1 = Sangat Kurang</strong>, <strong>2 = Kurang</strong>, <strong>3 = Cukup</strong>,
                    <strong>4 = Baik</strong>, <strong>5 = Sangat Baik</strong>
                </p>
                <div class="space-y-4">
                    @foreach([
                        'Integritas (Etika dan Moral)'       => 'nilai_integritas',
                        'Keahlian Berdasarkan Bidang Ilmu'   => 'nilai_keahlian',
                        'Keterampilan Bahasa Inggris'        => 'nilai_bahasa_inggris',
                        'Penggunaan Teknologi & TIK'         => 'nilai_teknologi',
                        'Keterampilan Komunikasi'            => 'nilai_komunikasi',
                        'Kerja Sama Tim'                     => 'nilai_kerjasama',
                        'Pemikiran Analitis & Inovasi'       => 'nilai_analitis',
                        'Kemampuan Kepemimpinan'             => 'nilai_kepemimpinan',
                        'Kemampuan Bekerja Dibawah Tekanan'  => 'nilai_tekanan',
                    ] as $label => $name)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-4 border-b border-slate-200 hover:bg-slate-50 px-3 rounded">
                            <span class="font-semibold text-slate-800 flex-1">{{ $label }}</span>
                            <div class="flex gap-3 flex-wrap md:flex-nowrap">
                                @for($score = 1; $score <= 5; $score++)
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="{{ $name }}" value="{{ $score }}"
                                               {{ old($name) == $score ? 'checked' : '' }}
                                               class="mr-2" required />
                                        <span class="text-sm">{{ $score }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        @error($name)
                            <span class="text-xs text-red-600 block px-3">{{ $message }}</span>
                        @enderror
                    @endforeach
                </div>
            </div>

            {{-- Masukan & Saran --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                    <i class="fas fa-lightbulb text-green-600 mr-3"></i>Masukan & Saran
                </h3>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-4">
                    Berikan saran untuk peningkatan kualitas lulusan SMKN 1 Garut
                </label>
                <textarea name="saran" placeholder="Tulis masukan dan saran Bapak/Ibu di sini..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 h-32 resize-none @error('saran') border-red-500 @enderror">{{ old('saran') }}</textarea>
                @error('saran')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex gap-4 pb-8">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-6 py-4 rounded-xl font-bold hover:bg-green-700 transition transform hover:scale-105 active:scale-95 shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Survey
                </button>
                <a href="{{ route('public.tracer') }}"
                   class="flex-1 bg-slate-200 text-slate-800 px-6 py-4 rounded-xl font-bold hover:bg-slate-300 transition text-center">
                    Batal
                </a>
            </div>

        </form>
    </div>
@endsection