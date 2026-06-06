<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Export Peserta Acara</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 15mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
                margin: 0;
                background: white;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            padding: 20px;
            background: #f8fafc;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-green {
            background: #059669;
            color: white;
        }

        .btn-dark {
            background: #1e293b;
            color: white;
        }

        .btn-blue {
            background: #2563eb;
            color: white;
        }

        .btn-gray {
            background: #e2e8f0;
            color: #334155;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .header-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        .header-box h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .header-box p {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            font-size: 11px;
        }

        thead {
            background: #f1f5f9;
        }

        th {
            padding: 10px 14px;
            text-align: left;
            font-weight: 700;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 9px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <div class="no-print toolbar">
        <a href="{{ route('admin.event-registrations.export.csv', request()->only(['event_slug', 'status'])) }}"
            class="btn btn-green">
            ⬇ Download Excel
        </a>
        <a href="{{ route('admin.event-registrations.export.pdf', request()->only(['event_slug', 'status'])) }}"
            class="btn btn-dark">
            ⬇ Download PDF
        </a>
        <button onclick="window.print()" class="btn btn-blue">
            🖨 Cetak
        </button>
        <button onclick="window.close()" class="btn btn-gray">
            ✕ Tutup
        </button>
    </div>

    <div class="header-box">
        <h1>Daftar Peserta Acara</h1>
        @if ($selectedEvent)
            <p>Acara: <strong>{{ $selectedEvent->title }}</strong></p>
        @else
            <p>Semua Acara</p>
        @endif
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Institusi</th>
                <th>Status</th>
                <th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $i => $reg)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $reg->name }}</td>
                    <td>{{ $reg->email }}</td>
                    <td>{{ $reg->phone }}</td>
                    <td>{{ $reg->institution ?? '-' }}</td>
                    <td>{{ ucfirst($reg->status) }}</td>
                    <td>{{ $reg->registered_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">Tidak ada data registrasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
