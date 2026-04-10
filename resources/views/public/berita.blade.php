@extends('layouts.app')

@section('title', 'Berita & Artikel - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="flex justify-between items-center mb-12">
        <h2 class="text-4xl font-extrabold text-[#001f3f]">Berita & Artikel</h2>
    </div>

    @if($news->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($news as $item)
                {{-- PERBAIKAN: Gunakan public.berita.detail --}}
                <article class="space-y-4 group cursor-pointer" onclick="window.location.href='{{ route('public.berita.detail', $item->id) }}'">
                    <div class="aspect-[16/10] overflow-hidden rounded-2xl bg-slate-200 shadow-sm">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition" alt="{{ $item->title }}" />
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-4xl">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Proteksi jika kategori null --}}
                    <span class="text-blue-600 font-bold text-xs uppercase tracking-widest">
                        {{ is_object($item->category) ? ($item->category->name ?? 'Berita') : ($item->category ?? 'Berita') }}
                    </span>

                    <h3 class="text-xl font-bold text-slate-800 leading-snug group-hover:text-blue-600 transition">{{ $item->title }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $item->excerpt ?? Str::limit(strip_tags($item->content), 100) }}...</p>
                    
                    <div class="flex items-center text-[10px] text-slate-400 font-bold">
                        <span>{{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ ceil(str_word_count(strip_tags($item->content)) / 200) }} MENIT BACA</span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $news->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-5xl text-slate-300 mb-4 block"></i>
            <p class="text-slate-500 text-lg">Belum ada berita yang dipublikasikan</p>
        </div>
    @endif
</div>
@endsection