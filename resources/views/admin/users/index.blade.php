@extends('layouts.admin')

@section('title', 'Daftar Pengguna - Admin BKK')
@section('page_title', 'Daftar Pengguna')

@section('content')
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="p-6 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users-cog text-indigo-600 mr-3"></i> Daftar Pengguna
                </h3>
                <p class="text-sm text-slate-500 mt-1">Kelola status akun untuk Admin, Petugas BKK, Perusahaan, dan Siswa.</p>
            </div>
        </div>

        {{-- Bulk Action Toolbar (muncul saat ada yang dicentang) --}}
        <div id="bulk-toolbar"
            class="hidden px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between gap-3 flex-wrap">
            <span class="text-sm font-medium text-indigo-700">
                <span id="selected-count">0</span> pengguna dipilih
            </span>
            <div class="flex items-center gap-2">
                <button onclick="submitBulkAction('activate')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-sm font-medium hover:bg-emerald-200 transition">
                    <i class="fas fa-check-circle text-xs"></i> Aktifkan
                </button>
                <button onclick="submitBulkAction('deactivate')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 text-sm font-medium hover:bg-amber-200 transition">
                    <i class="fas fa-ban text-xs"></i> Non-aktifkan
                </button>
                <button onclick="confirmDelete()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-100 text-red-700 text-sm font-medium hover:bg-red-200 transition">
                    <i class="fas fa-trash text-xs"></i> Hapus
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Form Bulk Action (hidden, di-submit lewat JS) --}}
        <form id="bulk-form" action="{{ route('admin.users.bulk-action') }}" method="POST">
            @csrf
            <input type="hidden" name="action" id="bulk-action-input">
            <div id="bulk-ids-container"></div>
        </form>

        {{-- Tabel --}}
        <div class="table-custom">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="w-10 px-4">
                            {{-- Checkbox select all --}}
                            <input type="checkbox" id="select-all"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th>Email</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="user-row" data-id="{{ $user->id }}">
                            <td class="px-4">
                                <input type="checkbox"
                                    class="user-checkbox w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                    value="{{ $user->id }}"
                                    {{ $user->id === Auth::id() ? 'disabled title=Tidak bisa memilih akun sendiri' : '' }}>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->role->display_name ?? ($user->role->name ?? '-') }}</td>
                            <td>
                                <span class="badge-pill {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.update-status', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $user->is_active ? '0' : '1' }}">
                                    <button type="submit" class="btn-action"
                                        title="{{ $user->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                        <i class="fas fa-toggle-{{ $user->is_active ? 'on text-emerald-500' : 'off text-slate-400' }}"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-500 py-8">
                                <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                Belum ada pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                <i class="fas fa-trash text-red-500 text-lg"></i>
            </div>
            <h3 class="text-center text-lg font-bold text-slate-800 mb-2">Hapus Pengguna?</h3>
            <p class="text-center text-sm text-slate-500 mb-6">
                <span id="delete-count" class="font-semibold text-slate-700"></span> pengguna yang dipilih akan dipindahkan ke tempat sampah. Aksi ini masih bisa dibatalkan oleh Super Admin.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                    Batal
                </button>
                <button onclick="doDelete()"
                    class="flex-1 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
<script>
    const selectAll      = document.getElementById('select-all');
    const checkboxes     = document.querySelectorAll('.user-checkbox:not([disabled])');
    const bulkToolbar    = document.getElementById('bulk-toolbar');
    const selectedCount  = document.getElementById('selected-count');
    const bulkForm       = document.getElementById('bulk-form');
    const bulkActionInput = document.getElementById('bulk-action-input');
    const bulkIdsContainer = document.getElementById('bulk-ids-container');

    // Select All
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateToolbar();
    });

    // Per-checkbox change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            selectAll.checked = [...checkboxes].every(c => c.checked);
            selectAll.indeterminate = [...checkboxes].some(c => c.checked) && !selectAll.checked;
            updateToolbar();
        });
    });

    function getCheckedIds() {
        return [...checkboxes].filter(cb => cb.checked).map(cb => cb.value);
    }

    function updateToolbar() {
        const ids = getCheckedIds();
        selectedCount.textContent = ids.length;
        bulkToolbar.classList.toggle('hidden', ids.length === 0);
    }

    function submitBulkAction(action) {
        const ids = getCheckedIds();
        if (!ids.length) return;

        // Set action
        bulkActionInput.value = action;

        // Set IDs sebagai hidden inputs
        bulkIdsContainer.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'user_ids[]';
            input.value = id;
            bulkIdsContainer.appendChild(input);
        });

        bulkForm.submit();
    }

    // Delete dengan konfirmasi modal
    function confirmDelete() {
        const ids = getCheckedIds();
        if (!ids.length) return;
        document.getElementById('delete-count').textContent = ids.length;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    function doDelete() {
        closeDeleteModal();
        submitBulkAction('delete');
    }

    // Tutup modal kalau klik backdrop
    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
@endsection
