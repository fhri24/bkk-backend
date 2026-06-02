@extends('layouts.admin')

@section('title', 'Tambah Tips Baru')
@section('page_title', 'Tambah Tips Baru')

@section('extra_css')
    <style>
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        input[type=checkbox]:checked~.toggle-bg {
            background: #2563eb;
        }

        input[type=checkbox]:checked~.toggle-dot {
            transform: translateX(20px);
        }

        .step-item {
            transition: all .2s;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-3xl">

        <a href="{{ route('admin.tips.index') }}"
            class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 text-sm font-semibold mb-6 transition">
            <i class="fas fa-chevron-left text-xs"></i> Kembali ke Daftar Tips
        </a>

        <form method="POST" action="{{ route('admin.tips.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="table-custom p-8 space-y-6">

                {{-- JUDUL --}}
                <div>
                    <label class="form-label">Judul Tips <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-input"
                        placeholder="Contoh: 7 Tips Lolos Interview Kerja" required>
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KATEGORI & ICON --}}
                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" class="form-input" required onchange="updateIcon(this.value)">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                                    {{ $kat }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Icon (Font Awesome)</label>
                        <div class="flex gap-3 items-center">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                                <i id="iconEl" class="{{ old('icon', 'fas fa-lightbulb') }}"></i>
                            </div>
                            <input type="text" name="icon" id="iconInput"
                                value="{{ old('icon', 'fas fa-lightbulb') }}" class="form-input flex-1"
                                placeholder="fas fa-lightbulb"
                                oninput="document.getElementById('iconEl').className = this.value">
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Cari di <a href="https://fontawesome.com/icons" target="_blank"
                                class="text-blue-500 underline">fontawesome.com</a>
                        </p>
                    </div>
                </div>

                {{-- RINGKASAN --}}
                <div>
                    <label class="form-label">
                        Ringkasan <span class="text-red-500">*</span>
                        <span class="normal-case font-normal text-slate-400 ml-1">(maks 500 karakter)</span>
                    </label>
                    <textarea name="ringkasan" class="form-input" rows="3" maxlength="500" required
                        placeholder="Deskripsi singkat yang tampil di card">{{ old('ringkasan') }}</textarea>
                    @error('ringkasan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- UPLOAD FOTO --}}
                <div>
                    <label class="form-label">Foto / Thumbnail</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center" id="dropzone">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden"
                            onchange="previewImage(this)">

                        <div id="imagePreview" class="hidden mb-4">
                            <img id="previewImg" src="" alt="Preview"
                                class="mx-auto max-h-48 rounded-xl object-cover">
                            <button type="button" onclick="removeImage()"
                                class="mt-2 text-xs text-red-500 hover:text-red-700 font-semibold">
                                <i class="fas fa-times mr-1"></i>Hapus foto
                            </button>
                        </div>

                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt text-slate-300 text-4xl mb-3 block"></i>
                            <p class="text-sm text-slate-500 mb-3">Drag & drop foto atau klik untuk pilih</p>
                            <button type="button" onclick="document.getElementById('imageInput').click()"
                                class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-100 transition">
                                Pilih Foto
                            </button>
                            <p class="text-xs text-slate-400 mt-2">JPG, PNG, WEBP — maks 2MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STEPS --}}
                <div>
                    <label class="form-label">Langkah-Langkah <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-400 mb-3">Tambahkan setiap langkah secara berurutan</p>

                    <div id="steps-container" class="space-y-4">
                        <div class="step-item border border-slate-200 rounded-xl p-4 bg-slate-50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-blue-600">Step <span class="step-number">1</span></span>
                                <button type="button" onclick="removeStep(this)"
                                    class="text-red-400 hover:text-red-600 text-sm font-semibold hidden">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
                            </div>
                            <input type="text" name="steps[0][title]" class="form-input mb-2"
                                placeholder="Judul langkah (contoh: Persiapkan Dokumen)" required>
                            <textarea name="steps[0][description]" class="form-input" rows="3"
                                placeholder="Penjelasan langkah ini..."></textarea>
                        </div>
                    </div>

                    <button type="button" onclick="addStep()"
                        class="mt-4 inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-100 transition">
                        <i class="fas fa-plus"></i> Tambah Step
                    </button>
                </div>
                {{-- PRO TIPS --}}
                <div>
                    <label class="form-label">Tips Profesional Tambahan</label>
                    <p class="text-xs text-slate-400 mb-3">Poin-poin tips ekstra yang ditampilkan di kotak biru</p>
                    <div id="pro-tips-container" class="space-y-2">
                        <div class="pro-tip-item flex gap-2">
                            <input type="text" name="pro_tips[]" class="form-input"
                                placeholder="Contoh: Latih intonasi bicara di depan cermin">
                            <button type="button" onclick="removePoin(this)"
                                class="text-red-400 hover:text-red-600 px-3 text-lg font-bold hidden">×</button>
                        </div>
                    </div>
                    <button type="button" onclick="addPoin('pro-tips-container', 'pro_tips')"
                        class="mt-3 inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-100 transition">
                        <i class="fas fa-plus"></i> Tambah Tips Pro
                    </button>
                </div>

                {{-- AVOID MISTAKES --}}
                <div>
                    <label class="form-label">Kesalahan Umum yang Harus Dihindari</label>
                    <p class="text-xs text-slate-400 mb-3">Poin-poin peringatan yang ditampilkan di kotak merah</p>
                    <div id="avoid-mistakes-container" class="space-y-2">
                        <div class="avoid-mistake-item flex gap-2">
                            <input type="text" name="avoid_mistakes[]" class="form-input"
                                placeholder="Contoh: Jangan menyebutkan kelemahan fatal">
                            <button type="button" onclick="removePoin(this)"
                                class="text-red-400 hover:text-red-600 px-3 text-lg font-bold hidden">×</button>
                        </div>
                    </div>
                    <button type="button" onclick="addPoin('avoid-mistakes-container', 'avoid_mistakes')"
                        class="mt-3 inline-flex items-center gap-2 bg-rose-50 text-rose-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-rose-100 transition">
                        <i class="fas fa-plus"></i> Tambah Kesalahan
                    </button>
                </div>

                {{-- URUTAN & TOGGLE --}}
                <div class="grid sm:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" min="0" value="{{ old('urutan', 0) }}"
                            class="form-input">
                        <p class="text-xs text-slate-400 mt-1">Angka kecil tampil duluan</p>
                    </div>
                    <div class="flex flex-col justify-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" name="is_published" value="1" class="sr-only" checked>
                                <div class="w-11 h-6 bg-slate-200 rounded-full toggle-bg transition"></div>
                                <div
                                    class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow toggle-dot transition">
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Publish langsung</span>
                        </label>
                    </div>
                    <div class="flex flex-col justify-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only">
                                <div class="w-11 h-6 bg-slate-200 rounded-full toggle-bg transition"></div>
                                <div
                                    class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow toggle-dot transition">
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Jadikan Unggulan ⭐</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-blue-600 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Tambah Tips
                </button>
                <a href="{{ route('admin.tips.index') }}"
                    class="px-8 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition flex items-center">
                    Batal
                </a>
            </div>

        </form>
    </div>
