@extends('layouts.app')

@section('title', 'Lamaran Saya - BKK SMKN 1 Garut')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4 md:px-6">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-[#001f3f]">Lamaran Saya</h2>
                    <p class="text-gray-500 mt-1">Pantau status lamaran kerja yang telah kamu kirimkan.</p>
                </div>
                <a href="{{ route('alumni.home') }}"
                    class="text-blue-600 hover:underline font-semibold flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($applications->isEmpty())
                {{-- State Kosong --}}
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                    <div
                        class="w-20 h-20 bg-blue-50 text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-paper-plane text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada lamaran terkirim</h3>
                    <p class="text-gray-500 mb-6">Kamu belum melamar ke lowongan manapun saat ini.</p>
                    <a href="{{ route('student.home') }}"
                        class="bg-[#001f3f] text-white px-8 py-3 rounded-full font-bold hover:bg-blue-900 transition">
                        Cari Lowongan Sekarang
                    </a>
                </div>
            @else
                <div class="grid gap-6">
                    @foreach ($applications as $app)
                        @php
                            $companyName = $app->job->company->company_name ?? 'Perusahaan';
                            // Logika Logo (disesuaikan dengan job atau company)
                            $logoUrl = null;
                            if ($app->job->logo) {
                                $logoUrl = Storage::disk('public')->url($app->job->logo);
                            } elseif ($app->job->company && $app->job->company->logo) {
                                $logoUrl = Storage::disk('public')->url($app->job->company->logo);
                            }
                        @endphp

                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4 group hover:shadow-md transition">

                            {{-- Info Utama --}}
                            <div class="flex items-center gap-4 w-full">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200">
                                    @if ($logoUrl)
                                        <img src="{{ $logoUrl }}" class="object-contain w-full h-full" alt="Logo">
                                    @else
                                        <span class="text-2xl font-black text-gray-400">
                                            {{ strtoupper(substr($companyName, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex-grow">
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition">
                                        {{ $app->job->title }}
                                    </h4>
                                    <p class="text-gray-600 font-medium text-sm">{{ $companyName }}</p>

                                    <div class="flex flex-wrap items-center gap-4 mt-2 text-xs font-semibold">
                                        <span class="text-gray-400">
                                            <i class="fas fa-calendar-alt mr-1 text-blue-400"></i>
                                            Melamar: {{ $app->application_date->format('d M Y') }}
                                        </span>

                                        {{-- Status Badge --}}
                                        <span
                                            class="px-3 py-1 rounded-full 
                                        @if ($app->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif ($app->status === 'review') bg-blue-100 text-blue-700
                                        @elseif ($app->status === 'accepted') bg-green-100 text-green-700
                                        @elseif ($app->status === 'rejected') bg-red-100 text-red-700 @endif">
                                            <i class="fas fa-circle text-[8px] mr-1 opacity-50"></i>
                                            {{ ucfirst($app->status) }}
                                        </span>

                                        @if ($app->additional_file)
                                            <a href="{{ $app->getCvUrl() }}"
                                                target="_blank" class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-file-pdf mr-1"></i> CV/Lampiran
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex items-center gap-3 w-full md:w-auto justify-end shrink-0">
                                <a href="{{ route('student.lowongan.detail', $app->job->job_id) }}"
                                    class="bg-blue-50 text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-100 transition whitespace-nowrap">
                                    Lihat Detail
                                </a>

                                <form method="POST"
                                    action="{{ route('student.applications.delete', $app->job_application_id) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat lamaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-2 text-red-500 hover:text-red-700 font-bold px-4 py-2.5 rounded-xl border border-red-100 hover:bg-red-50 transition whitespace-nowrap">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
