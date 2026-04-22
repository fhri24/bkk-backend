<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BKK SMKN 1 Garut')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: "Plus Jakarta Sans", sans-serif; }
        body { scroll-behavior: smooth; }
        .active-link { color: #3b82f6; border-bottom: 2px solid #3b82f6; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .page-transition { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        @keyframes zoomInUp { from { opacity: 0; transform: scale(0.85) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-zoom-in { animation: zoomInUp 0.6s ease-out forwards; }
        .card-zoom { transition: transform 0.3s ease-out; }
        .card-zoom:hover { transform: scale(1.02); }
        .stat-card { animation: zoomInUp 0.8s ease-out backwards; }
        .job-card { animation: zoomInUp 0.8s ease-out backwards; }
        .divider-line { background: linear-gradient(to right, transparent, rgb(226, 232, 240), transparent); height: 2px; }
        .section-header { position: relative; padding: 16px 0; }
        .section-header::before { content: ''; position: absolute; left: 0; top: 50%; width: 4px; height: 30px; background: linear-gradient(180deg, #2563eb, #1d4ed8); border-radius: 2px; transform: translateY(-50%); }
        
        @media (prefers-reduced-motion: no-preference) {
            .stat-card:nth-child(1) { animation-delay: 0s; }
            .stat-card:nth-child(2) { animation-delay: 0.1s; }
            .stat-card:nth-child(3) { animation-delay: 0.2s; }
            .stat-card:nth-child(4) { animation-delay: 0.3s; }
            .job-card:nth-child(1) { animation-delay: 0.1s; }
            .job-card:nth-child(2) { animation-delay: 0.2s; }
            .job-card:nth-child(3) { animation-delay: 0.3s; }
        }
    </style>
    @yield('extra_css')
</head>
<body class="bg-slate-50 text-slate-900">
    @include('layouts.navbar')
    
    <div class="page-transition">
        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>

    {{-- MODAL TERSIMPAN (Sesuai gambar image_92ca04.png) --}}
    @auth
    <div id="modal-tersimpan" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-zoom-in">
            <div class="bg-[#001f3f] p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bookmark text-blue-400"></i>
                    <h3 class="font-bold">Lowongan Tersimpan</h3>
                </div>
                <button onclick="closeSavedModal()" class="hover:text-red-400 transition text-2xl">&times;</button>
            </div>
            
            <div class="max-h-[60vh] overflow-y-auto p-4 space-y-3 hide-scrollbar">
                @php
                    $saved_jobs = \App\Models\SavedJob::where('user_id', auth()->id())->with('job')->get();
                @endphp

                @forelse(Auth::user()->savedJobs()->with('job')->get() as $saved)
                    @if($saved->job)
                        <div class="group flex items-center justify-between p-4 hover:bg-slate-50 transition-all border-b border-slate-100 last:border-0">
                            <div class="flex items-center space-x-4">
                                <!-- Icon/Logo Placeholder -->
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-briefcase text-lg"></i>
                                </div>
                                
                                <!-- Info Lowongan -->
                                <div class="flex flex-col">
                                    <h4 class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">
                                        {{ $saved->job->title ?? 'Judul Lowongan' }}
                                    </h4>
                                    <p class="text-xs text-slate-500 flex items-center mt-1">
                                        <i class="far fa-building mr-1"></i>
                                        {{ $saved->job->company->name ?? 'Nama Perusahaan' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('student.lowongan.detail', $saved->job_id) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('student.lowongan.save', $saved->job_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                                            title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-slate-500">Tidak ada lowongan tersimpan</p>
                @endforelse
                        {{-- Hapus extra @endforeach di sini --}}
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-center">
                <a href="{{ route('student.profile') }}#saved" class="text-sm font-bold text-blue-600 hover:underline">
                    Lihat Semua di Profil
                </a>
            </div>
        </div>
    </div>
    @endauth

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fungsi Menu Mobile
        function toggleMobileMenu() {
            const menu = document.getElementById("mobile-menu");
            menu.classList.toggle("hidden");
        }

        function closeMobileMenu() {
            document.getElementById("mobile-menu").classList.add("hidden");
        }

        // FUNGSI MODAL TERSIMPAN (Gabungan)
        function openSavedModal() {
            const modal = document.getElementById('modal-tersimpan');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Lock scroll body
            } else {
                console.error("Modal ID 'modal-tersimpan' tidak ditemukan!");
                window.location.href = "{{ route('student.profile') }}#saved";
            }
        }

        function closeSavedModal() {
            const modal = document.getElementById('modal-tersimpan');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto'; // Unlock scroll body
            }
        }

        // Tutup modal jika klik di area gelap (luar modal)
        window.onclick = function(event) {
            const modal = document.getElementById('modal-tersimpan');
            if (event.target == modal) {
                closeSavedModal();
            }
        }
    </script>
    @yield('extra_js')
</body>
</html>
