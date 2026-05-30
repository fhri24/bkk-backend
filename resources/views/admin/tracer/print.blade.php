<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tracer Study - BKK SMKN 1 Garut</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #fff;
        }

        .header {
            background: #1d4ed8;
            color: white;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 800;
        }

        .header p {
            font-size: 11px;
            opacity: .8;
            margin-top: 2px;
        }

        .header .meta {
            text-align: right;
            font-size: 11px;
            opacity: .8;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            padding: 20px 32px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
        }

        .summary-card .label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8;
        }

        .summary-card .value {
            font-size: 28px;
            font-weight: 900;
            margin-top: 4px;
        }

        .summary-card.blue .value {
            color: #1d4ed8;
        }

        .summary-card.green .value {
            color: #16a34a;
        }

        .summary-card.indigo .value {
            color: #4338ca;
        }

        .summary-card.amber .value {
            color: #d97706;
        }

        .content {
            padding: 20px 32px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #1d4ed8;
            color: white;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }

        tbody td {
            padding: 9px 12px;
            font-size: 11px;
            vertical-align: middle;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-bekerja {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-kuliah {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-wirausaha {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-belum {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-sesuai {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-tidak {
            background: #fee2e2;
            color: #dc2626;
        }

        .footer {
            margin-top: 32px;
            padding: 16px 32px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            color: #94a3b8;
            font-size: 10px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="padding:12px 32px;background:#fef3c7;display:flex;align-items:center;gap:12px;">
        <span style="font-size:12px;color:#92400e;font-weight:600;"><i>Pratinjau laporan.</i></span>
        <button onclick="window.print()"
            style="background:#1d4ed8;color:white;border:none;padding:8px 20px;border-radius:8px;font-weight:700;font-size:12px;cursor:pointer;">
            🖨️ Cetak Sekarang
        </button>
        <button onclick="window.close()"
            style="background:#e2e8f0;color:#475569;border:none;padding:8px 20px;border-radius:8px;font-weight:700;font-size:12px;cursor:pointer;">
            ✕ Tutup
        </button>
    </div>

    <div class="header">
        <div>
            <h1>Laporan Tracer Study Alumni</h1>
            <p>BKK SMKN 1 Garut — Bursa Kerja Khusus</p>
        </div>
        <div class="meta">
            <div>Dicetak: {{ now()->format('d F Y, H:i') }} WIB</div>
            <div>Total Responden: {{ $total }} alumni</div>
        </div>
    </div>

    <div class="summary">
        <div class="summary-card blue">
            <div class="label">Total Responden</div>
            <div class="value">{{ $total }}</div>
        </div>
        <div class="summary-card green">
            <div class="label">Bekerja</div>
            <div class="value">{{ $working }}</div>
        </div>
        <div class="summary-card indigo">
            <div class="label">Melanjutkan Studi</div>
            <div class="value">{{ $studying }}</div>
        </div>
        <div class="summary-card amber">
            <div class="label">Wirausaha</div>
            <div class="value">{{ $entrepren }}</div>
        </div>
    </div>

    <div class="content">
        <div class="section-title">Detail Data Tracer Study</div>
        <table>
            <thead>
                <tr>
                    <th style="width:40px">No</th>
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
                    <tr>
                        <td style="color:#94a3b8;font-weight:600;">{{ $i + 1 }}</td>
                        <td style="font-weight:700;">{{ $row->student->full_name ?? ($row->nama_lengkap ?? '-') }}</td>
                        <td>{{ $row->student->graduation_year ?? ($row->tahun_lulus ?? '-') }}</td>
                        <td>
                            @switch($row->status_saat_ini)
                                @case('Bekerja')
                                    <span class="badge badge-bekerja">Bekerja</span>
                                @break

                                @case('Kuliah')
                                    <span class="badge badge-kuliah">Kuliah</span>
                                @break

                                @case('Wirausaha')
                                    <span class="badge badge-wirausaha">Wirausaha</span>
                                @break

                                @case('Belum Bekerja')
                                    <span class="badge badge-belum">Belum Bekerja</span>
                                @break

                                @default
                                    <span class="badge badge-belum">-</span>
                            @endswitch
                        </td>
                        <td>{{ $row->nama_instansi ?? ($row->nama_pt ?? ($row->nama_usaha ?? '-')) }}</td>
                        <td>{{ $row->posisi_jabatan ?? ($row->jurusan_pt ?? ($row->detail_kegiatan ?? '-')) }}</td>
                        <td>
                            @php
                                $tglMulai = $row->tmt_bekerja ?? ($row->tmt_kuliah ?? ($row->tmt_wirausaha ?? null));
                            @endphp
                            {{ $tglMulai ? \Carbon\Carbon::parse($tglMulai)->format('d/m/Y') : '-' }}
                        </td>
                        <td>{{ $row->range_gaji ?? ($row->omzet_per_bulan ?? '-') }}</td>
                        <td style="color:#94a3b8;">{{ $row->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:24px;color:#94a3b8;">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <span>BKK SMKN 1 Garut — Laporan Tracer Study</span>
            <span>Dicetak {{ now()->format('d F Y') }}</span>
        </div>

    </body>

    </html>
