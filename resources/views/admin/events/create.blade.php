@extends('layouts.admin')

@section('title', 'Tambah Acara Baru')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:underline flex items-center font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Acara
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 border-b pb-4">Tambah Acara Baru</h2>

            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Acara <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Acara</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                            placeholder="Contoh: Workshop, Seminar, Career Fair"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Penyelenggara / Organizer</label>
                        <input type="text" name="organizer" value="{{ old('organizer', 'BKK SMKN 1 Garut') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            placeholder="Contoh: Aula Utama SMKN 1 Garut / Link Zoom"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                        <div class="flex gap-2 items-center">
                            <input type="date" name="start_date_day" value="{{ old('start_date_day') }}"
                                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                            <input type="time" name="start_date_time" value="{{ old('start_date_time') }}"
                                class="w-32 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                            <span class="text-sm font-bold text-slate-600">WIB</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Selesai <span class="text-red-500">*</span></label>
                        <div class="flex gap-2 items-center">
                            <input type="date" name="end_date_day" value="{{ old('end_date_day') }}"
                                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                            <input type="time" name="end_date_time" value="{{ old('end_date_time') }}"
                                class="w-32 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                            <span class="text-sm font-bold text-slate-600">WIB</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kapasitas Peserta <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', 0) }}" min="0"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" required>
                        <p class="text-xs text-slate-500 mt-1">Isi '0' jika tidak ada batasan kapasitas.</p>
                    </div>

                    <div></div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Foto Kartu / Thumbnail
                            <span class="text-xs text-slate-400 font-normal">(tampil di daftar acara)</span>
                        </label>
                        <input type="file" name="thumbnail" accept="image/*"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Foto Detail / Banner
                            <span class="text-xs text-slate-400 font-normal">(tampil di halaman detail)</span>
                        </label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Acara</label>
                        <textarea name="description" rows="5"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2 bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_published" value="1"
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                            <span class="font-bold text-slate-800">Publikasikan Acara</span>
                        </label>
                        <p class="text-sm text-slate-600 ml-8 mt-1">Jika dicentang, acara akan langsung terlihat di beranda siswa.</p>
                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t pt-6 mt-6">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-6 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-lg hover:bg-slate-300 transition">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Acara
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
