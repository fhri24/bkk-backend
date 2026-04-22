@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 md:px-6">
        
        {{-- Header Halaman --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-[#001f3f]">Lowongan Tersimpan</h2>
                <p class="text-gray-500 mt-1">Kelola daftar lowongan yang menarik perhatianmu di sini.</p>
            </div>
            <a href="{{ route('student.lowongan') }}" class="text-blue-600 hover:underline font-semibold flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Lowongan
            </a>
        </div>

        @if($saved_jobs->isEmpty())
            {{-- State Kosong --}}
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                <div class="w-20 h-20 bg-blue-50 text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bookmark text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Belum ada lowongan tersimpan</h3>
                <p class="text-gray-500 mb-6">Cari lowongan yang sesuai dengan keahlianmu dan simpan untuk dilihat nanti.</p>
                <a href="{{ route('student.lowongan') }}" class="bg-[#001f3f] text-white px-8 py-3 rounded-full font-bold hover:bg-blue-900 transition">
                    Jelajahi Lowongan
                </a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($saved_jobs as $saved)
                    {{-- Kartu Lowongan Tersimpan --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center group hover:shadow-md transition">
                        <div class="flex items-center space-x-4 w-full">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200">
                                @if($saved->job->company && $saved->job->company->logo)
                                    <img src="{{ asset('storage/' . $saved->job->company->logo) }}" class="object-cover w-full h-full" alt="Logo">
                                @else
                                    <i class="fas fa-building text-2xl text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $saved->job->title }}</h4>
                                <p class="text-gray-600 font-medium">{{ $saved->job->company->name ?? 'Perusahaan' }}</p>
                                <div class="flex items-center space-x-3 mt-2 text-sm text-gray-400">
                                    <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $saved->job->location }}</span>
                                    <span><i class="fas fa-clock mr-1"></i> Tersimpan {{ $saved->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 mt-4 md:mt-0 w-full md:w-auto justify-end">
                            <a href="{{ route('student.lowongan.show', $saved->job->id) }}" class="bg-blue-50 text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-100 transition whitespace-nowrap text-center">
                                Detail
                            </a>
                            
                            {{-- Form Hapus --}}
                            <form action="{{ route('student.lowongan.unsave', $saved->job->id) }}" method="POST" onsubmit="return confirm('Hapus dari simpanan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-red-500 hover:text-red-700 font-bold px-4 py-2 group whitespace-nowrap">
                                    <i class="fas fa-trash-alt mr-2 group-hover:animate-bounce"></i> Hapus
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