@extends('layouts.admin')

@section('title', 'Log Aktivitas - Admin BKK')
@section('page_title', 'Log Aktivitas')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fas fa-history text-red-600 mr-3"></i> Log Aktivitas
        </h3>
        <p class="text-sm text-slate-500 mt-2">Catatan login dan perubahan data yang dilakukan pengguna.</p>
    </div>
    <div class="table-custom">
        <table class="w-full">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $log->user->email ?? 'Guest' }}</td>
                        <td>{{ $log->user->role->display_name ?? '-' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-500 py-8">
                            <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                            Belum ada log aktivitas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-200">
        {{ $logs->links() }}
    </div>
</div>
@endsection