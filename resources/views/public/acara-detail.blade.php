@extends('layouts.app')

@section('title', $event->title . ' - BKK SMKN 1 Garut')

@section('content')
<div class="container mx-auto px-6 py-16">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="w-24 h-24 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shrink-0 border border-blue-100"><i class="fas fa-calendar-check text-4xl"></i></div>
                    <div class="flex-1">
                        <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-sm font-bold mb-4 inline-block">{{ $event->category ?? 'Kegiatan' }}</span>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-6">{{ $event->title }}</h1>
                        <p class="text-slate-600 text-lg leading-relaxed mb-8">{{ $event->description ?? 'Deskripsi tidak tersedia.' }}</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-slate-600 font-medium bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <div class="flex items-center"><i class="fas fa-calendar-alt w-8 text-blue-500 text-lg"></i> {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}</div>
                            <div class="flex items-center"><i class="fas fa-clock w-8 text-blue-500 text-lg"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} - Selesai</div>
                            <div class="flex items-center"><i class="fas fa-map-marker-alt w-8 text-blue-500 text-lg"></i> {{ $event->location }}</div>
                            <div class="flex items-center"><i class="fas fa-users w-8 text-blue-500 text-lg"></i> Kuota: {{ $event->capacity }} Orang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Pendaftaran Acara -->
        <div class="bg-gradient-to-br from-blue-600 to-[#001f3f] rounded-3xl shadow-xl p-8 md:p-12 text-white text-center relative overflow-hidden">
            <i class="fas fa-ticket-alt absolute -right-4 -bottom-4 text-[12rem] opacity-10 rotate-12"></i>
            <div class="relative z-10">
                <h3 class="text-3xl font-extrabold mb-4">Tertarik mengikuti acara ini?</h3>
                
                @auth
                    @if(auth()->user()->role && auth()->user()->role->name === 'siswa')
                        @if($isRegistered ?? false)
                            <div class="mt-8 bg-white/10 p-6 rounded-2xl backdrop-blur-sm max-w-lg mx-auto border border-white/20">
                                <p class="text-green-400 font-bold text-xl mb-2"><i class="fas fa-check-circle mr-2"></i> Anda Sudah Terdaftar</p>
                                <p class="text-blue-100 text-sm">Silakan cek email atau dashboard Anda untuk informasi lebih lanjut.</p>
                            </div>
                        @else
                            <form action="{{ route('student.acara.daftar', $event->id) }}" method="POST" class="max-w-md mx-auto mt-8 text-left bg-white/10 p-6 md:p-8 rounded-2xl backdrop-blur-sm border border-white/20">
                                @csrf
                                <div class="mb-6">
                                    <label class="block text-sm font-bold text-blue-100 mb-2">No. WhatsApp / HP</label>
                                    <input type="text" name="phone" value="{{ $student->phone ?? '' }}" required class="w-full px-4 py-3 rounded-xl text-slate-800 font-medium focus:outline-none focus:ring-4 focus:ring-blue-400/50" placeholder="Contoh: 08123456789">
                                </div>
                                <button type="submit" class="w-full bg-white text-[#001f3f] hover:bg-slate-100 px-8 py-3.5 rounded-xl font-extrabold transition shadow-lg inline-flex justify-center items-center transform hover:-translate-y-1"><i class="fas fa-paper-plane mr-2"></i> Konfirmasi Pendaftaran</button>
                            </form>
                        @endif
                    @endif
                @else
                    <p class="text-blue-100 mb-8 max-w-lg mx-auto text-lg">Pendaftaran saat ini hanya terbuka untuk Siswa dan Alumni BKK SMKN 1 Garut.</p>
                    <a href="{{ route('login') }}" class="bg-white text-[#001f3f] hover:bg-slate-100 px-10 py-4 rounded-xl font-extrabold transition inline-flex items-center shadow-lg transform hover:-translate-y-1"><i class="fas fa-sign-in-alt mr-2"></i> Login untuk Mendaftar</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
