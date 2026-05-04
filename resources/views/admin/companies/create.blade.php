@extends('layouts.admin')

@section('title', 'Tambah Perusahaan Baru')
@section('page_title', 'Tambah Perusahaan')

@section('content')
<div class="p-6 max-w-3xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Tambah Perusahaan Baru</h2>
        <p class="text-sm text-slate-500">Masukkan data perusahaan yang akan dipakai saat membuat lowongan kerja.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.companies.store') }}" method="POST" class="space-y-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        @csrf

        <div>
            <label for="company_name" class="block text-sm font-semibold text-slate-700">Nama Perusahaan</label>
            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nama Perusahaan">
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label for="industry" class="block text-sm font-semibold text-slate-700">Industri</label>
                <input type="text" name="industry" id="industry" value="{{ old('industry') }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nama Industri">
            </div>
            <div>
                <label for="contact_person" class="block text-sm font-semibold text-slate-700">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nama Contact Person">
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nomor Telepon">
            </div>
            <div>
                <label for="website" class="block text-sm font-semibold text-slate-700">Website <span class="text-slate-500">(Opsional)</span></label>
                <input type="url" name="website" id="website" value="{{ old('website') }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="https://contoh.com">
            </div>
        </div>

        <div>
            <label for="address" class="block text-sm font-semibold text-slate-700">Alamat</label>
            <textarea name="address" id="address" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Alamat Perusahaan">{{ old('address') }}</textarea>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <button type="submit" name="action" value="save" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Simpan Perusahaan
            </button>
            <button type="submit" name="action" value="save_and_create" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Simpan & Buat Lowongan
            </button>
            <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm text-slate-700 hover:bg-slate-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
