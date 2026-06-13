@extends('layouts.admin')

@section('title', 'Profil Sekolah - Admin BKK')
@section('page_title', 'Profil Sekolah')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== SIDEBAR KIRI ===== --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Card Preview --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                {{-- Banner --}}
                <div class="h-24 bg-gradient-to-r from-[#001f3f] to-[#003d6b]"></div>

                {{-- Logo & Nama --}}
                <div class="px-6 pb-6 -mt-10 text-center">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white shadow-lg border-4 border-white mx-auto flex items-center justify-center overflow-hidden">
                        @if (!empty($profile->logo ?? $profile->logo_path))
    <img src="{{ \App\Services\SchoolProfileService::storageUrl($profile->logo ?? $profile->logo_path) }}"
        class="w-full h-full object-contain p-2" id="logo-thumb">
@else
    <div id="logo-thumb-placeholder" class="text-center">
        <i class="fas fa-image text-slate-300 text-2xl"></i>
    </div>
    <img src="" class="w-full h-full object-contain p-2 hidden" id="logo-thumb">
@endif
                    </div>
                    <h2 class="font-bold text-slate-800 text-lg mt-3" id="preview-name">
                        {{ $profile->school_name ?? 'Nama Sekolah' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1" id="preview-tagline">
                        {{ $profile->tagline ?? 'Tagline sekolah' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed" id="preview-desc">
                        {{ $profile->site_description ?? 'Deskripsi sekolah akan tampil di sini.' }}
                    </p>
                </div>
            </div>

            {{-- Navigasi Tab --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Bagian</p>
                <a href="#section-identitas"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition group">
                    <span
                        class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-school text-xs"></i>
                    </span>
                    Identitas Sekolah
                </a>
                <a href="#section-kontak"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-green-50 hover:text-green-600 transition group">
                    <span
                        class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fas fa-phone text-xs"></i>
                    </span>
                    Informasi Kontak
                </a>
                <a href="#section-sosmed"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-pink-50 hover:text-pink-600 transition group">
                    <span
                        class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center group-hover:bg-pink-600 group-hover:text-white transition">
                        <i class="fas fa-share-alt text-xs"></i>
                    </span>
                    Media Sosial
                </a>
                <a href="#section-website"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-purple-50 hover:text-purple-600 transition group">
                    <span
                        class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-globe text-xs"></i>
                    </span>
                    Tampilan Website
                </a>
            </div>

            {{-- Info --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 mb-1">Tips</p>
                        <p class="text-xs text-blue-600 leading-relaxed">
                            Perubahan akan langsung tampil di semua halaman publik setelah disimpan. Cache akan otomatis
                            dibersihkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== FORM KANAN ===== --}}
        <div class="lg:col-span-2">
            <form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- ===== IDENTITAS SEKOLAH ===== --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden" id="section-identitas">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-school"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-800">Identitas Sekolah</h3>
                            <p class="text-xs text-slate-400">Nama, logo, dan info dasar sekolah</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">

                        {{-- Upload Logo --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Logo Sekolah</label>
                            <div class="flex items-center gap-5">
                                {{-- Preview --}}
                                <div class="w-20 h-20 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0"
                                    id="logo-drop-zone">
                                    @if (!empty($profile->logo ?? $profile->logo_path))
    <img src="{{ \App\Services\SchoolProfileService::storageUrl($profile->logo ?? $profile->logo_path) }}"
        class="w-full h-full object-contain p-1" alt="Logo" id="preview-logo">
@else
    <i class="fas fa-graduation-cap text-[#001f3f] text-2xl" id="logo-placeholder"></i>
    <img src="" class="w-full h-full object-contain p-1 hidden" id="preview-logo">
@endif
                                </div>
                                {{-- Input --}}
                                <div class="flex-1">
                                    <label for="logo-input"
                                        class="cursor-pointer inline-flex items-center gap-2 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition border border-slate-200 hover:border-blue-200">
                                        <i class="fas fa-upload text-xs"></i> Pilih Logo
                                    </label>
                                    <input type="file" name="logo" id="logo-input" accept="image/*" class="hidden">
                                    <p class="text-xs text-slate-400 mt-2">PNG, JPG, SVG. Maks 2MB.<br>Rekomendasi:
                                        200×200px</p>
                                </div>
                            </div>
                        </div>

                        {{-- Nama Sekolah --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Sekolah</label>
                            <input type="text" name="school_name" id="input-name" value="{{ old('school_name', $profile->school_name ?? '') }}"
                                placeholder="SMKN 1 Garut"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                        </div>

                        {{-- Tagline --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tagline</label>
                            <input type="text" name="tagline" id="input-tagline" value="{{ old('tagline', $profile->tagline ?? '') }}"
                                placeholder="Menghubungkan alumni..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Sekolah</label>
                            <textarea name="school_address" rows="2" placeholder="Jl. Cimanuk No. 309A, Garut..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ old('school_address', $profile->school_address ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- ===== INFORMASI KONTAK ===== --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden" id="section-kontak">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fas fa-phone"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-800">Informasi Kontak</h3>
                            <p class="text-xs text-slate-400">Tampil di footer website</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    <i class="fas fa-phone text-green-500 mr-1"></i> No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                                    placeholder="(0262) 233796"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    <i class="fas fa-envelope text-green-500 mr-1"></i> Email
                                </label>
                                <input type="email" name="email" value="{{ old('email', $profile->email ?? '') }}"
                                    placeholder="bkk@smkn1garut.sch.id"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('email') border-red-400 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== MEDIA SOSIAL ===== --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden" id="section-sosmed">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center">
                            <i class="fas fa-share-alt"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-800">Media Sosial</h3>
                            <p class="text-xs text-slate-400">Link tampil di footer website</p>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fab fa-instagram text-pink-500 mr-1"></i> Instagram
                            </label>
                            <input type="url" name="instagram"
                                value="{{ old('instagram', $profile->instagram ?? '') }}"
                                placeholder="https://instagram.com/..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('instagram') border-red-400 @enderror">
                            @error('instagram')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                            </label>
                            <input type="url" name="facebook"
                                value="{{ old('facebook', $profile->facebook ?? '') }}"
                                placeholder="https://facebook.com/..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('facebook') border-red-400 @enderror">
                            @error('facebook')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fab fa-twitter text-sky-500 mr-1"></i> Twitter / X
                            </label>
                            <input type="url" name="twitter" value="{{ old('twitter', $profile->twitter ?? '') }}"
                                placeholder="https://twitter.com/..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('twitter') border-red-400 @enderror">
                            @error('twitter')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fab fa-youtube text-red-500 mr-1"></i> YouTube
                            </label>
                            <input type="url" name="youtube" value="{{ old('youtube', $profile->youtube ?? '') }}"
                                placeholder="https://youtube.com/..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('youtube') border-red-400 @enderror">
                            @error('youtube')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== TAMPILAN WEBSITE ===== --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden" id="section-website">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="fas fa-globe"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-800">Tampilan Website</h3>
                            <p class="text-xs text-slate-400">Judul dan deskripsi di halaman utama</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Judul Website (Hero)
                            </label>
                            <textarea name="site_title" rows="2" placeholder="Sistem Informasi Bursa Kerja"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ old('site_title', $profile->site_title ?? '') }}</textarea>
                            <p class="text-xs text-slate-400 mt-1">Tampil sebagai judul besar di halaman beranda dan tab
                                browser. Gunakan Enter jika ingin pecah baris.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Deskripsi / Hero Text
                            </label>
                            <textarea name="site_description" id="input-desc" rows="3"
                                placeholder="Menghubungkan Talenta Alumni SMKN 1 Garut dengan Peluang Karir..."
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition resize-none">{{ old('site_description', $profile->site_description ?? '') }}</textarea>
                            <p class="text-xs text-slate-400 mt-1">Tampil sebagai subtitle di bawah judul beranda dan di
                                footer.</p>
                        </div>
                    </div>
                </div>

                {{-- ===== TOMBOL SIMPAN ===== --}}
                <div class="flex items-center justify-between bg-white rounded-2xl border border-slate-200 px-6 py-4">
                    <p class="text-xs text-slate-400">
                        <i class="fas fa-clock mr-1"></i>
                        Terakhir diperbarui:
                        {{ $profile->updated_at ? $profile->updated_at->diffForHumans() : 'Belum pernah' }}
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('public.beranda') }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                            <i class="fas fa-external-link-alt text-xs"></i> Preview
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-200 transition">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ===== PREVIEW LOGO REALTIME =====
            const logoInput = document.getElementById('logo-input');
            const logoThumb = document.getElementById('logo-thumb');
            const logoPlaceholder = document.getElementById('logo-thumb-placeholder');
            const previewLogo = document.getElementById('preview-logo');
            const logoIconPlaceholder = document.getElementById('logo-placeholder');

            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const src = e.target.result;

                        // Thumb di form
                        if (logoThumb) {
                            logoThumb.src = src;
                            logoThumb.classList.remove('hidden');
                        }
                        if (logoPlaceholder) logoPlaceholder.classList.add('hidden');

                        // Preview di sidebar
                        if (previewLogo) {
                            previewLogo.src = src;
                            previewLogo.classList.remove('hidden');
                        }
                        if (logoIconPlaceholder) logoIconPlaceholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

            // ===== PREVIEW NAMA & TAGLINE REALTIME =====
            const inputName = document.getElementById('input-name');
            const previewName = document.getElementById('preview-name');
            if (inputName && previewName) {
                inputName.addEventListener('input', function() {
                    previewName.textContent = this.value || 'Nama Sekolah';
                });
            }

            const inputTagline = document.getElementById('input-tagline');
            const previewTagline = document.getElementById('preview-tagline');
            if (inputTagline && previewTagline) {
                inputTagline.addEventListener('input', function() {
                    previewTagline.textContent = this.value || 'Tagline sekolah';
                });
            }

            // ===== PREVIEW DESKRIPSI REALTIME =====
            const inputDesc = document.getElementById('input-desc');
            const previewDesc = document.getElementById('preview-desc');
            if (inputDesc && previewDesc) {
                inputDesc.addEventListener('input', function() {
                    previewDesc.textContent = this.value || 'Deskripsi sekolah akan tampil di sini.';
                });
            }

            // ===== SMOOTH SCROLL KE SECTION =====
            document.querySelectorAll('a[href^="#section-"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

        });
    </script>
@endsection

