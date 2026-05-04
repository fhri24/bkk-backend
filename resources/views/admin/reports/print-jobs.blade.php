<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Lowongan - BKK {{ $profile->school_name ?? 'SMKN 1 Garut' }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; margin: 0; background: white; }
        }
        body { font-family: 'Helvetica', 'Arial', sans-serif; padding: 20px; color: #111827; }
        
        /* Kop Surat */
        .kop-container { display: flex; align-items: center; padding-bottom: 15px; border-bottom: 3px double #000; margin-bottom: 20px; }
        .logo-box { width: 80px; height: 80px; margin-right: 20px; display: flex; align-items: center; justify-content: center; }
        .school-info { flex-grow: 1; text-align: left; }
        .school-info h1 { margin: 0; text-transform: uppercase; font-size: 16px; line-height: 1.2; }
        .school-info h2 { margin: 2px 0; font-size: 20px; color: #1e40af; text-transform: uppercase; font-weight: bold; }
        .school-info p { margin: 0; font-size: 11px; }

        /* Judul Laporan */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { text-decoration: underline; text-transform: uppercase; font-size: 15px; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; font-size: 11px; }
        th { background-color: #f1f5f9 !important; font-weight: bold; }

        .btn { background: #2563eb; color: white; padding: 8px 18px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; gap: 10px;">
        <button onclick="window.print()" class="btn">Cetak Sekarang</button>
        <button onclick="window.close()" class="btn" style="background:#64748b;">Tutup</button>
    </div>

    <header class="kop-container">
        <div class="logo-box">
            @php
                $logoBase64 = '';
                try {
                    $dbPath = $profile->logo_path ?? '';
                    if (!empty($dbPath)) {
                        $locations = [
                            storage_path('app/public/' . $dbPath),
                            storage_path('app/' . $dbPath),
                            public_path('storage/' . $dbPath),
                        ];

                        foreach ($locations as $path) {
                            if (file_exists($path)) {
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {}
            @endphp

            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo" style="width: 75px; height: 75px; object-fit: contain;">
            @else
                <div style="width: 70px; height: 70px; border: 1px solid #ccc; font-size: 10px; text-align: center; display: flex; align-items: center; justify-content: center; color: #999;">LOGO</div>
            @endif
        </div>
        <div class="school-info">
            <h1>Bursa Kerja Khusus (BKK)</h1>
            <h2>{{ $profile->school_name ?? 'SMK NEGERI 1 GARUT' }}</h2>
            <p>{{ $profile->school_address ?? 'Alamat belum diatur' }}</p>
        </div>
    </header>

    <div class="report-title">
        <h3>LAPORAN DATA LOWONGAN KERJA</h3>
        <p style="margin: 0; font-size: 11px;">Update per tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Judul Lowongan</th>
                <th style="width: 20%;">Perusahaan</th>
                <th style="width: 15%;">Lokasi</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 10%; text-align: center;">Tgl Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $index => $job)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ $job->title }}</td>
                <td>{{ $job->company->company_name ?? '-' }}</td>
                <td>{{ $job->location ?? '-' }}</td>
                <td>{{ $job->category ?? '-' }}</td>
                <td style="text-align: center;">{{ ucfirst($job->status) }}</td>
                <td style="text-align: center;">{{ $job->created_at?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:30px;">Belum ada data lowongan pekerjaan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; width: 250px; text-align: center; font-size: 12px;">
        <p>Garut, {{ date('d F Y') }}</p>
        <p style="margin-bottom: 60px;">Ketua BKK,</p>
        <p><strong>( ____________________ )</strong></p>
    </div>

</body>
</html> 