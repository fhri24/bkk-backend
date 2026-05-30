@extends('layouts.admin')

@section('title', 'Tracer Study - BKK SMKN 1 Garut')
@section('page_title', 'Tracer Study')

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

        /* Highlight baris baru yang belum dibaca */
        tr.is-new {
            background: #eff6ff !important;
            border-left: 3px solid #2563eb;
        }

        tr.is-new:hover {
            background: #dbeafe !important;
        }
    </style>
@endsection

@section('content')

    {{-- STATISTIK CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <div class="stat-box-tracer">
        <div class="stat-icon bg-slate-100"><i class="fas fa-users text-slate-500"></i></div>
        <div>
            <p class="text-sm text-slate-500 font-semibold">Total Responden</p>
            <p class="text-3xl font-black text-slate-800">{{ $total }}</p>
        </div>
    </div>

    <div class="stat-box-tracer" style="border-left:4px solid #16a34a;">
        <div class="stat-icon bg-green-50"><i class="fas fa-briefcase text-green-600"></i></div>
        <div>
            <p class="text-sm text-slate-500 font-semibold">Bekerja</p>
            <p class="text-3xl font-black text-green-600">{{ $working }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($working / $total) * 100) : 0 }}% dari total</p>
        </div>
    </div>

    <div class="stat-box-tracer" style="border-left:4px solid #2563eb;">
        <div class="stat-icon bg-blue-50"><i class="fas fa-graduation-cap text-blue-600"></i></div>
        <div>
            <p class="text-sm text-slate-500 font-semibold">Melanjutkan Studi</p>
            <p class="text-3xl font-black text-blue-600">{{ $studying }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($studying / $total) * 100) : 0 }}% dari total</p>
        </div>
    </div>

    <div class="stat-box-tracer" style="border-left:4px solid #d97706;">
        <div class="stat-icon bg-amber-50"><i class="fas fa-store text-amber-600"></i></div>
        <div>
            <p class="text-sm text-slate-500 font-semibold">Wirausaha</p>
            <p class="text-3xl font-black text-amber-600">{{ $entrepren }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($entrepren / $total) * 100) : 0 }}% dari total</p>
        </div>
    </div>

    {{-- ✅ Card baru: Belum Bekerja --}}
    <div class="stat-box-tracer" style="border-left:4px solid #94a3b8;">
        <div class="stat-icon bg-slate-100"><i class="fas fa-clock text-slate-500"></i></div>
        <div>
            <p class="text-sm text-slate-500 font-semibold">Belum Bekerja</p>
            <p class="text-3xl font-black text-slate-600">{{ $unemployed }}</p>
            <p class="text-xs text-slate-400">{{ $total > 0 ? round(($unemployed / $total) * 100) : 0 }}% dari total</p>
        </div>
    </div>
