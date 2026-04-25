@extends('layouts.app')

@section('title', 'Acara & Event - BKK SMKN 1 Garut')

@section('content')
    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-transition container mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-[#001f3f] mb-4">Agenda Mendatang</h2>
            <p class="text-slate-500 max-w-xl mx-auto">
                Ikuti berbagai kegiatan pengembangan diri dan rekrutmen bersama mitra industri.
            </p>
        </div>

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
                            'Rekrutmen' => 'bg-blue-600',
                            'Seminar'   => 'bg-purple-600',
                            'Workshop'  => 'bg-orange-600',
                            'Pelatihan' => 'bg-teal-600',
                        ];
                        $badgeColor = $badgeColors[$event->category] ?? 'bg-blue-600';

                        $fallbackImage = 'https://images.unsplash.com/photo-1540575861501-7ad05823c95b?auto=format&fit=crop&w=800&q=80';
                        $imageUrl = $event->image ? Storage::url($event->image) : $fallbackImage;
                    @endphp

                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 flex flex-col hover:shadow-2xl transition duration-500 group">
                        <div class="h-56 overflow-hidden relative">
                            <img
                                src="{{ $imageUrl }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                alt="{{ $event->title }}"
                            />
                            <div class="absolute top-4 left-4 {{ $badgeColor }} text-white px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg">
                                {{ $event->category ?? 'Umum' }}
                            </div>
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex items-center text-blue-600 text-xs font-bold mb-4">
                                <i class="far fa-calendar-alt mr-2"></i>
                                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-800 mb-3">{{ $event->title }}</h3>
                            <p class="text-sm text-slate-500 mb-8 flex-1 leading-relaxed">
                                {{ Str::limit(strip_tags($event->description), 120) }}
                            </p>
                            <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center text-xs text-slate-400">
                                    @if(str_contains(strtolower($event->location ?? ''), 'zoom') || str_contains(strtolower($event->location ?? ''), 'online'))
                                        <i class="fas fa-video mr-2 text-red-500"></i>
                                    @else
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                    @endif
                                    {{ $event->location ?? 'Lokasi belum ditentukan' }}
                                </div>
                                <a href="{{ route('public.acara.detail', $event->id) }}"
                                   class="text-blue-600 font-bold text-sm flex items-center group-hover:translate-x-1 transition">
                                    Detail <i class="fas fa-chevron-right ml-2 text-[10px]"></i>
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
@endsection

@section('extra_js')
    {{-- Jika ada script khusus halaman acara saja, letakkan di sini --}}
@endsection