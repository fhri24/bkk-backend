@extends('layouts.admin')

@section('title', 'Tahun Lulus - Admin BKK')
@section('page_title', 'Tahun Lulus')

@section('content')
<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-3">Tambah Tahun Lulus</h3>
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @elseif(isset($tableMissing))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4 text-sm text-yellow-700">
                Tabel <strong>{{ $tableMissing }}</strong> belum ada. Jalankan <code>php artisan migrate</code> untuk melanjutkan.
            </div>
        @endif
        <form action="{{ route('admin.settings.years.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Tahun</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2" min="1900" max="2100" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Label (opsional)</label>
                    <input type="text" name="label" value="{{ old('label') }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2">
                </div>
                <button type="submit" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan Tahun</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800">Daftar Tahun Lulus</h3>
            <p class="text-sm text-slate-500 mt-2">Kelola periode angkatan alumni untuk laporan dan filter data.</p>
        </div>
        <div class="table-custom">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Label</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($years as $year)
                        <tr>
                            <td>{{ $year->year }}</td>
                            <td>{{ $year->label ?: '-' }}</td>
                            <td class="space-x-2">
                                <a href="{{ route('admin.settings.years.edit', $year) }}" class="btn-action text-slate-700 hover:bg-slate-100" title="Edit Tahun">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.settings.years.destroy', $year) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action text-red-600 hover:bg-red-50" onclick="return confirm('Yakin ingin menghapus tahun lulus ini?')" title="Hapus Tahun">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-slate-500 py-8">
                                <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                Belum ada tahun lulus.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
