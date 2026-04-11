@extends('layouts.admin')

@section('title', 'Edit Perusahaan - ' . $company->company_name)
@section('page_title', 'Edit Perusahaan')

@section('content')
<div class="p-6 max-w-3xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Edit Perusahaan</h2>
            <p class="text-sm text-slate-500">Perbarui data perusahaan mitra.</p>
        </div>
        <a href="{{ route('admin.companies.show', $company->company_id) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
        </a>
    </div>

    <form action="{{ route('admin.companies.update', $company->company_id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-5">
            <div>
                <label for="company_name" class="block text-sm font-medium text-slate-700">Nama Perusahaan</label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $company->company_name) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="industry" class="block text-sm font-medium text-slate-700">Industri</label>
                    <input type="text" name="industry" id="industry" value="{{ old('industry', $company->industry) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-slate-700">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $company->contact_person) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="website" class="block text-sm font-medium text-slate-700">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="address" id="address" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('address', $company->address) }}</textarea>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan Perubahan</button>
                <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
