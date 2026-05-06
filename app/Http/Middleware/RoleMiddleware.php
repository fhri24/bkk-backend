<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Menggunakan variadic agar bisa menerima banyak argumen dari koma
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role->name ?? '';

        // Inisialisasi daftar role yang diizinkan
        $allowedRoles = [];

        // Gabungkan semua role yang dikirim (baik yang dipisah koma di route maupun jika ada yang pakai '|')
        foreach ($roles as $roleItem) {
            // Tetap support jika ada yang iseng pakai pemisah '|'
            $exploded = explode('|', $roleItem);
            $allowedRoles = array_merge($allowedRoles, $exploded);
        }

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