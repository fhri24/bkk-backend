@extends('layouts.admin')

@section('page_title', 'Tambah Berita')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Berita</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="Contoh: SMKN 1 Garut Juara Umum..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Isi Berita</label>
                        <textarea name="content" id="editor" rows="15"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">

                    {{-- UPLOAD WORD --}}
                    <div class="p-6 bg-blue-50 rounded-2xl border border-blue-200">
                        <label class="block text-sm font-bold text-blue-700 mb-2">
                            <i class="fas fa-file-word mr-1"></i> Upload dari Word (.docx)
                        </label>
                        <input type="file" id="wordFile" accept=".docx"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                        <p class="mt-2 text-xs text-blue-500">Isi berita akan otomatis terisi dari file Word.</p>
                        <div id="wordStatus" class="mt-2 text-xs font-semibold hidden"></div>
                    </div>

                    {{-- GAMBAR UTAMA --}}
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gambar Utama</label>
                        <input type="file" name="image"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-2 text-xs text-slate-400">Rekomendasi ukuran: 1200 x 630 px</p>
                    </div>

                    {{-- TAGS --}}
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tags (Pisahkan dengan koma)</label>
                        <input type="text" name="tags" value="{{ old('tags') }}"
                            placeholder="Contoh: Prestasi, BKK, Tips"
                            class="w-full px-4 py-2 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="mt-2 text-xs text-slate-400">Tags akan muncul sebagai hashtag di halaman berita.</p>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                        <i class="fas fa-paper-plane mr-2"></i> Terbitkan Berita
                    </button>

                    <a href="{{ route('admin.news.index') }}"
                        class="block w-full py-4 bg-white text-slate-500 text-center rounded-xl font-bold border border-slate-200 hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('extra_js')
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
    <script>
        CKEDITOR.replace('editor', {
            versionCheck: false,
            height: 400,
        });

        document.getElementById('wordFile').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const status = document.getElementById('wordStatus');

            if (!file) return;

            if (!file.name.endsWith('.docx')) {
                status.textContent = '❌ File harus berformat .docx';
                status.className = 'mt-2 text-xs font-semibold text-red-500';
                status.classList.remove('hidden');
                return;
            }

            status.textContent = '⏳ Sedang memproses file...';
            status.className = 'mt-2 text-xs font-semibold text-blue-500';
            status.classList.remove('hidden');

            const reader = new FileReader();
            reader.onload = function (e) {
                mammoth.convertToHtml({ arrayBuffer: e.target.result })
                    .then(function (result) {
                        // Set konten ke CKEditor
                        CKEDITOR.instances.editor.setData(result.value);
                        status.textContent = '✅ Berhasil! Isi berita sudah terisi dari file Word.';
                        status.className = 'mt-2 text-xs font-semibold text-green-600';
                    })
                    .catch(function (err) {
                        status.textContent = '❌ Gagal membaca file. Pastikan file valid.';
                        status.className = 'mt-2 text-xs font-semibold text-red-500';
                        console.error(err);
                    });
            };
            reader.readAsArrayBuffer(file);
        });
    </script>
@endsection
