@extends('layouts.admin')

@section('title', 'Role & Permissions - Admin BKK')
@section('page_title', 'Role & Permissions')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-user-shield text-yellow-600 mr-3"></i> Role & Permissions
        </h3>
        <p class="text-sm text-slate-500 mt-2">Atur hak akses role. Contoh: Petugas BKK dapat melihat laporan tetapi tidak dapat mengubah pengaturan sistem.</p>
    </div>

    @foreach($roles as $role)
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-semibold text-slate-900">{{ $role->display_name }}</h4>
                    <p class="text-sm text-slate-500">{{ $role->description }}</p>
                </div>
                <span class="badge-pill badge-info">{{ $role->name }}</span>
            </div>

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 cursor-pointer hover:border-slate-300">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 rounded">
                            <div>
                                <div class="font-medium text-slate-800">{{ $permission->display_name }}</div>
                                <div class="text-xs text-slate-500">{{ $permission->description }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Simpan Hak Akses</button>
            </form>
        </div>
    @endforeach
</div>
@endsection