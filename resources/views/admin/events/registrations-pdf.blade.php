<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta Acara</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .subtitle { font-size: 12px; color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Daftar Peserta Acara</div>
        @if($selectedEvent)
            <div class="subtitle">Acara: {{ $selectedEvent->title }}</div>
        @endif
        <div class="subtitle">Tanggal: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Institusi</th>
                <th>Posisi</th>
                <th>Status</th>
                <th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $reg)
                <tr>
                    <td>{{ $reg->name }}</td>
                    <td>{{ $reg->email }}</td>
                    <td>{{ $reg->phone }}</td>
                    <td>{{ $reg->institution ?? '-' }}</td>
                    <td>{{ $reg->position ?? '-' }}</td>
                    <td>{{ ucfirst($reg->status) }}</td>
                    <td>{{ $reg->registered_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data registrasi untuk acara ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
