@extends('layouts.admin')

@section('title', 'Tambah Lowongan Kerja Baru')
@section('page_title', 'Tambah Lowongan')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Tambah Lowongan Kerja Baru</h2>
            <p class="text-sm text-slate-500">Isi semua informasi lowongan pekerjaan yang ingin dipublikasikan.</p>
        </div>
        <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
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

    <form action="{{ route('admin.jobs.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        @csrf

        <div class="grid gap-6">

            {{-- TAMBAHAN: Pilih Perusahaan --}}
            <div>
                <label for="company_id" class="block text-sm font-medium text-slate-700">Pilih Perusahaan</label>
                <select name="company_id" id="company_id" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->company_id }}" {{ (old('company_id') == $company->company_id || $selectedCompanyId == $company->company_id) ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bidang Keahlian (Jurusan) --}}
            <div>
                <label for="major_id" class="block text-sm font-medium text-slate-700">Bidang Keahlian (Jurusan)</label>
                <select name="major_id" id="major_id" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">-- Semua Jurusan / Umum --</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1 italic">*Kosongkan jika lowongan untuk semua jurusan</p>
            </div>

            {{-- Judul Posisi --}}
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Judul Posisi</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Contoh: Developer PHP"
                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
            </div>

            {{-- Poster / Banner --}}
            <div>
                <label for="image" class="block text-sm font-medium text-slate-700">Poster / Banner Lowongan <span class="text-slate-400">(Opsional)</span></label>
                <div class="mt-2 flex items-center gap-3 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3">
                    <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <p class="text-xs text-slate-400 mt-1 italic">*Format: JPG, PNG (Max 2MB)</p>
            </div>

            {{-- Deskripsi Pekerjaan --}}
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi Pekerjaan</label>
                <textarea name="description" id="description" rows="4" placeholder="Jelaskan gambaran umum pekerjaan..."
                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>{{ old('description') }}</textarea>
            </div>

            {{-- Tanggung Jawab --}}
            <div>
                <label for="responsibilities" class="block text-sm font-medium text-slate-700">Tanggung Jawab</label>
                <textarea name="responsibilities" id="responsibilities" rows="4" placeholder="Daftar tanggung jawab pekerjaan (pisahkan tiap baris)..."
                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('responsibilities') }}</textarea>
            </div>

            {{-- Persyaratan --}}
            <div>
                <label for="requirements" class="block text-sm font-medium text-slate-700">Persyaratan</label>
                <textarea name="requirements" id="requirements" rows="3" placeholder="Daftar persyaratan (pisahkan tiap baris)..."
                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('requirements') }}</textarea>
            </div>

            {{-- Benefit & Tunjangan --}}
            <div>
                <label for="benefits" class="block text-sm font-medium text-slate-700">Benefit & Tunjangan</label>
                <textarea name="benefits" id="benefits" rows="3" placeholder="Contoh: BPJS, Tunjangan Makan, Asuransi..."
                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('benefits') }}</textarea>
            </div>

            {{-- Lokasi & Pengalaman --}}
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Contoh: Jakarta, Bandung"
                        class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="experience" class="block text-sm font-medium text-slate-700">Pengalaman</label>
                    <input type="text" name="experience" id="experience" value="{{ old('experience') }}" placeholder="Contoh: 1-2 Tahun"
                        class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            {{-- Gaji & Jenis Pekerjaan --}}
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="salary" class="block text-sm font-medium text-slate-700">Gaji</label>
                    <input type="text" name="salary" id="salary" value="{{ old('salary') }}" placeholder="Contoh: Kompetitif / Rp 5.000.000"
                        class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="job_type" class="block text-sm font-medium text-slate-700">Jenis Pekerjaan</label>
                    <select name="job_type" id="job_type" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach (['Full-time','Part-time','Contract','Internship'] as $type)
                            <option value="{{ $type }}" {{ old('job_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Visibility & Kadaluarsa --}}
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label for="visibility" class="block text-sm font-medium text-slate-700">Visibility</label>
                    <select name="visibility" id="visibility" class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                        @foreach (['public' => 'Public', 'alumni_only' => 'Alumni Only', 'private' => 'Private', 'internal' => 'Internal'] as $value => $label)
                            <option value="{{ $value }}" {{ old('visibility') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="expired_at" class="block text-sm font-medium text-slate-700">Tanggal Kadaluarsa</label>
                    <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Lowongan
                </button>
                <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Batal</a>
            </div>

        </div>
    </form>
</div>
@endsection
