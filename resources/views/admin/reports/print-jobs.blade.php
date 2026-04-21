<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Lowongan - BKK SMKN 1 Garut</title>
    <style>
        /* Pengaturan Halaman Cetak */
        @page {
            size: A4;
            margin: 15mm;
        }

        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; margin: 0; background: white; }
            table { border: 1px solid #000; }
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            padding: 20px; 
            line-height: 1.4; 
            color: #111827; 
        }

        .print-container {
            background: white;
            
            margin: 0 auto;
        }

        /* Header / Kop Surat */
        .kop-container {
            display: flex; 
            align-items: center; 
            padding-bottom: 15px;
            border-bottom: 3px double #000;
        }

        .logo-box {
            width: 85px;
            height: 85px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-info {
            flex-grow: 1;
            text-align: left;
        }

        .school-info h1 { margin: 0; text-transform: uppercase; font-size: 16px; line-height: 1.2; font-weight: normal; }
        .school-info h2 { margin: 2px 0; font-size: 20px; color: #1e40af; text-transform: uppercase; font-weight: bold; }
        .school-info p { margin: 0; font-size: 11px; color: #374151; }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .report-title h3 { 
            text-decoration: underline; 
            margin-bottom: 5px; 
            text-transform: uppercase; 
            font-size: 15px;
        }

        /* Styling Tabel */
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; font-size: 11px; }
        th { background-color: #f1f5f9 !important; font-weight: bold; text-transform: uppercase; }
        
        /* Tombol Navigasi */
        .no-print {
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn { 
            background: #2563eb; 
            color: white; 
            padding: 8px 18px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 13px; 
            font-weight: bold;
        }

        .btn-secondary { background: #64748b; }
        .hint { margin-left: auto; color: #64748b; font-size: 12px; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn">Cetak Sekarang</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
        <div class="hint">
            *Pilih format <b>PDF</b> pada dialog print untuk menyimpan.
        </div>
    </div>

    <div class="print-container">
        <header class="kop-container">
            <div class="logo-box">
                @php
                    // 1. Ambil path dari database (cek variabel logo_path atau logo)
                    $dbPath = $profile->logo_path ?? $profile->logo ?? '';
                    
                    // 2. Tentukan path fisik di server
                    $fullPath = public_path('storage/' . $dbPath);
                    
                    // 3. Cek keberadaan file
                    $fileExists = (!empty($dbPath) && file_exists($fullPath));
                    
                    $logoBase64 = '';
                    if ($fileExists) {
                        try {
                            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                            $data = file_get_contents($fullPath);
                            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        } catch (\Exception $e) {
                            // Gagal proses
                        }
                    }
                @endphp

                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    {{-- KOTAK DEBUG: Muncul jika gambar gagal diproses --}}
                    <div style="width: 80px; height: 80px; background: #fee2e2; border: 1px dashed #ef4444; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 8px; color: #b91c1c; text-align: center; padding: 5px; overflow: hidden;">
                        <strong>FAILED</strong>
                        <span style="word-break: break-all;">P: {{ $dbPath ?: 'NULL' }}</span>
                        <span>E: {{ $fileExists ? 'YES' : 'NO' }}</span>
                    </div>
                @endif
            </div>

            <div class="school-info">
                <h1>Bursa Kerja Khusus (BKK)</h1>
                <h2>{{ $profile->school_name ?? 'SMK NEGERI 1 GARUT' }}</h2>
                <p>{{ $profile->school_address ?? $profile->address ?? 'Alamat belum diatur' }}</p>
            </div>
        </header>

        <div class="report-title">
            <h3>LAPORAN DATA LOWONGAN KERJA</h3>
            <p style="margin: 0; font-size: 11px; color: #64748b;">Periode: {{ date('F Y') }} | Per tanggal: {{ date('d/m/Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 30%;">Judul Lowongan</th>
                    <th style="width: 25%;">Perusahaan</th>
                    <th style="width: 15%; text-align: center;">Status</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 10%; text-align: center;">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $index => $job)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold; color: #1e40af;">{{ $job->title }}</td>
                    <td>{{ $job->company->company_name ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span style="color: {{ $job->status == 'active' ? '#16a34a' : '#ca8a04' }}; font-weight: bold;">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td>{{ $job->category ?? '-' }}</td>
                    <td style="text-align: center;">{{ $job->created_at?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:#94a3af; font-style: italic;">
                        Tidak ada data lowongan yang tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 40px; float: right; width: 200px; text-align: center; font-size: 12px;">
            <p>Garut, {{ date('d F Y') }}</p>
            <p style="margin-bottom: 60px;">Ketua BKK,</p>
            <p><strong>( ____________________ )</strong></p>
        </div>
    </div>

</body>
</html>
