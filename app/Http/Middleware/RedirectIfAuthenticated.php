<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $adminRoles = ['super_admin', 'admin_bkk', 'kepala_bkk', 'perusahaan'];

            if (in_array($user->role->name, $adminRoles)) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role->name === 'siswa') {
                return redirect()->route('student.home');
            }
        }

        return $next($request);
    }
}
