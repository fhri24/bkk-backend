@extends('layouts.app')

@section('title', 'BKK SMKN 1 Garut - Profil Pengguna')

@section('content')
<div class="bg-slate-100 text-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        {{-- PERBAIKAN: Menambahkan items-start agar kolom kiri tidak dipaksa memanjang, sehingga sticky berfungsi --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- Kolom Kiri: Profil Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center sticky top-24">
                    @if($student->profile_picture)
                        <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Profile Picture" class="w-24 h-24 mx-auto mb-4 rounded-full object-cover shadow-lg">
                    @else
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-4xl shadow-lg">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif

                    <h2 class="text-2xl font-extrabold text-slate-900 mb-1">{{ $student->full_name ?? $user->name }}</h2>
                    <p class="text-sm text-slate-500 font-bold mb-4">{{ $user->email }}</p>

                    <div class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ ($student && $student->alumni_flag) ? 'Alumni' : 'Siswa Aktif' }}
                    </div>

                    <div class="space-y-3 border-t border-slate-200 pt-6">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                            <div class="text-left">
                                <p class="text-xs text-slate-500 font-bold uppercase">NIS</p>
                                <p class="text-lg font-extrabold text-blue-600">{{ $student->nis ?? '-' }}</p>
                            </div>
                            <i class="fas fa-id-card text-blue-200 text-2xl"></i>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                            <div class="text-left">
                                <p class="text-xs text-slate-500 font-bold uppercase">Lamaran Diajukan</p>
                                <p class="text-lg font-extrabold text-purple-600">{{ $applications->count() }}</p>
                            </div>
                            <i class="fas fa-paper-plane text-purple-200 text-2xl"></i>
                        </div>

                        <div class="text-left px-2">
                            <p class="text-xs text-slate-500 font-bold uppercase">Akun Dibuat</p>
                            <p class="text-sm font-bold text-slate-600">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <button onclick="openEditModal()" class="w-full mt-8 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        <i class="fas fa-edit mr-2"></i>Edit Profil
                    </button>
                </div>
            </div>

            {{-- Kolom Kanan: Detail Informasi --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-3"></i>Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Nama Lengkap</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->full_name ?? $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Jenis Kelamin</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '-') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Email</p>
                            <p class="text-lg font-bold text-slate-900 text-break">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">No. Handphone</p>
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
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Sekolah</p>
                            <p class="text-lg font-bold text-slate-900">SMKN 1 Garut</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Jurusan</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->major ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Tahun Lulus / Angkatan</p>
                            <p class="text-lg font-bold text-slate-900">{{ $student->graduation_year ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Status Pekerjaan</p>
                            <p class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                                {{ ucfirst($student->status ?? 'Mencari Kerja') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-bookmark mr-3 text-blue-500"></i> Lowongan yang Saya Simpan
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse(Auth::user()->savedJobs()->with('job')->get() as $saved)
                            @if($saved->job)
                                <div class="group flex items-center justify-between p-4 hover:bg-slate-50 transition-all border-b border-slate-100 last:border-0">
                                    <div class="flex items-center space-x-4"> 
                                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            <i class="fas fa-briefcase text-lg"></i>
                                        </div> 
                                        <div class="flex flex-col">
                                            <h4 class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">
                                                {{ $saved->job->title ?? 'Judul Lowongan' }}
                                            </h4>
                                            <p class="text-xs text-slate-500 flex items-center mt-1">
                                                <i class="far fa-building mr-1"></i>
                                                {{ $saved->job->company->name ?? 'Nama Perusahaan' }}
                                            </p>
                                        </div>
                                    </div> 
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('student.lowongan.detail', $saved->job_id) }}" 
                                           class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('student.lowongan.save', $saved->job_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-sm text-slate-500">Tidak ada lowongan tersimpan</p>
                        @endforelse
                    </div>
                </div> 

                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-paper-plane text-blue-600 mr-3"></i>Lamaran Pekerjaan Terbaru
                    </h3>
                    <div class="space-y-4">
                        @forelse($applications as $app)
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $app->job->title ?? 'Pekerjaan' }}</p>
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

                <div class="flex justify-end">
                    <a href="{{ route('student.home') }}" class="bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition">
                         Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</div> 

{{-- Modal Edit Profil --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 text-white flex justify-between items-center">
            <h2 class="text-2xl font-extrabold">Edit Profil</h2>
            <button onclick="closeEditModal()" class="text-white text-2xl hover:text-blue-200 transition">&times;</button>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ $student->full_name ?? $user->name }}" placeholder="Nama Lengkap" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600" required />
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">NIK / NIS</label>
                    <input type="text" name="nis" value="{{ $student->nis }}" placeholder="NIK / NIS" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600" />
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                    <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Info Kelahiran</label>
                    <input type="text" name="birth_info" value="{{ $student->birth_info }}" placeholder="Tempat, Tanggal Lahir" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600" />
                </div> 
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Jurusan</label>
                    <select name="major" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600">
                        <option value="">Pilih Jurusan</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->name }}" {{ $student->major == $major->name ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Tahun Lulus</label>
                    <select name="graduation_year" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600">
                        <option value="">Pilih Tahun Lulus</option>
                        @foreach($years as $year)
                            <option value="{{ $year->year }}" {{ $student->graduation_year == $year->year ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">No. Handphone</label>
                    <input type="tel" name="phone" value="{{ $student->phone }}" placeholder="No. Handphone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600" />
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Alamat</label>
                    <textarea name="address" placeholder="Alamat Lengkap" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600" rows="3">{{ $student->address }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-2">Foto Profil</label>
                    <input type="file" name="profile_picture" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-600" />
                    <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition">Simpan Perubahan</button>
                <button type="button" onclick="closeEditModal()" class="flex-1 bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; 
    } 

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection 