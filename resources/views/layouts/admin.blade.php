<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - BKK SMKN 1 Garut')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f5f7fa; }

        .sidebar {
            background: linear-gradient(135deg, #001f3f 0%, #003d6b 100%);
            transition: margin-left 0.3s ease;
        }

        .sidebar-link {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-toggle {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover,
        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #3b82f6;
        }

        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.2);
            border-left-color: #3b82f6;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .sidebar-link i { width: 20px; text-align: center; }

        .nav-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 12px 0;
        }

        .stat-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .table-custom {
            background: white;
            border-radius: 12px;
            overflow: hidden;
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

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }

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
            .sidebar { margin-left: -16rem; }
            .sidebar.mobile-active { margin-left: 0; }
        }

        .mobile-menu-btn {
            display: none;
        }

        @media (max-width: 1024px) {
            .mobile-menu-btn { display: flex; }
        }

        .breadcrumb-item {
            color: #94a3b8;
            font-size: 13px;
        }

        .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 600;
        }

        .header-profile {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }
        #searchResult {
            z-index: 40;
        }

        #notifDropdown {
            z-index: 50;
        }
    </style>

    @yield('extra_css')
</head>
<body>
<div class="flex h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="sidebar w-64 text-white flex flex-col fixed h-full lg:relative z-40 lg:z-10">
        <!-- Logo -->
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center font-bold text-lg">BKK</div>
                <div class="ml-3">
                    <h2 class="font-bold text-white text-sm">BKK SMKN 1</h2>
                    <p class="text-xs text-blue-200">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto px-4 py-6">
            <div>
                <h3 class="text-xs font-semibold text-blue-200 uppercase tracking-wider px-3 mb-4">Menu Utama</h3>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                @if(auth()->user()->hasPermission('manage_jobs'))
                <a href="{{ route('admin.jobs.index') }}" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->segment(2) === 'jobs' ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span class="ml-3">Lowongan Kerja</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage_job_applications'))
                <a href="{{ route('admin.job-applications.index') }}" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->segment(2) === 'job-applications' ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span class="ml-3">Lamaran</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage_students'))
                <a href="{{ route('admin.students.index') }}" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->segment(2) === 'students' ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="ml-3">Alumni</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage_companies'))
                <a href="{{ route('admin.companies.index') }}" class="sidebar-link flex items-center px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white {{ request()->segment(2) === 'companies' ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span class="ml-3">Perusahaan</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('view_reports'))
                <div class="mb-2">
                    <button type="button" class="sidebar-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white bg-transparent focus:outline-none" aria-expanded="false">
                        <span class="flex items-center">
                            <i class="fas fa-chart-line"></i>
                            <span class="ml-3">Laporan</span>
                        </span>
                        <i class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                    <div class="reports-submenu ml-8 space-y-1 hidden">
                        <a href="{{ route('admin.reports.index') }}#bmw-report" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'reports' ? 'active' : '' }}">Laporan BMW</a>
                        <a href="{{ route('admin.reports.index') }}#job-report" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'reports' ? 'active' : '' }}">Rekapitulasi Lamaran</a>
                        <a href="{{ route('admin.reports.index') }}#export-actions" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'reports' ? 'active' : '' }}">Export Data</a>
                    </div>
                </div>
                @endif

                @if(auth()->user()->hasPermission('manage_settings'))
                <div class="mb-2">
                    <button type="button" class="sidebar-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white bg-transparent focus:outline-none" aria-expanded="false">
                        <span class="flex items-center">
                            <i class="fas fa-sliders-h"></i>
                            <span class="ml-3">Pengaturan</span>
                        </span>
                        <i class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                    <div class="settings-submenu ml-8 space-y-1 hidden">
                        <a href="{{ route('admin.settings.profile') }}" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'settings' && request()->segment(3) === 'profile' ? 'active' : '' }}">Profil Sekolah</a>
                        <a href="{{ route('admin.settings.majors.index') }}" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'settings' && request()->segment(3) === 'majors' ? 'active' : '' }}">Tabel Jurusan</a>
                        <a href="{{ route('admin.settings.years.index') }}" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'settings' && request()->segment(3) === 'years' ? 'active' : '' }}">Tahun Lulus</a>
                    </div>
                </div>
                @endif
            </div>

            <div class="nav-divider"></div>

            @if(auth()->user()->hasPermission('manage_users') || auth()->user()->hasPermission('manage_settings') || auth()->user()->hasPermission('view_activity_logs'))
            <div class="mb-2">
                <button type="button" class="sidebar-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-lg mb-2 text-white/80 hover:text-white bg-transparent focus:outline-none" aria-expanded="false">
                    <span class="flex items-center">
                        <i class="fas fa-cogs"></i>
                        <span class="ml-3">Manajemen</span>
                    </span>
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div class="management-submenu ml-8 space-y-1 hidden">
                    @if(auth()->user()->hasPermission('manage_users'))
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'users' ? 'active' : '' }}">
                        Pengguna
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('manage_settings'))
                    <a href="{{ route('admin.roles.index') }}" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'roles' ? 'active' : '' }}">
                        Hak Akses
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_activity_logs'))
                    <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link block px-3 py-2 rounded-lg text-white/80 hover:text-white {{ request()->segment(2) === 'activity-logs' ? 'active' : '' }}">
                        Log Aktivitas
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-white/10">
            <button class="w-full flex items-center px-3 py-2.5 rounded-lg text-white/80 hover:text-white text-left text-sm" onclick="document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span class="ml-3">Logout</span>
            </button>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <button class="mobile-menu-btn lg:hidden mr-4" onclick="document.querySelector('.sidebar').classList.toggle('mobile-active')">
                    <i class="fas fa-bars text-2xl text-slate-800"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                    <div class="flex items-center mt-1">
                        <a href="/" class="breadcrumb-item hover:text-slate-700">Home</a>
                        <span class="mx-2 text-slate-300">/</span>
                        <span class="breadcrumb-item active">@yield('page_title', 'Dashboard')</span>
                    </div>
                </div>
            </div>

            <!-- Top Right -->
