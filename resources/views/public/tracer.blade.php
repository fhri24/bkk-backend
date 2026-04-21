@extends('layouts.app')

@section('title', 'Tracer Study - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-extrabold text-[#001f3f] mb-8 text-center">Tracer Study & Data Siswa</h1>

        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-16">
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <div class="text-center p-6 bg-blue-50 rounded-2xl border border-blue-100">
                    <div class="text-4xl font-extrabold text-blue-600 mb-2">85%</div>
                    <p class="text-slate-700 font-semibold">Terserap di Dunia Kerja</p>
                </div>
                <div class="text-center p-6 bg-green-50 rounded-2xl border border-green-100">
                    <div class="text-4xl font-extrabold text-green-600 mb-2">{{ count($alumni) + count($siswaAktif) }}+</div>
                    <p class="text-slate-700 font-semibold">Total Terdata</p>
                </div>
            </div>

            @auth
                <h2 class="text-2xl font-bold text-[#001f3f] mb-6 border-l-4 border-blue-600 pl-4">Isi Tracer Study Anda</h2>
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status Saat Ini</label>
                            <select name="status_kerja" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold focus:ring-2 focus:ring-blue-500 outline-none" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="working">Bekerja</option>
                                <option value="studying">Melanjutkan Studi</option>
                                <option value="both">Bekerja & Studi</option>
                                <option value="unemployed">Mencari Kerja</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Perusahaan / Institusi</label>
                            <input type="text" name="company" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Masukkan nama">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Posisi / Program</label>
                            <input type="text" name="position" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Masukkan posisi">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Gaji / Kompetensi (Optional)</label>
                            <input type="text" name="salary" class="w-full px-6 py-3 border border-slate-300 rounded-xl font-semibold focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Misal: 5.000.000">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-1">
                        Simpan Tracer Study
                    </button>
                </form>
            @else
                <div class="text-center p-8 border-2 border-dashed border-slate-200 rounded-2xl">
                    <p class="text-slate-600 mb-4 font-medium">Silakan login untuk mengisi data Tracer Study Anda.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-[#001f3f] text-white px-8 py-3 rounded-xl font-bold hover:bg-slate-800 transition">Login Sekarang</a>
                </div>
            @endauth
        </div>

        <div class="mt-20">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h2 class="text-3xl font-bold text-[#001f3f]">Direktori Siswa & Alumni</h2>

                <div class="inline-flex rounded-xl bg-slate-100 p-1.5 shadow-inner">
                    <button onclick="switchTab('alumni')" id="btn-alumni"
                        class="px-6 py-2.5 rounded-lg bg-white text-blue-600 shadow-md font-bold transition-all text-sm md:text-base">
                        🎓 Alumni
                    </button>
                    <button onclick="switchTab('siswa')" id="btn-siswa"
                        class="px-6 py-2.5 rounded-lg text-slate-500 font-bold transition-all text-sm md:text-base">
                        👨‍🎓 Siswa Aktif
                    </button>
                </div>
            </div>

            <div id="content-alumni" class="tab-content active">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($alumni as $item)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-300 transition">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-xl font-bold">
                                    {{ substr($item->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 leading-tight">{{ $item->full_name }}</h4>
                                    <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Angkatan {{ $item->graduation_year }}</p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-50">
                                <p class="text-sm text-slate-500"><span class="font-semibold text-slate-700">Jurusan:</span> {{ $item->major }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-slate-400 font-medium">Belum ada data alumni.</div>
                    @endforelse
                </div>
            </div>

            <div id="content-siswa" class="tab-content hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($siswaAktif as $s)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-slate-300 transition">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 text-xl font-bold">
                                    {{ substr($s->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 leading-tight">{{ $s->full_name }}</h4>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider italic">Siswa Aktif</p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-50">
                                <p class="text-sm text-slate-500"><span class="font-semibold text-slate-700">Jurusan:</span> {{ $s->major }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-slate-400 font-medium">Belum ada data siswa aktif.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-content.hidden { display: none; }
</style>

<script>
    function switchTab(type) {
        const alumniContent = document.getElementById('content-alumni');
        const siswaContent = document.getElementById('content-siswa');
        const btnAlumni = document.getElementById('btn-alumni');
        const btnSiswa = document.getElementById('btn-siswa');

        if (type === 'alumni') {
            alumniContent.classList.remove('hidden');
            siswaContent.classList.add('hidden');
            btnAlumni.classList.add('bg-white', 'text-blue-600', 'shadow-md');
            btnAlumni.classList.remove('text-slate-500');
            btnSiswa.classList.remove('bg-white', 'text-blue-600', 'shadow-md');
            btnSiswa.classList.add('text-slate-500');
        } else {
            siswaContent.classList.remove('hidden');
            alumniContent.classList.add('hidden');
            btnSiswa.classList.add('bg-white', 'text-blue-600', 'shadow-md');
            btnSiswa.classList.remove('text-slate-500');
            btnAlumni.classList.remove('bg-white', 'text-blue-600', 'shadow-md');
            btnAlumni.classList.add('text-slate-500');
        }
    }
</script>
@endsection
