<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            
            <div id="modal-saved-list" class="max-h-[60vh] overflow-y-auto p-4 space-y-3 hide-scrollbar">
                @php 
                    $saved_jobs = \App\Models\SavedJob::where('user_id', auth()->id())->with('job.company')->get();
                @endphp

                @forelse($saved_jobs as $saved)
                    @if($saved->job)
                        <div id="modal-item-{{ $saved->job_id }}" class="group flex items-center justify-between p-4 hover:bg-slate-50 transition-all border-b border-slate-100 last:border-0">
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
                                {{-- Tombol hapus modal menggunakan AJAX agar angka navbar sinkron --}}
                                <button onclick="removeSavedJob({{ $saved->job_id }}, 'modal')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                @empty
                    <div id="modal-empty-state" class="text-center py-10">
                        <i class="fas fa-folder-open text-slate-200 text-5xl mb-3"></i>
                        <p class="text-sm text-slate-500">Belum ada lowongan tersimpan</p>
                    </div>
                @endforelse 
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-center">
                <a href="{{ route('student.saved-jobs') }}" class="text-sm font-bold text-blue-600 hover:underline">
                    Lihat Semua Lowongan
                </a>
            </div>
        </div>
    </div>
    @endauth

    {{-- Memanggil Footer Pusat --}}
    @include('layouts.footer')
 
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // 1. Fungsi Update Angka di Navbar (Badge)
        function updateNavbarBadge(count) {
            // Update semua elemen dengan class .badge-saved
            const badges = document.querySelectorAll('.badge-saved'); 
            badges.forEach(badge => {
                if (count > 0) {
                    badge.innerText = count;
                    badge.classList.remove('hidden');
                    badge.style.display = 'inline-block';
                } else {
                    badge.classList.add('hidden');
                    badge.style.display = 'none';
                }
            });

            // Update elemen dengan ID spesifik (sesuai code baru Anda)
            const specificBadge = document.getElementById('saved-count-badge');
            if (specificBadge) {
                specificBadge.innerText = count;
                specificBadge.style.display = count > 0 ? 'inline-block' : 'none';
            }
        }

        // 2. Fungsi Toggle Simpan (AJAX Terintegrasi)
        function toggleSaveJob(btn, jobId) {
            fetch(`/student/lowongan/${jobId}/save`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const icon = btn.querySelector('i');
                
                if (data.status === 'added' || data.saved === true) {
                    // Tampilan MERAH (Saved)
                    btn.classList.remove('bg-gray-100', 'bg-gray-50', 'text-gray-400', 'border-slate-200');
                    btn.classList.add('bg-red-50', 'text-red-500', 'border-red-200');
                    if(icon) {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                    }
                } else {
                    // Tampilan ABU-ABU (Unsaved)
                    btn.classList.remove('bg-red-50', 'text-red-500', 'border-red-200');
                    btn.classList.add('bg-gray-50', 'text-gray-400', 'border-slate-200');
                    if(icon) {
                        icon.classList.remove('fa-solid');
                        icon.classList.add('fa-regular');
                    }
                }

                // Update angka notifikasi tanpa refresh
                if (data.count !== undefined) {
                    updateNavbarBadge(data.count);
                }
                
                // Show toast if available
                if(typeof showToast === 'function') showToast(data.message);
            })
            .catch(error => console.error('Error:', error));
        }

        // 3. Fungsi Hapus di Modal / Page
        function removeSavedJob(jobId, context = 'page') {
            if(!confirm('Hapus dari simpanan?')) return;

            fetch(`/student/lowongan/${jobId}/save`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'removed' || data.saved === false) {
                    // Hapus elemen di modal
                    const modalItem = document.getElementById(`modal-item-${jobId}`);
                    if(modalItem) modalItem.remove();

                    // Hapus elemen di halaman saved-jobs (jika ada)
                    const pageItem = document.getElementById(`saved-card-${jobId}`);
                    if(pageItem) {
                        pageItem.classList.add('opacity-0');
                        setTimeout(() => {
                            pageItem.remove();
                            if (document.querySelectorAll('[id^="saved-card-"]').length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                    
                    updateNavbarBadge(data.count);
                    if(typeof showToast === 'function') showToast(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Fungsi UI Lainnya
        function toggleMobileMenu() {
            const menu = document.getElementById("mobile-menu");
            if(menu) menu.classList.toggle("hidden");
        }
 
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
 
        window.onclick = function(event) {
            const modal = document.getElementById('modal-tersimpan')
            if (event.target == modal) {
                closeSavedModal();
            }
        }
    </script>
    @yield('extra_js')
</body>
</html>