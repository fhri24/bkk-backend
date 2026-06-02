@extends('layouts.app')

@section('title', 'Tips & Tricks Karir - BKK SMKN 1 Garut')

@section('extra_css')
<style>
  .tip-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
  .tip-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.10); }
  .kategori-btn { transition: all 0.2s ease; }
  .kategori-btn.active { background: #2563eb; color: white; border-color: #2563eb; }
</style>
@endsection

@section('content')

  {{-- HERO --}}
  <div class="bg-slate-900 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
      <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1920&q=80"
           class="w-full h-full object-cover opacity-30" alt="">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-slate-900/90"></div>
    </div>
    <div class="container mx-auto px-6 py-20 relative z-10 text-center text-white">
      <span class="inline-block bg-white/10 backdrop-blur-sm text-blue-200 text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full mb-4">
        Panduan Karir Alumni
      </span>
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Tips & Tricks Dunia Kerja</h1>
      <p class="text-lg opacity-80 max-w-2xl mx-auto leading-relaxed">
        Panduan praktis untuk mempersiapkan dirimu — dari lolos interview, psikotes, hingga membangun karir yang sukses.
      </p>
    </div>
  </div>

  <div class="page-transition container mx-auto px-6 py-16">

    {{-- FEATURED TIPS --}}
    @if($featured->count() > 0 && !request()->filled('search') && !request()->filled('kategori'))
    <div class="mb-14">
      <h2 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-5 flex items-center gap-2">
        <i class="fas fa-star text-amber-400"></i> Tips Unggulan
      </h2>
      <div class="grid md:grid-cols-3 gap-6">
        @foreach($featured->take(3) as $item)
        @php
          $fallback = 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=800&q=80';
          $featImg  = $item->image ? Storage::url($item->image) : $fallback;
        @endphp
        <a href="{{ route('public.tips.detail', $item->slug) }}"
           class="tip-card rounded-[28px] overflow-hidden block group relative">
          <div class="aspect-[16/10] overflow-hidden">
            <img src="{{ $featImg }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $item->judul }}">
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#001f3f]/95 via-[#001f3f]/40 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-300 mb-1 block">
              <i class="fas fa-star text-amber-400 mr-1"></i> {{ $item->kategori }}
            </span>
            <h3 class="text-lg font-bold mb-1 line-clamp-2">{{ $item->judul }}</h3>
            <p class="text-white/70 text-xs leading-relaxed line-clamp-2">{{ $item->ringkasan }}</p>
            <div class="mt-3 flex items-center text-blue-300 text-xs font-semibold">
              Baca selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
          </div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

    {{-- FILTER & SEARCH --}}
    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between mb-8">
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('public.tips') }}"
           class="kategori-btn px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 {{ !request('kategori') ? 'active' : '' }}">
          Semua
        </a>
        @foreach($kategoriList as $kat)
        <a href="{{ route('public.tips', ['kategori' => $kat]) }}"
           class="kategori-btn px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 {{ request('kategori') === $kat ? 'active' : '' }}">
          {{ $kat }}
          @if(isset($kategoriCount[$kat]))
            <span class="ml-1 text-xs opacity-60">({{ $kategoriCount[$kat] }})</span>
          @endif
        </a>
        @endforeach
      </div>

      <form method="GET" action="{{ route('public.tips') }}" class="flex gap-2">
        @if(request('kategori'))
          <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <div class="flex items-center bg-white border border-slate-200 rounded-xl px-4 py-2.5 gap-2 shadow-sm">
          <i class="fas fa-search text-slate-400 text-sm"></i>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Cari tips..." class="outline-none text-sm text-slate-700 bg-transparent w-44">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">Cari</button>
        @if(request()->hasAny(['search','kategori']))
          <a href="{{ route('public.tips') }}" class="bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">Reset</a>
        @endif
      </form>
    </div>

    {{-- GRID TIPS — mirip grid Berita --}}
    @if($tips->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      @foreach($tips as $tip)
      @php
        $colorMap = [
          'Interview'       => ['badge'=>'text-blue-600',   'icon_bg'=>'bg-blue-50 text-blue-600'],
          'Psikotes'        => ['badge'=>'text-purple-600', 'icon_bg'=>'bg-purple-50 text-purple-600'],
          'CV & Portofolio' => ['badge'=>'text-orange-600', 'icon_bg'=>'bg-orange-50 text-orange-600'],
          'Dunia Kerja'     => ['badge'=>'text-green-600',  'icon_bg'=>'bg-green-50 text-green-600'],
          'Wirausaha'       => ['badge'=>'text-amber-600',  'icon_bg'=>'bg-amber-50 text-amber-600'],
          'Lainnya'         => ['badge'=>'text-slate-500',  'icon_bg'=>'bg-slate-100 text-slate-500'],
        ];
        $color    = $colorMap[$tip->kategori] ?? $colorMap['Lainnya'];
        $fallback = 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=800&q=80';
        $imgUrl   = $tip->image ? Storage::url($tip->image) : $fallback;
      @endphp
      <a href="{{ route('public.tips.detail', $tip->slug) }}"
         class="tip-card bg-white border border-slate-100 rounded-[24px] overflow-hidden shadow-sm block group">
        {{-- Gambar --}}
        <div class="aspect-[16/10] overflow-hidden bg-slate-100">
          <img src="{{ $imgUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $tip->judul }}">
        </div>
        {{-- Konten card --}}
        <div class="p-6">
          <div class="flex items-center gap-2 mb-3">
            <span class="text-[10px] font-bold uppercase tracking-widest {{ $color['badge'] }}">
              {{ $tip->kategori }}
            </span>
          </div>
          <h3 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-blue-600 transition line-clamp-2">
            {{ $tip->judul }}
          </h3>
          <p class="text-slate-500 text-sm leading-relaxed mb-4 line-clamp-2">{{ $tip->ringkasan }}</p>
          <div class="flex items-center justify-between text-xs text-slate-400 font-medium">
            <span><i class="far fa-calendar-alt mr-1"></i>{{ $tip->created_at->translatedFormat('d M Y') }}</span>
            <span class="text-blue-600 font-semibold flex items-center gap-1">
              Baca Tips <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </span>
          </div>
        </div>
      </a>
      @endforeach
    </div>

    @if($tips->hasPages())
      <div class="flex justify-center">{{ $tips->links() }}</div>
    @endif

    @else
    <div class="text-center py-20">
      <i class="fas fa-search text-slate-200 text-6xl mb-6 block"></i>
      <p class="text-slate-500 font-semibold text-lg">Belum ada tips yang ditemukan</p>
      <a href="{{ route('public.tips') }}" class="inline-block mt-6 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition">
        Lihat Semua Tips
      </a>
    </div>
    @endif

  </div>

@endsection
