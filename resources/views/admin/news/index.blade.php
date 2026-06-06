@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- LIST VIEW --}}
        <div id="view-list">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Berita</h1>
                <a href="{{ route('admin.news.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-md">
                    <i class="fas fa-plus mr-2"></i>Tambah Berita
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th
                                class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Thumbnail</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Judul & Slug</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($news as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-5 text-sm bg-white">
                                    <div class="w-16 h-12 rounded overflow-hidden bg-gray-200">
                                        @if ($item->image)
                                            <img src="{{ Storage::disk('public')->url($item->image) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-5 text-sm bg-white">
                                    <p class="text-gray-900 font-bold mb-1">{{ $item->title }}</p>
                                    <p class="text-gray-500 text-xs italic">{{ $item->slug }}</p>
                                </td>
                                <td class="px-5 py-5 text-sm bg-white text-gray-600">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-5 text-sm bg-white">
                                    <div class="flex items-center gap-3">
                                        <button onclick="showDetail({{ $item->id }})"
                                            class="text-green-600 hover:text-green-900 flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        <a href="{{ route('admin.news.edit', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900 flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 flex items-center">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DETAIL VIEW --}}
        <div id="view-detail" class="hidden">
            <button onclick="showList()"
                class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-semibold text-sm mb-6">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
            </button>

            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8 max-w-4xl mx-auto">
                <div class="mb-4 flex items-center gap-3">
                    <span id="detail-category"
                        class="bg-blue-100 text-blue-600 font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-full"></span>
                    <span id="detail-date" class="text-slate-400 text-sm"></span>
                </div>
                <h1 id="detail-title" class="text-3xl font-bold text-slate-900 mb-3"></h1>
                <p id="detail-author" class="text-slate-500 text-sm mb-6"></p>
                <img id="detail-image" src="" class="w-full rounded-xl mb-6 object-cover max-h-72 hidden">
                <div id="detail-body" class="text-slate-700 leading-relaxed"></div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <button onclick="showList()"
                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function showDetail(id) {
            fetch(`/admin/news/${id}/preview-json`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('detail-category').textContent = data.category;
                    document.getElementById('detail-date').textContent = data.created_at;
                    document.getElementById('detail-title').textContent = data.title;
                    document.getElementById('detail-author').innerHTML =
                        `<i class="fas fa-user mr-1"></i>${data.author}`;
                    document.getElementById('detail-body').innerHTML = data.body;

                    const img = document.getElementById('detail-image');
                    if (data.image) {
                        img.src = data.image;
                        img.classList.remove('hidden');
                    } else {
                        img.classList.add('hidden');
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
