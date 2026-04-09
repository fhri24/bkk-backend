<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $adminRoles = ['super_admin', 'admin_bkk', 'kepala_bkk', 'perusahaan'];

        if (!in_array($user->role->name, $adminRoles)) {
            abort(403, 'Unauthorized - Admin access required');
        }

        return $next($request);
    }
}
