@extends('layouts.admin')

@section('title', 'Profil Sekolah - Admin BKK')
@section('page_title', 'Profil Sekolah')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-school text-indigo-600 mr-3"></i> Profil Sekolah
        </h3>
        <p class="text-sm text-slate-500 mt-2">Ubah nama sekolah, alamat, dan logo yang akan muncul di kop surat laporan.</p>
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @elseif(isset($tableMissing))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4 text-sm text-yellow-700">
                Tabel <strong>{{ $tableMissing }}</strong> belum ada. Jalankan <code>php artisan migrate</code> untuk melanjutkan.
            </div>
        @endif
    </div>
    <div class="p-6">
        <form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Nama Sekolah</label>
                    <input type="text" name="school_name" value="{{ old('school_name', $profile->school_name) }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Alamat Sekolah</label>
                    <textarea name="school_address" rows="4" class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2">{{ old('school_address', $profile->school_address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Logo Sekolah</label>
                    <input type="file" name="logo" class="w-full mt-2" accept="image/*">
                    @if($profile->logo_path)
                        <div class="mt-4">
                            <p class="text-sm text-slate-500">Logo saat ini:</p>
                            <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Logo Sekolah" class="mt-3 h-24 object-contain rounded-lg border border-slate-200">
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit" class="btn-action bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan Profil</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
