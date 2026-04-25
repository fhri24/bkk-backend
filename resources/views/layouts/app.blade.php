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
    </style>
    @yield('extra_css')
</head>
<body class="bg-slate-50 text-slate-900">
    
    {{-- Memanggil Navbar Pusat --}}
    @include('layouts.navbar')
    
    <div class="page-transition">
        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>

    {{-- MODAL TERSIMPAN --}}
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
                    // Mengambil data jobs yang disimpan oleh user yang login
                    $saved_jobs = \App\Models\SavedJob::where('user_id', auth()->id())->with('job.company')->get();
                @endphp

                @forelse($saved_jobs as $saved)
                    @if($saved->job)
                        <div class="group flex items-center justify-between p-4 hover:bg-slate-50 transition-all border-b border-slate-100 last:border-0">
                            <div class="flex items-center space-x-4"> 
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-briefcase text-lg"></i>
                                </div>
                                 
                                <div class="flex flex-col">
                                    <h4 class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">
                                        {{ $saved->job->title }}
                                    </h4>
                                    <p class="text-xs text-slate-500 flex items-center mt-1">
                                        <i class="far fa-building mr-1"></i>
                                        {{ $saved->job->company->company_name ?? 'Perusahaan' }}
                                    </p>
                                </div>
                            </div>
 
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('public.lowongan.detail', $saved->job_id) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Gunakan route save/unsave Anda --}}
                                <form action="{{ route('student.lowongan.save', $saved->job_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-10">
                        <i class="fas fa-folder-open text-slate-200 text-5xl mb-3"></i>
                        <p class="text-sm text-slate-500">Belum ada lowongan tersimpan</p>
                    </div>
                @endforelse 
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-center">
                <a href="{{ route('student.profile') }}" class="text-sm font-bold text-blue-600 hover:underline">
                    Lihat Semua di Profil
                </a>
            </div>
        </div>
    </div>
    @endauth

    {{-- Memanggil Footer Pusat --}}
    @include('layouts.footer')
 
    <script>
        // Fungsi Menu Mobile
        function toggleMobileMenu() {
            const menu = document.getElementById("mobile-menu");
            if(menu) menu.classList.toggle("hidden");
        }

        // FUNGSI MODAL TERSIMPAN
        function openSavedModal() {
            const modal = document.getElementById('modal-tersimpan');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSavedModal() {
            const modal = document.getElementById('modal-tersimpan');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Tutup modal jika klik di area luar modal
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