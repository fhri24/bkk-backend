@extends('layouts.app')

@section('title', 'Berita & Artikel - BKK SMKN 1 Garut')

@section('content')

    {{-- ===== HERO SECTION ===== --}}
    <div class="relative bg-[#001f3f] overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1600&q=80"
                class="w-full h-full object-cover opacity-30"
                alt="Background"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-[#001f3f]/80 via-[#001f3f]/60 to-[#001f3f]/90"></div>
        </div>

        <div class="relative z-10 text-center py-24 px-6">
            <span class="inline-block bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-400/30 mb-5">
                📰 Berita & Artikel BKK SMKN 1 Garut
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                Berita & Artikel
            </h1>
            <p class="text-slate-300 max-w-xl mx-auto text-base md:text-lg leading-relaxed">
                Informasi terkini seputar dunia karir, industri, dan kegiatan BKK SMKN 1 Garut.
            </p>
        </div>

        <div class="relative z-10">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full -mb-1">
                <path d="M0 60L1440 60L1440 20C1200 60 960 0 720 20C480 40 240 0 0 20L0 60Z" fill="#f1f5f9"/>
            </svg>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-transition bg-slate-100 min-h-screen">
        <div class="container mx-auto px-6 py-16">

            @if($newsItems->isEmpty())
                <div class="text-center py-20">
                    <i class="fas fa-newspaper text-6xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 text-lg">Belum ada berita yang dipublikasikan.</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($newsItems as $news)
                        @php
                            $catColors = [
                                'Sekolah'    => 'text-blue-600',
                                'Tips Karir' => 'text-green-600',
                                'Industri'   => 'text-red-600',
                                'Umum'       => 'text-slate-600',
                            ];
                            $catColor = $catColors[$news->category] ?? 'text-blue-600';

                            $fallbackImage = 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80';
                            $imageUrl = $news->image ? Storage::url($news->image) : $fallbackImage;
                        @endphp

                        <article class="space-y-4 group cursor-pointer" onclick="window.location.href='{{ route('public.berita.detail', $news->slug) }}'">
                            <div class="aspect-[16/10] overflow-hidden rounded-2xl bg-slate-200 shadow-sm">
                                <img
                                    src="{{ $imageUrl }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                    alt="{{ $news->title }}"
                                    onerror="this.onerror=null; this.src='{{ $fallbackImage }}';"
                                    loading="lazy"
                                />
                            </div>

                            <div class="space-y-2">
                                <span class="{{ $catColor }} font-bold text-xs uppercase tracking-widest">
                                    {{ $news->category ?? 'Umum' }}
                                </span>

                                <h3 class="text-xl font-bold text-slate-800 leading-snug group-hover:text-blue-600 transition">
                                    {{ $news->title }}
                                </h3>

                                <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($news->content), 120) }}
                                </p>

                                <div class="flex items-center text-[10px] text-slate-400 font-bold pt-2">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>{{ strtoupper(\Carbon\Carbon::parse($news->published_at ?? $news->created_at)->translatedFormat('d F Y')) }}</span>

                                    @if($news->read_time)
                                        <span class="mx-2">•</span>
                                        <span>{{ $news->read_time }} MENIT BACA</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($newsItems->hasPages())
                    <div class="mt-16 flex justify-center">
                        {{ $newsItems->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection

@section('extra_js') 
@endsection