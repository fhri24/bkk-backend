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

    {{-- ===== HERO SECTION ===== --}}
    <div class="relative bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=1920&q=80"
                class="w-full h-full object-cover opacity-30" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-slate-900/90"></div>
        </div>
        <div class="container mx-auto px-6 py-20 relative z-10 text-center text-white">
            <span
                class="inline-block bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-400/30 mb-5">
                📊 BKK SMKN 1 Garut
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Tracer Study & Laporan Karir</h1>
            <p class="text-lg opacity-90">Sistem pelacakan jejak alumni untuk memetakan kualitas pendidikan dan kebutuhan
                dunia industri.</p>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="page-transition container mx-auto px-6 py-16">

        {{-- Statistik & Chart --}}
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
            <div>
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

            {{-- Chart --}}
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
                <button onclick="window.location.href='{{ route('company.tracer.index') }}'"
                    class="block bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-green-600 hover:text-white transition text-center w-full">
                    Lihat Laporan Industri
                </button>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
