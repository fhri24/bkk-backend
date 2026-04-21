<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Alumni - BKK SMKN 1 Garut</title>
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
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 10.5px; }
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
            *Gunakan setting <b>Portrait</b> dan aktifkan <b>Background Graphics</b>.
        </div>
    </div>

    <div class="print-container">

        <header class="kop-container">
            <div class="logo-box">
                @php
                    // 1. Ambil path dari database
                    $dbPath = $profile->logo_path ?? '';
                    
                    // 2. Tentukan path fisik di server
                    $fullPath = public_path('storage/' . $dbPath);
                    
                    // 3. Cek keberadaan file fisik
                    $fileExists = (!empty($dbPath) && file_exists($fullPath));
                    
                    $logoBase64 = '';
                    if ($fileExists) {
                        try {
                            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                            $data = file_get_contents($fullPath);
                            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        } catch (\Exception $e) {
                            // Gagal encode
                        }
                    }
                @endphp

                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    {{-- DEBUG BOX: Akan muncul warna MERAH jika file gagal dimuat --}}
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

                <p>{{ $profile->school_address ?? 'Alamat belum diatur di Profil Sekolah' }}</p>
            </div>
        </header>


        <div class="report-title">
            <h3>LAPORAN DATA ALUMNI</h3>
            <p style="margin: 0; font-size: 10px; color: #64748b;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
        </div>

        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 12%;">NIS</th>
                    <th style="width: 25%;">Nama Lengkap</th>
                    <th style="width: 18%;">Jurusan</th>
                    <th style="width: 10%; text-align: center;">Lulus</th>
                    <th style="width: 15%;">Status / Karir</th>
                    <th style="width: 15%;">Kontak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumni as $index => $student)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $student->nis }}</td>
                    <td style="font-weight: bold; color: #1e40af;">{{ $student->full_name }}</td>
                    <td>{{ $student->major }}</td>
                    <td style="text-align: center;">{{ $student->graduation_year }}</td>
                    <td>
                        @php
                            $status = ucfirst($student->career_path ?? $student->status_bmw ?? 'Belum Terdata');
                        @endphp
                        <span style="color: {{ $status == 'Bekerja' ? '#16a34a' : ($status == 'Melanjutkan' ? '#2563eb' : '#ca8a04') }};">
                            {{ $status }}
                        </span>
                    </td>
                    <td>{{ $student->user->email ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:30px; color:#94a3af; font-style: italic;">
                        Data alumni tidak ditemukan.
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
