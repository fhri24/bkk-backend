@extends('layouts.app')

@section('title', 'Acara - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <h2 class="text-4xl font-extrabold text-[#001f3f] mb-12">Acara & Event</h2>

    {{-- 1. Ganti $acara menjadi $events agar sesuai dengan Controller --}}
    @if($events->count() > 0)
        <div class="grid md:grid-cols-2 gap-8">
            @foreach($events as $event)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition group cursor-pointer">
                    <div class="relative h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-4xl">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition" alt="{{ $event->title }}">
                        @else
                            <i class="fas fa-calendar-check"></i>
                        @endif
                    </div>
                    
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 group-hover:text-blue-600 transition mb-3">{{ $event->title }}</h3>
                        
                        <div class="space-y-3 mb-6 text-slate-600 font-semibold">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-blue-600 mr-3 w-5"></i>
                                {{-- 2. Gunakan optional format jika start_date berupa string atau carbon --}}
                                <span>{{ is_string($event->start_date) ? $event->start_date : $event->start_date->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-3 w-5"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            @if($event->capacity > 0)
                                <div class="flex items-center">
                                    <i class="fas fa-users text-blue-600 mr-3 w-5"></i>
                                    <span>Kapasitas: {{ $event->capacity }} Peserta</span>
                                </div>
                            @endif
                            @if($event->description)
                                <p class="text-sm mt-3 text-slate-700">{{ Str::limit(strip_tags($event->description), 150) }}</p>
                            @endif
                        </div>
                        
                        <button class="w-full bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                            Lihat Detail Acara
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{-- 3. Ganti $acara menjadi $events --}}
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-5xl text-slate-300 mb-4 block"></i>
            <p class="text-slate-500 text-lg">Belum ada acara yang dijadwalkan</p>
        </div>
    @endif
</div>
@endsection