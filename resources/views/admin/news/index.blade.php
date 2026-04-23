@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Berita</h1>
        <a href="{{ route('admin.news.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-md">
            <i class="fas fa-plus mr-2"></i>Tambah Berita
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thumbnail</th>
                    <th class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul & Slug</th>
                    <th class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($news as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-5 text-sm bg-white">
                        <div class="w-16 h-12 rounded overflow-hidden bg-gray-200">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
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
                            {{-- Edit --}}
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
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
@endsection
