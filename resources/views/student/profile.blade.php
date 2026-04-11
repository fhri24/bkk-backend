@extends('layouts.app')

@section('title', 'BKK SMKN 1 Garut - Profil Pengguna')

@section('content')
<div class="bg-slate-100 text-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-4xl shadow-lg">
                        <i class="fas fa-user"></i>
                    </div>

                    <h2 class="text-2xl font-extrabold text-slate-900 mb-1">{{ $student->full_name ?? $user->name }}</h2>
                    <p class="text-sm text-slate-500 font-bold mb-6">NIS: {{ $student->nis ?? '-' }}</p>

                    <div class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ ($student && $student->alumni_flag) ? 'Alumni' : 'Siswa Aktif' }}
                    </div>

                    <div class="space-y-3 border-t border-slate-200 pt-6">
                        <div class="text-left">
                            <p class="text-xs text-slate-500 font-bold uppercase">Lowongan Tersimpan</p>
                            <p class="text-2xl font-extrabold text-blue-600">{{ $saved_jobs->count() }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-slate-500 font-bold uppercase">Lamaran Diajukan</p>
                            <p class="text-2xl font-extrabold text-blue-600">{{ $applications->count() }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-slate-500 font-bold uppercase">Akun Dibuat</p>
                            <p class="text-sm font-bold text-slate-600">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <button onclick="alert('Fitur edit sedang dikembangkan')" class="w-full mt-8 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                        <i class="fas fa-edit mr-2"></i>Edit Profil
                    </button>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-3"></i>Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Nama Lengkap</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->full_name ?? $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">NIK / NIS</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->nis ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Email</p>
                            <p class="text-lg font-bold text-slate-900 text-break">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">No. Handphone</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>Informasi Pendidikan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Sekolah</p>
                            <p class="text-lg font-bold text-slate-900">SMKN 1 Garut</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Jurusan</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->major ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Tahun Lulus</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->graduation_year ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Status Pekerjaan</p>
                            <p class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                                {{ ucfirst($student->status ?? 'Mencari Kerja') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-briefcase text-blue-600 mr-3"></i>Lamaran Pekerjaan Terbaru
                    </h3>
                    <div class="space-y-4">
                        @forelse($applications as $app)
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $app->job_title }}</p>
                                    <p class="text-xs text-slate-500">Dikirim pada: {{ $app->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase">
                                    {{ $app->status }}
                                </span>
                            </div>
                        @empty
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                                <p class="text-slate-500 text-sm">Belum ada lamaran pekerjaan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-bookmark text-blue-600 mr-3"></i>Lowongan Tersimpan
                    </h3>
                    <div class="space-y-4">
                        @forelse($saved_jobs as $job)
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $job->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $job->company_name ?? 'Perusahaan Terkait' }}</p>
                                </div>
                                <button class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        @empty
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                                <p class="text-slate-500 text-sm">Belum ada lowongan tersimpan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('student.home') }}" class="bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition">
                         Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
