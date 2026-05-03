@extends('layouts.app')

@section('title', 'Acara & Event - BKK SMKN 1 Garut')

@section('content')

    {{-- ===== HERO SECTION ===== --}}
    <div class="relative bg-[#001f3f] overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1540575861501-7ad05823c95b?auto=format&fit=crop&w=1600&q=80"
                class="w-full h-full object-cover opacity-30"
                alt="Background"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-[#001f3f]/80 via-[#001f3f]/60 to-[#001f3f]/90"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 text-center py-24 px-6">
            <span class="inline-block bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-400/30 mb-5">
                📅 Agenda BKK SMKN 1 Garut
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                Agenda Mendatang
            </h1>
            <p class="text-slate-300 max-w-xl mx-auto text-base md:text-lg leading-relaxed">
                Ikuti berbagai kegiatan pengembangan diri dan rekrutmen bersama mitra industri.
            </p>
        </div>

        {{-- Bottom Wave --}}
        <div class="relative z-10">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full -mb-1">
                <path d="M0 60L1440 60L1440 20C1200 60 960 0 720 20C480 40 240 0 0 20L0 60Z" fill="#f1f5f9"/>
            </svg>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-transition bg-slate-100 min-h-screen">
        <div class="container mx-auto px-6 py-16">

            @if($events->isEmpty())
                <div class="text-center py-20">
                    <i class="fas fa-calendar-times text-6xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 text-lg">Belum ada acara mendatang saat ini.</p>
                    <p class="text-slate-400 text-sm mt-2">Pantau terus halaman ini untuk informasi terbaru.</p>
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        @php
                            $badgeColors = [
                                'Rekrutmen'            => 'bg-blue-600',
                                'Seminar'              => 'bg-purple-600',
                                'Workshop'             => 'bg-orange-500',
                                'Pelatihan'            => 'bg-teal-600',
                                'Sosialisasi Perusahaan' => 'bg-cyan-600',
                                'Career Fair'          => 'bg-indigo-600',
                                'Mentoring'            => 'bg-green-600',
                            ];
                            $badgeColor = $badgeColors[$event->category] ?? 'bg-blue-600';

                            $fallbackImage = 'https://images.unsplash.com/photo-1540575861501-7ad05823c95b?auto=format&fit=crop&w=800&q=80';
                            $imageUrl = $event->image ? Storage::url($event->image) : $fallbackImage;
                        @endphp

                        <div class="bg-white rounded-[24px] overflow-hidden shadow-sm border border-slate-100 flex flex-col hover:shadow-xl transition-all duration-500 group">
                            {{-- Image Container --}}
                            <div class="h-52 overflow-hidden relative bg-slate-200">
                                <img
                                    src="{{ $imageUrl }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    alt="{{ $event->title }}"
                                    onerror="this.onerror=null; this.src='{{ $fallbackImage }}';"
                                    loading="lazy"
                                />
                                {{-- Gradient overlay bottom --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

                                {{-- Badge --}}
                                <div class="absolute top-4 left-4 {{ $badgeColor }} text-white px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg">
                                    {{ $event->category ?? 'Umum' }}
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center text-blue-600 text-xs font-bold mb-3">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                                </div>
                                <h3 class="text-lg font-extrabold text-slate-800 mb-2 leading-snug">{{ $event->title }}</h3>
                                <p class="text-sm text-slate-500 mb-6 flex-1 leading-relaxed">
                                    {{ Str::limit(strip_tags($event->description), 120) }}
                                </p>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center text-xs text-slate-400 truncate max-w-[60%]">
                                        @if(str_contains(strtolower($event->location ?? ''), 'zoom') || str_contains(strtolower($event->location ?? ''), 'online'))
                                            <i class="fas fa-video mr-2 text-red-500 flex-shrink-0"></i>
                                        @else
                                            <i class="fas fa-map-marker-alt mr-2 flex-shrink-0"></i>
                                        @endif
                                        <span class="truncate">{{ $event->location ?? 'Lokasi belum ditentukan' }}</span>
                                    </div>
                                    <a href="{{ route('public.acara.detail', $event->id) }}"
                                       class="text-blue-600 font-bold text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform flex-shrink-0">
                                        Detail <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $events->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection

@section('extra_js') 
@endsection