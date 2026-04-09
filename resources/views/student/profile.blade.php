@extends('layouts.app')

@section('title', 'Profile Siswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Profile Saya</h1>

    @if ($student)
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <p class="mt-1 text-lg font-semibold">{{ $student->full_name ?? $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIS</label>
                    <p class="mt-1 text-lg font-semibold">{{ $student->nis ?? 'Not set' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-lg font-semibold">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1 text-lg font-semibold">{{ ucfirst($student->status ?? 'N/A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Angkatan</label>
                    <p class="mt-1 text-lg font-semibold">{{ $student->graduation_year ?? 'Not set' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alumni Status</label>
                    <p class="mt-1 text-lg font-semibold">
                        <span class="px-3 py-1 rounded {{ $student->alumni_flag ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $student->alumni_flag ? 'Alumni' : 'Aktif' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t">
                <p class="text-sm text-gray-600">Member sejak {{ $user->created_at->format('d F Y') }}</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('student.home') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 inline-block">
                    Kembali ke Home
                </a>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
            <p class="text-yellow-800">Profile siswa tidak ditemukan. Hubungi administrator.</p>
        </div>
    @endif
</div>
@endsection