<div class="flex items-center gap-4">

    <!-- SEARCH -->
    <div class="relative hidden md:block">
        <div class="flex items-center bg-slate-100 rounded-lg px-3 py-2">
            <i class="fas fa-search text-slate-400 mr-2"></i>
            <input id="search" type="text" placeholder="Cari..." class="bg-transparent text-sm focus:outline-none text-slate-700" />
        </div>

        <!-- HASIL SEARCH -->
        <div id="searchResult" class="absolute bg-white shadow rounded w-72 mt-2 hidden z-50 max-h-64 overflow-auto"></div>
    </div>

    <!-- NOTIF -->
    <div class="relative">
        <button id="notifBtn" class="relative w-10 h-10 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-700">
            <i class="fas fa-bell"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- DROPDOWN NOTIF -->
        <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow rounded z-50 max-h-64 overflow-auto"></div>
    </div>

    <!-- PROFILE -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
            {{ substr(auth()->user()->email, 0, 1) }}
        </div>
        <div class="hidden md:block text-sm">
            <p class="font-semibold text-slate-800">{{ auth()->user()->email }}</p>
            <p class="text-xs text-slate-500 capitalize">
                @if(auth()->user()->role)
                    {{ auth()->user()->role->display_name ?? 'Admin' }}
                @else
                    Admin
                @endif
            </p>
        </div>
    </div>

</div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-auto">
            <div class="p-6">
                @if ($errors->any())
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
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    // Mark active sidebar link and manage collapsible sections
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;

        document.querySelectorAll('.sidebar-link').forEach(link => {
            if (link.href.includes(currentPath)) {
                link.classList.add('active');
            }
        });

        document.querySelectorAll('.sidebar-toggle').forEach(button => {
            const submenu = button.nextElementSibling;
            button.addEventListener('click', function() {
                if (!submenu) return;
                submenu.classList.toggle('hidden');
                this.querySelector('i.fas.fa-chevron-down')?.classList.toggle('rotate-180');
            });
        });

        if (currentPath.includes('/admin/reports')) {
            const reportsSubmenu = document.querySelector('.reports-submenu');
            const reportChevron = document.querySelector('.reports-submenu')?.previousElementSibling?.querySelector('i.fas.fa-chevron-down');
            if (reportsSubmenu) {
                reportsSubmenu.classList.remove('hidden');
            }
            if (reportChevron) {
                reportChevron.classList.add('rotate-180');
            }
        }

        if (currentPath.includes('/admin/users') || currentPath.includes('/admin/roles') || currentPath.includes('/admin/activity-logs')) {
            const managementSubmenu = document.querySelector('.management-submenu');
            const managementChevron = document.querySelector('.management-submenu')?.previousElementSibling?.querySelector('i.fas.fa-chevron-down');
            if (managementSubmenu) {
                managementSubmenu.classList.remove('hidden');
            }
            if (managementChevron) {
                managementChevron.classList.add('rotate-180');
            }
        }

        if (currentPath.includes('/admin/settings')) {
            const settingsSubmenu = document.querySelector('.settings-submenu');
            const settingsChevron = document.querySelector('.settings-submenu')?.previousElementSibling?.querySelector('i.fas.fa-chevron-down');
            if (settingsSubmenu) {
                settingsSubmenu.classList.remove('hidden');
            }
            if (settingsChevron) {
                settingsChevron.classList.add('rotate-180');
            }
        }
    });
