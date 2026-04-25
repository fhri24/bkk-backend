@extends('layouts.app')

@section('title', $news->title . ' - BKK SMKN 1 Garut')

@section('content')
    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-transition container mx-auto px-6 py-12">

        {{-- Back Button --}}
        <a href="{{ route('public.berita') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold mb-8 transition group">
            <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition"></i>Kembali ke Berita
        </a>

        <article class="max-w-4xl mx-auto">

            {{-- Article Header --}}
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    @php
                        $catBgColors = [
                            'Sekolah'   => 'bg-blue-100 text-blue-600',
                            'Tips Karir'=> 'bg-green-100 text-green-600',
                            'Industri'  => 'bg-red-100 text-red-600',
                            'Umum'      => 'bg-slate-100 text-slate-600',
                        ];
                        $catStyle = $catBgColors[$news->category] ?? 'bg-blue-100 text-blue-600';
                    @endphp
                    <span class="{{ $catStyle }} font-bold text-xs uppercase tracking-widest px-4 py-2 rounded-full">
                        {{ $news->category ?? 'Umum' }}
                    </span>
                    <span class="text-slate-400 text-sm">
                        {{ \Carbon\Carbon::parse($news->published_at ?? $news->created_at)->translatedFormat('d F Y') }}
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl font-extrabold text-[#001f3f] mb-4 leading-tight">{{ $news->title }}</h1>
                
                <div class="flex items-center gap-6 text-slate-600 text-sm border-y border-slate-200 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xs"></i>
                        </div>
                        <span class="font-medium">{{ $news->author ?? 'BKK SMKN 1 Garut' }}</span>
                    </div>
                    @if($news->read_time)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-blue-600"></i>
                            <span>{{ $news->read_time }} Menit Baca</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Featured Image --}}
            @if($news->image)
                <div class="mb-10 rounded-[32px] overflow-hidden shadow-2xl">
                    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="w-full h-auto md:h-[500px] object-cover" />
                </div>
            @endif

            {{-- Article Content --}}
            <div class="prose prose-lg prose-slate max-w-none space-y-6 text-slate-700 leading-relaxed">
                {!! $news->content !!}
            </div>

            {{-- Tags --}}
            @if($news->tags && count($news->tags) > 0)
                <div class="border-t border-slate-200 pt-8 mt-12">
                    <p class="text-sm text-slate-600 mb-3 font-bold">Tags:</p>
                    <div class="flex gap-2 flex-wrap">
                        @foreach($news->tags as $tag)
                            <a href="{{ route('public.berita') }}?tag={{ $tag }}"
                               class="bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 px-4 py-1.5 rounded-full text-xs font-semibold transition-all">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Related Articles --}}
            @if(isset($relatedNews) && $relatedNews->isNotEmpty())
                <div class="mt-20 pt-12 border-t border-slate-200">
                    <h3 class="text-2xl font-bold text-[#001f3f] mb-8 flex items-center">
                        <span class="w-8 h-1 bg-blue-600 rounded-full mr-3"></span>
                        Artikel Terkait
                    </h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        @foreach($relatedNews as $related)
                            @php
                                $relFallback = 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=500&q=80';
                                $relImage = $related->image ? Storage::url($related->image) : $relFallback;
                            @endphp
                            <article class="group cursor-pointer bg-white p-4 rounded-2xl border border-transparent hover:border-blue-100 hover:shadow-xl transition-all duration-300" 
                                     onclick="window.location.href='{{ route('public.berita.detail', $related->slug) }}'">
                                <div class="aspect-[16/10] overflow-hidden rounded-xl bg-slate-200 mb-4">
                                    <img src="{{ $relImage }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $related->title }}" />
                                </div>
                                <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-2">{{ $related->title }}</h4>
                                <div class="flex items-center text-xs text-slate-400 mt-3 font-semibold">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($related->published_at ?? $related->created_at)->translatedFormat('d F Y') }}
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </article>
    </div>
@endsection

@section('extra_js')
    {{-- Custom JS for detail page --}}
@endsection