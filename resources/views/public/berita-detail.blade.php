@extends('layouts.app')

@section('title', ($news->title ?? 'Detail Berita') . ' - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-3xl mx-auto">
        {{-- Tombol Kembali --}}
        <a href="{{ route('public.berita') }}" class="text-blue-600 hover:text-blue-700 font-bold mb-8 inline-flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Berita
        </a>

        <article class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">
            {{-- Bagian Gambar Utama --}}
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" class="w-full h-96 object-cover" alt="{{ $news->title }}">
            @else
                <div class="w-full h-96 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-6xl">
                    <i class="fas fa-newspaper"></i>
                </div>
            @endif

            <div class="p-8 md:p-12">
                {{-- Kategori (String/Object Protection) --}}
                <span class="text-blue-600 font-bold text-xs uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full">
                    {{ is_object($news->category) ? ($news->category->name ?? 'Berita') : ($news->category ?? 'Berita') }}
                </span>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001f3f] mt-4 mb-6 leading-tight">{{ $news->title }}</h1>

                {{-- Meta Data --}}
                <div class="flex flex-wrap items-center gap-4 md:gap-6 text-sm text-slate-500 font-medium mb-8 pb-8 border-b border-slate-100">
                    <span class="flex items-center"><i class="fas fa-calendar-alt mr-2 text-blue-400"></i>
                        {{ $news->published_at instanceof \Carbon\Carbon ? $news->published_at->format('d M Y') : $news->created_at->format('d M Y') }}
                    </span>
                    <span class="flex items-center"><i class="fas fa-user-edit mr-2 text-blue-400"></i>
                        {{ $news->author->name ?? 'Admin BKK' }}
                    </span>
                    <span class="flex items-center"><i class="fas fa-clock mr-2 text-blue-400"></i>
                        {{ ceil(str_word_count(strip_tags($news->content)) / 200) }} Menit Baca
                    </span>
                </div>

                {{-- Konten Utama --}}
                <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed prose-headings:text-[#001f3f] prose-a:text-blue-600">
                    {!! $news->content !!}
                </div>

                {{-- Bagian Share (Sudah Diperbaiki) --}}
                <div class="mt-12 pt-8 border-t border-slate-100">
                    <h3 class="font-bold text-[#001f3f] mb-4 flex items-center">
                        <i class="fas fa-share-alt mr-2"></i>Bagikan Artikel
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                           target="_blank" 
                           class="flex items-center px-5 py-2.5 bg-[#1877F2] text-white rounded-xl hover:opacity-90 transition font-semibold text-sm">
                            <i class="fab fa-facebook mr-2"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($news->title) }}" 
                           target="_blank" 
                           class="flex items-center px-5 py-2.5 bg-[#1DA1F2] text-white rounded-xl hover:opacity-90 transition font-semibold text-sm">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link berhasil disalin!')" 
                                class="flex items-center px-5 py-2.5 bg-slate-800 text-white rounded-xl hover:bg-slate-900 transition font-semibold text-sm">
                            <i class="fas fa-link mr-2"></i>Salin Link
                        </button>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>
@endsection