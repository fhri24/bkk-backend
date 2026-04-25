@extends('layouts.app')

@section('title', 'Lowongan Kerja - BKK SMKN 1 Garut')

@section('content')
    {{-- ===== SEARCH HERO ===== --}}
    <div class="search-hero bg-slate-900 relative overflow-hidden">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80" 
                 class="w-full h-full object-cover opacity-30" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-slate-900/90"></div>
        </div>

        <div class="container mx-auto px-6 py-20 relative z-10 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Sistem Informasi Bursa Kerja</h1>
            <p class="text-lg md:text-xl opacity-90 mb-10">Temukan pekerjaan terbaik sesuai keahlianmu</p>
            
            <div class="flex flex-col md:flex-row justify-center items-center gap-3 max-w-2xl mx-auto bg-white/10 p-2 rounded-2xl backdrop-blur-md">
                <input type="text" id="searchInput" placeholder="Cari posisi atau perusahaan..."
                    class="w-full md:flex-1 px-6 py-4 rounded-xl text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-400" />
                <button onclick="applyFilters()" class="w-full md:w-auto bg-amber-400 hover:bg-amber-500 text-slate-900 px-10 py-4 rounded-xl font-bold transition transform hover:scale-105 active:scale-95">
                    Cari Peluang
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="container mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-md mb-2">Job Board</span>
                <h2 class="text-4xl font-extrabold text-slate-800 text-[#001f3f]">Lowongan Terbaru</h2>
            </div>
            
            {{-- Category Filter (Internal vs Kemenaker) --}}
            <div class="flex bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200">
                <button class="filter-btn px-6 py-2.5 rounded-xl text-sm font-bold transition" data-filter="all" onclick="filterByCategory('all')">Semua</button>
                <button class="filter-btn px-6 py-2.5 rounded-xl text-sm font-bold transition" data-filter="internal" onclick="filterByCategory('internal')">Internal BKK</button>
                <button class="filter-btn px-6 py-2.5 rounded-xl text-sm font-bold transition" data-filter="kemenaker" onclick="filterByCategory('kemenaker')">Kemenaker</button>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-10">
            {{-- Sidebar Filter --}}
            <aside class="space-y-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 sticky top-24">
                    <h4 class="font-bold mb-6 flex items-center text-slate-800">
                        <i class="fas fa-filter mr-2 text-blue-500"></i> Filter Pencarian
                    </h4>
                    
                    <div class="space-y-8">
                        {{-- Tipe Pekerjaan --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Tipe Kontrak</label>
                            <div class="space-y-2">
                                @foreach(['Tetap' => 'Full Time', 'Kontrak' => 'Kontrak', 'Magang' => 'Magang (PKL)'] as $val => $label)
                                <button onclick="toggleTypeFilter('{{ $val }}')" 
                                        class="type-filter-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition flex items-center justify-between group" 
                                        data-type="{{ $val }}">
                                    <span>{{ $label }}</span>
                                    <i class="fas fa-check-circle opacity-0 transition"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Skill/Jurusan --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Bidang Keahlian</label>
                            <div class="space-y-2">
                                @foreach(['Semua', 'Teknik Otomotif', 'Teknik Komputer', 'Tata Boga'] as $skill)
                                <button onclick="filterBySkill('{{ $skill }}')" 
                                        class="skill-filter-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition" 
                                        data-skill="{{ $skill }}">
                                    {{ $skill === 'Semua' ? 'Semua Jurusan' : $skill }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <button onclick="resetFilters()" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-500 py-3 rounded-xl text-xs font-bold transition">
                            <i class="fas fa-redo mr-2"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </aside>

            {{-- Job List --}}
            <div class="lg:col-span-3">
                <div id="jobContainer" class="space-y-6">
                    @forelse($jobs as $job)
                        @php
                            $source = $job->source ?? 'internal';
                            $badgeStyle = $source === 'kemenaker' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
                            $logoFallback = 'https://ui-avatars.com/api/?name=' . urlencode($job->company) . '&background=e2e8f0&color=001f3f&size=80';
                        @endphp

                        <div class="bg-white p-7 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-8 hover:shadow-xl hover:shadow-blue-500/5 transition job-item show"
                             data-category="{{ $source }}"
                             data-type="{{ $job->type }}"
                             data-skill="{{ $job->skill_required ?? 'Semua' }}">
                            
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center shrink-0 border border-slate-100 shadow-inner overflow-hidden">
                                <img src="{{ $job->logo ? Storage::url($job->logo) : $logoFallback }}" class="w-10 h-10 object-contain" alt="Logo">
                            </div>

                            <div class="flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                                    <h3 class="job-title font-extrabold text-xl text-slate-800">{{ $job->title }}</h3>
                                    <span class="{{ $badgeStyle }} text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                                        {{ $source === 'kemenaker' ? 'Kemenaker' : 'Internal BKK' }}
                                    </span>
                                </div>
                                <p class="company-name text-blue-600 font-bold text-sm mb-5">{{ $job->company }}</p>
                                
                                <div class="flex flex-wrap gap-6 text-xs text-slate-500 font-bold mb-6">
                                    <span class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-blue-400"></i> {{ $job->location }}</span>
                                    <span class="flex items-center gap-2"><i class="fas fa-wallet text-blue-400"></i> {{ $job->salary ?? 'Kompetitif' }}</span>
                                    <span class="flex items-center gap-2"><i class="fas fa-briefcase text-blue-400"></i> {{ $job->type }}</span>
                                </div>

                                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                    <a href="{{ route('public.lowongan.detail', $job->job_id ?? $job->id) }}" 
                                       class="text-blue-600 font-extrabold text-sm hover:underline flex items-center gap-2">
                                        Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                    
                                    @auth
                                        <button onclick="saveJob(this, {{ $job->job_id ?? $job->id }})" 
                                                class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-100 transition">
                                            <i class="far fa-bookmark"></i>
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100">
                             <i class="fas fa-briefcase text-6xl text-slate-200 mb-4"></i>
                             <p class="text-slate-500 font-bold">Belum ada lowongan tersedia.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Empty State for Search JS --}}
                <div id="noResults" class="hidden text-center py-32 bg-white rounded-3xl border border-dashed border-slate-200">
                    <i class="fas fa-search text-5xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold">Lowongan tidak ditemukan.</p>
                    <button onclick="resetFilters()" class="text-blue-600 font-bold mt-2 hover:underline">Hapus semua filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('extra_js')
<script>
    let filters = { search: '', category: 'all', types: [], skill: 'Semua' };

    function filterByCategory(cat) {
        filters.category = cat;
        applyFilters();
    }

    function toggleTypeFilter(type) {
        const index = filters.types.indexOf(type);
        if (index > -1) filters.types.splice(index, 1);
        else filters.types.push(type);
        applyFilters();
    }

    function filterBySkill(skill) {
        filters.skill = skill;
        applyFilters();
    }

    function resetFilters() {
        filters = { search: '', category: 'all', types: [], skill: 'Semua' };
        document.getElementById('searchInput').value = '';
        applyFilters();
    }

    function applyFilters() {
        filters.search = document.getElementById('searchInput').value.toLowerCase().trim();
        const items = document.querySelectorAll('.job-item');
        let visibleCount = 0;

        items.forEach(item => {
            const title = item.querySelector('.job-title').innerText.toLowerCase();
            const company = item.querySelector('.company-name').innerText.toLowerCase();
            const cat = item.getAttribute('data-category');
            const type = item.getAttribute('data-type');
            const skill = item.getAttribute('data-skill');

            const mSearch = title.includes(filters.search) || company.includes(filters.search);
            const mCat = filters.category === 'all' || cat === filters.category;
            const mType = filters.types.length === 0 || filters.types.includes(type);
            const mSkill = filters.skill === 'Semua' || skill === filters.skill;

            if (mSearch && mCat && mType && mSkill) {
                item.classList.add('show');
                visibleCount++;
            } else {
                item.classList.remove('show');
            }
        });

        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
        updateUI();
    }

    function updateUI() {
        // Update Buttons (Kategori, Tipe, Skill) secara visual
        document.querySelectorAll('.filter-btn').forEach(btn => {
            const active = btn.getAttribute('data-filter') === filters.category;
            btn.className = `filter-btn px-6 py-2.5 rounded-xl text-sm font-bold transition ${active ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'}`;
        });

        document.querySelectorAll('.type-filter-btn').forEach(btn => {
            const active = filters.types.includes(btn.getAttribute('data-type'));
            btn.className = `type-filter-btn w-full text-left px-4 py-3 rounded-xl border text-sm font-semibold transition flex items-center justify-between group ${active ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-slate-100 text-slate-600'}`;
            btn.querySelector('i').className = `fas fa-check-circle transition ${active ? 'opacity-100 text-blue-500' : 'opacity-0'}`;
        });

        document.querySelectorAll('.skill-filter-btn').forEach(btn => {
            const active = btn.getAttribute('data-skill') === filters.skill;
            btn.className = `skill-filter-btn w-full text-left px-4 py-3 rounded-xl border text-sm font-semibold transition ${active ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-slate-100 text-slate-600'}`;
        });
    }

    document.getElementById('searchInput').addEventListener('keypress', e => { if (e.key === 'Enter') applyFilters(); });
    window.addEventListener('load', applyFilters);
</script>
@endpush