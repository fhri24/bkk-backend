@extends('layouts.admin')

@section('title', 'Detail Kisah Sukses - ' . $alumniStory->name)

@section('content')
    <div class="p-6">

        {{-- ── Back + Header ── --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin.alumni-stories.index') }}"
                class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-[#001f3f]">Detail Kisah Sukses</h1>
                <p class="text-slate-400 text-xs mt-0.5">Dikirim
                    {{ $alumniStory->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            {{-- ── Card Profil Alumni ── --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">

                    {{-- Avatar / Foto --}}
                    @php
                        $photoUrl = null;
                        if ($alumniStory->photo) {
                            $photoUrl = Storage::disk('public')->url($alumniStory->photo);
                        } elseif ($alumniStory->student && $alumniStory->student->profile_picture) {
                            $photoUrl = \Illuminate\Support\Facades\Storage::url(
                                $alumniStory->student->profile_picture,
                            );
                        }
                    @endphp

                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}"
                            class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-blue-50 shadow"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 items-center justify-center text-white font-extrabold text-3xl mx-auto mb-4 shadow"
                            style="display:none;">
                            {{ $alumniStory->initials }}
                        </div>
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-extrabold text-3xl mx-auto mb-4 shadow">
                            {{ $alumniStory->initials }}
                        </div>
                    @endif

                    <h2 class="text-lg font-extrabold text-slate-800">{{ $alumniStory->name }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $alumniStory->job_title }}</p>

                    {{-- Status Badge --}}
                    <div class="mt-4">
                        {!! $alumniStory->status_badge !!}
                    </div>

                    {{-- Aksi --}}
                    <div class="mt-6 space-y-2">
                        @if ($alumniStory->status !== 'approved')
                            <form action="{{ route('admin.alumni-stories.approve', $alumniStory) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold text-sm transition flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Setujui & Tampilkan
                                </button>
                            </form>
                        @endif

                        @if ($alumniStory->status !== 'rejected')
                            <form action="{{ route('admin.alumni-stories.reject', $alumniStory) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-bold text-sm transition flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.alumni-stories.destroy', $alumniStory) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus kisah sukses dari {{ addslashes($alumniStory->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold text-sm transition flex items-center justify-center gap-2">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Info Waktu --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mt-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Informasi</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Dikirim</span>
                            <span
                                class="font-semibold text-slate-700">{{ $alumniStory->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Pukul</span>
                            <span class="font-semibold text-slate-700">{{ $alumniStory->created_at->format('H:i') }}
                                WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Panjang cerita</span>
                            <span class="font-semibold text-slate-700">{{ strlen($alumniStory->story) }} karakter</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Card Cerita ── --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 h-full">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-6 bg-gradient-to-b from-blue-600 to-blue-400 rounded-full"></div>
                        <h3 class="font-extrabold text-slate-800">Cerita Singkat</h3>
                    </div>

                    {{-- Quote decoration --}}
                    <p class="text-slate-600 leading-relaxed text-[15px] whitespace-pre-line">{{ $alumniStory->story }}</p>
                </div>
            </div>

        </div>

    </div>
@endsection