</div>

    {{-- CHART + FILTER --}}
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="table-custom p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-chart-pie text-blue-500"></i> Distribusi Status
            </h3>
            <div style="height:220px;display:flex;align-items:center;justify-content:center;">
                <canvas id="adminTracerChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 table-custom p-6">
            <h3 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-filter text-blue-500"></i> Filter & Pencarian
            </h3>
            <form method="GET" action="{{ route('admin.tracer.index') }}" class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cari Nama</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama alumni..."
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Bekerja" {{ request('status') === 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                        <option value="Kuliah" {{ request('status') === 'Kuliah' ? 'selected' : '' }}>Kuliah</option>
                        <option value="Wirausaha" {{ request('status') === 'Wirausaha' ? 'selected' : '' }}>Wirausaha
                        </option>
                        <option value="Belum Bekerja" {{ request('status') === 'Belum Bekerja' ? 'selected' : '' }}>Belum
                            Bekerja</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Angkatan</label>
                    <select name="year"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Angkatan</option>
                        @foreach ($graduationYears as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-3 flex flex-wrap gap-3 pt-1">
                    <button type="submit" class="btn-action"
                        style="background:#2563eb;color:white;border-color:#2563eb;padding:0 20px;">
                        <i class="fas fa-search"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('admin.tracer.index') }}" class="btn-action" style="padding:0 20px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <div class="flex-1"></div>
                    <a href="{{ route('admin.tracer.export.csv', request()->query()) }}" class="btn-action"
                        style="background:#16a34a;color:white;border-color:#16a34a;padding:0 20px;">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="{{ route('admin.tracer.print', request()->query()) }}" target="_blank" class="btn-action"
                        style="background:#4f46e5;color:white;border-color:#4f46e5;padding:0 20px;">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="table-custom overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Data Tracer Study Alumni</h3>
                <p class="text-slate-400 text-xs mt-0.5">
                    Menampilkan {{ $tracerStudies->firstItem() ?? 0 }}–{{ $tracerStudies->lastItem() ?? 0 }} dari
                    {{ $tracerStudies->total() }} data
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Legend baru --}}
                <div
                    class="flex items-center gap-1.5 text-xs text-blue-600 font-semibold bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                    <div class="w-3 h-3 rounded-sm bg-blue-200 border-l-2 border-blue-600"></div>
                    = Data baru
                </div>
                @if (request()->hasAny(['status', 'year', 'search']))
                    <span class="badge-pill badge-info"><i class="fas fa-filter mr-1"></i>Filter aktif</span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th style="width:48px;">No</th>
                        <th>Nama Alumni</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th>Instansi / PT / Usaha</th>
                        <th>Posisi / Jurusan</th>
                        <th>Tgl Mulai</th>
                        <th>Penghasilan / Omzet</th>
                        <th>Tanggal Isi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tracerStudies as $i => $row)
                        {{-- Highlight baris yang belum dibaca --}}
                        <tr class="{{ !$row->is_read ? 'is-new' : '' }}">
                            <td class="text-slate-400 font-medium">
                                {{ $tracerStudies->firstItem() + $i }}
                                @if (!$row->is_read)
                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500 ml-1" title="Baru"></span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($row->nama_lengkap ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $row->nama_lengkap ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-400">{{ $row->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-slate-600 font-semibold">{{ $row->tahun_lulus ?? '-' }}
                            </td>
                            <td>
                                @switch($row->status_saat_ini)
                                    @case('Bekerja')
                                        <span class="badge-status badge-bekerja"><i class="fas fa-circle"
                                                style="font-size:6px"></i> Bekerja</span>
                                    @break

                                    @case('Kuliah')
                                        <span class="badge-status badge-kuliah"><i class="fas fa-circle"
                                                style="font-size:6px"></i> Kuliah</span>
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
                            <td class="text-sm font-semibold text-slate-700">
                                {{ $row->nama_instansi ?? ($row->nama_pt ?? ($row->nama_usaha ?? '-')) }}
                            </td>
                            <td class="text-sm text-slate-500">
                                {{ $row->posisi_jabatan ?? ($row->jurusan_pt ?? ($row->detail_kegiatan ?? '-')) }}
                            </td>
                            <td class="text-sm text-slate-500">
                                @php
                                    $tglMulai = $row->tmt_bekerja ?? ($row->tmt_kuliah ?? ($row->tmt_wirausaha ?? null));
                                @endphp
                                {{ $tglMulai ? \Carbon\Carbon::parse($tglMulai)->format('d M Y') : '-' }}
                            </td>
                            <td class="text-sm text-slate-500">
                                {{ $row->range_gaji ?? ($row->omzet_per_bulan ?? '-') }}
                            </td>
                            <td class="text-xs text-slate-400 font-medium">{{ $row->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;padding:48px 16px;color:#94a3b8;">
                                    <i class="fas fa-folder-open"
                                        style="font-size:36px;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                    <p class="font-semibold">Belum ada data tracer study</p>
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
                const ctx = document.getElementById('adminTracerChart');
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
