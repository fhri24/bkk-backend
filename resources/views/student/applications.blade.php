@extends('layouts.app')

@section('title', 'Lamaran Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Lamaran Saya</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($applications->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lowongan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Melamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Pendukung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CV File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($applications as $app)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $app->job->title }}</div>
                                <div class="text-sm text-gray-500">{{ $app->job->company->company_name ?? 'Company' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $app->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $app->application_date->format('d F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if ($app->cover_letter)
                                    <span class="text-green-600">Ada</span>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if ($app->additional_file)
                                    <a href="{{ Storage::url('cv_applications/' . $app->additional_file) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $app->phone_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($app->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif ($app->status === 'review') bg-blue-100 text-blue-800
                                    @elseif ($app->status === 'accepted') bg-green-100 text-green-800
                                    @elseif ($app->status === 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('student.lowongan.detail', $app->job->job_id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                <form method="POST" action="{{ route('student.applications.delete', $app->job_application_id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lamaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-300 p-8 rounded-lg text-center">
            <p class="text-gray-600 text-lg mb-4">Anda belum melakukan lamaran ke perusahaan manapun 😕</p>
            <a href="{{ route('student.home') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 inline-block">
                Lihat Lowongan Kerja
            </a>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('student.home') }}" class="text-blue-600 hover:underline">← Kembali ke Home</a>
    </div>
</div>
@endsection