</script>

@yield('extra_js')
<script>
document.addEventListener("DOMContentLoaded", function () {

 // =======================
// 🔍 SEARCH SYSTEM
// =======================
const input = document.getElementById("search");
const resultBox = document.getElementById("searchResult");

if (input && resultBox) {
    input.addEventListener("keyup", function () {
        let query = this.value.trim();

        if (query.length < 1) {
            resultBox.innerHTML = "";
            resultBox.classList.add("hidden");
            return;
        }

        fetch(`/admin/search?q=${query}`)
            .then(res => res.json())
            .then(response => {
                if (!response.success) {
                    resultBox.innerHTML = "<div class='p-3 text-red-500 text-sm'>⚠️ Error: " + (response.message || "Server bermasalah") + "</div>";
                    resultBox.classList.remove("hidden");
                    return;
                }

                const students = response.students || [];
                const applications = response.applications || [];

                let html = "";

                // 🔵 ALUMNI (Sesuai kolom Migration lu: student_id & full_name)
                if (students.length > 0) {
                    html += "<div class='p-2 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50'>Alumni</div>";
                    students.forEach(item => {
                        html += `
                            <a href="/admin/students/${item.student_id}" class="block p-3 hover:bg-slate-100 border-b border-slate-50 transition">
                                <div class="flex items-center">
                                    <span class="mr-2">👨‍🎓</span>
                                    <div class="text-sm font-medium text-slate-700">${item.full_name}</div>
                                </div>
                            </a>`;
                    });
                }

                // 🟡 LAMARAN
                if (applications.length > 0) {
                    html += "<div class='p-2 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50'>Lamaran</div>";
                    applications.forEach(item => {
                        html += `
                            <a href="/admin/job-applications/${item.id}" class="block p-3 hover:bg-slate-100 border-b border-slate-50 transition">
                                <div class="flex items-center">
                                    <span class="mr-2">📄</span>
                                    <div class="text-sm font-medium text-slate-700">Status: ${item.status}</div>
                                </div>
                            </a>`;
                    });
                }

                // ❌ NOT FOUND
                if (students.length === 0 && applications.length === 0) {
                    html = `
                        <div class="p-6 text-center text-slate-500">
                            <div class="text-2xl mb-2">😕</div>
                            <div class="text-sm font-semibold">" ${query} "</div>
                            <div class="text-xs mt-1 text-slate-400">Tidak ditemukan hasil apa pun</div>
                        </div>
                    `;
                }

                resultBox.innerHTML = html;
                resultBox.classList.remove("hidden");
            })
            .catch(err => {
                console.error("Search Error:", err);
                resultBox.innerHTML = "<div class='p-3 text-red-500 text-sm italic'>Gagal mengambil data dari server...</div>";
                resultBox.classList.remove("hidden");
            });
    });

    // Klik di luar kotak search buat nutup hasil
    document.addEventListener("click", function(e) {
        if (!input.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.classList.add("hidden");
        }
    });
}

    // =======================
    // 🔔 NOTIF
    // =======================
    const notifBtn = document.getElementById("notifBtn");
    const notifDropdown = document.getElementById("notifDropdown");

    if (notifBtn && notifDropdown) {

        notifBtn.addEventListener("click", function (e) {
            e.stopPropagation();

            notifDropdown.classList.toggle("hidden");

            // 🔥 LOAD DATA
            fetch('/admin/notifications')
                .then(res => res.json())
                .then(res => {

                    let html = "";

                    if (res.length === 0) {
                        html = "<div class='p-3 text-slate-500'>Tidak ada notifikasi</div>";
                    } else {
                        res.forEach(item => {
                            html += `
                                <a href="${item.link}" class="block p-3 border-b hover:bg-slate-100">
                                    <div class="font-semibold text-sm">${item.title}</div>
                                    <div class="text-xs text-slate-500">${item.time}</div>
                                </a>
                            `;
                        });
                    }

                    notifDropdown.innerHTML = html;

                })
                .catch(err => {
                    console.error(err);
                    notifDropdown.innerHTML = "<div class='p-3 text-red-500'>Gagal load notif</div>";
                });
        });

        // klik luar notif nutup
        document.addEventListener("click", function (e) {
            if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                notifDropdown.classList.add("hidden");
            }
        });
    }

});
</script>
</body>
</html>
