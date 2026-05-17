@extends('layouts.admin')

@section('title', 'Tips & Tricks')
@section('page_title', 'Tips & Tricks')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-slate-500 text-sm">Kelola konten panduan karir untuk alumni</p>
        <a href="{{ route('admin.tips.create') }}" class="btn-action"
            style="background:#2563eb;color:white;border-color:#2563eb;padding:0 20px;height:40px;">
            <i class="fas fa-plus"></i> Tambah Tips Baru
        </a>
    </div>

    <div class="table-custom p-5 mb-6">
        <form method="GET" action="{{ route('admin.tips.index') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tips..."
                    class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
                <select name="kategori"
                    class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>
                            {{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-action"
                style="background:#2563eb;color:white;border-color:#2563eb;padding:0 16px;">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.tips.index') }}" class="btn-action" style="padding:0 16px;">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>

    <div class="table-custom overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Daftar Tips & Tricks</h3>
            <p class="text-slate-400 text-xs mt-0.5">Total {{ $tips->total() }} tips</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th style="text-align:center;">Featured</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Urutan</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tips as $tip)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $tip->icon }} text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $tip->judul }}</p>
                                        <p class="text-xs text-slate-400">{{ Str::limit($tip->ringkasan, 60) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill badge-info text-xs">{{ $tip->kategori }}</span>
                            </td>
                            <td style="text-align:center;">
                                <form method="POST" action="{{ route('admin.tips.featured', $tip) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xl {{ $tip->is_featured ? 'text-amber-400' : 'text-slate-200' }} hover:text-amber-400 transition">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                            </td>
                            <td style="text-align:center;">
                                <form method="POST" action="{{ route('admin.tips.publish', $tip) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="badge-pill {{ $tip->is_published ? 'badge-success' : 'badge-warning' }} cursor-pointer border-0 text-xs">
                                        {{ $tip->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td style="text-align:center;" class="text-slate-500 text-sm">
                                {{ $tip->urutan }}
                            </td>
                            <td style="text-align:center;">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.tips.edit', $tip) }}" class="btn-action text-blue-600 px-3"
                                        style="height:32px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.tips.destroy', $tip) }}"
                                        onsubmit="return confirm('Hapus tips ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action text-red-500 px-3" style="height:32px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;">
                                <i class="fas fa-lightbulb"
                                    style="font-size:36px;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                                <p class="font-semibold">Belum ada tips</p>
                                <a href="{{ route('admin.tips.create') }}"
                                    class="inline-block mt-4 bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                                    + Tambah Tips Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tips->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $tips->links() }}
            </div>
        @endif
    </div>

@endsection
