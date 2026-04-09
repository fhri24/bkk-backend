@extends('layouts.app')

@section('title', $berita->title . ' - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('student.berita') }}" class="text-blue-600 hover:text-blue-700 font-bold mb-8 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Berita
        </a>

        <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @if($berita->image)
                <img src="{{ $berita->image }}" class="w-full h-96 object-cover" alt="{{ $berita->title }}">
            @else
                <div class="w-full h-96 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-6xl">
                    <i class="fas fa-newspaper"></i>
                </div>
            @endif

            <div class="p-12">
                <span class="text-blue-600 font-bold text-xs uppercase tracking-widest">{{ $berita->category ?? 'Berita' }}</span>
                <h1 class="text-4xl font-extrabold text-[#001f3f] mt-4 mb-6">{{ $berita->title }}</h1>

                <div class="flex items-center gap-6 text-sm text-slate-500 font-semibold mb-8 pb-8 border-b border-slate-200">
                    <span><i class="fas fa-calendar mr-2"></i>{{ $berita->published_at->format('d M Y') }}</span>
                    <span><i class="fas fa-user mr-2"></i>{{ $berita->author->name ?? 'Admin BKK' }}</span>
                    <span><i class="fas fa-clock mr-2"></i>{{ ceil(str_word_count(strip_tags($berita->content)) / 200) }} Menit Baca</span>
                </div>

                <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed">
                    {!! $berita->content !!}
                </div>

                <div class="mt-12 pt-8 border-t border-slate-200">
                    <h3 class="font-bold text-slate-700 mb-4">Bagikan Artikel</h3>
                    <div class="flex gap-4">
                        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                            <i class="fab fa-facebook mr-2"></i>Facebook
                        </button>
                        <button class="px-6 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 font-semibold">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </button>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>
@endsection
