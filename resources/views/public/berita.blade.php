@extends('layouts.app')

@section('title', 'Berita & Artikel - BKK SMKN 1 Garut')

@section('content')
    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-transition container mx-auto px-6 py-16">
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-4xl font-extrabold text-[#001f3f]">Berita & Artikel</h2>
        </div>

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
                            'Sekolah'   => 'text-blue-600',
                            'Tips Karir'=> 'text-green-600',
                            'Industri'  => 'text-red-600',
                            'Umum'      => 'text-slate-600',
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
@endsection

@section('extra_js')
    {{-- Script khusus halaman berita jika diperlukan --}}
@endsection