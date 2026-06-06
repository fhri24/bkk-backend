@extends('layouts.admin')

@section('title', 'Tips & Tricks')
@section('page_title', 'Tips & Tricks')

@section('content')

    <div id="view-list">
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
                                        <button onclick="showDetail({{ $tip->id }})"
                                            class="btn-action text-green-600 px-3" style="height:32px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.tips.edit', $tip) }}"
                                            class="btn-action text-blue-600 px-3" style="height:32px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.tips.destroy', $tip) }}"
                                            onsubmit="return confirm('Hapus tips ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action text-red-500 px-3"
                                                style="height:32px;">
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
    </div> {{-- end view-list --}}
    {{-- DETAIL VIEW --}}
    <div id="view-detail" class="hidden">
        <button onclick="showList()"
            class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-semibold text-sm mb-6">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tips
        </button>

        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-8 max-w-4xl mx-auto">
            <div class="mb-4 flex items-center gap-3">
                <span id="detail-kategori"
                    class="bg-blue-100 text-blue-600 font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-full"></span>
            </div>
            <h1 id="detail-judul" class="text-2xl font-bold text-slate-900 mb-2"></h1>
            <p id="detail-ringkasan" class="text-slate-500 text-sm mb-6"></p>
            <img id="detail-image" src="" class="w-full rounded-xl mb-6 object-cover max-h-64 hidden">

            <div id="detail-steps" class="mb-6 hidden">
                <h3 class="font-bold text-slate-800 mb-3">Langkah-langkah</h3>
                <div id="detail-steps-list" class="space-y-3"></div>
            </div>

            <div id="detail-konten" class="text-slate-700 leading-relaxed mb-6"></div>

            <div id="detail-pro-tips" class="mb-4 hidden">
                <h3 class="font-bold text-green-700 mb-2">💡 Pro Tips</h3>
                <ul id="detail-pro-tips-list" class="list-disc list-inside text-slate-600 text-sm space-y-1"></ul>
            </div>

            <div id="detail-avoid" class="mb-6 hidden">
                <h3 class="font-bold text-red-600 mb-2">⚠ Hindari</h3>
                <ul id="detail-avoid-list" class="list-disc list-inside text-slate-600 text-sm space-y-1"></ul>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <button onclick="showList()"
                    class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDetail(id) {
            fetch(`/admin/tips/${id}/preview-json`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('detail-judul').textContent = data.judul;
                    document.getElementById('detail-kategori').textContent = data.kategori;
                    document.getElementById('detail-ringkasan').textContent = data.ringkasan;
                    document.getElementById('detail-konten').innerHTML = data.konten ?? '';

                    const img = document.getElementById('detail-image');
                    if (data.image) {
                        img.src = data.image;
                        img.classList.remove('hidden');
                    } else {
                        img.classList.add('hidden');
                    }

                    const stepsList = document.getElementById('detail-steps-list');
                    if (data.steps && data.steps.length > 0) {
                        stepsList.innerHTML = data.steps.map((s, i) => `
                    <div class="flex gap-3 p-3 bg-slate-50 rounded-xl">
                        <div class="w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">${i+1}</div>
                        <div><p class="font-semibold text-slate-800 text-sm">${s.title}</p><p class="text-slate-500 text-xs mt-1">${s.description ?? ''}</p></div>
                    </div>
                `).join('');
                        document.getElementById('detail-steps').classList.remove('hidden');
                    } else {
                        document.getElementById('detail-steps').classList.add('hidden');
                    }

                    const proList = document.getElementById('detail-pro-tips-list');
                    if (data.pro_tips && data.pro_tips.length > 0) {
                        proList.innerHTML = data.pro_tips.map(t => `<li>${t}</li>`).join('');
                        document.getElementById('detail-pro-tips').classList.remove('hidden');
                    } else {
                        document.getElementById('detail-pro-tips').classList.add('hidden');
                    }

                    const avoidList = document.getElementById('detail-avoid-list');
                    if (data.avoid_mistakes && data.avoid_mistakes.length > 0) {
                        avoidList.innerHTML = data.avoid_mistakes.map(t => `<li>${t}</li>`).join('');
                        document.getElementById('detail-avoid').classList.remove('hidden');
                    } else {
                        document.getElementById('detail-avoid').classList.add('hidden');
                    }

                    document.getElementById('view-list').classList.add('hidden');
                    document.getElementById('view-detail').classList.remove('hidden');
                });
        }

        function showList() {
            document.getElementById('view-detail').classList.add('hidden');
            document.getElementById('view-list').classList.remove('hidden');
        }
    </script>

@endsection
