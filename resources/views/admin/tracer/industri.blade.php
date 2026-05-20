@extends('layouts.admin')

@section('title', 'Laporan Industri - Tracer Study - BKK SMKN 1 Garut')
@section('page_title', 'Laporan Industri')

@section('extra_css')
    <style>
        .stat-box-tracer {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-box-tracer:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .badge-sesuai {
            background: #dcfce7;
            color: #166534;
        }

        .badge-tidak-sesuai {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
@endsection

@section('content')

    {{-- STATISTIK CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <div class="stat-box-tracer">
            <div class="stat-icon bg-slate-100"><i class="fas fa-building text-slate-500"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Total Alumni Bekerja</p>
                <p class="text-3xl font-black text-slate-800">{{ $withCompany }}</p>
            </div>
        </div>

        <div class="stat-box-tracer" style="border-left:4px solid #16a34a;">
            <div class="stat-icon bg-green-50"><i class="fas fa-check-circle text-green-600"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Kesesuaian Jurusan</p>
                <p class="text-3xl font-black text-green-600">{{ $matching }}</p>
                <p class="text-xs text-slate-400">{{ $withCompany > 0 ? round(($matching / $withCompany) * 100) : 0 }}%</p>
            </div>
        </div>

        <div class="stat-box-tracer" style="border-left:4px solid #dc2626;">
            <div class="stat-icon bg-red-50"><i class="fas fa-times-circle text-red-600"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Tidak Sesuai</p>
                <p class="text-3xl font-black text-red-600">{{ $notMatching }}</p>
                <p class="text-xs text-slate-400">{{ $withCompany > 0 ? round(($notMatching / $withCompany) * 100) : 0 }}%</p>
            </div>
        </div>

        <div class="stat-box-tracer" style="border-left:4px solid #2563eb;">
            <div class="stat-icon bg-blue-50"><i class="fas fa-chart-line text-blue-600"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Total Data Alumni</p>
                <p class="text-3xl font-black text-blue-600">{{ $total }}</p>
            </div>
        </div>

    </div>

    {{-- CHART + FILTER --}}
    <div class="grid lg:grid-cols-3 gap-6 mb-6">

        <div class="table-custom p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-chart-pie text-green-500"></i> Kesesuaian Jurusan
            </h3>
            <div style="height:220px;display:flex;align-items:center;justify-content:center;">
                <canvas id="industriChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 table-custom p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-filter text-green-500"></i> Filter
            </h3>
            <form method="GET" action="{{ route('admin.tracer.industri') }}" class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Angkatan</label>
                    <select name="year"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Angkatan</option>
                        @foreach ($graduationYears as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="btn-action flex-1"
                        style="background:#16a34a;color:white;border-color:#16a34a;padding:0 20px;">
                        <i class="fas fa-search"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.tracer.industri') }}" class="btn-action" style="padding:0 20px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

    </div>

    {{-- DISTRIBUSI GAJI --}}
    @if($salaryDistribution->count() > 0)
        <div class="table-custom p-6 mb-6">
            <h3 class="font-bold text-slate-700 mb-6 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-money-bill text-amber-500"></i> Distribusi Pendapatan Alumni
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($salaryDistribution as $salary)
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-lg border border-amber-200">
                        <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-2">{{ $salary->salary_range }}</p>
                        <p class="text-2xl font-black text-amber-600">{{ $salary->count }}</p>
                        <p class="text-xs text-amber-500 mt-1">
                            {{ $total > 0 ? round(($salary->count / $total) * 100) : 0 }}% dari total
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- TABEL DATA INDUSTRI --}}
    <div class="table-custom overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Laporan Alumni di Industri</h3>
                <p class="text-slate-400 text-xs mt-0.5">
                    Menampilkan {{ $tracerStudies->firstItem() ?? 0 }}–{{ $tracerStudies->lastItem() ?? 0 }}
                    dari {{ $tracerStudies->total() }} data
                </p>
            </div>
            @if (request()->hasAny(['year']))
                <span class="badge-pill badge-info"><i class="fas fa-filter mr-1"></i>Filter aktif</span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th style="width:48px;">No</th>
                        <th>Nama Alumni</th>
                        <th>Angkatan</th>
                        <th>Nama Instansi</th>
                        <th>Bidang Pekerjaan</th>
                        <th>Kesesuaian</th>
                        <th>Pendapatan</th>
                        <th>Tgl Mulai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tracerStudies as $i => $row)
                        <tr>
                            <td class="text-slate-400 font-medium">{{ $tracerStudies->firstItem() + $i }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($row->student->full_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $row->student->full_name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-slate-400">{{ $row->student->nis ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-slate-600 font-semibold">{{ $row->student->graduation_year ?? '-' }}
                            </td>
                            <td class="text-sm font-semibold text-slate-700">{{ $row->nama_instansi ?? '-' }}</td>
                            <td class="text-sm text-slate-600">{{ $row->deskripsi_pekerjaan ?? '-' }}</td>
                            <td>
                                @if ($row->keselarasan_jurusan === 'Sesuai')
                                    <span class="badge-pill badge-sesuai"><i class="fas fa-check mr-1"></i>Sesuai</span>
                                @elseif($row->keselarasan_jurusan === 'Tidak Sesuai')
                                    <span class="badge-pill badge-tidak-sesuai"><i class="fas fa-times mr-1"></i>Tidak
                                        Sesuai</span>
                                @else
                                    <span class="text-slate-300 text-sm">—</span>
                                @endif
                            </td>
                            <td class="text-sm text-slate-500">
                                {{ $row->pendapatan_bulanan ? 'Rp ' . number_format($row->pendapatan_bulanan, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-sm text-slate-500">
                                {{ $row->tgl_mulai_masuk ? \Carbon\Carbon::parse($row->tgl_mulai_masuk)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:48px 16px;color:#94a3b8;">
                                    <i class="fas fa-folder-open"
                                        style="font-size:36px;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                    <p class="font-semibold">Belum ada data tracer industri</p>
                                    <p class="text-xs mt-1">Coba ubah filter atau tunggu data industri terpenuhi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tracerStudies->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $tracerStudies->links() }}
                </div>
            @endif
        </div>

    @endsection

    @section('extra_js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            window.addEventListener('load', function() {
                const ctx = document.getElementById('industriChart');
                if (!ctx) return;
                const chartData = @json($chartData);
                new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(chartData),
                        datasets: [{
                            data: Object.values(chartData),
                            backgroundColor: ['#16a34a', '#dc2626'],
                            borderWidth: 0,
                            hoverOffset: 10,
                        }]
                    },
                    options: {
                        cutout: '65%',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 14,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endsection
