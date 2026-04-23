@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Berita</h1>
        <a href="{{ route('admin.news.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Judul Berita --}}
        <div class="mb-5">
            <label class="block font-bold text-gray-700 mb-2">Judul Berita</label>
            <input type="text" name="title" value="{{ old('title', $news->title) }}"
                   class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Masukkan judul berita" required>
        </div>

        {{-- Konten --}}
        <div class="mb-5">
            <label class="block font-bold text-gray-700 mb-2">Konten</label>
            <textarea name="content" rows="8"
                      class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Tulis isi berita di sini..." required>{{ old('content', $news->content) }}</textarea>
        </div>

        {{-- Tags (INI YANG TADI GAK ADA) --}}
        <div class="mb-5">
            <label class="block font-bold text-gray-700 mb-2">Tags</label>
            <input type="text" name="tags" value="{{ old('tags', $news->tags) }}"
                   class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: magang, smk, industri (pisahkan dengan koma)">
            <p class="text-xs text-gray-400 mt-1 italic">*Pisahkan setiap tag dengan tanda koma (,)</p>
        </div>

        {{-- Gambar --}}
        <div class="mb-6">
            <label class="block font-bold text-gray-700 mb-2">Gambar Berita</label>

            @if($news->image)
                <div class="mb-3">
                    <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                    <img src="{{ asset('storage/' . $news->image) }}" class="w-40 h-24 object-cover rounded-lg border">
                </div>
            @endif

            <input type="file" name="image"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-400 mt-2">Biarkan kosong jika tidak ingin mengganti gambar. Maksimal 2MB.</p>
        </div>

        {{-- Button --}}
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-bold transition shadow-lg">
                Update Berita
            </button>
            <button type="reset" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-8 py-2.5 rounded-lg font-bold transition">
                Reset
            </button>
        </div>
    </form>
</div>
@endsection
