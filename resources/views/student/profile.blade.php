@extends('layouts.app')

@section('title', 'BKK SMKN 1 Garut - Profil Pengguna')

@section('content')
    <div class="bg-slate-100 text-slate-900 min-h-screen">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- Kolom Kiri: Profil Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-8 text-center sticky top-24">

                        {{-- Foto Profil --}}
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            @if ($student && $student->profile_picture)
                                <img id="avatar-img" src="{{ Storage::url($student->profile_picture) }}" alt="Profile Picture"
                                    class="w-24 h-24 rounded-full object-cover shadow-lg border-2 border-blue-500"
                                    onerror="this.style.display='none'; document.getElementById('avatar-fallback').style.display='flex';">
                                <div id="avatar-fallback"
                                    class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 items-center justify-center text-white text-4xl shadow-lg"
                                    style="display:none;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @else
                                <div
                                    class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-4xl shadow-lg">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>

                        <h2 class="text-2xl font-extrabold text-slate-900 mb-1">
                            {{ $student->full_name ?? $user->name }}
                        </h2>
                        <p class="text-sm text-slate-500 font-bold mb-4">{{ $user->email }}</p>

                        {{-- Badge Role --}}
                        <div class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-bold mb-6">
                            <i class="fas fa-check-circle mr-2"></i>
                            @if (auth()->user()->role && auth()->user()->role->name === 'publik')
                                Pengguna Umum
                            @elseif((auth()->user()->role && auth()->user()->role->name === 'alumni') || ($student && $student->alumni_flag))
                                Alumni
                            @else
                                Siswa Aktif
                            @endif
                        </div>

                        <div class="space-y-3 border-t border-slate-200 pt-6">
                            {{-- NIS / NIK --}}
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                                <div class="text-left">
                                    <p class="text-xs text-slate-500 font-bold uppercase">NIS / NIK</p>
                                    <p class="text-lg font-extrabold text-blue-600">{{ $student->nis ?? '-' }}</p>
                                </div>
                                <i class="fas fa-id-card text-blue-200 text-2xl"></i>
                            </div>

                            {{-- Lamaran Diajukan --}}
                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                                <div class="text-left">
                                    <p class="text-xs text-slate-500 font-bold uppercase">Lamaran Diajukan</p>
                                    <p class="text-lg font-extrabold text-purple-600">
                                        {{ is_countable($applications) ? count($applications) : 0 }}
                                    </p>
                                </div>
                                <i class="fas fa-paper-plane text-purple-200 text-2xl"></i>
                            </div>

                            {{-- Tersimpan --}}
                            <a href="{{ auth()->user()->isAlumni() ? '#' : route('student.saved-jobs') }}"
                                class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl border border-blue-100 hover:bg-blue-100 transition">
                                <span class="font-bold text-blue-700 text-sm">
                                    <i class="fas fa-bookmark mr-2"></i> Tersimpan
                                </span>
                                <span class="bg-blue-600 text-white text-xs font-black px-3 py-1 rounded-full">
                                    {{ $savedCount ?? 0 }}
                                </span>
                            </a>

                            {{-- Akun Dibuat --}}
                            <div class="text-left px-2 pt-2">
                                <p class="text-xs text-slate-500 font-bold uppercase">Akun Dibuat</p>
                                <p class="text-sm font-bold text-slate-600">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                            </div>
                        </div>

                        <button onclick="openEditModal()"
                            class="w-full mt-8 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            <i class="fas fa-edit mr-2"></i>Edit Profil
                        </button>
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Informasi --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Informasi Pribadi --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                            <i class="fas fa-user-circle text-blue-600 mr-3"></i>Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Nama Lengkap</p>
                                <p class="text-lg font-bold text-slate-900">
                                    {{ $student->full_name ?? $user->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Jenis Kelamin</p>
                                <p class="text-lg font-bold text-slate-900">
                                    @if ($student && $student->gender === 'L')
                                        Laki-laki
                                    @elseif($student && $student->gender === 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Email</p>
                                <p class="text-lg font-bold text-slate-900 break-all">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">No. Handphone</p>
                                <p class="text-lg font-bold text-slate-900">{{ $student->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Pendidikan & Status --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                            <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>Informasi Pendidikan & Status
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Sekolah / Instansi</p>
                                <p class="text-lg font-bold text-slate-900">
                                    {{ $student->school_origin ?? 'SMKN 1 Garut' }}
                                </p>
                            </div>

                            @if (auth()->user()->role && auth()->user()->role->name !== 'publik')
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Jurusan</p>
                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $student->major ?? 'Tidak Diisi' }}
                                    </p>
                                </div>
                            @endif

                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Tahun Lulus / Angkatan</p>
                                <p class="text-lg font-bold text-slate-900">
                                    {{ $student->graduation_year ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Status Saat Ini</p>
                                <p class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                                    {{ ucfirst($student->status ?? 'Aktif') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Lowongan Tersimpan --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center">
                            <i class="fas fa-bookmark mr-3 text-blue-500"></i> Lowongan yang Saya Simpan
                        </h3>
                        <div class="space-y-4">
                            @forelse(auth()->user()->savedJobs()->with('job.company')->get() as $saved)
                                @if ($saved->job)
                                    <div
                                        class="group flex items-center justify-between p-4 hover:bg-slate-50 transition-all border-b border-slate-100 last:border-0 rounded-xl">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                                <i class="fas fa-briefcase text-lg"></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <h4
                                                    class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">
                                                    {{ $saved->job->title }}
                                                </h4>
                                                <p class="text-xs text-slate-500 flex items-center mt-1">
                                                    <i class="far fa-building mr-1"></i>
                                                    {{ $saved->job->company->name ?? 'Perusahaan' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ auth()->user()->isAlumni() ? route('alumni.lowongan.detail', $saved->job_id) : route('student.lowongan.detail', $saved->job_id) }}"
                                                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form
                                                action="{{ auth()->user()->isAlumni() ? route('alumni.lowongan.save', $saved->job_id) : route('student.lowongan.save', $saved->job_id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                    onclick="return confirm('Hapus dari simpanan?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p class="text-sm text-slate-500 italic">Tidak ada lowongan tersimpan</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Lamaran Pekerjaan Terbaru --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center justify-between">
                            <span>
                                <i class="fas fa-paper-plane mr-3 text-purple-500"></i> Lamaran Pekerjaan Terbaru
                            </span>
                            <a href="{{ auth()->user()->isAlumni() ? route('alumni.applications') : route('student.applications') }}"
                                class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline transition">
                                Lihat Semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </h3>

                        @php
                            $recentApplications = collect($applications)->take(5);
                        @endphp

                        @if ($recentApplications->isEmpty())
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-file-alt text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-sm text-slate-500 italic">Belum ada lamaran pekerjaan</p>
                                <a href="{{ route('public.lowongan') }}"
                                    class="mt-3 text-xs font-bold text-blue-600 hover:underline">
                                    Cari Lowongan Sekarang →
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($recentApplications as $app)
                                    <div
                                        class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition group">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                            @if ($app->status === 'pending') bg-yellow-50 text-yellow-500
                                            @elseif($app->status === 'accepted') bg-green-50 text-green-500
                                            @elseif($app->status === 'rejected') bg-red-50 text-red-400
                                            @else bg-blue-50 text-blue-500 @endif">
                                                <i
                                                    class="fas text-sm
                                                @if ($app->status === 'pending') fa-clock
                                                @elseif($app->status === 'accepted') fa-check-circle
                                                @elseif($app->status === 'rejected') fa-times-circle
                                                @else fa-info-circle @endif"></i>
                                            </div>
                                            <div>
                                                <p
                                                    class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition line-clamp-1">
                                                    {{ $app->job->title ?? 'Lowongan tidak tersedia' }}
                                                </p>
                                                <p class="text-xs text-slate-500 mt-0.5">
                                                    <i class="far fa-building mr-1"></i>
                                                    {{ $app->job->company->name ?? '-' }}
                                                    &nbsp;·&nbsp;
                                                    <i class="far fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($app->application_date)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs font-bold px-3 py-1 rounded-full flex-shrink-0
                                        @if ($app->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($app->status === 'accepted') bg-green-100 text-green-700
                                        @elseif($app->status === 'rejected') bg-red-100 text-red-600
                                        @else bg-blue-100 text-blue-700 @endif">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ auth()->user()->isAlumni() ? route('alumni.home') : route('student.home') }}"
                            class="bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Profil --}}
    <div id="editModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300"
        aria-hidden="true">
        <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto shadow-2xl transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div
                class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 text-white flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-2xl font-extrabold">Edit Profil</h2>
                <button onclick="closeEditModal()"
                    class="text-white text-3xl hover:text-blue-200 transition leading-none">&times;</button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ $student->full_name ?? $user->name }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none"
                            required />
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">NIS / NIK /
                            No.Identitas</label>
                        <input type="text" name="nis" value="{{ $student->nis ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">Jenis Kelamin</label>
                        <select name="gender"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 outline-none">
                            <option value="">Pilih</option>
                            <option value="L" @selected(($student->gender ?? '') == 'L')>Laki-laki</option>
                            <option value="P" @selected(($student->gender ?? '') == 'P')>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">No. Handphone</label>
                        <input type="tel" name="phone" value="{{ $student->phone ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none" />
                    </div>

                    @if (auth()->user()->role && auth()->user()->role->name !== 'publik')
                        <div>
                            <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">Jurusan</label>
                            <input type="text" name="major" list="major_list" value="{{ $student->major ?? '' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 outline-none"
                                placeholder="Ketik atau pilih jurusan">
                            <datalist id="major_list">
                                @foreach ($majors as $major)
                                    <option value="{{ $major->name }}">
                                @endforeach
                            </datalist>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">Tahun Lulus</label>
                        <select name="graduation_year"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 outline-none">
                            <option value="">Pilih Tahun</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->year }}" @selected(($student->graduation_year ?? '') == $year->year)>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">Alamat</label>
                        <textarea name="address"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-600 outline-none"
                            rows="2">{{ $student->address ?? '' }}</textarea>
                    </div>

                    {{-- PERBAIKAN STRUKTUR DIV FOTO PROFIL --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-extrabold text-slate-400 uppercase mb-2">Foto Profil</label>

                        {{-- Preview foto --}}
                        <div class="mb-4 flex items-center gap-4">
                            <div class="relative">
                                <img id="preview-foto"
                                    src="{{ $student && $student->profile_picture ? Storage::url($student->profile_picture) : '' }}"
                                    alt="Preview"
                                    class="w-20 h-20 rounded-full object-cover border-2 border-blue-300 {{ $student && $student->profile_picture ? '' : 'hidden' }}" />
                                <div id="preview-fallback"
                                    class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-3xl {{ $student && $student->profile_picture ? 'hidden' : '' }}">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">
                                    {{ $student && $student->profile_picture ? 'Foto saat ini. Upload baru untuk mengganti.' : 'Belum ada foto profil.' }}
                                </p>
                                <p id="file-name-label" class="text-xs text-blue-600 font-bold hidden"></p>
                            </div>
                        </div>

                        {{-- Input file dengan styling custom area klik --}}
                        <label class="block w-full cursor-pointer">
                            <div
                                class="flex items-center gap-3 w-full bg-slate-50 border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl px-4 py-3 transition">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-camera text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Pilih foto baru</p>
                                    <p class="text-xs text-slate-400">JPG, PNG, GIF — maks. 2MB</p>
                                </div>
                            </div>
                            <input type="file" name="profile_picture" id="input-foto" accept="image/*"
                                class="hidden" onchange="previewFoto(this)" />
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition">Simpan
                        Perubahan</button>
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('modalContent');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }

        // PENAMBAHAN FUNGSI LIVE PREVIEW FOTO PROFIL
        function previewFoto(input) {
            const preview = document.getElementById('preview-foto');
            const fallback = document.getElementById('preview-fallback');
            const label = document.getElementById('file-name-label');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    fallback.classList.add('hidden');
                    label.textContent = '✓ ' + file.name;
                    label.classList.remove('hidden');
                };

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
