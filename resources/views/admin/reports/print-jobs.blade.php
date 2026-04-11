<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Lowongan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
        th { background: #f8fafc; }
        .header { margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 24px; }
        .note { color: #475569; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Lowongan</h1>
        <p class="note">Cetak halaman ini menjadi PDF menggunakan fitur print browser.</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Judul Lowongan</th>
                <th>Perusahaan</th>
                <th>Status</th>
                <th>Jenis</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company->company_name ?? '-' }}</td>
                    <td>{{ ucfirst($job->status) }}</td>
                    <td>{{ $job->category ?? '-' }}</td>
                    <td>{{ $job->created_at?->format('Y-m-d') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:24px; color:#64748b;">Tidak ada data lowongan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
