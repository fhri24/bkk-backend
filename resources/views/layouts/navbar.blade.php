<nav class="bg-[#001f3f] text-white sticky top-0 z-50 shadow-xl">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center h-20">

            {{-- Logo --}}
            <div class="flex items-center space-x-3 cursor-pointer hover:opacity-80 transition"
                 onclick="window.location.href = '{{ route('home') }}'">
                <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-graduation-cap text-[#001f3f] text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-xl tracking-tight leading-none">BKK SMKN 1</h1>
                    <p class="text-[10px] uppercase tracking-widest opacity-70">Garut Bermartabat</p>
                </div>
            </div>

            {{-- Menu Tengah (Desktop) --}}
            <div class="hidden lg:flex items-center space-x-8 text-sm font-semibold">
                @auth
                    @php
                        if (auth()->user()->role->name === 'publik') {
                            $homeRoute = route('publik.home');
                        } elseif (auth()->user()->role->name === 'alumni') {
                            $homeRoute = route('alumni.home');
                        } else {
                            $homeRoute = route('student.home');
                        }
                    @endphp
                    <a href="{{ $homeRoute }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('student.home') || request()->routeIs('publik.home') || request()->routeIs('alumni.home') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Beranda</a>
                    <a href="{{ route('public.lowongan') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.lowongan*') || request()->routeIs('student.lowongan*') || request()->routeIs('publik.lowongan*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Lowongan</a>
                    <a href="{{ route('public.berita') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.berita*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Berita</a>
                    <a href="{{ route('public.acara') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.acara*') || request()->routeIs('student.acara*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Acara</a>
                    <a href="{{ route('public.tracer') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.tracer*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Tracer Study</a>
                @else
                    <a href="{{ route('public.beranda') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.beranda') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Beranda</a>
                    <a href="{{ route('public.lowongan') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.lowongan*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Lowongan</a>
                    <a href="{{ route('public.berita') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.berita*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Berita</a>
                    <a href="{{ route('public.acara') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.acara*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Acara</a>
                    <a href="{{ route('public.tracer') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.tracer*') ? 'text-blue-400 border-b-2 border-blue-400' : '' }}">Tracer Study</a>
                @endauth
            </div>

            {{-- Menu Kanan (Desktop) --}}
            <div class="hidden lg:flex items-center space-x-6">
                @auth
                    <div class="flex items-center gap-6">
                        <!-- Sembunyikan Tersimpan & Lamaran untuk Publik -->
                        @if(auth()->user()->role->name !== 'publik')
                            @php $savedCount = \App\Models\SavedJob::where('user_id', auth()->id())->count(); @endphp
                            <a href="{{ route('student.saved-jobs') }}" class="flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-lg hover:bg-white/20 transition relative group {{ request()->routeIs('student.saved-jobs') ? 'bg-white/20 ring-1 ring-blue-400' : '' }}">
                                <i class="fas fa-bookmark text-blue-400 group-hover:shake transition"></i>
                                <span class="text-sm font-semibold">Tersimpan</span>
                                @if($savedCount > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-[#001f3f]">
                                        {{ $savedCount }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('student.applications') }}" class="flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-lg hover:bg-white/20 transition {{ request()->routeIs('student.applications') ? 'bg-white/20 ring-1 ring-blue-400' : '' }}">
                                <i class="fas fa-file-alt text-green-400"></i>
                                <span class="text-sm font-semibold">Lamaran</span>
                            </a>
                        @endif

                        <!-- Menggunakan rute global 'profile' -->
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 text-sm font-semibold hover:text-blue-300 transition group {{ request()->routeIs('profile') ? 'text-blue-300' : '' }}">
                            <i class="fas fa-user-circle text-xl group-hover:scale-110 transition"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-full text-sm font-bold shadow-lg transition transform hover:scale-105 active:scale-95">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('register') }}" class="text-sm font-bold hover:text-blue-400 transition">Daftar</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 px-8 py-2.5 rounded-full text-sm font-bold shadow-lg transition transform hover:scale-105 active:scale-95">
                        Masuk
                    </a>
                @endauth
            </div>

            {{-- Hamburger Button --}}
            <button class="lg:hidden text-2xl" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden lg:hidden bg-[#001f3f] border-t border-white/10 px-6 py-6 space-y-4 shadow-inner">
        @auth
            @php 
                if (auth()->user()->role->name === 'publik') {
                    $homeRouteMobile = route('publik.home');
                } elseif (auth()->user()->role->name === 'alumni') {
                    $homeRouteMobile = route('alumni.home');
                } else {
                    $homeRouteMobile = route('student.home');
                }
            @endphp
            
            <!-- Menggunakan rute global 'profile' -->
            <a href="{{ route('profile') }}" class="block py-2 font-bold text-blue-300 border-b border-white/10">
                <i class="fas fa-user-circle mr-2"></i>Profil Saya
            </a>

            @if(auth()->user()->role->name !== 'publik')
                @php $savedCountMobile = \App\Models\SavedJob::where('user_id', auth()->id())->count(); @endphp
                <a href="{{ route('student.saved-jobs') }}" class="block py-2 text-blue-400 font-bold flex items-center">
                    <i class="fas fa-bookmark mr-2"></i> Tersimpan ({{ $savedCountMobile }})
                </a>

                <a href="{{ route('student.applications') }}" class="block py-2 text-green-400 font-bold">
                    <i class="fas fa-file-alt mr-2"></i>Lamaran Saya
                </a>
            @endif

            <a href="{{ $homeRouteMobile }}" class="block py-2">Beranda</a>
            <a href="{{ route('public.lowongan') }}" class="block py-2">Lowongan</a>
            <a href="{{ route('public.berita') }}" class="block py-2">Berita</a>
            
            @if(auth()->user()->role->name !== 'publik')
                <a href="{{ route('public.acara') }}" class="block py-2">Acara</a>
                <a href="{{ route('public.tracer') }}" class="block py-2">Tracer Study</a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                @csrf
                <button type="submit" class="w-full bg-red-600 py-3 rounded-xl font-bold">Logout</button>
            </form>
        @else
            <a href="{{ route('public.beranda') }}" class="block py-2">Beranda</a>
            <a href="{{ route('public.lowongan') }}" class="block py-2">Lowongan</a>
            <a href="{{ route('public.berita') }}" class="block py-2">Berita</a>
            <a href="{{ route('login') }}" class="block py-2 text-blue-400 font-bold">Masuk</a>
        @endauth
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
