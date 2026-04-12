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

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById("mobile-menu");
            menu.classList.toggle("hidden");
        }

        function closeMobileMenu() {
            document.getElementById("mobile-menu").classList.add("hidden");
        }
    </script>
    @yield('extra_js')
</body>
</html>
