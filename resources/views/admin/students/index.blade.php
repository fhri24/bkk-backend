@extends('layouts.admin')

@section('title', 'Daftar Alumni - Admin BKK')
@section('page_title', 'Daftar Alumni')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-users text-green-600 mr-3"></i> Daftar Alumni
        </h3>
    </div>
    <div class="table-custom">
        <table class="w-full">
            <thead>
                <tr>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat/Tgl Lahir</th>
                    <th>Jurusan</th>
                    <th>Tahun Lulus</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>CV File</th>
                    <th>Foto Profil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>{{ $student->birth_info }}</td>
                        <td>{{ $student->major }}</td>
                        <td>{{ $student->graduation_year }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->address }}</td>
                        <td>
                            @if($student->resume_url)
                                <a href="{{ asset('storage/' . $student->resume_url) }}" target="_blank" class="text-blue-600 hover:underline">Lihat CV</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($student->profile_picture)
                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Foto" class="w-10 h-10 rounded-full">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.students.show', $student->student_id) }}" class="btn-action" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-slate-500 py-8">
                            <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                            Belum ada data alumni
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-200">
        {{ $students->links() }}
    </div>
</div>
@endsection