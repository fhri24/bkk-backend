<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Perusahaan - BKK SMKN 1 Garut')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f5f7fa;
        }

        .sidebar {
            background: linear-gradient(135deg, #001f3f 0%, #003d6b 100%);
            transition: margin-left 0.3s ease;
        }

        .sidebar-link {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #3b82f6;
        }

        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.2);
            border-left-color: #3b82f6;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
        }

        .nav-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 12px 0;
        }

        .table-custom {
            background: white;
            border-radius: 12px;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
        }

        .table-custom thead {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-custom thead th {
            padding: 16px;
            font-weight: 700;
            color: #475569;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-custom tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .table-custom tbody tr:hover {
            background: #f8fafc;
        }

        .table-custom tbody td {
            padding: 14px 16px;
            color: #334e68;
            font-size: 14px;
        }

        .badge-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-action {
            min-height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            padding: 0 0.75rem;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .btn-action:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }

        @media (max-width: 1024px) {
            .sidebar {
                margin-left: -16rem;
            }

            .sidebar.mobile-active {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: flex;
            }
        }

        .mobile-menu-btn {
            display: none;
        }

        .breadcrumb-item {
            color: #94a3b8;
            font-size: 13px;
        }

        .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 600;
        }
    </style>

    @yield('extra_css')
</head>

<body>
    <div class="flex h-screen bg-gray-50">

        {{-- ===== SIDEBAR OVERLAY (Mobile) ===== --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden"
             onclick="document.querySelector('.sidebar').classList.remove('mobile-active'); this.classList.add('hidden')">
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="sidebar w-64 text-white flex flex-col fixed h-full lg:relative z-40 lg:z-10">

            {{-- Logo --}}
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center font-bold text-lg overflow-hidden">
                        @php
                            $cp = auth()->user()->company;
                        @endphp
                        @if ($cp && $cp->logo)
                            <img src="{{ Storage::disk('public')->url($cp->logo) }}" class="w-full h-full object-cover"
                                alt="Logo">
                        @else
                            <i class="fas fa-building text-white"></i>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h2 class="font-bold text-white text-sm leading-tight">
                            {{ auth()->user()->company->company_name ?? 'Panel Perusahaan' }}
                        </h2>
                        <p class="text-xs text-blue-200">Panel Perusahaan</p>
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6">
                <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider px-3 mb-4">Menu Utama</h3>

                <a href="{{ route('company.dashboard') }}"
                    class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <a href="{{ route('company.lowongan.index') }}"
                    class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->routeIs('company.lowongan*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span class="ml-3">Lowongan Saya</span>
                    @php
                        $pendingLowongan = \App\Models\Job::where('company_id', auth()->user()->company_id)
                            ->where('approval_status', 'pending')
                            ->count();
                    @endphp
                    @if ($pendingLowongan > 0)
                        <span
                            class="ml-auto bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingLowongan }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('company.lamaran.index') }}"
                    class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->routeIs('company.lamaran*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span class="ml-3">Lamaran Masuk</span>
                    @php
                        $newLamaran = \App\Models\JobApplication::whereHas(
                            'job',
                            fn($q) => $q->where('company_id', auth()->user()->company_id),
                        )
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if ($newLamaran > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ $newLamaran }}
                        </span>
                    @endif
                </a>

                <div class="nav-divider"></div>

                {{-- Info Perusahaan --}}
                @if (auth()->user()->company)
                    <div class="px-3 py-3 rounded-xl bg-white/5 border border-white/10 mt-2">
                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-2">Info Perusahaan</p>
                        <p class="text-xs text-white/70">
                            {{ auth()->user()->company->industry ?? 'Industri tidak diset' }}</p>
                        <p class="text-xs text-white/50 mt-1">{{ auth()->user()->company->address ?? '-' }}</p>
                    </div>
                @endif
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-white/10">
                <a href="{{ route('logout') }}"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-white/80 hover:text-white text-left text-sm"
                    onclick="event.preventDefault(); document.getElementById('company-logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="ml-3">Logout</span>
                </a>
                <form id="company-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Header --}}
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button class="mobile-menu-btn lg:hidden mr-4"
                        onclick="document.querySelector('.sidebar').classList.toggle('mobile-active'); document.getElementById('sidebar-overlay').classList.toggle('hidden')">
                        <i class="fas fa-bars text-2xl text-slate-800"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                        <div class="flex items-center mt-1">
                            <a href="{{ route('company.dashboard') }}"
                                class="breadcrumb-item hover:text-slate-700">Home</a>
                            <span class="mx-2 text-slate-300">/</span>
                            <span class="breadcrumb-item active">@yield('page_title', 'Dashboard')</span>
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-sm">
                        <p class="font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->company->company_name ?? 'Perusahaan' }}
                        </p>
                    </div>
                </div>
            </header>

            {{-- Main --}}
            <main class="flex-1 overflow-auto">
                <div class="p-6">

                    @if (isset($errors) && $errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                                <div>
                                    <h3 class="font-semibold text-red-800 mb-2">Error</h3>
                                    <ul class="text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <p class="text-green-700 text-sm font-semibold">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <p class="text-red-700 text-sm font-semibold">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('extra_js')
</body>

</html>

