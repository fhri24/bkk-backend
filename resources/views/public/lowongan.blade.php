@extends('layouts.app')

@section('title', 'Lowongan Kerja - BKK SMKN 1 Garut')

@section('content')

    {{-- ===== SEARCH HERO ===== --}}
    <div class="bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80"
                 class="w-full h-full object-cover opacity-30" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-slate-900/90"></div>
        </div> 
        <div class="container mx-auto px-6 py-20 relative z-10 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Sistem Informasi Bursa Kerja</h1>
            <p class="text-lg opacity-90 mb-10">Temukan pekerjaan terbaik sesuai keahlianmu</p>
            <div class="flex flex-col md:flex-row justify-center gap-3 max-w-2xl mx-auto bg-white/10 p-2 rounded-2xl backdrop-blur-md">
                <input type="text" id="searchInput" placeholder="Cari posisi atau perusahaan..."
                    class="w-full md:flex-1 px-6 py-4 rounded-xl text-slate-900 font-medium focus:outline-none" />
                <button id="btnCari"
                    class="bg-amber-400 hover:bg-amber-500 text-slate-900 px-10 py-4 rounded-xl font-bold transition">
                    Cari Peluang
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MAIN ===== --}}
    <div class="container mx-auto px-6 py-16">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-md mb-2">Job Board</span>
                <h2 class="text-4xl font-extrabold text-[#001f3f]">Lowongan Terbaru</h2>
            </div>

            {{-- Filter Visibility --}}
            <div id="visGroup" class="flex flex-wrap bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200 gap-1">
                <button class="vis-btn px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 transition" data-v="all">Semua</button>
                <button class="vis-btn px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 transition" data-v="public">Public</button>
                <button class="vis-btn px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 transition" data-v="alumni_only">Alumni Only</button>
                <button class="vis-btn px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 transition" data-v="private">Private</button>
                <button class="vis-btn px-4 py-2.5 rounded-xl text-sm font-bold text-slate-500 transition" data-v="internal">Internal</button>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-10">

            {{-- Sidebar --}}
            <aside>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 sticky top-24">
                    <h4 class="font-bold mb-6 flex items-center text-slate-800">
                        <i class="fas fa-filter mr-2 text-blue-500"></i> Filter Pencarian
                    </h4>

                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tipe Kontrak</p>
                    <div id="typeGroup" class="space-y-2 mb-8">
                        <button class="type-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition flex justify-between items-center" data-t="Full-time">
                            <span>Full-time</span><i class="fas fa-check-circle text-blue-500 opacity-0 transition-opacity"></i>
                        </button>
                        <button class="type-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition flex justify-between items-center" data-t="Part-time">
                            <span>Part-time</span><i class="fas fa-check-circle text-blue-500 opacity-0 transition-opacity"></i>
                        </button>
                        <button class="type-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition flex justify-between items-center" data-t="Contract">
                            <span>Contract</span><i class="fas fa-check-circle text-blue-500 opacity-0 transition-opacity"></i>
                        </button>
                        <button class="type-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition flex justify-between items-center" data-t="Internship">
                            <span>Internship</span><i class="fas fa-check-circle text-blue-500 opacity-0 transition-opacity"></i>
                        </button>
                    </div>

                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Bidang Keahlian</p>
                    <div id="skillGroup" class="space-y-2 mb-8">
                        <button class="skill-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition" data-s="all">Semua Jurusan</button>
                        <button class="skill-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition" data-s="Teknik Otomotif">Teknik Otomotif</button>
                        <button class="skill-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition" data-s="Teknik Komputer">Teknik Komputer</button>
                        <button class="skill-btn w-full text-left px-4 py-3 rounded-xl border border-slate-100 text-sm font-semibold transition" data-s="Tata Boga">Tata Boga</button>
                    </div>

                    <button id="btnReset" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-500 py-3 rounded-xl text-xs font-bold transition">
                        <i class="fas fa-redo mr-2"></i> Reset Filter
                    </button>
                </div>
            </aside>

            {{-- Job Cards --}}
            <div class="lg:col-span-3">
                <div id="jobList" class="space-y-6">
                    @forelse($jobs as $job)
                        @php
                            $jType   = $job->job_type   ?? '';
                            $jVis    = $job->visibility  ?? 'public';
                            $jSkill  = $job->skill_required ?? '';
                            $jSalary = $job->salary     ?? 'Kompetitif';
                            $jLoc    = $job->location   ?? '-';
                            $jId     = $job->job_id;
                            $cName   = $job->company->company_name ?? 'Tidak Diketahui';
                            $src     = $job->source ?? 'internal';

                            $logo = $job->logo
                                ? Storage::url($job->logo)
                                : ($job->company && $job->company->logo
                                    ? Storage::url($job->company->logo)
                                    : 'https://ui-avatars.com/api/?name='.urlencode($cName).'&background=e2e8f0&color=001f3f&size=80');

                            $badge = $src === 'kemenaker'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-blue-100 text-blue-700';
                            $badgeTxt = $src === 'kemenaker' ? 'Kemenaker' : 'Internal BKK';

                            // CEK STATUS TERSEIMPAN
                            $isSaved = isset($savedJobIds) && in_array($jId, $savedJobIds);
                        @endphp

                        <div class="jcard bg-white p-7 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-6 hover:shadow-xl transition"
                             data-title="{{ strtolower($job->title) }}"
                             data-company="{{ strtolower($cName) }}"
                             data-type="{{ $jType }}"
                             data-vis="{{ $jVis }}"
                             data-skill="{{ $jSkill }}">

                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center shrink-0 border border-slate-100 overflow-hidden">
                                <img src="{{ $logo }}" class="w-10 h-10 object-contain" alt="logo">
                            </div>

                            <div class="flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                    <h3 class="font-extrabold text-xl text-slate-800">{{ $job->title }}</h3>
                                    <span class="{{ $badge }} text-[9px] font-black px-3 py-1 rounded-full uppercase">{{ $badgeTxt }}</span>
                                </div>
                                <p class="text-blue-600 font-bold text-sm mb-4">{{ $cName }}</p>

                                <div class="flex flex-wrap gap-5 text-xs text-slate-500 font-bold mb-5">
                                    <span><i class="fas fa-map-marker-alt text-blue-400 mr-1"></i>{{ $jLoc }}</span>
                                    <span><i class="fas fa-wallet text-blue-400 mr-1"></i>{{ $jSalary }}</span>
                                    <span><i class="fas fa-briefcase text-blue-400 mr-1"></i>{{ $jType ?: '-' }}</span>
                                    <span><i class="fas fa-eye text-blue-400 mr-1"></i>{{ ucfirst(str_replace('_',' ',$jVis)) }}</span>
                                </div>

                                <div class="flex items-center justify-between pt-5 border-t border-slate-50">
                                    <a href="{{ route('public.lowongan.detail', $jId) }}"
                                       class="text-blue-600 font-extrabold text-sm hover:underline flex items-center gap-2">
                                        Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                                    </a> 
                                    @auth
                                    {{-- TOMBOL BARU DISINI --}}
                                    <button onclick="toggleSaveJob(this, {{ $job->job_id }})" 
                                        class="w-10 h-10 rounded-xl flex items-center justify-center border transition-all 
                                        {{ $isSaved ? 'bg-red-50 text-red-500 border-red-200' : 'bg-gray-50 text-gray-400 border-slate-200 hover:text-red-500' }}">
                                        <i class="fas fa-bookmark {{ $isSaved ? 'fa-solid' : 'fa-regular' }}"></i>
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

                <div id="emptyMsg" style="display:none"
                     class="text-center py-32 bg-white rounded-3xl border border-dashed border-slate-200 mt-6">
                    <i class="fas fa-search text-5xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold mb-2">Lowongan tidak ditemukan.</p>
                    <button id="btnReset2" class="text-blue-600 font-bold hover:underline">Hapus semua filter</button>
                </div>
            </div>
        </div>
    </div> 

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---------- STATE ---------- */
    var S = { search: '', vis: 'all', types: [], skill: 'all' };

    /* ---------- SEMUA CARD ---------- */
    var cards = document.querySelectorAll('.jcard');

    /* ---------- FILTER UTAMA ---------- */
    function applyAll() {
        var shown = 0;
        cards.forEach(function (c) {
            var t    = c.getAttribute('data-title')    || '';
            var co   = c.getAttribute('data-company') || '';
            var type = c.getAttribute('data-type')     || '';
            var vis  = c.getAttribute('data-vis')      || '';
            var sk   = c.getAttribute('data-skill')    || '';

            var ok =
                (S.search === '' || t.indexOf(S.search) !== -1 || co.indexOf(S.search) !== -1) &&
                (S.vis   === 'all' || vis   === S.vis) &&
                (S.types.length === 0 || S.types.indexOf(type) !== -1) &&
                (S.skill === 'all' || sk === S.skill);

            c.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        document.getElementById('emptyMsg').style.display = shown === 0 ? '' : 'none';
        paintButtons();
    }

    /* ---------- PAINT TOMBOL ---------- */
    function paintButtons() {
        document.querySelectorAll('.vis-btn').forEach(function (b) {
            var on = b.getAttribute('data-v') === S.vis;
            b.style.background  = on ? '#2563eb' : '';
            b.style.color        = on ? '#ffffff'  : '';
            b.style.boxShadow   = on ? '0 2px 8px #2563eb55' : '';
        });
        document.querySelectorAll('.type-btn').forEach(function (b) {
            var on = S.types.indexOf(b.getAttribute('data-t')) !== -1;
            b.style.background   = on ? '#eff6ff' : '';
            b.style.borderColor  = on ? '#3b82f6' : '';
            b.style.color        = on ? '#1d4ed8' : '';
            b.querySelector('i').style.opacity = on ? '1' : '0';
        });
        document.querySelectorAll('.skill-btn').forEach(function (b) {
            var on = b.getAttribute('data-s') === S.skill;
            b.style.background  = on ? '#eff6ff' : '';
            b.style.borderColor = on ? '#3b82f6' : '';
            b.style.color        = on ? '#1d4ed8' : '';
        });
    }

    function reset() {
        S = { search: '', vis: 'all', types: [], skill: 'all' };
        var inp = document.getElementById('searchInput');
        if (inp) inp.value = '';
        applyAll();
    }

    document.querySelectorAll('.vis-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            S.vis = this.getAttribute('data-v');
            applyAll();
        });
    });

    document.querySelectorAll('.type-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            var t = this.getAttribute('data-t');
            var i = S.types.indexOf(t);
            if (i !== -1) S.types.splice(i, 1); else S.types.push(t);
            applyAll();
        });
    });

    document.querySelectorAll('.skill-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            S.skill = this.getAttribute('data-s');
            applyAll();
        });
    });

    var searchInp = document.getElementById('searchInput');
    var btnCari   = document.getElementById('btnCari');

    if (searchInp) {
        searchInp.addEventListener('input', function () {
            S.search = this.value.toLowerCase().trim();
            applyAll();
        });
        searchInp.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { S.search = this.value.toLowerCase().trim(); applyAll(); }
        });
    }
    if (btnCari) {
        btnCari.addEventListener('click', function () {
            S.search = (searchInp ? searchInp.value : '').toLowerCase().trim();
            applyAll();
        });
    }

    var r1 = document.getElementById('btnReset');
    var r2 = document.getElementById('btnReset2');
    if (r1) r1.addEventListener('click', reset);
    if (r2) r2.addEventListener('click', reset);

    applyAll();
});
</script>

@endsection