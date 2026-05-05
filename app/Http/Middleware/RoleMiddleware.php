<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role->name ?? '';

        // Support multiple role dipisah | contoh: role:alumni|publik|siswa
        $allowedRoles = explode('|', $role);

        // Tambahkan alias untuk group role
        if (in_array('any_admin', $allowedRoles)) {
            $allowedRoles = array_merge($allowedRoles, ['super_admin', 'admin_bkk', 'kepala_bkk', 'kepala_sekolah']);
        }
        if (in_array('any_user', $allowedRoles)) {
            $allowedRoles = array_merge($allowedRoles, ['siswa', 'alumni', 'publik']);
        }

        // Cek apakah role user ada di daftar yang diizinkan
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}