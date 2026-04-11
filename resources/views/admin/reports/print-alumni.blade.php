<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Alumni</title>
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
        <h1>Data Alumni</h1>
        <p class="note">Cetak halaman ini menjadi PDF menggunakan fitur print browser.</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Tahun Lulus</th>
                <th>Karir</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumni as $student)
                <tr>
                    <td>{{ $student->nis }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->major }}</td>
                    <td>{{ $student->graduation_year }}</td>
                    <td>{{ ucfirst($student->career_path) }}</td>
                    <td>{{ $student->user->email ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:24px; color:#64748b;">Tidak ada data alumni.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
