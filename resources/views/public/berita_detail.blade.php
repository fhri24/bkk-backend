@extends('layouts.app')

@section('content')
<main class="pt-24 pb-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">

            {{-- Header Berita --}}
            <div class="text-center mb-12">
                <p class="text-blue-600 font-bold uppercase tracking-widest text-sm mb-4">Informasi Terbaru</p>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-6">
                    {{ $news->title }}
                </h1>
                <div class="flex items-center justify-center gap-6 text-slate-500 font-medium text-sm md:text-base">
                    {{-- Perbaikan: Pastikan relasi author ada atau default ke Admin --}}
                    <span class="flex items-center gap-2"><i class="far fa-user text-blue-500"></i> {{ $news->user->name ?? 'Admin BKK' }}</span>
                    <span class="flex items-center gap-2"><i class="far fa-calendar text-blue-500"></i> {{ $news->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            {{-- Main Image --}}
            <div class="group relative rounded-[40px] overflow-hidden shadow-2xl mb-12 ring-1 ring-slate-100">
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" class="w-full h-[300px] md:h-[500px] object-cover transition duration-700 group-hover:scale-105">
                @else
                    <img src="https://images.unsplash.com/photo-1504711432869-0df30b7eab4c?q=80&w=1000&auto=format&fit=crop" class="w-full h-[300px] md:h-[500px] object-cover">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>

            {{-- Body Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <div class="lg:col-span-8">
                    {{-- Konten Utama --}}
                    <div class="prose prose-lg max-w-none text-slate-600 leading-relaxed mb-16">
                        {!! $news->content !!}
                    </div>

                    {{-- FITUR BARU: BERITA SELANJUTNYA --}}
                    <div class="border-t border-slate-100 pt-10">
                        <h4 class="text-slate-900 font-bold text-lg mb-6 flex items-center gap-2">
                            <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                            Baca Berita Lainnya
                        </h4>

                        @php
                            // Logika: Cari berita yang dibuat sebelum berita ini
                            $next_news = \App\Models\News::where('id', '<', $news->id)->orderBy('id', 'desc')->first();

                            // Kalau nggak ada berita sebelumnya (berita paling pertama), ambil yang paling baru
                            if(!$next_news) {
                                $next_news = \App\Models\News::where('id', '!=', $news->id)->latest()->first();
                            }
                        @endphp

                        @if($next_news)
                        <a href="{{ route('student.berita.detail', $next_news->id) }}" class="group block p-6 bg-slate-50 rounded-[2rem] border border-transparent hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-300">
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <div class="w-full md:w-32 h-24 rounded-2xl overflow-hidden flex-shrink-0 shadow-sm">
                                    @if($next_news->image)
                                        <img src="{{ asset('storage/' . $next_news->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full bg-blue-600 flex items-center justify-center text-white">
                                            <i class="fas fa-newspaper opacity-30"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 text-center md:text-left">
                                    <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition duration-300 line-clamp-1">
                                        {{ $next_news->title }}
                                    </h3>
                                    <p class="text-blue-600 text-sm font-bold mt-2 flex items-center justify-center md:justify-start gap-2">
                                        Lanjut Baca <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-2 transition-transform"></i>
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Sidebar Info --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-28 space-y-8">
                        {{-- Card Bantuan --}}
                        <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 shadow-sm">
                            <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                                <i class="fas fa-question text-xl"></i>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-4 text-xl">Butuh Bantuan?</h4>
                            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Punya pertanyaan atau butuh informasi lebih lanjut mengenai berita ini?</p>
                            <a href="https://wa.me/62812345678" class="block w-full py-4 bg-green-500 text-white text-center rounded-2xl font-bold hover:bg-green-600 transition-all shadow-lg shadow-green-100 active:scale-95">
                                <i class="fab fa-whatsapp mr-2"></i> Chat Admin BKK
                            </a>
                        </div>

                        {{-- Tombol Kembali --}}
                        <a href="{{ route('student.berita') }}" class="flex items-center justify-center gap-2 text-slate-400 font-bold hover:text-blue-600 transition-colors py-4 group">
                            <i class="fas fa-chevron-left text-xs group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Berita
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
