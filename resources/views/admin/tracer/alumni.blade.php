@extends('layouts.admin')

@section('title', 'Laporan Alumni - Tracer Study - BKK SMKN 1 Garut')
@section('page_title', 'Laporan Alumni')

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

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-bekerja {
            background: #dcfce7;
            color: #166534;
        }

        .badge-kuliah {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-wirausaha {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-belum {
            background: #f1f5f9;
            color: #475569;
        }
    </style>
@endsection

@section('content')

    {{-- STATISTIK CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <div class="stat-box-tracer">
            <div class="stat-icon bg-slate-100"><i class="fas fa-users text-slate-500"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Total Alumni</p>
                <p class="text-3xl font-black text-slate-800">{{ $total }}</p>
            </div>
        </div>

        <div class="stat-box-tracer" style="border-left:4px solid #16a34a;">
            <div class="stat-icon bg-green-50"><i class="fas fa-briefcase text-green-600"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Bekerja</p>
                <p class="text-3xl font-black text-green-600">{{ $working }}</p>
                <p class="text-xs text-slate-400">{{ $total > 0 ? round(($working / $total) * 100) : 0 }}%</p>
            </div>
        </div>

        <div class="stat-box-tracer" style="border-left:4px solid #2563eb;">
            <div class="stat-icon bg-blue-50"><i class="fas fa-graduation-cap text-blue-600"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Melanjutkan Studi</p>
                <p class="text-3xl font-black text-blue-600">{{ $studying }}</p>
                <p class="text-xs text-slate-400">{{ $total > 0 ? round(($studying / $total) * 100) : 0 }}%</p>
            </div>
        </div>

        <div class="stat-box-tracer" style="border-left:4px solid #d97706;">
            <div class="stat-icon bg-amber-50"><i class="fas fa-store text-amber-600"></i></div>
            <div>
                <p class="text-sm text-slate-500 font-semibold">Wirausaha</p>
                <p class="text-3xl font-black text-amber-600">{{ $entrepren }}</p>
                <p class="text-xs text-slate-400">{{ $total > 0 ? round(($entrepren / $total) * 100) : 0 }}%</p>
            </div>
        </div>

    </div>

    {{-- CHART + FILTER --}}
    <div class="grid lg:grid-cols-3 gap-6 mb-6">

        <div class="table-custom p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-chart-pie text-blue-500"></i> Distribusi Status Alumni
            </h3>
            <div style="height:220px;display:flex;align-items:center;justify-content:center;">
                <canvas id="alumniChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white">
            <h3 class="font-bold text-white mb-6 text-lg uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-search text-blue-100"></i> Filter & Pencarian Alumni
            </h3>
            <form method="GET" action="{{ route('admin.tracer.alumni') }}" class="space-y-6">
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-2.5">Cari Nama Alumni</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Masukkan nama alumni..."
                            class="w-full px-4 py-3 bg-white/10 border-2 border-white/30 rounded-xl text-white placeholder-white/50 text-sm focus:outline-none focus:border-white focus:ring-2 focus:ring-white/20 backdrop-blur-sm transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-2.5">Status Alumni</label>
                        <select name="status"
                            class="w-full px-4 py-3 bg-white/10 border-2 border-white/30 rounded-xl text-white text-sm focus:outline-none focus:border-white focus:ring-2 focus:ring-white/20 backdrop-blur-sm transition [color-scheme:dark]">
                            <option value="" class="text-slate-900">Semua Status</option>
                            <option value="Bekerja" {{ request('status') === 'Bekerja' ? 'selected' : '' }} class="text-slate-900">Bekerja</option>
                            <option value="Kuliah" {{ request('status') === 'Kuliah' ? 'selected' : '' }} class="text-slate-900">Kuliah</option>
                            <option value="Wirausaha" {{ request('status') === 'Wirausaha' ? 'selected' : '' }} class="text-slate-900">Wirausaha</option>
                            <option value="Belum Bekerja" {{ request('status') === 'Belum Bekerja' ? 'selected' : '' }} class="text-slate-900">Belum Bekerja</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-2.5">Angkatan / Tahun Lulus</label>
                        <select name="year"
                            class="w-full px-4 py-3 bg-white/10 border-2 border-white/30 rounded-xl text-white text-sm focus:outline-none focus:border-white focus:ring-2 focus:ring-white/20 backdrop-blur-sm transition [color-scheme:dark]">
                            <option value="" class="text-slate-900">Semua Angkatan</option>
                            @foreach ($graduationYears as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }} class="text-slate-900">
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-white text-blue-600 rounded-xl font-bold shadow-lg hover:shadow-xl transition transform hover:scale-105 active:scale-95">
                        <i class="fas fa-search mr-2"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.tracer.alumni') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white/20 border-2 border-white/50 text-white rounded-xl font-bold hover:bg-white/30 transition">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

    </div>

    {{-- TABEL DATA ALUMNI --}}
    <div class="table-custom overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Laporan Alumni Berdasarkan Status</h3>
                <p class="text-slate-400 text-xs mt-0.5">
                    Menampilkan {{ $tracerStudies->firstItem() ?? 0 }}–{{ $tracerStudies->lastItem() ?? 0 }}
                    dari {{ $tracerStudies->total() }} data
                </p>
            </div>
            @if (request()->hasAny(['status', 'year', 'search']))
                <span class="badge-pill badge-info"><i class="fas fa-filter mr-1"></i>Filter aktif</span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th style="width:48px;">No</th>
                        <th>Nama Alumni</th>
                        <th>NIS</th>
                        <th>Angkatan</th>
                        <th>Status Saat Ini</th>
                        <th>Instansi/Perusahaan</th>
                        <th>Pendapatan</th>
                        <th>Tanggal Isi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tracerStudies as $i => $row)
                        <tr>
                            <td class="text-slate-400 font-medium">{{ $tracerStudies->firstItem() + $i }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($row->student->full_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $row->student->full_name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-slate-500">{{ $row->student->nis ?? '-' }}</td>
                            <td class="text-sm text-slate-600 font-semibold">{{ $row->student->graduation_year ?? '-' }}
                            </td>
                            <td>
                                @switch($row->status_saat_ini)
                                    @case('Bekerja')
                                        <span class="badge-status badge-bekerja"><i class="fas fa-circle" style="font-size:6px"></i>
                                            Bekerja</span>
                                    @break

                                    @case('Kuliah')
                                        <span class="badge-status badge-kuliah"><i class="fas fa-circle" style="font-size:6px"></i>
                                            Kuliah</span>
                                    @break

                                    @case('Wirausaha')
                                        <span class="badge-status badge-wirausaha"><i class="fas fa-circle"
                                                style="font-size:6px"></i> Wirausaha</span>
                                    @break

                                    @case('Belum Bekerja')
                                        <span class="badge-status badge-belum"><i class="fas fa-circle"
                                                style="font-size:6px"></i> Belum Bekerja</span>
                                    @break

                                    @default
                                        <span class="badge-status badge-belum">-</span>
                                @endswitch
                            </td>
                            <td class="text-sm text-slate-700 font-semibold">{{ $row->nama_instansi ?? '-' }}</td>
                            <td class="text-sm text-slate-500">
                                {{ $row->pendapatan_bulanan ? 'Rp ' . number_format($row->pendapatan_bulanan, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-xs text-slate-400 font-medium">{{ $row->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:48px 16px;color:#94a3b8;">
                                    <i class="fas fa-folder-open"
                                        style="font-size:36px;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                    <p class="font-semibold">Belum ada data tracer alumni</p>
                                    <p class="text-xs mt-1">Coba ubah filter atau tunggu alumni mengisi data</p>
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
                const ctx = document.getElementById('alumniChart');
                if (!ctx) return;
                const chartData = @json($chartData);
                new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(chartData),
                        datasets: [{
                            data: Object.values(chartData),
                            backgroundColor: ['#16a34a', '#2563eb', '#d97706', '#94a3b8'],
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
