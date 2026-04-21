<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Lowongan - BKK SMKN 1 Garut</title>
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
            line-height: 1.6; 
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
        th, td { border: 1px solid #94a3b8; padding: 12px; text-align: left; font-size: 12px; }
        th { background-color: #f8fafc !important; font-weight: bold; text-transform: uppercase; }
        
        /* Tombol Navigasi */
        .no-print {
            margin-bottom: 30px;
            background: #f1f5f9;
            padding: 15px 25px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
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

    <div class="no-print">
        <div>
            <button onclick="window.print()" class="btn">Cetak Sekarang</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup Halaman</button>
        </div>
        <div class="hint">
            *Pilih format <b>PDF</b> pada dialog print untuk menyimpan.
        </div>
    </div>

    <div class="print-container">
        <header class="kop-container">
            <div class="logo-box">
                @if($profile && $profile->logo)
                    {{-- Path storage untuk mengakses symlink public/storage/school-logos --}}
                    <img src="{{ asset('storage/' . $profile->logo) }}" 
                         alt="Logo Sekolah" 
                         style="width: 80px; height: 80px; object-fit: contain;"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Logo&background=eee&color=999';">
                @else
                    <div style="width: 80px; height: 80px; background: #eee; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999; text-align: center;">
                        LOGO<br>KOSONG
                    </div>
                @endif
            </div>

            <div class="school-info">
                <h1>Bursa Kerja Khusus (BKK)</h1>
                <h2>{{ $profile->school_name ?? 'SMK NEGERI 1 GARUT' }}</h2>
                <p>{{ $profile->address ?? 'Alamat belum diatur di Profil Sekolah' }}</p>
            </div>
        </header>

        <div class="report-title">
            <h3>LAPORAN DATA LOWONGAN KERJA</h3>
            <p style="margin: 0; font-size: 11px; color: #64748b;">Periode: {{ date('F Y') }} | Per tanggal: {{ date('d/m/Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Judul Lowongan</th>
                    <th style="width: 25%;">Perusahaan</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 15%;">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td style="font-weight: bold; color: #1e40af;">{{ $job->title }}</td>
                    <td>{{ $job->company->company_name ?? '-' }}</td>
                    <td>
                        {{-- Warna status hanya muncul jika Background Graphics dicentang saat print --}}
                        <span style="color: {{ $job->status == 'active' ? '#16a34a' : '#ca8a04' }}; font-weight: bold;">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td>{{ $job->category ?? '-' }}</td>
                    <td>{{ $job->created_at?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:40px; color:#94a3af; font-style: italic;">
                        Tidak ada data lowongan yang tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
