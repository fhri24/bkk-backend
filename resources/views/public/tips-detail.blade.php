@extends('layouts.app')

@section('title', $tip->judul . ' - BKK SMKN 1 Garut')

@section('extra_css')
<style>
  body { font-family: 'Inter', sans-serif; }
  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #f1f1f1; }
  ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

  .step-card {
    position: relative;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .step-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37,99,235,0.10);
  }
  .step-number-badge {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    font-weight: 800;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25);
  }
  .step-connector {
    position: absolute;
    left: 22px;
    top: 60px;
    bottom: -16px;
    width: 2px;
    background: linear-gradient(to bottom, #2563eb33, transparent);
  }
</style>
@endsection

@section('content')

@php
  $colorMap = [
    'Interview'       => ['badge' => 'bg-blue-50 text-blue-600',   'icon' => 'bg-blue-50 text-blue-600'],
    'Psikotes'        => ['badge' => 'bg-purple-50 text-purple-600', 'icon' => 'bg-purple-50 text-purple-600'],
    'CV & Portofolio' => ['badge' => 'bg-orange-50 text-orange-600', 'icon' => 'bg-orange-50 text-orange-600'],
    'Dunia Kerja'     => ['badge' => 'bg-green-50 text-green-600',  'icon' => 'bg-green-50 text-green-600'],
    'Wirausaha'       => ['badge' => 'bg-amber-50 text-amber-600',  'icon' => 'bg-amber-50 text-amber-600'],
    'Lainnya'         => ['badge' => 'bg-slate-100 text-slate-500', 'icon' => 'bg-slate-100 text-slate-500'],
  ];
  $color    = $colorMap[$tip->kategori] ?? $colorMap['Lainnya'];
  $fallback = 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1200&q=80';
  $imgUrl   = $tip->image ? Storage::url($tip->image) : $fallback;
@endphp

{{-- TOMBOL KEMBALI --}}
<div class="bg-slate-100 border-b border-slate-200 py-4">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <a href="{{ route('public.tips') }}"
       class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
      <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Tips
    </a>
  </div>
</div>

{{-- MAIN CONTENT --}}
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    {{-- ===== LEFT: ARTIKEL ===== --}}
    <article class="lg:col-span-8 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 space-y-6">

      {{-- Kategori, Tanggal & Tools --}}
      <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div class="flex items-center space-x-3">
          <span class="px-3.5 py-1 {{ $color['badge'] }} text-xs font-bold uppercase rounded-full tracking-wider">
            {{ $tip->kategori }}
          </span>
          <span class="text-xs text-slate-400 flex items-center">
            <i class="fas fa-calendar-days mr-1.5 text-blue-500"></i>
            {{ $tip->created_at->translatedFormat('d F Y') }}
          </span>
        </div>
        <div class="flex items-center space-x-2">
          <button onclick="changeFontSize('dec')" title="Perkecil Teks"
            class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition text-sm font-semibold">A-</button>
          <button onclick="changeFontSize('inc')" title="Perbesar Teks"
            class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition text-base font-semibold">A+</button>
          <div class="w-px h-5 bg-slate-200 mx-1"></div>
          <button onclick="copyToClipboard()"
            class="bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 text-xs font-semibold px-3 py-2 rounded-lg transition flex items-center space-x-1.5">
            <i class="fas fa-share-nodes"></i>
            <span>Bagikan</span>
          </button>
        </div>
      </div>

      {{-- Judul & Ringkasan --}}
      <div class="space-y-4">
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
          {{ $tip->judul }}
        </h1>
        @if($tip->ringkasan)
        <p class="text-base md:text-lg text-slate-500 font-light italic border-l-4 border-blue-500 pl-4 py-1 leading-relaxed">
          {{ $tip->ringkasan }}
        </p>
        @endif
      </div>

      {{-- Toast Share --}}
      <div id="share-toast" class="hidden items-center p-4 text-emerald-800 bg-emerald-50 rounded-xl border border-emerald-200 transition-all duration-300">
        <i class="fas fa-circle-check text-lg mr-2 text-emerald-600"></i>
        <span class="text-sm font-medium">Tautan berhasil disalin! Bagikan ke teman satu perjuangan.</span>
      </div>

      {{-- FOTO UTAMA (ganti navigasi) --}}
      <div class="rounded-2xl overflow-hidden shadow-lg">
        <img src="{{ $imgUrl }}" alt="{{ $tip->judul }}" class="w-full h-auto md:h-[400px] object-cover">
      </div>

      {{-- Info Penulis --}}
      <div class="flex items-center gap-4 text-slate-600 text-sm border-y border-slate-100 py-3">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg {{ $color['icon'] }} flex items-center justify-center">
            <i class="{{ $tip->icon }} text-sm"></i>
          </div>
          <span class="font-medium">BKK SMKN 1 Garut</span>
        </div>
        <div class="flex items-center gap-2">
          <i class="fas fa-tag text-blue-600"></i>
          <span>{{ $tip->kategori }}</span>
        </div>
        @if($tip->steps->count() > 0)
        <div class="flex items-center gap-2">
          <i class="fas fa-list-ol text-blue-600"></i>
          <span>{{ $tip->steps->count() }} Langkah</span>
        </div>
        @endif
      </div>

      {{-- STEPS / LANGKAH-LANGKAH --}}
      @if($tip->steps->count() > 0)
      <div id="detail-rich-content">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-8 h-1 bg-blue-600 rounded-full"></div>
          <h2 class="text-xl font-bold text-slate-900">Langkah-Langkah</h2>
        </div>

        <div class="space-y-4">
          @foreach($tip->steps as $i => $step)
          <div class="step-card bg-white border border-slate-100 rounded-2xl p-5 flex gap-4 relative
                      @if(!$loop->last) mb-2 @endif">
            {{-- Connector line --}}
            @if(!$loop->last)
            <div class="step-connector"></div>
            @endif

            {{-- Nomor --}}
            <div class="step-number-badge">{{ $step->step_order }}</div>

            {{-- Konten --}}
            <div class="flex-1 min-w-0">
              <h3 class="text-base font-bold text-slate-900 mb-1.5 leading-snug">
                {{ $step->title }}
              </h3>
              @if($step->description)
              <p class="text-slate-600 text-sm leading-relaxed">
                {{ $step->description }}
              </p>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @elseif($tip->konten)
      {{-- Fallback: tampilkan konten lama kalau steps kosong --}}
      <div id="detail-rich-content" class="prose-content text-slate-700 text-base leading-relaxed space-y-4">
        {!! $tip->konten !!}
      </div>
      @endif

    {{-- TIPS PROFESIONAL TAMBAHAN --}}
      @if(!empty($tip->pro_tips) && count($tip->pro_tips) > 0)
      <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 md:p-8 space-y-4">
        <div class="flex items-center space-x-3 text-blue-800">
          <div class="bg-blue-600 text-white p-2 rounded-lg">
            <i class="fas fa-lightbulb text-lg"></i>
          </div>
          <h3 class="text-lg font-bold">Tips Profesional Tambahan</h3>
        </div>
        <ul class="space-y-3 text-sm md:text-base text-slate-700 pl-4 list-disc">
          @foreach($tip->pro_tips as $pt)
          <li class="leading-relaxed">{{ $pt }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      {{-- KESALAHAN UMUM --}}
      @if(!empty($tip->avoid_mistakes) && count($tip->avoid_mistakes) > 0)
      <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 md:p-8 space-y-4">
        <div class="flex items-center space-x-3 text-rose-800">
          <div class="bg-rose-600 text-white p-2 rounded-lg">
            <i class="fas fa-triangle-exclamation text-lg"></i>
          </div>
          <h3 class="text-lg font-bold">Kesalahan Umum yang Harus Dihindari</h3>
        </div>
        <ul class="space-y-3 text-sm md:text-base text-slate-700 pl-4 list-disc">
          @foreach($tip->avoid_mistakes as $am)
          <li class="leading-relaxed">{{ $am }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      {{-- Feedback --}}
      <div class="border-t border-slate-100 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <span class="text-sm text-slate-500 font-medium">Apakah panduan ini membantumu?</span>
        <div class="flex items-center space-x-2" id="feedback-buttons">
          <button onclick="handleFeedback('yes')"
            class="flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-600 border border-transparent rounded-xl text-sm font-semibold text-slate-600 transition">
            <i class="far fa-thumbs-up"></i><span>Ya, Membantu</span>
          </button>
          <button onclick="handleFeedback('no')"
            class="flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 border border-transparent rounded-xl text-sm font-semibold text-slate-600 transition">
            <i class="far fa-thumbs-down"></i><span>Biasa Saja</span>
          </button>
        </div>
        <div id="feedback-thanks" class="hidden text-sm font-semibold text-emerald-600">
          <i class="fas fa-circle-check mr-1.5"></i> Terima kasih atas masukanmu! Sukses terus!
        </div>
      </div>

    </article>

    {{-- ===== RIGHT: SIDEBAR ===== --}}
    <aside class="lg:col-span-4 space-y-8">

      {{-- BKK CTA Banner --}}
      <div class="bg-gradient-to-br from-[#002347] to-[#0b3c6f] text-white rounded-2xl p-6 shadow-md space-y-4 text-center">
        <div class="w-16 h-16 bg-white/10 text-blue-300 rounded-2xl flex items-center justify-center mx-auto text-3xl">
          <i class="fas fa-briefcase"></i>
        </div>
        <h3 class="text-lg font-bold">Siap Memulai Karir?</h3>
        <p class="text-xs text-slate-300 leading-relaxed">
          Daftarkan dirimu ke sistem Tracer Study atau jelajahi puluhan lowongan kerja terbaru yang bekerja sama dengan SMKN 1 Garut.
        </p>
        <div class="pt-2 flex flex-col gap-2">
          <a href="{{ route('public.lowongan') }}"
            class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold tracking-wide transition shadow-sm">
            Lihat Lowongan Kerja
          </a>
          <a href="{{ route('public.tracer') }}"
            class="block w-full py-2.5 bg-white/15 hover:bg-white/20 text-white rounded-xl text-xs font-semibold transition">
            Tracer Study Alumni
          </a>
        </div>
      </div>

      {{-- Rekomendasi Tips --}}
      @if($relatedTips->isNotEmpty())
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
        <h3 class="text-base font-bold text-slate-950 border-b border-slate-100 pb-3 flex items-center">
          <i class="fas fa-bookmark mr-2 text-blue-600"></i> Panduan Rekomendasi
        </h3>
        <div class="space-y-4">
          @foreach($relatedTips as $related)
          @php $relColor = $colorMap[$related->kategori] ?? $colorMap['Lainnya']; @endphp
          <a href="{{ route('public.tips.detail', $related->slug) }}"
            class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition group">
            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 mt-2 flex-shrink-0 group-hover:scale-125 transition-transform"></div>
            <div class="space-y-1">
              <span class="text-[10px] font-bold {{ explode(' ', $relColor['badge'])[1] ?? 'text-blue-600' }} uppercase tracking-wide block">
                {{ $related->kategori }}
              </span>
              <h4 class="text-xs md:text-sm font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                {{ $related->judul }}
              </h4>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Share Card --}}
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center space-y-4">
        <h4 class="text-sm font-bold text-slate-800">Bagikan Panduan Ini</h4>
        <p class="text-xs text-slate-500 leading-relaxed">Ajak alumni SMKN 1 Garut lainnya untuk sukses bersama di dunia industri.</p>
        <div class="flex items-center justify-center space-x-3">
          <a href="#" onclick="shareSocial('wa')"
            class="w-10 h-10 rounded-full bg-emerald-100 hover:bg-emerald-200 text-emerald-600 flex items-center justify-center transition" title="WhatsApp">
            <i class="fab fa-whatsapp text-lg"></i>
          </a>
          <a href="#" onclick="shareSocial('fb')"
            class="w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-600 flex items-center justify-center transition" title="Facebook">
            <i class="fab fa-facebook-f text-base"></i>
          </a>
          <a href="#" onclick="shareSocial('tw')"
            class="w-10 h-10 rounded-full bg-sky-100 hover:bg-sky-200 text-sky-600 flex items-center justify-center transition" title="Twitter/X">
            <i class="fab fa-x-twitter text-base"></i>
          </a>
          <button onclick="copyToClipboard()"
            class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition" title="Salin Tautan">
            <i class="fas fa-link text-sm"></i>
          </button>
        </div>
      </div>

    </aside>
  </div>

  {{-- Tips Terkait (bawah) --}}
  @if($relatedTips->isNotEmpty())
  <div class="mt-16 pt-10 border-t border-slate-200">
    <h3 class="text-2xl font-bold text-[#001f3f] mb-8 flex items-center">
      <span class="w-8 h-1 bg-blue-600 rounded-full mr-3 inline-block"></span> Tips Terkait
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($relatedTips->take(3) as $related)
      @php
        $relColor    = $colorMap[$related->kategori] ?? $colorMap['Lainnya'];
        $relFallback = 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=600&q=80';
        $relImg      = $related->image ? Storage::url($related->image) : $relFallback;
      @endphp
      <a href="{{ route('public.tips.detail', $related->slug) }}"
        class="group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:border-blue-100 hover:shadow-xl transition-all duration-300 block">
        <div class="aspect-[16/10] overflow-hidden bg-slate-100">
          <img src="{{ $relImg }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $related->judul }}">
        </div>
        <div class="p-5">
          <span class="text-[10px] font-bold {{ explode(' ', $relColor['badge'])[1] ?? 'text-slate-500' }} uppercase tracking-widest mb-2 block">
            {{ $related->kategori }}
          </span>
          <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-2 mb-2">{{ $related->judul }}</h4>
          <p class="text-slate-500 text-sm line-clamp-2">{{ $related->ringkasan }}</p>
          <div class="flex items-center text-blue-600 text-sm font-semibold mt-3">
            Baca Tips <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif

</main>

@endsection

@section('extra_js')
<script>
  let currentSize = 100;

  function changeFontSize(action) {
    const content = document.getElementById('detail-rich-content');
    if (!content) return;
    if (action === 'inc' && currentSize < 130) currentSize += 10;
    else if (action === 'dec' && currentSize > 90) currentSize -= 10;
    content.style.fontSize = `${currentSize}%`;
  }

  function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).catch(() => {
      const dummy = document.createElement('input');
      document.body.appendChild(dummy);
      dummy.value = window.location.href;
      dummy.select();
      document.execCommand('copy');
      document.body.removeChild(dummy);
    });
    const toast = document.getElementById('share-toast');
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    setTimeout(() => {
      toast.classList.add('hidden');
      toast.classList.remove('flex');
    }, 5000);
  }

  function shareSocial(platform) {
    const url   = encodeURIComponent(window.location.href);
    const title = encodeURIComponent("{{ $tip->judul }}");
    let shareUrl = '';
    if (platform === 'wa') shareUrl = `https://api.whatsapp.com/send?text=${title}%20-%20${url}`;
    else if (platform === 'fb') shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
    else if (platform === 'tw') shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
    window.open(shareUrl, '_blank', 'width=600,height=400');
  }

  function handleFeedback(status) {
    document.getElementById('feedback-buttons').classList.add('hidden');
    document.getElementById('feedback-thanks').classList.remove('hidden');
  }
</script>
@endsection
