@extends('layouts.app')

@section('title', 'Lamaran Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Lamaran Saya</h1>

    @if ($applications->count() > 0)
        <div class="grid gap-6">
            @foreach ($applications as $app)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">{{ $app->job->title }}</h3>
                            <p class="text-gray-600">{{ $app->job->company->company_name ?? 'Company' }}</p>
                        </div>
                        <span class="px-4 py-2 rounded-full font-semibold
                            @if ($app->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif ($app->status === 'review') bg-blue-100 text-blue-800
                            @elseif ($app->status === 'accepted') bg-green-100 text-green-800
                            @elseif ($app->status === 'rejected') bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($app->status) }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-500 mb-4">
                        Melamar pada: {{ $app->application_date->format('d F Y') }}
                    </div>

                    @if ($app->cover_letter)
                        <div class="mb-4 p-4 bg-gray-50 rounded">
                            <h4 class="font-semibold mb-2">Surat Lamaran:</h4>
                            <p class="text-gray-700">{{ substr($app->cover_letter, 0, 200) }}...</p>
                        </div>
                    @endif

                    @if ($app->admin_notes)
                        <div class="mb-4 p-4 bg-blue-50 rounded border-l-4 border-blue-500">
                            <h4 class="font-semibold mb-2 text-blue-800">Catatan Admin:</h4>
                            <p class="text-blue-700">{{ $app->admin_notes }}</p>
                        </div>
                    @endif

                    <a href="#" class="text-blue-600 hover:underline text-sm">Lihat Detail Lowongan →</a>
                </div>
            @endforeach
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
