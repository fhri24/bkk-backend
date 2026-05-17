@extends('layouts.app')

@section('title', 'Tips & Tricks Karir - BKK SMKN 1 Garut')

@section('extra_css')
    <style>
        .tip-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .tip-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.10);
        }

        .kategori-btn {
            transition: all 0.2s ease;
        }

        .kategori-btn.active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .prose-content h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 1.25rem 0 0.5rem;
            color: #1e293b;
        }

        .prose-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 1rem 0 0.4rem;
            color: #334155;
        }

        .prose-content p {
            margin-bottom: 0.75rem;
            color: #475569;
            line-height: 1.7;
        }

        .prose-content ul {
            list-style: disc;
            padding-left: 1.5rem;
            margin-bottom: 0.75rem;
            color: #475569;
        }

        .prose-content ol {
            list-style: decimal;
            padding-left: 1.5rem;
            margin-bottom: 0.75rem;
            color: #475569;
        }

        .prose-content li {
            margin-bottom: 0.3rem;
        }

        .prose-content strong {
            color: #1e293b;
        }

        .tip-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.2s ease;
        }

        .tip-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tip-modal-content {
            background: white;
            border-radius: 24px;
            width: 90%;
            max-width: 720px;
            max-height: 88vh;
            overflow-y: auto;
            animation: slideUp 0.25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-transition container mx-auto px-6 py-16">

        {{-- HEADER --}}
        <div class="text-center mb-12">
            <span
                class="inline-block bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full mb-4">
                Panduan Karir Alumni
            </span>
            <h1 class="text-4xl font-extrabold text-[#001f3f] mb-4">Tips & Tricks Dunia Kerja</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto leading-relaxed">
                Panduan praktis untuk mempersiapkan dirimu — dari lolos interview, psikotes, hingga membangun karir yang
                sukses.
            </p>
            <a href="{{ route('public.tutorial') }}"
                class="inline-flex items-center gap-2 mt-4 text-sm text-slate-400 hover:text-blue-600 transition">
                <i class="fas fa-chevron-left text-xs"></i> Lihat Panduan Pendaftaran
            </a>
        </div>

        {{-- FEATURED TIPS --}}
        @if ($featured->count() > 0 && !request()->filled('search') && !request()->filled('kategori'))
            <div class="mb-14">
                <h2 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-5 flex items-center gap-2">
                    <i class="fas fa-star text-amber-400"></i> Tips Unggulan
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach ($featured->take(3) as $item)
                        <div class="tip-card bg-gradient-to-br from-[#001f3f] to-[#003d6b] text-white rounded-[28px] p-8 cursor-pointer"
                            onclick="openTip(`{{ addslashes($item->judul) }}`, `{{ addslashes($item->kategori) }}`, `{{ $item->icon }}`, `{{ addslashes($item->konten) }}`)">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-2xl mb-6">
                                <i class="{{ $item->icon }}"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest text-blue-300 mb-2 block">{{ $item->kategori }}</span>
                            <h3 class="text-xl font-bold mb-3">{{ $item->judul }}</h3>
                            <p class="text-white/70 text-sm leading-relaxed">{{ Str::limit($item->ringkasan, 120) }}</p>
                            <div class="mt-6 flex items-center text-blue-300 text-sm font-semibold">
                                Baca selengkapnya <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- FILTER & SEARCH --}}
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between mb-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('public.tips') }}"
                    class="kategori-btn px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 {{ !request('kategori') ? 'active' : '' }}">
                    Semua
                </a>
                @foreach ($kategoriList as $kat)
                    <a href="{{ route('public.tips', ['kategori' => $kat]) }}"
                        class="kategori-btn px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 {{ request('kategori') === $kat ? 'active' : '' }}">
                        {{ $kat }}
                        @if (isset($kategoriCount[$kat]))
                            <span class="ml-1 text-xs opacity-60">({{ $kategoriCount[$kat] }})</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('public.tips') }}" class="flex gap-2">
                @if (request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <div class="flex items-center bg-white border border-slate-200 rounded-xl px-4 py-2.5 gap-2 shadow-sm">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tips..."
                        class="outline-none text-sm text-slate-700 bg-transparent w-44">
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    Cari
                </button>
                @if (request()->hasAny(['search', 'kategori']))
                    <a href="{{ route('public.tips') }}"
                        class="bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- GRID TIPS --}}
        @if ($tips->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach ($tips as $tip)
                    @php
                        $colorMap = [
                            'Interview' => [
                                'bg' => 'bg-blue-50',
                                'text' => 'text-blue-600',
                                'badge' => 'bg-blue-50 text-blue-600',
                            ],
                            'Psikotes' => [
                                'bg' => 'bg-purple-50',
                                'text' => 'text-purple-600',
                                'badge' => 'bg-purple-50 text-purple-600',
                            ],
                            'CV & Portofolio' => [
                                'bg' => 'bg-orange-50',
                                'text' => 'text-orange-600',
                                'badge' => 'bg-orange-50 text-orange-600',
                            ],
                            'Dunia Kerja' => [
                                'bg' => 'bg-green-50',
                                'text' => 'text-green-600',
                                'badge' => 'bg-green-50 text-green-600',
                            ],
                            'Wirausaha' => [
                                'bg' => 'bg-amber-50',
                                'text' => 'text-amber-600',
                                'badge' => 'bg-amber-50 text-amber-600',
                            ],
                            'Lainnya' => [
                                'bg' => 'bg-slate-50',
                                'text' => 'text-slate-600',
                                'badge' => 'bg-slate-100 text-slate-500',
                            ],
                        ];
                        $color = $colorMap[$tip->kategori] ?? $colorMap['Lainnya'];
                    @endphp
                    <div class="tip-card bg-white border border-slate-100 rounded-[24px] p-7 shadow-sm cursor-pointer"
                        onclick="openTip(`{{ addslashes($tip->judul) }}`, `{{ addslashes($tip->kategori) }}`, `{{ $tip->icon }}`, `{{ addslashes($tip->konten) }}`)">
                        <div class="flex items-start justify-between mb-5">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl {{ $color['bg'] }} {{ $color['text'] }}">
                                <i class="{{ $tip->icon }}"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full {{ $color['badge'] }}">
                                {{ $tip->kategori }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $tip->judul }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ Str::limit($tip->ringkasan, 110) }}</p>
                        <div class="flex items-center text-blue-600 text-sm font-semibold">
                            Baca Tips <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($tips->hasPages())
                <div class="flex justify-center">{{ $tips->links() }}</div>
            @endif
        @else
            <div class="text-center py-20">
                <i class="fas fa-search text-slate-200 text-6xl mb-6 block"></i>
                <p class="text-slate-500 font-semibold text-lg">Belum ada tips yang ditemukan</p>
                <a href="{{ route('public.tips') }}"
                    class="inline-block mt-6 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition">
                    Lihat Semua Tips
                </a>
            </div>
        @endif

    </div>

    {{-- MODAL DETAIL --}}
    <div id="tipModal" class="tip-modal">
        <div class="tip-modal-content">
            <div
                class="sticky top-0 bg-white border-b border-slate-100 px-8 py-5 flex items-center justify-between rounded-t-3xl z-10">
                <div class="flex items-center gap-3">
                    <div id="modalIcon"
                        class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    </div>
                    <div>
                        <p id="modalKategori" class="text-[10px] font-bold uppercase tracking-widest text-blue-600"></p>
                        <h3 id="modalJudul" class="font-bold text-slate-800 text-base"></h3>
                    </div>
                </div>
                <button onclick="closeTip()"
                    class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center text-slate-500 text-lg">&times;</button>
            </div>
            <div class="px-8 py-7">
                <div id="modalKonten" class="prose-content text-sm"></div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function openTip(judul, kategori, icon, konten) {
            document.getElementById('modalJudul').textContent = judul;
            document.getElementById('modalKategori').textContent = kategori;
            document.getElementById('modalIcon').innerHTML = `<i class="${icon}"></i>`;
            document.getElementById('modalKonten').innerHTML = konten;
            document.getElementById('tipModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeTip() {
            document.getElementById('tipModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        document.getElementById('tipModal').addEventListener('click', function(e) {
            if (e.target === this) closeTip();
        });
    </script>
@endsection