@endsection

@section('extra_js')
    <script>
        const defaultIcons = {
            'Interview': 'fas fa-comments',
            'Psikotes': 'fas fa-brain',
            'CV & Portofolio': 'fas fa-file-alt',
            'Dunia Kerja': 'fas fa-briefcase',
            'Wirausaha': 'fas fa-store',
            'Lainnya': 'fas fa-lightbulb',
        };

        function updateIcon(kategori) {
            const icon = defaultIcons[kategori] || 'fas fa-lightbulb';
            document.getElementById('iconInput').value = icon;
            document.getElementById('iconEl').className = icon;
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('imageInput').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadPlaceholder').classList.remove('hidden');
        }

        let stepCount = document.querySelectorAll('.step-item').length;

        function addStep() {
            const container = document.getElementById('steps-container');
            const div = document.createElement('div');
            div.className = 'step-item border border-slate-200 rounded-xl p-4 bg-slate-50';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-blue-600">Step <span class="step-number"></span></span>
                    <button type="button" onclick="removeStep(this)"
                        class="text-red-400 hover:text-red-600 text-sm font-semibold">
                        <i class="fas fa-trash-alt mr-1"></i>Hapus
                    </button>
                </div>
                <input type="text" name="steps[${stepCount}][title]" class="form-input mb-2"
                    placeholder="Judul langkah" required>
                <textarea name="steps[${stepCount}][description]" class="form-input" rows="3"
                    placeholder="Penjelasan langkah ini..."></textarea>
            `;
            container.appendChild(div);
            stepCount++;
            renumberSteps();
        }

        function removeStep(btn) {
            const items = document.querySelectorAll('.step-item');
            if (items.length <= 1) return;
            btn.closest('.step-item').remove();
            renumberSteps();
        }

        function renumberSteps() {
            const items = document.querySelectorAll('.step-item');
            items.forEach((item, i) => {
                item.querySelector('.step-number').textContent = i + 1;
                item.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/steps\[\d+\]/, `steps[${i}]`);
                });
                const btn = item.querySelector('button[onclick="removeStep(this)"]');
                if (btn) btn.classList.toggle('hidden', items.length === 1);
            });
        }

        function addPoin(containerId, fieldName) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="${fieldName}[]" class="form-input"
                    placeholder="Tulis poin di sini...">
                <button type="button" onclick="removePoin(this)"
                    class="text-red-400 hover:text-red-600 px-3 text-lg font-bold">×</button>
            `;
            container.appendChild(div);
            updateRemoveButtons(container);
        }

        function removePoin(btn) {
            const container = btn.closest('[id$="-container"]');
            const items = container.querySelectorAll('.flex');
            if (items.length <= 1) return;
            btn.closest('.flex').remove();
            updateRemoveButtons(container);
        }

        function updateRemoveButtons(container) {
            const items = container.querySelectorAll('.flex');
            items.forEach(item => {
                const btn = item.querySelector('button');
                if (btn) btn.classList.toggle('hidden', items.length === 1);
            });
        }
    </script>
@endsection
