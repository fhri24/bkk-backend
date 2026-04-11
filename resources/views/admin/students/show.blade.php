@extends('layouts.admin')

@section('title', 'Detail Alumni - Admin BKK')
@section('page_title', 'Detail Alumni')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Detail Alumni #{{ $student->student_id }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-slate-700 mb-4">Informasi Pribadi</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Nama Lengkap:</label>
                        <p class="text-slate-800">{{ $student->full_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Email:</label>
                        <p class="text-slate-800">{{ $student->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">NISN:</label>
                        <p class="text-slate-800">{{ $student->nis }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Jenis Kelamin:</label>
                        <p class="text-slate-800">{{ $student->gender }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Tempat/Tanggal Lahir:</label>
                        <p class="text-slate-800">{{ $student->birth_info }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Jurusan:</label>
                        <p class="text-slate-800">{{ $student->major }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Tahun Lulus:</label>
                        <p class="text-slate-800">{{ $student->graduation_year }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-slate-700 mb-4">Kontak & Dokumen</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600">No HP:</label>
                        <p class="text-slate-800">{{ $student->phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Alamat:</label>
                        <p class="text-slate-800">{{ $student->address }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">CV File:</label>
                        @if($student->resume_url)
                            <a href="{{ asset('storage/' . $student->resume_url) }}" target="_blank" class="text-blue-600 hover:underline">Lihat CV</a>
                        @else
                            <p class="text-slate-500">-</p>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Foto Profil:</label>
                        @if($student->profile_picture)
                            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Foto Profil" class="w-20 h-20 rounded-full mt-2">
                        @else
                            <p class="text-slate-500">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('admin.students.index') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Kembali ke Daftar</a>
    </div>
</div>
@endsection