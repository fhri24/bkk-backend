@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Laporan Eksekutif</h3>
            <p class="text-slate-500">Analisis keterserapan lulusan SMKN 1 Garut.</p>
        </div>
        <button class="flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
            <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Laporan PDF
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-blue-50">
            <h4 class="font-bold text-slate-800 mb-6 flex items-center">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-2 text-blue-500"></i> Statistik Status Alumni
            </h4>
            <div class="h-64">
                <canvas id="tracerChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-3xl text-white shadow-xl shadow-blue-100">
                <p class="opacity-80 text-sm">Total Mitra Industri</p>
                <h4 class="text-4xl font-bold mt-1">48</h4>
                <div class="mt-4 flex items-center text-xs bg-white/20 w-fit px-2 py-1 rounded-full">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +5 bulan ini
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-3xl border border-blue-50 shadow-sm">
                <h5 class="font-bold text-slate-800 flex items-center mb-4">
                    <i data-lucide="bell" class="w-4 h-4 mr-2 text-orange-400"></i> Perlu Validasi MOU
                </h5>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between items-center text-slate-600">
                        <span>PT. Astra International</span>
                        <span class="text-red-500 font-medium">Expiring</span>
                    </li>
                    <li class="flex justify-between items-center text-slate-600">
                        <span>Telkom Indonesia</span>
                        <span class="text-blue-500 font-medium">Pending</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('tracerChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: [450, 120, 80, 50],
                backgroundColor: ['#3B82F6', '#60A5FA', '#93C5FD', '#DBEAFE'],
                borderRadius: 12,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
        }
    });
</script>
@endsection