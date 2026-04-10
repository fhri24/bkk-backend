    @extends('layouts.app')

@section('title', 'Profile Saya - BKK SMKN 1 Garut')

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-extrabold text-[#001f3f] mb-8">Profile Saya</h1>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center sticky top-24">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $student->full_name ?? $user->name }}</h2>
                    <p class="text-slate-500 mt-2">{{ $user->email }}</p>
                    
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-slate-600 font-semibold">NIS</p>
                        <p class="text-xl font-bold text-blue-600">{{ $student->nis ?? 'N/A' }}</p>
                    </div>

                    <div class="mt-4 p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-slate-600 font-semibold">Angkatan</p>
                        <p class="text-xl font-bold text-green-600">{{ $student->graduation_year ?? 'N/A' }}</p>
                    </div>

                    <div class="mt-4 p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-slate-600 font-semibold">Status</p>
                        <p class="text-xl font-bold text-purple-600">
                            <span class="px-3 py-1 rounded-full {{ $student->alumni_flag ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800' }}">
                                {{ $student->alumni_flag ? 'Alumni' : 'Aktif' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-[#001f3f] mb-6">Informasi Lengkap</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Nama Lengkap</label>
                            <p class="text-lg font-semibold text-slate-900 mt-2">{{ $student->full_name ?? $user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Email</label>
                            <p class="text-lg font-semibold text-slate-900 mt-2">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Jurusan</label>
                            <p class="text-lg font-semibold text-slate-900 mt-2">{{ $student->major ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Jenis Kelamin</label>
                            <p class="text-lg font-semibold text-slate-900 mt-2">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">No. Telepon</label>
                            <p class="text-lg font-semibold text-slate-900 mt-2">{{ $student->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Alamat</label>
                            <p class="text-lg font-semibold text-slate-900 mt-2">{{ $student->address ?? '-' }}</p>
                        </div>
                    </div>

                    <button class="mt-8 bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                        Edit Profile
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-[#001f3f] mb-6">Statistik</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-50 rounded-lg text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ $student->jobApplications->count() ?? 0 }}</p>
                            <p class="text-sm text-slate-600 font-semibold mt-2">Lamaran</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg text-center">
                            <p class="text-3xl font-bold text-green-600">0</p>
                            <p class="text-sm text-slate-600 font-semibold mt-2">Diterima</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
