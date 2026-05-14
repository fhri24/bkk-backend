@extends('layouts.admin')

@section('title', 'Reset Password - ' . $user->name)
@section('page_title', 'Reset Password Akun Perusahaan')

@section('content')
    <div class="max-w-lg">

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex gap-3 text-sm text-yellow-700">
            <i class="fas fa-exclamation-triangle mt-0.5 shrink-0"></i>
            <p>Anda akan mereset password untuk akun <strong>{{ $user->name }}</strong> ({{ $user->email }}). Pastikan
                Anda sudah memberi tahu password baru kepada perusahaan.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.company-accounts.reset-password.update', $user->id) }}" method="POST"
            class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru <span
                        class="text-red-500">*</span></label>
                <input type="password" name="password" placeholder="Minimal 8 karakter" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password <span
                        class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2">
                    <i class="fas fa-key"></i> Reset Password
                </button>
                <a href="{{ route('admin.company-accounts.index') }}"
                    class="border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
