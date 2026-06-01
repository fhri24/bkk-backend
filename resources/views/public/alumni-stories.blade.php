@extends('layouts.app')

@section('title', 'Kisah Alumni - BKK SMKN 1 Garut')

@section('content')
    <div class="relative bg-[#001f3f] overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1516251193007-45ef944ab0c6?auto=format&fit=crop&w=1600&q=80"
                class="w-full h-full object-cover opacity-30"
                alt="Kisah Alumni"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-[#001f3f]/85 via-[#001f3f]/70 to-[#001f3f]/95"></div>
        </div>

        <div class="relative z-10 text-center py-20 px-6">
            <span class="inline-block bg-blue-500/20 text-blue-200 text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full border border-blue-400/30 mb-5">
                Kisah Alumni
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                Semua Kisah Sukses Alumni
            </h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg leading-relaxed">
                Temukan pengalaman inspiratif para alumni yang telah menapaki perjalanan karir dan meraih kesuksesan.
            </p>
        </div>
    </div>

    <div class="page-transition bg-slate-100 min-h-screen">
        <div class="container mx-auto px-6 py-16">
            @if($stories->isEmpty())
                <div class="text-center py-20">
                    <p class="text-slate-500 text-lg">Belum ada kisah alumni yang dipublikasikan.</p>
                </div>
            @else
                <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($stories as $story)
                        @php
                            $avatarUrl = null;
                            if ($story->student && $story->student->profile_picture) {
                                $avatarUrl = Storage::disk('public')->url($story->student->profile_picture);
                            } elseif ($story->photo) {
                                $avatarUrl = Storage::disk('public')->url($story->photo);
                            }
                        @endphp

                        <article class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 transition hover:-translate-y-1 hover:shadow-md">
                            <div class="flex items-start gap-4 mb-5">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $story->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-slate-100" />
                                @else
                                    <div class="w-16 h-16 rounded-full bg-blue-500 text-white font-bold text-xl flex items-center justify-center">
                                        {{ $story->initials }}
                                    </div>
                                @endif

                                <div>
                                    <h2 class="text-xl font-bold text-slate-900">{{ $story->name }}</h2>
                                    <p class="text-sm text-slate-500">{{ $story->job_title }}</p>
                                </div>
                            </div>

                            <div class="mb-6 text-slate-600 leading-relaxed line-clamp-6">
                                {!! nl2br(e(Str::limit($story->story, 340))) !!}
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs uppercase tracking-wider text-slate-400">{{ $story->created_at->translatedFormat('d M Y') }}</span>
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    Inspirasi Karir
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $stories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('extra_js')
@endsection