@extends('layouts.admin')

@section('title', 'Manajemen Kisah Sukses Alumni - Admin Dashboard')
@section('page_title', 'Manajemen Kisah Sukses Alumni')

@section('content')
<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
    <div>
        <p class="text-slate-600">Kelola semua kisah sukses alumni yang telah dibagikan</p>
    </div>
</div>

@if ($stories->count() > 0)
<div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Nama Alumni</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Pekerjaan</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Cerita</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Tanggal Dibagikan</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stories as $story)
            <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                <td class="px-6 py-4 text-sm font-medium text-slate-900">
                    {{ $story->name }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ $story->job }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs overflow-hidden text-ellipsis">
                    {{ Str::limit($story->story, 100) }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-500">
                    {{ $story->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4 text-center text-sm font-medium flex gap-2 justify-center">
                    <button onclick="showStoryModal({{ json_encode($story) }})" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <form action="{{ route('admin.alumni-stories.destroy', $story->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Hapus" onclick="return confirm('Yakin ingin menghapus cerita ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-8 text-center">
    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
    <p class="text-slate-600 text-lg font-medium">Belum ada cerita alumni yang dibagikan</p>
    <p class="text-slate-500 text-sm mt-2">Cerita akan muncul di sini ketika alumni mulai berbagi di homepage</p>
</div>
@endif

<!-- Modal untuk lihat detail cerita -->
<div id="storyModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-slate-900">Detail Cerita Alumni</h3>
            <button onclick="closeStoryModal()" class="text-slate-500 hover:text-slate-700 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Alumni</label>
                <p id="modalName" class="text-slate-900 font-medium"></p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pekerjaan & Instansi</label>
                <p id="modalJob" class="text-slate-900 font-medium"></p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Cerita</label>
                <p id="modalStory" class="text-slate-700 leading-relaxed whitespace-pre-wrap"></p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Dibagikan</label>
                <p id="modalDate" class="text-slate-600 text-sm"></p>
            </div>
        </div>
    </div>
</div>

<script>
function showStoryModal(story) {
    document.getElementById('modalName').textContent = story.name;
    document.getElementById('modalJob').textContent = story.job;
    document.getElementById('modalStory').textContent = story.story;
    
    const date = new Date(story.created_at);
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    document.getElementById('modalDate').textContent = date.toLocaleDateString('id-ID', options);
    
    document.getElementById('storyModal').classList.remove('hidden');
}

function closeStoryModal() {
    document.getElementById('storyModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('storyModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeStoryModal();
    }
});
</script>
@endsection
