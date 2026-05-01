@extends('layouts.app')

@section('title', $event->title . ' - BKK SMKN 1 Garut')

@section('extra_css')
    <style>
        .event-header {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.9), rgba(0, 31, 63, 0.9)),
                url("{{ $event->image ? Storage::url($event->image) : 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80' }}");
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
        }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); animation: fadeIn 0.3s ease-in; }
        .modal.show { display: flex; align-items: center; justify-content: center; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { background-color: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; animation: slideUp 0.3s ease-out; }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .detail-section { margin-bottom: 40px; }
        .detail-section h2 { font-size: 24px; font-weight: 700; color: #001f3f; margin-bottom: 16px; display: flex; align-items: center; }
        .detail-section h2 i { margin-right: 12px; color: #3b82f6; }
        .agenda-item { display: flex; gap: 20px; padding: 16px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #3b82f6; margin-bottom: 12px; }
        .agenda-item .time { font-weight: 700; color: #3b82f6; min-width: 120px; flex-shrink: 0; }
        .agenda-item .content h4 { font-weight: 600; color: #001f3f; margin-bottom: 4px; }
        .agenda-item .content p { font-size: 14px; color: #64748b; }
        .target-list { list-style: none; padding: 0; }
        .target-list li { padding: 12px 0; padding-left: 28px; color: #475569; font-size: 15px; position: relative; }
        .target-list li:before { content: "✓"; position: absolute; left: 0; color: #3b82f6; font-weight: bold; font-size: 18px; }
        .sidebar-box { background: white; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .sidebar-box h3 { font-size: 18px; font-weight: 700; color: #001f3f; margin-bottom: 16px; display: flex; align-items: center; }
        .sidebar-box h3 i { margin-right: 8px; color: #3b82f6; }
        .progress-bar { width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin: 8px 0; }
        .progress { height: 100%; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 4px; }
        .share-buttons { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .share-btn { padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc; color: #001f3f; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .share-btn:hover { background: #3b82f6; color: white; border-color: #3b82f6; }
    </style>
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <div class="bg-[#f8fafc] border-b border-slate-200">
        <div class="container mx-auto px-6 py-4 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Beranda</a> 
            <span class="mx-2">/</span>
            <a href="{{ route('public.acara') }}" class="text-blue-600 hover:underline">Acara</a> 
            <span class="mx-2">/</span>
            <span class="text-slate-400">{{ $event->title }}</span>
        </div>
    </div>

    {{-- ===== EVENT HEADER ===== --}}
    <div class="event-header">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-8 leading-tight">{{ $event->title }}</h1>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1"><i class="fas fa-calendar-alt mr-2"></i> Tanggal</p>
                    <p class="text-white font-bold">{{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1"><i class="fas fa-clock mr-2"></i> Waktu</p>
                    <p class="text-white font-bold">
                        {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} -
                        {{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('H:i') : 'Selesai' }} WIB
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi</p>
                    <p class="text-white font-bold">{{ $event->location ?? 'TBD' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1"><i class="fas fa-users mr-2"></i> Kapasitas</p>
                    <p class="text-white font-bold">{{ $event->capacity ?? '—' }} Peserta</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <p class="text-blue-100 text-xs uppercase tracking-wider mb-1"><i class="fas fa-tag mr-2"></i> Biaya</p>
                    <p class="font-bold {{ $event->is_free ? 'text-green-300' : 'text-white' }}">
                        {{ $event->is_free ? 'GRATIS' : ($event->price ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'TBD') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Main Content Column --}}
            <div class="lg:col-span-2">
                {{-- Event Image --}}
                <div class="rounded-3xl overflow-hidden shadow-2xl mb-10">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-auto object-cover" />
                    @else
                        <div class="w-full h-80 bg-gradient-to-br from-blue-600 to-indigo-800 flex flex-col items-center justify-center text-white">
                            <i class="fas fa-calendar-alt text-7xl mb-4 opacity-20"></i>
                            <span class="font-bold opacity-50 uppercase tracking-widest">Detail Acara</span>
                        </div>
                    @endif
                </div>

                {{-- About Event --}}
                <div class="detail-section">
                    <h2><i class="fas fa-info-circle"></i> Tentang Event Ini</h2>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                {{-- Agenda --}}
                <div class="detail-section">
                    <h2><i class="fas fa-list-check"></i> Agenda Acara</h2>
                    @if(isset($event->agenda) && $event->agenda)
                        <div class="space-y-4">
                            @foreach(json_decode($event->agenda, true) ?? [] as $agendaItem)
                                <div class="agenda-item group hover:bg-blue-50 transition-colors">
                                    <div class="time">{{ $agendaItem['time'] ?? '' }}</div>
                                    <div class="content">
                                        <h4>{{ $agendaItem['title'] ?? '' }}</h4>
                                        <p>{{ $agendaItem['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-100 p-8 rounded-2xl text-center border-2 border-dashed border-slate-200">
                            <p class="text-slate-500 italic">Detail agenda akan diperbarui dalam waktu dekat.</p>
                        </div>
                    @endif
                </div>

                {{-- Target Peserta --}}
                <div class="detail-section">
                    <h2><i class="fas fa-bullseye"></i> Target Peserta</h2>
                    <ul class="target-list grid md:grid-cols-2 gap-x-8">
                        <li>Siswa dan alumni SMKN 1 Garut</li>
                        <li>Pencari kerja yang ingin berkembang</li>
                        <li>Umum (sesuai kriteria acara)</li>
                        <li>Peserta yang terdaftar secara resmi</li>
                    </ul>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-1">
                {{-- Registration Box --}}
                {{-- PERBAIKAN: Menghapus class 'sticky' dan 'top-24' agar tombol tidak ikut saat scroll --}}
                <div class="sidebar-box border-t-4 border-t-blue-600">
                    <h3><i class="fas fa-ticket-alt"></i> Registrasi</h3>

                    @php
                        $registered = $event->registration_count ?? 0;
                        $capacity = $event->capacity ?? 0;
                        $percentage = $capacity > 0 ? min(100, round(($registered / $capacity) * 100)) : 0;
                        $isFull = $capacity > 0 && $registered >= $capacity;
                    @endphp

                    @if($isFull)
                        <button disabled class="w-full bg-slate-200 text-slate-500 px-6 py-4 rounded-xl font-bold mb-4 cursor-not-allowed">
                            <i class="fas fa-times-circle mr-2"></i> Kuota Sudah Penuh
                        </button>
                    @elseif(\Carbon\Carbon::parse($event->start_date)->isPast())
                        <button disabled class="w-full bg-slate-200 text-slate-500 px-6 py-4 rounded-xl font-bold mb-4 cursor-not-allowed">
                            <i class="fas fa-clock mr-2"></i> Acara Telah Selesai
                        </button>
                    @else
                        <button onclick="openRegistrationForm()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl font-bold transition shadow-lg shadow-blue-200 mb-4 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>
                            {{ $event->is_free ? 'Daftar Sekarang (Gratis)' : 'Daftar Sekarang' }}
                        </button>
                    @endif

                    @if($capacity > 0)
                        <div class="bg-slate-50 p-4 rounded-xl">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500">Ketersediaan Kursi</span>
                                <span class="font-bold text-slate-800">{{ $registered }}/{{ $capacity }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress" style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-[11px] mt-2 font-bold uppercase tracking-wider">
                                @if($isFull) <span class="text-red-500">Maaf, pendaftaran ditutup</span>
                                @elseif($percentage >= 80) <span class="text-orange-500">Sisa sedikit lagi!</span>
                                @else <span class="text-green-600">Pendaftaran masih dibuka</span> @endif
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Share Box --}}
                <div class="sidebar-box">
                    <h3><i class="fas fa-share-nodes"></i> Bagikan</h3>
                    <div class="share-buttons">
                        <button class="share-btn hover:!bg-blue-600" onclick="shareEvent('facebook')"><i class="fab fa-facebook-f mr-1"></i> FB</button>
                        <button class="share-btn hover:!bg-sky-400" onclick="shareEvent('twitter')"><i class="fab fa-twitter mr-1"></i> X</button>
                        <button class="share-btn hover:!bg-green-500" onclick="shareEvent('whatsapp')"><i class="fab fa-whatsapp mr-1"></i> WA</button>
                        <button class="share-btn hover:!bg-red-500" onclick="shareEvent('email')"><i class="fas fa-envelope mr-1"></i> Email</button>
                    </div>
                </div>

                {{-- Related Events --}}
                @if($relatedEvents->isNotEmpty())
                    <div class="sidebar-box">
                        <h3><i class="fas fa-calendar-days"></i> Acara Lainnya</h3>
                        <div class="space-y-4">
                            @foreach($relatedEvents as $related)
                                <div class="group border-b border-slate-100 last:border-0 pb-3 last:pb-0">
                                    <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition text-sm mb-1">{{ $related->title }}</h4>
                                    <p class="text-xs text-slate-500 mb-2">{{ \Carbon\Carbon::parse($related->start_date)->translatedFormat('d M Y') }}</p>
                                    <a href="{{ route('public.acara.detail', $related->id) }}" class="text-xs font-bold text-blue-600 flex items-center">
                                        Selengkapnya <i class="fas fa-arrow-right ml-1 group-hover:translate-x-1 transition"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== REGISTRATION MODAL ===== --}}
    <div id="registrationModal" class="modal">
        <div class="modal-content overflow-hidden">
            <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-clipboard-check mr-3"></i> Formulir Registrasi
                </h2>
                <button onclick="closeRegistrationForm()" class="text-white text-2xl leading-none hover:rotate-90 transition-transform">&times;</button>
            </div>

            <div class="p-8">
                @if(session('registration_success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        {{ session('registration_success') }}
                    </div>
                @endif

                <form action="{{ route('public.event.register', $event->id) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}" />

                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none transition" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none transition" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">WhatsApp / Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 outline-none transition" required />
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-xl font-bold transition flex items-center justify-center shadow-lg shadow-blue-100">
                            Kirim Pendaftaran <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function openRegistrationForm() {
            document.getElementById('registrationModal').classList.add('show');
            document.body.style.overflow = 'hidden'; // Stop scrolling
        }
        function closeRegistrationForm() {
            document.getElementById('registrationModal').classList.remove('show');
            document.body.style.overflow = 'auto'; // Enable scrolling
        }
        
        // Close on background click
        window.onclick = function(e) {
            const modal = document.getElementById('registrationModal');
            if (e.target === modal) closeRegistrationForm();
        };

        // Share Function
        function shareEvent(platform) {
            const title = '{{ addslashes($event->title) }}';
            const url = window.location.href;
            const platforms = {
                facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
                twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`,
                whatsapp: `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`,
                email: `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}`
            };
            window.open(platforms[platform], '_blank');
        }

        // Keep modal open if validation fails
        @if(session('registration_success') || $errors->any())
            openRegistrationForm();
        @endif
    </script>
@endsection