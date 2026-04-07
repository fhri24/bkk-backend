<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BKK Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-blue-50/50 font-sans">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white border-r border-blue-100 flex-none hidden md:flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-blue-600 tracking-tight">BKK Connect</h1>
            </div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="#" class="flex items-center px-4 py-3 text-blue-600 bg-blue-50 rounded-xl transition-all">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                    <i data-lucide="building-2" class="w-5 h-5 mr-3"></i> Perusahaan
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                    <i data-lucide="briefcase" class="w-5 h-5 mr-3"></i> Lowongan Kerja
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i> Data Siswa
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="h-16 bg-white border-b border-blue-100 flex items-center justify-between px-8">
                <h2 class="text-slate-700 font-medium">Selamat Datang, Admin</h2>
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 border border-blue-200"></div>
                </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons(); // Mengaktifkan icon
    </script>
</body>
</html>