<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Alumni - BKK {{ $profile->school_name ?? 'SMKN 1 Garut' }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; margin: 0; background: white; }
            table { border: 1px solid #000; width: 100%; }
        }
        body { font-family: 'Helvetica', 'Arial', sans-serif; padding: 20px; line-height: 1.4; color: #111827; }
        
        /* Kop Surat */
        .kop-container { display: flex; align-items: center; padding-bottom: 15px; border-bottom: 3px double #000; margin-bottom: 20px; }
        .logo-box { width: 80px; height: 80px; margin-right: 20px; display: flex; align-items: center; justify-content: center; }
        .school-info { flex-grow: 1; text-align: left; }
        .school-info h1 { margin: 0; text-transform: uppercase; font-size: 16px; line-height: 1.2; font-weight: normal; }
        .school-info h2 { margin: 2px 0; font-size: 20px; color: #1e40af; text-transform: uppercase; font-weight: bold; }
        .school-info p { margin: 0; font-size: 11px; color: #374151; }

        /* Judul Laporan */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { text-decoration: underline; margin-bottom: 5px; text-transform: uppercase; font-size: 15px; }

        /* Tabel Data */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 10px; }
        th { background-color: #f1f5f9 !important; font-weight: bold; text-transform: uppercase; }
        
        /* Tombol Navigasi */
        .no-print { margin-bottom: 20px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; gap: 10px; }
        .btn { background: #2563eb; color: white; padding: 8px 18px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: bold; text-decoration: none; }
    </style>
</head>
<body>

    <div class="no-print">
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
                        // Mencari di berbagai kemungkinan lokasi folder Laravel
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
                <div style="width: 70px; height: 70px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 10px; color: #999;">
                    TIDAK ADA<br>LOGO
                </div>
            @endif
        </div>
        <div class="school-info">
            <h1>Bursa Kerja Khusus (BKK)</h1>
            <h2>{{ $profile->school_name ?? 'SMK NEGERI 1 GARUT' }}</h2>
            <p>{{ $profile->school_address ?? 'Alamat sekolah belum diatur di menu profil' }}</p>
        </div>
    </header>

    <div class="report-title">
        <h3>LAPORAN DATA ALUMNI</h3>
        <p style="margin: 0; font-size: 10px;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">No</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 20%;">Nama Lengkap</th>
                <th style="width: 15%;">Jurusan</th>
                <th style="width: 8%; text-align: center;">Lulus</th>
                <th style="width: 12%;">Status Karir</th>
                <th style="width: 15%;">No. HP</th>
                <th style="width: 17%;">Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumni as $index => $student)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $student->nis }}</td>
                <td style="font-weight: bold;">{{ $student->full_name }}</td>
                <td>{{ $student->major }}</td>
                <td style="text-align: center;">{{ $student->graduation_year }}</td>
                <td>{{ ucfirst($student->career_path ?? 'Belum Terdata') }}</td>
                <td>{{ $student->phone ?? '-' }}</td>
                <td>{{ $student->user->email ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center; padding:20px;">Data alumni tidak tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; width: 250px; text-align: center; font-size: 12px;">
        <p>Garut, {{ date('d F Y') }}</p>
        <p style="margin-bottom: 60px;">Petugas BKK,</p>
        <p><strong>( ____________________ )</strong></p>
    </div>

</body>
</html> 