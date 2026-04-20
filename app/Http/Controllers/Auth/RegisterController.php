<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegistered;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        // ambil role dari table roles
        $role = Role::where('name', $request->role)->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id
        ]);

        // 🔔 kirim notif ke admin & super admin
        $admins = User::whereHas('role', function($q){
            $q->whereIn('name', ['super_admin', 'admin_bkk']);
        })->get();

        Notification::send($admins, new UserRegistered($user));

        return response()->json([
            'message' => 'User berhasil daftar',
            'data' => $user
        ]);
    }
}
