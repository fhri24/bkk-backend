<nav class="bg-[#001f3f] text-white sticky top-0 z-[100] shadow-xl">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center space-x-3 cursor-pointer hover:opacity-80 transition" onclick="window.location.href = '{{ route(auth()->check() && auth()->user()->role->name === 'siswa' ? 'student.home' : 'public.home') }}'">
                <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-graduation-cap text-[#001f3f] text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-xl tracking-tight leading-none">BKK SMKN 1</h1>
                    <p class="text-[10px] uppercase tracking-widest opacity-70">Garut Bermartabat</p>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex space-x-8 text-sm font-semibold">
                @auth
                    @if(auth()->user()->role->name === 'siswa')
                        <a href="{{ route('student.home') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('student.home') ? 'active-link' : '' }}">Beranda</a>
                        <a href="{{ route('student.lowongan') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('student.lowongan') ? 'active-link' : '' }}">Lowongan</a>
                        <a href="{{ route('student.berita') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('student.berita*') ? 'active-link' : '' }}">Berita</a>
                        <a href="{{ route('student.acara') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('student.acara*') ? 'active-link' : '' }}">Acara</a>
                        <a href="{{ route('student.tracer') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('student.tracer*') ? 'active-link' : '' }}">Tracer Study</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn transition hover:text-blue-400">Admin Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('public.home') }}" class="nav-btn transition hover:text-blue-400 {{ request()->routeIs('public.home') ? 'active-link' : '' }}">Beranda</a>
                @endauth
            </div>

            <!-- Auth Buttons -->
            <div class="hidden lg:flex items-center space-x-4">
                @auth
                    <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                    @if(in_array(auth()->user()->role->name, ['super_admin', 'admin_bkk', 'kepala_bkk', 'perusahaan']))
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold hover:text-blue-400 transition">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 px-7 py-2.5 rounded-full text-sm font-bold shadow-lg transition transform hover:scale-105 active:scale-95">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="text-sm font-bold hover:text-blue-400 transition">Daftar</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 px-7 py-2.5 rounded-full text-sm font-bold shadow-lg transition transform hover:scale-105 active:scale-95">Masuk</a>
                @endauth
            </div>

            <!-- Mobile Toggle -->
            <button class="lg:hidden text-2xl" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-[#001f3f] border-t border-white/10 px-6 py-6 space-y-4">
        @auth
            @if(auth()->user()->role->name === 'siswa')
                <a href="{{ route('student.home') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400" onclick="closeMobileMenu()">Beranda</a>
                <a href="{{ route('student.lowongan') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400" onclick="closeMobileMenu()">Lowongan</a>
                <a href="{{ route('student.berita') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400" onclick="closeMobileMenu()">Berita</a>
                <a href="{{ route('student.acara') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400" onclick="closeMobileMenu()">Acara</a>
                <a href="{{ route('student.tracer') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400" onclick="closeMobileMenu()">Tracer Study</a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400">Admin</a>
            @endif
        @else
            <a href="{{ route('public.home') }}" class="block w-full text-left py-2 font-semibold hover:text-blue-400" onclick="closeMobileMenu()">Beranda</a>
        @endauth

        <div class="pt-4 border-t border-white/10 flex flex-col space-y-3">
            @auth
                <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 py-3 rounded-xl font-bold">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full bg-blue-600 py-3 rounded-xl font-bold text-center" onclick="closeMobileMenu()">Masuk Ke Akun</a>
                <a href="{{ route('register') }}" class="w-full bg-green-600 py-3 rounded-xl font-bold text-center" onclick="closeMobileMenu()">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
