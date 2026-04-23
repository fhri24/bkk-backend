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
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: SMKN 1 Garut Juara Umum..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Isi Berita</label>
                    <textarea name="content" id="editor" rows="15" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Gambar Utama</label>
                    <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-2 text-xs text-slate-400">Rekomendasi ukuran: 1200 x 630 px</p>
                </div>

                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tags (Pisahkan dengan koma)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Contoh: Prestasi, BKK, Tips"
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-2 text-xs text-slate-400">Tags akan muncul sebagai hashtag di halaman berita.</p>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i class="fas fa-paper-plane mr-2"></i> Terbitkan Berita
                </button>

                <a href="{{ route('admin.news.index') }}" class="block w-full py-4 bg-white text-slate-500 text-center rounded-xl font-bold border border-slate-200 hover:bg-slate-50 transition-all">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
@endsection
