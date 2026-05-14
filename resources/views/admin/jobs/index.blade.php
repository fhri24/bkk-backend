@extends('layouts.admin')

@section('title', 'Manajemen Lowongan Kerja - Admin Dashboard')
@section('page_title', 'Manajemen Lowongan Kerja')

@section('content')
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6">
        <div>
            <p class="text-slate-600">Kelola semua lowongan kerja yang telah dipublikasikan</p>
            @php $pendingCount = $jobs->where('approval_status', 'pending')->count(); @endphp
            @if ($pendingCount > 0)
                <div
                    class="mt-2 inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 text-yellow-700 px-3 py-1.5 rounded-lg text-xs font-bold">
                    <i class="fas fa-clock"></i> {{ $pendingCount }} lowongan menunggu persetujuan
                </div>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.jobs.index', ['approval' => 'pending']) }}"
                class="border border-yellow-300 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 px-4 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
                <i class="fas fa-clock"></i> Menunggu Approval
            </a>
            <a href="{{ route('admin.jobs.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition">
                <i class="fas fa-plus"></i> Tambah Lowongan
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-6 grid gap-4 lg:grid-cols-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700">Cari</label>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                placeholder="Judul, perusahaan, atau jenis">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Visibility</label>
            <select name="visibility"
                class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua Visibility</option>
                <option value="public" {{ ($visibility ?? '') === 'public' ? 'selected' : '' }}>Public</option>
                <option value="alumni_only" {{ ($visibility ?? '') === 'alumni_only' ? 'selected' : '' }}>Alumni Only
                </option>
                <option value="private" {{ ($visibility ?? '') === 'private' ? 'selected' : '' }}>Private</option>
                <option value="internal" {{ ($visibility ?? '') === 'internal' ? 'selected' : '' }}>Internal</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Status</label>
            <select name="status"
                class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua Status</option>
                <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Approval</label>
            <select name="approval"
                class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <option value="">Semua</option>
                <option value="pending" {{ ($approval ?? '') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ ($approval ?? '') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ ($approval ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="w-full rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Terapkan Filter
            </button>
        </div>
    </form>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if ($jobs->count() > 0)
        <div class="table-custom">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">Judul</th>
                        <th class="text-center">Perusahaan</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Approval BKK</th>
                        <th class="text-center">Kadaluarsa</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        @php $approval = $job->approval_status ?? 'approved'; @endphp
                        <tr class="{{ $approval === 'pending' ? 'bg-yellow-50' : '' }}">
                            <td class="font-semibold">
                                {{ $job->title }}
                                @if ($approval === 'pending')
                                    <span
                                        class="ml-2 text-[10px] bg-yellow-100 text-yellow-700 font-bold px-2 py-0.5 rounded-full">BARU</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $job->company->company_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $job->job_type ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge-pill {{ $job->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($approval === 'pending')
                                    <span
                                        class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-clock"></i> Menunggu
                                    </span>
                                @elseif($approval === 'approved')
                                    <span
                                        class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-check"></i> Disetujui
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">
                                        <i class="fas fa-times"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">{{ $job->expired_at ? $job->expired_at->format('d M Y') : '-' }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2 flex-wrap">

                                    {{-- Tombol Approve (hanya muncul kalau pending atau rejected) --}}
                                    @if ($approval !== 'approved')
                                        <form action="{{ route('admin.jobs.approve', $job->job_id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit" class="btn-action text-green-600 hover:bg-green-50"
                                                title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Reject (hanya muncul kalau pending atau approved) --}}
                                    @if ($approval !== 'rejected')
                                        <button onclick="openRejectModal({{ $job->job_id }})"
                                            class="btn-action text-orange-600 hover:bg-orange-50" title="Tolak">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.jobs.edit', $job->job_id) }}"
                                        class="btn-action text-blue-600 hover:bg-blue-50" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jobs.destroy', $job->job_id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action text-red-600 hover:bg-red-50"
                                            onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-lg border border-slate-200 p-12 text-center">
            <i class="fas fa-inbox text-4xl text-slate-300 mb-3 block"></i>
            <p class="text-slate-600 mb-4">Belum ada lowongan kerja</p>
            <a href="{{ route('admin.jobs.create') }}"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                <i class="fas fa-plus mr-2"></i>Buat Lowongan Baru
            </a>
        </div>
    @endif

    {{-- Modal Reject --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
            <h3 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                <i class="fas fa-ban text-red-500"></i> Tolak Lowongan
            </h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alasan Penolakan</label>
                    <textarea name="approval_notes" rows="3" required placeholder="Tulis alasan penolakan untuk perusahaan..."
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition resize-none"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl text-sm font-bold transition">
                        <i class="fas fa-ban mr-2"></i> Tolak Lowongan
                    </button>
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 border border-slate-200 text-slate-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function openRejectModal(jobId) {
            document.getElementById('rejectForm').action = '/admin/jobs/' + jobId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
        window.onclick = function(e) {
            const modal = document.getElementById('rejectModal');
            if (e.target === modal) closeRejectModal();
        }
    </script>
@endsection
