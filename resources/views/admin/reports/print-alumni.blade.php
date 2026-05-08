<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Alumni</title>
    <style>
        @page { size: A4 landscape; margin: 10mm 15mm; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; background: white; }
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            padding: 20px;
            background: #fff;
        }

        /* Tombol */
        .no-print {
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .btn-print {
            background: #1d6f42;
            color: white;
            padding: 8px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }
        .btn-close {
            background: #64748b;
            color: white;
            padding: 8px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }
        .filter-form {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-form select, .filter-form button {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
        }
        .filter-form button {
            background: #2563eb;
            color: white;
            border: none;
            font-weight: bold;
        }

        /* Header / Kop seperti Excel */
        .kop { margin-bottom: 12px; }
        .kop .judul-utama {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 2px 0;
        }
        .kop .nama-sekolah {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 2px 0;
        }
        .kop .alamat {
            font-size: 11px;
            margin: 0 0 2px 0;
        }
        .kop .tanggal {
            font-size: 11px;
            margin: 0 0 10px 0;
        }

        /* Info filter aktif */
        .filter-info {
            font-size: 11px;
            margin-bottom: 6px;
            color: #374151;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        thead tr th {
            background-color: #d9d9d9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border: 1px solid #000;
            padding: 6px 8px;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }
        tbody tr td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 10px;
            vertical-align: middle;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        td.center { text-align: center; }

        .total-row {
            font-size: 11px;
            margin-top: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>

{{-- ===== TOMBOL & FILTER (tidak ikut cetak) ===== --}}
<div class="no-print">
    <button onclick="window.print()" class="btn-print">🖨️ Cetak Sekarang</button>
    <button onclick="window.close()" class="btn-close">Tutup</button>

    <form method="GET" action="{{ route('admin.reports.export.alumni.print') }}" class="filter-form">
        <label style="font-size:12px; font-weight:bold;">Filter:</label>

        <select name="year">
            <option value="">Semua Angkatan</option>
            @foreach($availableYears as $yr)
                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                    Angkatan {{ $yr }}
                </option>
            @endforeach
        </select>

        <select name="major">
            <option value="">Semua Jurusan</option>
            @foreach($availableMajors as $mj)
                <option value="{{ $mj }}" {{ request('major') == $mj ? 'selected' : '' }}>
                    {{ $mj }}
                </option>
            @endforeach
        </select>

        <button type="submit">Terapkan Filter</button>

        @if(request('year') || request('major'))
            <a href="{{ route('admin.reports.export.alumni.print') }}"
               style="font-size:12px; color:#dc2626; text-decoration:none;">✕ Reset</a>
        @endif
    </form>
</div>

{{-- ===== KOP SEPERTI FORMAT EXCEL ===== --}}
<div class="kop">
    <p class="judul-utama">Daftar Peserta Didik</p>
    <p class="nama-sekolah">{{ strtoupper($profile->school_name ?? 'SMKN 1 GARUT') }}</p>
    <p class="alamat">{{ $profile->school_address ?? 'Kecamatan Kec. Tarogong Kidul, Kabupaten Kab. Garut, Provinsi Prov. Jawa Barat' }}</p>
    <p class="tanggal">Tanggal Unduh: {{ now()->format('Y-m-d H:i:s') }}</p>
</div>

{{-- Info filter aktif --}}
@if(request('year') || request('major'))
<p class="filter-info no-print">
    Menampilkan:
    @if(request('year')) Angkatan <strong>{{ request('year') }}</strong> @endif
    @if(request('year') && request('major')) — @endif
    @if(request('major')) Jurusan <strong>{{ request('major') }}</strong> @endif
    ({{ $alumni->count() }} siswa)
</p>
@endif

{{-- ===== TABEL DATA ===== --}}
<table>
    <thead>
        <tr>
            <th style="width: 4%;">No</th>
            <th style="width: 25%;">Nama</th>
            <th style="width: 13%;">NIPD</th>
            <th style="width: 5%;">JK</th>
            <th style="width: 13%;">NISN</th>
            <th style="width: 20%;">Rombel Saat Ini</th>
            <th style="width: 10%;">Tahun Lulus</th>
        </tr>
    </thead>
    <tbody>
        @forelse($alumni as $index => $student)
        <tr>
            <td class="center">{{ $index + 1 }}</td>
            <td>{{ $student->full_name }}</td>
            <td class="center">{{ $student->nis ?? '-' }}</td>
            <td class="center">{{ $student->gender ?? '-' }}</td>
            <td class="center">{{ $student->nisn ?? $student->nis ?? '-' }}</td>
            {{-- Rombel = Jurusan (karena saat import kita extract jurusan dari rombel) --}}
            <td class="center">{{ $student->major ?? '-' }}</td>
            <td class="center">{{ $student->graduation_year }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center; padding:20px;">
                Tidak ada data alumni
                @if(request('year') || request('major'))
                    untuk filter yang dipilih
                @endif
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<p class="total-row">Total: {{ $alumni->count() }} siswa</p>

</body>
</html>