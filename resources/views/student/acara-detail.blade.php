@extends('layouts.app')

@section('title', 'Detail Acara - BKK SMKN 1 Garut')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        
        {{-- Alerts --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="mb-6">
            <a href="{{ route('student.home') }}" class="text-blue-600 hover:underline flex items-center font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Detail Info Acara --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                    <div class="mb-6">
                        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-3">{{ $event->category ?? 'Acara' }}</span>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">{{ $event->title }}</h1>
                        <p class="text-slate-600 text-lg">{{ $event->organizer ?? 'BKK SMKN 1 Garut' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 bg-slate-50 p-6 rounded-xl">
                        <div class="flex items-start">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl mt-1 w-8"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-500 uppercase">Waktu</p>
                                <p class="text-slate-800 font-medium">{{ $event->start_date->format('d M Y, H:i') }} - Selesai</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-red-500 text-xl mt-1 w-8"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-500 uppercase">Lokasi</p>
                                <p class="text-slate-800 font-medium">{{ $event->location }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-users text-green-500 text-xl mt-1 w-8"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-500 uppercase">Kapasitas</p>
                                <p class="text-slate-800 font-medium">{{ $event->capacity }} Orang</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4">Deskripsi Acara</h3>
                        <div class="prose max-w-none text-slate-600">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Pendaftaran (Kanan) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-4 border-b pb-4">Formulir Pendaftaran</h3>
                    
                    @if($isRegistered)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                            <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                            <h4 class="font-bold text-slate-800 mb-1">Anda Telah Terdaftar</h4>
                            <p class="text-sm text-slate-600">Terima kasih, Anda sudah terdaftar sebagai peserta di acara ini. Silakan tunggu informasi berikutnya.</p>
                        </div>
                    @else
                        <form action="{{ route('student.acara.daftar', $event->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" value="{{ $user->name }}" class="w-full bg-slate-100 border-none rounded-lg px-4 py-2.5 text-slate-500" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                                <input type="email" value="{{ $user->email }}" class="w-full bg-slate-100 border-none rounded-lg px-4 py-2.5 text-slate-500" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">No. Handphone <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ $student->phone ?? '' }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:outline-none" required placeholder="Contoh: 08123456789">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Institusi / Sekolah</label>
                                <input type="text" name="institution" value="SMKN 1 Garut" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Posisi / Jurusan</label>
                                <input type="text" name="position" value="{{ $student->major ?? '' }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-600 focus:outline-none" placeholder="Jurusan Anda">
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition mt-4">
                                <i class="fas fa-paper-plane mr-2"></i> Daftar Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection