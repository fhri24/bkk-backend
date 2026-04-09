@extends('layouts.app')

@section('title', 'BKK System - Job Portal')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold mb-4">Pusat Informasi Lowongan Kerja</h2>
        <p class="text-lg text-gray-600">Platform terpadu untuk menghubungkan alumni dan siswa dengan perusahaan</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-blue-50 p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-2">📋 Lowongan Kerja</h3>
            <p class="text-gray-600">Temukan ribuan lowongan kerja dari berbagai perusahaan</p>
        </div>
        <div class="bg-green-50 p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-2">👥 Alumni Network</h3>
            <p class="text-gray-600">Terhubung dengan alumnus dan perusahaan terpercaya</p>
        </div>
        <div class="bg-purple-50 p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-2">🎯 Career Development</h3>
            <p class="text-gray-600">Kembangkan karir Anda bersama kami</p>
        </div>
    </div>

    <div class="text-center">
        @auth
            @if(auth()->user()->role->name === 'siswa')
                <a href="{{ route('student.home') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 inline-block">
                    Lihat Lowongan Kerja
                </a>
            @endif
        @else
            <p class="text-lg text-gray-600 mb-6">Silakan login atau daftar untuk memulai</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700">
                    Daftar Siswa
                </a>
            </div>
        @endauth
    </div>
</div>
@endsection
