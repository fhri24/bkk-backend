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
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

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
                {{-- Input Nama Sekolah --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Nama Sekolah</label>
                    <input type="text" 
                           name="school_name" 
                           {{-- Logika: Jika isi di DB adalah 'Nama Sekolah', dikosongkan agar placeholder muncul --}}
                           value="{{ old('school_name', ($profile->school_name == 'Nama Sekolah' ? '' : $profile->school_name)) }}" 
                           placeholder="Masukkan Nama Sekolah..." 
                           class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                           required>
                </div>

                {{-- Input Alamat Sekolah --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Alamat Sekolah</label>
                    <textarea name="school_address" 
                              rows="4" 
                              placeholder="Masukkan Alamat Lengkap Sekolah..."
                              class="w-full border border-slate-200 rounded-lg px-4 py-3 mt-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">{{ old('school_address', ($profile->school_address == 'Alamat sekolah...' ? '' : $profile->school_address)) }}</textarea>
                </div>

                {{-- Input Logo Sekolah --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Logo Sekolah</label>
                    <div class="mt-2 flex items-center gap-4">
                        <input type="file" name="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                    </div>
                    
                    @if($profile->logo_path)
                        @php
                            $logoBase64 = '';
                            $fullPath = storage_path('app/public/' . $profile->logo_path);
                            
                            // Jika di app/public tidak ada, cek di public/storage (symlink)
                            if (!file_exists($fullPath)) {
                                $fullPath = public_path('storage/' . $profile->logo_path);
                            }

                            if (file_exists($fullPath)) {
                                try {
                                    $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                                    $data = file_get_contents($fullPath);
                                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                } catch (\Exception $e) {}
                            }
                        @endphp

                        <div class="mt-4 p-4 border border-slate-100 rounded-lg bg-slate-50 inline-block">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Logo Saat Ini</p>
                            @if($logoBase64)
                                <img src="{{ $logoBase64 }}" alt="Logo Sekolah" class="mt-2 h-24 object-contain rounded-md shadow-sm">
                            @else
                                <div class="mt-2 text-xs text-red-500 italic">File gambar tidak ditemukan di server.</div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200 flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Profil Sekolah
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 