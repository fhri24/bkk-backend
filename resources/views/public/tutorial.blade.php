@extends('layouts.app')

@section('title', 'Tutorial Pendaftaran - BKK SMKN 1 Garut')

@section('extra_css')
<style>
    .step-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #001f3f, #003d6b);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
    }
    .step-line {
        width: 2px;
        height: 60px;
        background: #e2e8f0;
        margin: 0 auto;
    }
    .tutorial-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border-left: 4px solid #001f3f;
        transition: all 0.3s ease;
    }
    .tutorial-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Panduan Pendaftaran</h1>
        <p class="text-xl text-blue-100">Langkah demi langkah cara mendaftar dan menggunakan sistem BKK</p>
    </div>
</section>

<!-- Main Content -->
<div class="container mx-auto px-6 py-20">
    <div class="grid md:grid-cols-3 gap-12">
        <!-- Left: Steps -->
        <div class="md:col-span-2">
            <!-- Step 1: Registration -->
            <div class="mb-12">
                <div class="flex gap-6">
                    <div class="flex flex-col items-center">
                        <div class="step-number">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="flex-1 pb-12">
                        <h2 class="text-2xl font-bold text-slate-800 mb-4">Membuat Akun</h2>
                        <div class="tutorial-card">
                            <h3 class="font-bold text-slate-800 mb-3">Langkah-langkah:</h3>
                            <ol class="space-y-2 text-slate-600 ml-4">
                                <li class="list-decimal">Klik tombol <strong>"Daftar Sekarang"</strong> atau kunjungi halaman login</li>
                                <li class="list-decimal">Pilih tipe pendaftar: <strong>Alumni/Siswa</strong></li>
                                <li class="list-decimal">Isi form dengan data yang benar:
                                    <ul class="ml-4 mt-2 space-y-1 text-sm">
                                        <li>• Email (gunakan email pribadi)</li>
                                        <li>• Password (minimal 8 karakter)</li>
                                        <li>• Nama Lengkap sesuai identitas</li>
                                        <li>• Nomor Identitas Siswa (NIS)</li>
                                        <li>• Jurusan</li>
                                        <li>• Tahun Lulus</li>
                                    </ul>
                                </li>
                                <li class="list-decimal">Klik <strong>"Daftar"</strong> dan tunggu verifikasi</li>
                                <li class="list-decimal">Akun Anda siap digunakan!</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Complete Profile -->
            <div class="mb-12">
                <div class="flex gap-6">
                    <div class="flex flex-col items-center">
                        <div class="step-number">2</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="flex-1 pb-12">
                        <h2 class="text-2xl font-bold text-slate-800 mb-4">Lengkapi Profil</h2>
                        <div class="tutorial-card">
                            <h3 class="font-bold text-slate-800 mb-3">Apa yang perlu dilengkapi:</h3>
                            <ul class="space-y-2 text-slate-600 ml-4">
                                <li class="list-disc"><strong>Foto Profil</strong> - Gunakan foto formal/profesional</li>
                                <li class="list-disc"><strong>Foto CV</strong> - Upload file CV yang sudah disiapkan</li>
                                <li class="list-disc"><strong>Portofolio</strong> - Tambahkan link portfolio atau proyek Anda</li>
                                <li class="list-disc"><strong>Kontak</strong> - Nomor telepon dan alamat lengkap</li>
                                <li class="list-disc"><strong>Kompetensi</strong> - Skill atau keahlian yang Anda miliki</li>
                            </ul>
                            <p class="mt-4 p-3 bg-blue-50 text-blue-800 rounded text-sm">
                                <i class="fas fa-info-circle mr-2"></i> Profil yang lengkap meningkatkan peluang Anda untuk diterima di perusahaan!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Browse & Apply -->
            <div class="mb-12">
                <div class="flex gap-6">
                    <div class="flex flex-col items-center">
                        <div class="step-number">3</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="flex-1 pb-12">
                        <h2 class="text-2xl font-bold text-slate-800 mb-4">Cari & Lamar Lowongan</h2>
                        <div class="tutorial-card">
                            <h3 class="font-bold text-slate-800 mb-3">Cara mencari dan melamar:</h3>
                            <ol class="space-y-2 text-slate-600 ml-4">
                                <li class="list-decimal">Buka menu <strong>"Lowongan Kerja"</strong> di halaman student</li>
                                <li class="list-decimal">Gunakan filter untuk mencari:
                                    <ul class="ml-4 mt-2 space-y-1 text-sm">
                                        <li>• Posisi/Jabatan</li>
                                        <li>• Lokasi pekerjaan</li>
                                        <li>• Tipe pekerjaan (fulltime, partime, kontrak)</li>
                                        <li>• Rentang gaji</li>
                                    </ul>
                                </li>
                                <li class="list-decimal">Klik lowongan yang minat untuk melihat detail lengkap</li>
                                <li class="list-decimal">Baca deskripsi, persyaratan, dan benefit dengan teliti</li>
                                <li class="list-decimal">Klik <strong>"Lamar Sekarang"</strong> dan isi surat lamaran</li>
                                <li class="list-decimal">Lamaran Anda akan diterima oleh tim HRD perusahaan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Track Application -->
            <div class="mb-12">
                <div class="flex gap-6">
                    <div class="flex flex-col items-center">
                        <div class="step-number">4</div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-slate-800 mb-4">Monitor Lamaran</h2>
                        <div class="tutorial-card">
                            <h3 class="font-bold text-slate-800 mb-3">Fitur monitoring:</h3>
                            <ul class="space-y-2 text-slate-600 ml-4">
                                <li class="list-disc">Lihat status lamaran Anda di <strong>"Aplikasi Saya"</strong></li>
                                <li class="list-disc">Status akan berubah dari: <strong>Pending → Review → Interview → Accepted/Rejected</strong></li>
                                <li class="list-disc">Anda akan menerima notifikasi untuk setiap update status</li>
                                <li class="list-disc">Hubungi perusahaan jika ada kesempatan interview</li>
                                <li class="list-disc">Jangan lupa melaporkan hasil lamaran di fitur <strong>"Tracer"</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Quick Tips -->
        <div class="md:col-span-1">
            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <i class="fas fa-lightbulb mr-3"></i> Tips Sukses
                </h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex gap-3">
                        <i class="fas fa-check-circle flex-shrink-0 mt-1"></i>
                        <span>Gunakan email yang aktif dan mudah diakses</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check-circle flex-shrink-0 mt-1"></i>
                        <span>Upload foto yang profesional dan terlihat jelas</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check-circle flex-shrink-0 mt-1"></i>
                        <span>Baca persyaratan lowongan dengan teliti</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check-circle flex-shrink-0 mt-1"></i>
                        <span>Surat lamaran harus singkat, jelas, dan menarik</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check-circle flex-shrink-0 mt-1"></i>
                        <span>Jangan melamar terlalu banyak sekaligus</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check-circle flex-shrink-0 mt-1"></i>
                        <span>Persiapkan diri untuk interview</span>
                    </li>
                </ul>
            </div>

            <!-- FAQ Card -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">
                    <i class="fas fa-question-circle mr-2 text-yellow-500"></i> FAQ
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Apakah pendaftaran gratis?</p>
                        <p class="text-slate-600 text-sm">Ya, 100% gratis tanpa biaya apapun.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Berapa lama verifikasi akun?</p>
                        <p class="text-slate-600 text-sm">Biasanya kurang dari 24 jam.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Bisa edit data setelah daftar?</p>
                        <p class="text-slate-600 text-sm">Ya, bisa kapan saja di halaman profil.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Berapa lowongan bisa diedit?</p>
                        <p class="text-slate-600 text-sm">Unlimited atau tanpa batas.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6">
                <h3 class="font-bold mb-3 flex items-center">
                    <i class="fas fa-headset mr-2"></i> Butuh Bantuan?
                </h3>
                <p class="text-sm mb-4">Tim BKK kami siap membantu Anda!</p>
                <div class="space-y-2 text-sm">
                    <p class="flex items-center"><i class="fas fa-phone mr-2"></i> (0262) 123-4567</p>
                    <p class="flex items-center"><i class="fas fa-envelope mr-2"></i> bkk@smkn1garut.sch.id</p>
                    <p class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> SMKN 1 Garut</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
