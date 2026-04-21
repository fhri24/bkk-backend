<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Alumni - BKK SMKN 1 Garut</title>
    <style>
        /* Pengaturan Halaman Cetak */
        @page {
            size: auto;
            margin: 15mm;
        }

        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; margin: 0; background: white; }
        }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            padding: 20px; 
            line-height: 1.5; 
            color: #111827; 
        }

        .print-container {
            background: white;
            padding: 0;
            margin: 0 auto;
        }

        /* Header / Kop Surat */
        .kop-container {
            display: flex; 
            align-items: center; 
            gap: 20px;
            padding-bottom: 10px;
            border-bottom: 3px double #000;
        }

        .logo-box {
            width: 85px;
            height: 85px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-info {
            flex-grow: 1;
        }

        .school-info h1 { margin: 0; text-transform: uppercase; font-size: 18px; line-height: 1.2; }
        .school-info h2 { margin: 2px 0; font-size: 22px; color: #1e40af; text-transform: uppercase; font-weight: bold; }
        .school-info p { margin: 0; font-size: 12px; color: #374151; }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 20px;
        }

        .report-title h3 { 
            text-decoration: underline; 
            margin-bottom: 5px; 
            text-transform: uppercase; 
            font-size: 16px;
        }

        /* Styling Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #94a3b8; padding: 10px; text-align: left; font-size: 11px; }
        th { background-color: #f8fafc !important; font-weight: bold; }
        
        /* Tombol Navigasi */
        .no-print {
            margin-bottom: 30px;
            background: #f1f5f9;
            padding: 15px 25px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
        }

        .btn { 
            background: #2563eb; 
            color: white; 
            padding: 10px 22px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 14px; 
            font-weight: bold;
        }

        .btn-secondary { background: #64748b; margin-left: 10px; }
        .hint { margin-left: auto; color: #64748b; font-size: 13px; }
    </style>
</head>
<body>

    <!-- Tombol Navigasi -->
    <div class="no-print">
        <button onclick="window.print()" class="btn">Cetak Sekarang</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup Halaman</button>
        <div class="hint">
            *Tips: Aktifkan <b>Background Graphics</b> di setelan print agar warna tabel muncul.
        </div>
    </div>

    <div class="print-container">
        <!-- Kop Surat -->
        <header class="kop-container">
            <div class="logo-box">
                {{-- Diperbaiki ke logo_path sesuai database --}}
                @if($profile && $profile->logo_path)
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" 
                         alt="Logo" 
                         style="width: 80px; height: 80px; object-fit: contain;"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Logo&background=eee&color=999';">
                @else
                    <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999; border: 1px solid #ddd;">
                        LOGO KOSONG
                    </div>
                @endif
            </div>

            <div class="school-info">
                <h1>Bursa Kerja Khusus (BKK)</h1>
                <h2>{{ $profile->school_name ?? 'SMK NEGERI 1 GARUT' }}</h2>
                {{-- Diperbaiki ke school_address sesuai database --}}
                <p>{{ $profile->school_address ?? 'Alamat belum diatur di Profil Sekolah' }}</p>
            </div>
        </header>

        <!-- Judul Laporan -->
        <div class="report-title">
            <h3>{{ Request::is('*jobs*') ? 'Laporan Data Lowongan Kerja' : 'Laporan Data Alumni' }}</h3>
            <p style="font-size: 11px; color: #64748b;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">NIS</th>
                    <th style="width: 25%;">Nama Lengkap</th>
                    <th style="width: 15%;">Jurusan</th>
                    <th style="width: 10%;">Lulus</th>
                    <th style="width: 15%;">Status / Karir</th>
                    <th style="width: 25%;">Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumni as $student)
                <tr>
                    <td>{{ $student->nis }}</td>
                    <td style="font-weight: bold; color: #1e40af;">{{ $student->full_name }}</td>
                    <td>{{ $student->major }}</td>
                    <td>{{ $student->graduation_year }}</td>
                    <td>{{ ucfirst($student->career_path ?? $student->status_bmw ?? 'Belum Terdata') }}</td>
                    <td>{{ $student->user->email ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:#94a3af; font-style: italic;">
                        Data alumni tidak ditemukan atau masih kosong.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
