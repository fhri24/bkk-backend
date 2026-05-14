@extends('layouts.admin')

@section('title', 'Tambah Akun Perusahaan')
@section('page_title', 'Tambah Akun Perusahaan')

@section('content')
    <div class="max-w-2xl">

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex gap-3 text-sm text-blue-700">
            <i class="fas fa-info-circle mt-0.5 shrink-0"></i>
            <p>Akun yang dibuat di sini akan otomatis terhubung ke perusahaan yang dipilih. Perusahaan bisa login
                menggunakan email dan password yang Anda tentukan.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.company-accounts.store') }}" method="POST"
            class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            @csrf

            {{-- Pilih Perusahaan --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Perusahaan <span class="text-red-500">*</span>
                </label>
                <select name="company_id" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->company_id }}"
                            {{ old('company_id') == $company->company_id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Perusahaan harus sudah terdaftar di sistem terlebih dahulu.</p>
            </div>

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Nama PIC / HR <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: HR Alfamart Garut"
                    required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Email Login <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="hr@alfamart.com" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                <p class="text-xs text-slate-400 mt-1">Email ini digunakan perusahaan untuk login. Harus unik.</p>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" placeholder="Minimal 8 karakter" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Buat Akun Perusahaan
                </button>
                <a href="{{ route('admin.company-accounts.index') }}"
                    class="border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
