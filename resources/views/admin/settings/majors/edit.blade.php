@extends('layouts.admin')

@section('title', 'Edit Jurusan - Admin BKK')
@section('page_title', 'Edit Jurusan')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Edit Jurusan</h3>
        <p class="text-sm text-slate-500 mt-2">Perbarui data jurusan yang telah tersimpan.</p>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.settings.majors.update', $major) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Nama Jurusan</label>
                    <input type="text" name="name" value="{{ old('name', $major->name) }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2">{{ old('description', $major->description) }}</textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
                    <a href="{{ route('admin.settings.majors.index') }}" class="btn-action bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
