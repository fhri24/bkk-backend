@extends('layouts.admin')

@section('title', 'Role & Permissions - Admin BKK')
@section('page_title', 'Role & Permissions')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                <i class="fas fa-user-shield text-yellow-600 mr-3"></i> Role & Permissions
            </h3>
            <p class="text-sm text-slate-500 mt-2">Atur hak akses dan menu yang dapat diakses oleh setiap role.</p>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span class="text-sm text-green-700 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Toast Notification (untuk AJAX) --}}
        <div id="toast"
            class="hidden fixed bottom-6 right-6 z-[100] bg-green-600 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition-all">
            <i class="fas fa-check-circle"></i>
            <span id="toastMsg">Berhasil disimpan.</span>
        </div>

        {{-- Matrix Table --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th
                                class="text-left px-6 py-4 font-semibold text-slate-700 min-w-[220px] sticky left-0 bg-slate-50 z-10 border-r border-slate-200">
                                Permission
                            </th>
                            @foreach ($roles as $role)
                                <th class="px-4 py-4 text-center font-semibold text-slate-700 min-w-[140px]">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="text-slate-900 font-bold text-xs leading-tight">
                                            {{ $role->display_name }}
                                        </span>
                                        <span
                                            class="inline-block bg-slate-200 text-slate-600 text-[10px] px-2 py-0.5 rounded-full font-mono">
                                            {{ $role->name }}
                                        </span>
                                        {{-- Tombol Kelola Menu, kirim role ID dan nama --}}
                                        <button type="button" data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->display_name }}"
                                            onclick="openMenuModal({{ $role->id }}, '{{ $role->display_name }}')"
                                            class="mt-1 inline-flex items-center gap-1 text-[10px] bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 px-2 py-1 rounded-md transition-colors font-medium">
                                            <i class="fas fa-th-list text-[9px]"></i> Kelola Menu
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($permissions as $permission)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td
                                    class="px-6 py-4 sticky left-0 bg-white hover:bg-slate-50 z-10 border-r border-slate-200">
                                    <div class="font-medium text-slate-800">{{ $permission->display_name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $permission->description }}</div>
                                </td>
                                @foreach ($roles as $role)
                                    <td class="px-4 py-4 text-center">
                                        <label class="inline-flex items-center justify-center cursor-pointer">
                                            <input type="checkbox" class="permission-toggle sr-only"
                                                data-role-id="{{ $role->id }}"
                                                data-permission-id="{{ $permission->id }}"
                                                {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                            <div
                                                class="toggle-track relative w-11 h-6 bg-slate-200 rounded-full transition-colors duration-200">
                                                <div
                                                    class="toggle-thumb absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200">
                                                </div>
                                            </div>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer: Simpan per role --}}
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <p class="text-sm text-slate-500">
                        <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                        Klik tombol <strong>Simpan</strong> pada role yang ingin disimpan perubahannya.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($roles as $role)
                            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="save-form"
                                data-role-id="{{ $role->id }}">
                                @csrf
                                @method('PUT')
                                <div id="hidden-permissions-{{ $role->id }}"></div>
                                <button type="submit" id="save-btn-{{ $role->id }}"
                                    class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-save"></i> {{ $role->display_name }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL KELOLA MENU ===== --}}
    <div id="menuModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="closeMenuModal()"></div>

        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 max-h-[85vh] flex flex-col">

            {{-- Header Modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="fas fa-th-list text-blue-600"></i> Kelola Menu
                    </h3>
                    <p id="modalRoleName" class="text-sm text-slate-500 mt-0.5"></p>
                </div>
                <button onclick="closeMenuModal()"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-slate-600"></i>
                </button>
            </div>

            {{-- Select All --}}
            <div class="px-6 py-3 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-slate-700">
                    <input type="checkbox" id="selectAllMenus" class="h-4 w-4 text-blue-600 rounded"
                        onchange="toggleAllMenus(this)">
                    Pilih Semua Menu
                </label>
            </div>

            {{-- Menu List --}}
            <div class="overflow-y-auto flex-1 px-6 py-4">
                @php $menuGroups = $menus->groupBy('group'); @endphp
                @foreach ($menuGroups as $group => $groupMenus)
                    <div class="mb-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            {{ $group === 'main' ? 'Menu Utama' : 'Manajemen' }}
                        </p>
                        <div class="space-y-1">
                            @foreach ($groupMenus as $menu)
                                <label
                                    class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-100 hover:border-blue-200 hover:bg-blue-50 cursor-pointer transition-colors">
                                    <input type="checkbox" class="menu-checkbox h-4 w-4 text-blue-600 rounded"
                                        data-menu-id="{{ $menu->id }}" value="{{ $menu->id }}">
                                    <i class="{{ $menu->icon }} text-slate-400 w-4 text-center"></i>
                                    <span class="text-sm text-slate-700 font-medium">{{ $menu->label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer Modal --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <button onclick="closeMenuModal()"
                    class="px-4 py-2 text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors font-medium">
                    Batal
                </button>
                {{-- 
                    PENTING: Tombol ini memanggil saveMenuAjax() bukan submit form biasa.
                    Ini fix utama bug "selalu ke Super Admin" — kita tidak pakai form action,
                    tapi AJAX fetch ke URL yang sudah pasti benar berdasarkan currentRoleId.
                --}}
                <button type="button" onclick="saveMenuAjax()" id="btnSimpanMenu"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Menu
                </button>
            </div>
        </div>
    </div>

    {{-- Data JS dari server --}}
    <script>
        // CSRF token untuk AJAX
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // Mapping role ID -> array menu ID yang sudah aktif (dari DB)
        const roleMenuData = {
            @foreach ($roles as $role)
                {{ $role->id }}: [{{ $role->menus->pluck('id')->join(',') }}],
            @endforeach
        };

        // Mapping role ID -> URL endpoint PUT menu
        const roleMenuUrls = {
            @foreach ($roles as $role)
                {{ $role->id }}: '{{ route('admin.roles.menus', $role->id) }}',
            @endforeach
        };

        // Variabel global: menyimpan role ID yang sedang dibuka di modal
        // Ini yang jadi penentu utama kemana AJAX dikirim — tidak bergantung pada form action
        let currentRoleId = null;
    </script>

    <style>
        input.permission-toggle:checked+.toggle-track {
            background-color: #16a34a;
        }

        input.permission-toggle:checked+.toggle-track .toggle-thumb {
            transform: translateX(20px);
        }

        td.sticky,
        th.sticky {
            box-shadow: 2px 0 4px -2px rgba(0, 0, 0, 0.08);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            closeMenuModal();

            // Init visual toggle sesuai state dari DB
            document.querySelectorAll('.permission-toggle').forEach(function(cb) {
                setToggleVisual(cb);
                cb.addEventListener('change', function() {
                    setToggleVisual(this);
                    // Beri indikator kuning di tombol Simpan role ini
                    const btn = document.getElementById('save-btn-' + this.dataset.roleId);
                    if (btn) {
                        btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600', 'ring-2',
                            'ring-yellow-300');
                    }
                });
            });

            // Handler submit permission per role
            document.querySelectorAll('.save-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const roleId = this.dataset.roleId;
                    const container = document.getElementById('hidden-permissions-' + roleId);
                    container.innerHTML = '';
                    document.querySelectorAll('.permission-toggle[data-role-id="' + roleId +
                            '"]:checked')
                        .forEach(function(cb) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'permissions[]';
                            input.value = cb.dataset.permissionId;
                            container.appendChild(input);
                        });
                });
            });
        });

        function setToggleVisual(cb) {
            const track = cb.nextElementSibling;
            const thumb = track?.querySelector('.toggle-thumb');
            if (!track || !thumb) return;
            if (cb.checked) {
                track.style.backgroundColor = '#16a34a';
                thumb.style.transform = 'translateX(20px)';
            } else {
                track.style.backgroundColor = '';
                thumb.style.transform = '';
            }
        }

        /**
         * Buka modal kelola menu.
         * currentRoleId di-set di sini — ini satu-satunya sumber kebenaran
         * untuk tahu role mana yang sedang diedit.
         */
        function openMenuModal(roleId, roleName) {
            // Simpan role yang sedang aktif ke variabel global
            currentRoleId = roleId;

            document.getElementById('modalRoleName').textContent = 'Role: ' + roleName;

            // Reset selectAll
            const selectAllCb = document.getElementById('selectAllMenus');
            if (selectAllCb) {
                selectAllCb.checked = false;
                selectAllCb.indeterminate = false;
            }

            // Reset SEMUA checkbox menu ke unchecked dulu
            document.querySelectorAll('.menu-checkbox').forEach(function(cb) {
                cb.checked = false;
            });

            // Tampilkan modal
            const modal = document.getElementById('menuModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Centang hanya menu yang tersimpan di DB untuk role ini
            const activeMenus = roleMenuData[roleId] || [];
            document.querySelectorAll('.menu-checkbox').forEach(function(cb) {
                cb.checked = activeMenus.includes(parseInt(cb.dataset.menuId));
            });

            updateSelectAll();
        }

        function closeMenuModal() {
            const modal = document.getElementById('menuModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            currentRoleId = null;
        }

        function toggleAllMenus(selectAll) {
            document.querySelectorAll('.menu-checkbox').forEach(function(cb) {
                cb.checked = selectAll.checked;
            });
        }

        function updateSelectAll() {
            const all = document.querySelectorAll('.menu-checkbox');
            const checked = document.querySelectorAll('.menu-checkbox:checked');
            const selectAllCb = document.getElementById('selectAllMenus');
            if (!selectAllCb || all.length === 0) return;

            if (checked.length === 0) {
                selectAllCb.checked = false;
                selectAllCb.indeterminate = false;
            } else if (checked.length === all.length) {
                selectAllCb.checked = true;
                selectAllCb.indeterminate = false;
            } else {
                selectAllCb.checked = false;
                selectAllCb.indeterminate = true;
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('menu-checkbox')) {
                updateSelectAll();
            }
        });

        /**
         * AJAX Save Menu — ini pengganti form submit biasa.
         * Menggunakan currentRoleId (bukan form action) sebagai penentu URL.
         * Ini yang fix bug "selalu ke Super Admin".
         */
        function saveMenuAjax() {
            if (!currentRoleId) {
                alert('Error: role tidak dikenali. Tutup modal dan coba lagi.');
                return;
            }

            const url = roleMenuUrls[currentRoleId];
            if (!url) {
                alert('Error: URL tidak ditemukan untuk role ini.');
                return;
            }

            // Kumpulkan menu yang dicentang
            const selectedMenuIds = [];
            document.querySelectorAll('.menu-checkbox:checked').forEach(function(cb) {
                selectedMenuIds.push(cb.value);
            });

            // Siapkan form data
            const formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'PUT');
            selectedMenuIds.forEach(function(id) {
                formData.append('menus[]', id);
            });

            // Disable tombol saat loading
            const btn = document.getElementById('btnSimpanMenu');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            fetch(url, {
                    method: 'POST', // Laravel method spoofing: POST + _method=PUT
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(function(response) {
                    // Update roleMenuData di JS agar sinkron dengan DB
                    roleMenuData[currentRoleId] = selectedMenuIds.map(Number);

                    closeMenuModal();
                    showToast('Menu role berhasil disimpan!');

                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save"></i> Simpan Menu';
                })
                .catch(function(err) {
                    console.error('Gagal simpan menu:', err);
                    showToast('Gagal menyimpan menu. Coba lagi.', true);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save"></i> Simpan Menu';
                });
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            toastMsg.textContent = message;
            toast.classList.remove('hidden', 'bg-green-600', 'bg-red-600');
            toast.classList.add(isError ? 'bg-red-600' : 'bg-green-600');
            toast.classList.add('flex');
            setTimeout(function() {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 3000);
        }
    </script>
@endsection
