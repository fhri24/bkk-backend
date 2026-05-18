@extends('layouts.app')

@section('title', 'Tracer Study - BKK SMKN 1 Garut')

@section('extra_css')
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .status-card {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .status-radio:checked+.status-card {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .status-card:hover {
            border-color: #93c5fd;
        }

        .kesesuaian-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }

        .kesesuaian-radio:checked+.kesesuaian-card {
            border-color: #2563eb;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="page-transition container mx-auto px-6 py-16">

        {{-- Header & Statistik --}}
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
            <div>
                <h2 class="text-4xl font-extrabold text-[#001f3f] mb-6">Tracer Study & <br />Laporan Karir</h2>
                <p class="text-slate-500 text-lg leading-relaxed mb-8">Sistem pelacakan jejak alumni untuk memetakan kualitas
                    pendidikan dan kebutuhan dunia industri.</p>

                @auth
                    @if (in_array(auth()->user()->role->name ?? '', ['siswa', 'alumni']))
                        {{-- Flash success --}}
                        @if (session('success'))
                            <div
                                class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm mb-4 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div
                                class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm mb-4 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <button onclick="openTracerForm()"
                            class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 mb-8">
                            <i class="fas fa-edit mr-2"></i>Isi Data Tracer Study Anda
                        </button>
                    @endif
                @else
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl mb-8">
                        <p class="text-amber-700 text-sm italic">
                            Silahkan <a href="{{ route('login') }}" class="font-bold underline">Login</a> sebagai Siswa/Alumni
                            untuk mengisi data tracer.
                        </p>
                    </div>
                @endauth

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-600 p-6 rounded-2xl text-white shadow-lg">
                        <div class="text-3xl font-bold mb-1">92%</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest opacity-80">Data Terverifikasi</div>
                    </div>
                    <div class="bg-slate-800 p-6 rounded-2xl text-white shadow-lg">
                        <div class="text-3xl font-bold mb-1">450+</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest opacity-80">User Surveyed</div>
                    </div>
                </div>
            </div>

            {{-- Chart Container - FIXED --}}
            <div class="bg-white p-8 rounded-[40px] shadow-2xl border border-slate-100"
                style="height: 360px; display: flex; align-items: center; justify-content: center;">
                <div class="chart-wrapper">
                    <canvas id="tracerChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Opsi Laporan --}}
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100 group">
                <div
                    class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition duration-500">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-4">Tracer Study Report</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Laporan lengkap hasil pelacakan alumni mencakup masa tunggu
                    kerja dan relevansi kurikulum.</p>
                <button onclick="window.location.href='{{ route('tracer.report') }}'"
                    class="bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition">
                    Lihat Laporan Alumni
                </button>
            </div>

            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100 group">
                <div
                    class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-8 group-hover:bg-green-600 group-hover:text-white transition duration-500">
                    <i class="fas fa-industry"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-4">User Study Report (DUDI)</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Survei tingkat kepuasan mitra industri terhadap performa
                    kerja alumni SMKN 1 Garut.</p>
                <button onclick="window.location.href='{{ route('public.tracer-industri') }}'"
                    class="block bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-green-600 hover:text-white transition text-center w-full">
                    Lihat Laporan Industri
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL FORM TRACER ===== --}}
    @auth
        <div id="tracerFormModal" class="modal">
            <div class="modal-content max-h-[90vh] overflow-y-auto">

                {{-- Header --}}
                <div class="bg-[#001f3f] p-8 text-white sticky top-0 flex justify-between items-center z-10 rounded-t-3xl">
                    <div>
                        <h2 class="text-2xl font-extrabold">Isi Data Tracer Study</h2>
                        <p class="text-blue-200 text-sm mt-1">Bantu kami memetakan karir alumni SMKN 1 Garut</p>
                    </div>
                    <button onclick="closeTracerForm()"
                        class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 transition flex items-center justify-center text-white text-xl">
                        &times;
                    </button>
                </div>

                <form method="POST" action="{{ route('student.tracer.store') }}" class="p-8 space-y-6">
                    @csrf

                    {{-- 1. Status --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">
                            Status Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">

                            <label class="block">
                                <input type="radio" name="status_saat_ini" value="Bekerja" class="status-radio sr-only"
                                    required onchange="handleStatusChange('Bekerja')">
                                <div class="status-card">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <span class="font-semibold text-slate-700 text-sm">Bekerja</span>
                                </div>
                            </label>

                            <label class="block">
                                <input type="radio" name="status_saat_ini" value="Kuliah" class="status-radio sr-only"
                                    onchange="handleStatusChange('Kuliah')">
                                <div class="status-card">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <span class="font-semibold text-slate-700 text-sm">Melanjutkan Pendidikan</span>
                                </div>
                            </label>

                            <label class="block">
                                <input type="radio" name="status_saat_ini" value="Wirausaha" class="status-radio sr-only"
                                    onchange="handleStatusChange('Wirausaha')">
                                <div class="status-card">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <span class="font-semibold text-slate-700 text-sm">Wirausaha</span>
                                </div>
                            </label>

                            <label class="block">
                                <input type="radio" name="status_saat_ini" value="Belum Bekerja" class="status-radio sr-only"
                                    onchange="handleStatusChange('Belum Bekerja')">
                                <div class="status-card">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 flex-shrink-0">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <span class="font-semibold text-slate-700 text-sm">Mencari Pekerjaan</span>
                                </div>
                            </label>

                        </div>
                    </div>

                    {{-- 2. Nama Instansi (kondisional) --}}
                    <div id="instansiGroup" class="hidden">
                        <label class="block text-sm font-bold text-slate-700 mb-2" id="instansiLabel">
                            Nama Perusahaan / Institusi
                        </label>
                        <input type="text" name="nama_instansi"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Masukkan nama tempat bekerja / kuliah / usaha">
                    </div>

                    {{-- 3. Tanggal Mulai (kondisional) --}}
                    <div id="tglGroup" class="hidden">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai Masuk</label>
                        <input type="date" name="tgl_mulai_masuk"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                            max="{{ date('Y-m-d') }}">
                    </div>

                    {{-- 4. Kesesuaian Jurusan (hanya Bekerja & Wirausaha) --}}
                    <div id="keselarasanGroup" class="hidden">
                        <label class="block text-sm font-bold text-slate-700 mb-3">
                            Apakah sesuai dengan jurusan?
                        </label>
                        <div class="flex gap-3">
                            <label class="flex-1">
                                <input type="radio" name="keselarasan_jurusan" value="Sesuai"
                                    class="kesesuaian-radio sr-only">
                                <div class="kesesuaian-card">✓ Sesuai</div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="keselarasan_jurusan" value="Tidak Sesuai"
                                    class="kesesuaian-radio sr-only">
                                <div class="kesesuaian-card">✕ Tidak Sesuai</div>
                            </label>
                        </div>
                    </div>

                    {{-- 5. Pendapatan (hanya Bekerja & Wirausaha, opsional) --}}
                    <div id="pendapatanGroup" class="hidden">
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Kisaran Pendapatan Bulanan
                            <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <select name="pendapatan_bulanan"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- Pilih kisaran --</option>
                            <option value="1000000">Di bawah Rp 1.000.000</option>
                            <option value="1500000">Rp 1.000.000 – Rp 1.500.000</option>
                            <option value="2000000">Rp 1.500.000 – Rp 2.000.000</option>
                            <option value="3000000">Rp 2.000.000 – Rp 3.000.000</option>
                            <option value="4000000">Rp 3.000.000 – Rp 4.000.000</option>
                            <option value="5000000">Rp 4.000.000 – Rp 5.000.000</option>
                            <option value="6000000">Di atas Rp 5.000.000</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-lock mr-1"></i>Data ini bersifat rahasia, hanya untuk keperluan statistik BKK.
                        </p>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Data Tracer Study
                        </button>
                        <button type="button" onclick="closeTracerForm()"
                            class="px-6 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    {{-- MODAL 2: Survey DUDI --}}
@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ── Modal functions ──
        function openTracerForm() {
            document.getElementById('tracerFormModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeTracerForm() {
            document.getElementById('tracerFormModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // ── Kondisional field ──
        function handleStatusChange(status) {
            const instansiGroup = document.getElementById('instansiGroup');
            const instansiLabel = document.getElementById('instansiLabel');
            const tglGroup = document.getElementById('tglGroup');
            const keselarasanGroup = document.getElementById('keselarasanGroup');
            const pendapatanGroup = document.getElementById('pendapatanGroup');

            // Sembunyikan semua dulu
            [instansiGroup, tglGroup, keselarasanGroup, pendapatanGroup]
            .forEach(el => el.classList.add('hidden'));

            if (status === 'Bekerja') {
                instansiLabel.textContent = 'Nama Perusahaan';
                instansiGroup.classList.remove('hidden');
                tglGroup.classList.remove('hidden');
                keselarasanGroup.classList.remove('hidden');
                pendapatanGroup.classList.remove('hidden');

            } else if (status === 'Kuliah') {
                instansiLabel.textContent = 'Nama Perguruan Tinggi';
                instansiGroup.classList.remove('hidden');
                tglGroup.classList.remove('hidden');

            } else if (status === 'Wirausaha') {
                instansiLabel.textContent = 'Nama Usaha / Bisnis';
                instansiGroup.classList.remove('hidden');
                tglGroup.classList.remove('hidden');
                keselarasanGroup.classList.remove('hidden');
                pendapatanGroup.classList.remove('hidden');
            }
            // 'Belum Bekerja' → tidak ada field tambahan
        }

        // ── Chart ──
        window.addEventListener('load', function() {
            const canvas = document.getElementById('tracerChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            if (!ctx || typeof Chart === 'undefined') return;

            @php
                $defaultChartData = [
                    'Bekerja' => 0,
                    'Kuliah' => 0,
                    'Wirausaha' => 0,
                    'Mencari Kerja' => 0,
                ];
            @endphp

            const chartData = @json($chartData ?? $defaultChartData);
            const total = Object.values(chartData).reduce((a, b) => a + b, 0);

            // Kalau semua 0, pakai nilai dummy supaya donut tetap render
            const displayData = total === 0 ? {
                    'Bekerja': 1,
                    'Kuliah': 1,
                    'Wirausaha': 1,
                    'Mencari Kerja': 1
                } :
                chartData;

            const labelsWithCounts = Object.entries(chartData).map(([label, value]) => `${label} (${value})`);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labelsWithCounts,
                    datasets: [{
                        data: Object.values(displayData),
                        backgroundColor: ['#2563eb', '#9333ea', '#f59e0b', '#94a3b8'],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '55%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 16,
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (total === 0) return ' Belum ada data';
                                    const label = Object.keys(chartData)[context.dataIndex];
                                    const value = Object.values(chartData)[context.dataIndex];
                                    const pct = Math.round((value / total) * 100);
                                    return ` ${label}: ${value} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
