<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user     = auth()->user()->load('role');
            $userRole = $user->role?->name;

            $allowedRoles = ['siswa', 'alumni', 'publik'];

            if (!in_array($userRole, $allowedRoles)) {
                // Kalau admin nyasar ke area user, redirect ke admin dashboard
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
