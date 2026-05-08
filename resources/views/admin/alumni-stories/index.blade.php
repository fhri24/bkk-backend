@extends('layouts.admin')

@section('title', 'Kisah Sukses Alumni')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">

    {{-- ── Page Header ── --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#001f3f]">Kisah Sukses Alumni</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola kiriman kisah sukses dari para alumni SMKN 1 Garut</p>
    </div>

    {{-- ── Flash Message ── --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4 mb-6">
        <i class="fas fa-check-circle text-green-500"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ── Stats / Filter Tabs ── --}}
    <div class="flex flex-wrap gap-3 mb-6">
        @php
            $tabs = [
                'all'      => ['label' => 'Semua',     'count' => $counts['all'],      'color' => 'bg-slate-800 text-white', 'inactive' => 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'],
                'pending'  => ['label' => 'Menunggu',  'count' => $counts['pending'],  'color' => 'bg-yellow-500 text-white', 'inactive' => 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'],
                'approved' => ['label' => 'Disetujui', 'count' => $counts['approved'], 'color' => 'bg-green-600 text-white', 'inactive' => 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'],
                'rejected' => ['label' => 'Ditolak',   'count' => $counts['rejected'], 'color' => 'bg-red-500 text-white', 'inactive' => 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'],
            ];
        @endphp
        @foreach($tabs as $key => $tab)
        <a href="{{ route('admin.alumni-stories.index', ['status' => $key]) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition
                  {{ $status === $key ? $tab['color'] : $tab['inactive'] }}">
            {{ $tab['label'] }}
            <span class="text-xs px-2 py-0.5 rounded-full
                {{ $status === $key ? 'bg-white/20' : 'bg-slate-100' }}">
                {{ $tab['count'] }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- ── Table ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">#</th>
                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">Alumni</th>
                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">Pekerjaan</th>
                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">Cerita</th>
                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">Status</th>
                        <th class="text-left px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">Dikirim</th>
                        <th class="text-right px-6 py-4 font-bold text-xs uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($stories as $story)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-4 text-slate-400 font-medium">
                            {{ $stories->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($story->photo)
                                    <img src="{{ asset('storage/' . $story->photo) }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0 border border-slate-100">
                                @else
                                    @php
                                        $colors = ['from-blue-500 to-blue-700','from-indigo-500 to-indigo-700','from-violet-500 to-violet-700','from-sky-500 to-sky-700'];
                                        $c = $colors[$loop->index % count($colors)];
                                    @endphp
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $c }} flex items-center justify-center text-white font-extrabold text-xs flex-shrink-0">
                                        {{ $story->initials }}
                                    </div>
                                @endif
                                <span class="font-semibold text-slate-800">{{ $story->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $story->job_title }}</td>
                        <td class="px-6 py-4 text-slate-500 max-w-xs">
                            <p class="line-clamp-2 leading-relaxed">{{ $story->story }}</p>
                        </td>
                        <td class="px-6 py-4">
                            {!! $story->status_badge !!}
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            {{ $story->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.alumni-stories.show', $story->id) }}" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-blue-600 transition" title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                @if($story->status !== 'approved')
                                <form action="{{ route('admin.alumni-stories.approve', $story->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-green-50 hover:text-green-600 transition" title="Setujui">
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                </form>
                                @endif

                                @if($story->status !== 'rejected')
                                <form action="{{ route('admin.alumni-stories.reject', $story->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-yellow-50 hover:text-yellow-600 transition" title="Tolak">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.alumni-stories.destroy', $story->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition" title="Hapus">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                            <p>Belum ada kisah sukses.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stories->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $stories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 