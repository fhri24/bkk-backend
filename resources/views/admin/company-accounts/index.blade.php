@extends('layouts.admin')

@section('title', 'Akun Perusahaan')
@section('page_title', 'Manajemen Akun Perusahaan')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-slate-600 text-sm">Daftar semua akun yang terhubung ke perusahaan</p>
        <a href="{{ route('admin.company-accounts.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition text-sm">
            <i class="fas fa-user-plus"></i> Tambah Akun Perusahaan
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3 text-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3 text-sm">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if ($accounts->count() > 0)
        <div class="table-custom">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">Nama / Email</th>
                        <th class="text-left">Perusahaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-800">{{ $account->name }}</p>
                                <p class="text-xs text-slate-400">{{ $account->email }}</p>
                            </td>
                            <td>
                                <p class="font-semibold text-slate-700">{{ $account->company->company_name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $account->company->industry ?? '' }}</p>
                            </td>
                            <td class="text-center">
                                <span class="badge-pill {{ $account->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center text-sm text-slate-500">
                                {{ $account->created_at->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Toggle aktif/nonaktif --}}
                                    <form action="{{ route('admin.company-accounts.toggle', $account->id) }}"
                                        method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                            class="btn-action {{ $account->is_active ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }}"
                                            title="{{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            onclick="return confirm('{{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                            <i class="fas {{ $account->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    {{-- Reset password --}}
                                    <a href="{{ route('admin.company-accounts.reset-password', $account->id) }}"
                                        class="btn-action text-blue-600 hover:bg-blue-50" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.company-accounts.destroy', $account->id) }}"
                                        method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action text-red-600 hover:bg-red-50"
                                            onclick="return confirm('Hapus akun ini permanen?')" title="Hapus">
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
            <i class="fas fa-building text-4xl text-slate-200 mb-3 block"></i>
            <p class="text-slate-500 mb-4">Belum ada akun perusahaan</p>
            <a href="{{ route('admin.company-accounts.create') }}"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 inline-block text-sm font-semibold">
                <i class="fas fa-user-plus mr-2"></i> Tambah Akun Perusahaan
            </a>
        </div>
    @endif
@endsection
