@extends('layouts.admin')

@section('title', 'Daftar Pengguna - Admin BKK')
@section('page_title', 'Daftar Pengguna')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-users-cog text-indigo-600 mr-3"></i> Daftar Pengguna
        </h3>
        <p class="text-sm text-slate-500 mt-2">Kelola status akun untuk Admin, Petugas BKK, Perusahaan, dan Siswa.</p>
    </div>
    <div class="table-custom">
        <table class="w-full">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
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
                                <button type="submit" class="btn-action" title="{{ $user->is_active ? 'Non-aktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-toggle-{{ $user->is_active ? 'on' : 'off' }}"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-500 py-8">
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
@endsection