@extends('layouts.app')

@section('title', $news->title . ' - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-12">
    <a href="{{ route('student.berita') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-bold mb-8 transition">
        <i class="fas fa-chevron-left mr-2"></i>Kembali ke Berita
    </a>

    <article class="max-w-4xl mx-auto">
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="bg-blue-100 text-blue-600 font-bold text-xs uppercase tracking-widest px-4 py-2 rounded-full">
                    {{ is_object($news->category) ? ($news->category->name ?? 'Berita') : ($news->category ?? 'Berita') }}
                </span>
                <span class="text-slate-400 text-sm">
                    {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d F Y') : $news->created_at->format('d F Y') }}
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-extrabold text-[#001f3f] mb-4 leading-tight">
                {{ $news->title }}
            </h1>

            <div class="flex items-center gap-6 text-slate-600 text-sm border-t border-b border-slate-200 py-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-circle text-blue-600"></i>
                    <span>{{ $news->author->name ?? 'BKK SMKN 1 Garut' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-blue-600"></i>
                    <span>{{ ceil(str_word_count(strip_tags($news->content)) / 200) }} Menit Baca</span>
                </div>
            </div>
        </div>

        <div class="mb-10 rounded-2xl overflow-hidden shadow-lg bg-slate-200 border border-slate-100">
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-auto max-h-[500px] object-cover" />
            @else
                <div class="w-full h-[400px] bg-gradient-to-br from-[#001f3f] to-blue-900 flex items-center justify-center text-white text-6xl">
                    <i class="fas fa-newspaper opacity-20"></i>
                </div>
            @endif
        </div>

        <div class="prose prose-lg max-w-none prose-slate prose-headings:text-[#001f3f] prose-a:text-blue-600 text-slate-700 leading-relaxed">
            {!! $news->content !!}
        </div>

        @if($news->tags)
        <div class="border-t border-slate-200 pt-8 mt-12">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-tags text-blue-600"></i>
                <span class="font-bold text-slate-800">Tags:</span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $news->tags) as $tag)
                    <span class="bg-slate-100 text-slate-700 px-4 py-1.5 rounded-full text-xs font-semibold border border-slate-200 transition hover:bg-blue-50 hover:text-blue-600">
                        #{{ trim($tag) }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($relatedNews) && $relatedNews->count() > 0)
        <div class="mt-16 pt-12 border-t border-slate-200">
            <h3 class="text-2xl font-bold text-slate-900 mb-8 flex items-center gap-3">
                <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                Artikel Terkait
            </h3>
            <div class="grid md:grid-cols-2 gap-8">
                @foreach($relatedNews as $related)
                {{-- ROUTE DETAIL DISESUAIKAN --}}
                <article class="group cursor-pointer" onclick="window.location.href = '{{ route('student.berita.detail', $related->slug) }}'">
                    <div class="aspect-[16/10] overflow-hidden rounded-xl bg-slate-200 mb-4 shadow-sm border border-slate-100">
                        @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" />
                        @else
                            <div class="w-full h-full bg-slate-300 flex items-center justify-center text-white">
                                <i class="fas fa-newspaper text-3xl opacity-50"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition leading-snug">{{ $related->title }}</h4>
                    <p class="text-xs text-slate-500 mt-2 font-medium uppercase tracking-wider">
                        {{ $related->created_at->format('d M Y') }} • {{ ceil(str_word_count(strip_tags($related->content)) / 200) }} MIN BACA
                    </p>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </article>
</div>
@endsection
