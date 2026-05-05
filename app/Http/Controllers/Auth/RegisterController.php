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
    /**
     * Menampilkan form registrasi (jika menggunakan Blade)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses registrasi user baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required',
            'phone'    => 'required|string|unique:users', // Ditambahkan untuk OTP
            'nis'      => 'nullable|string|unique:users', // Ditambahkan untuk data Siswa
            'major'    => 'nullable|string',             // Ditambahkan untuk data Siswa
        ]);

        // 1. Ambil role dari table roles
        $role = Role::where('name', $request->role)->first();

        // 2. Format nomor HP ke 62 (agar Fonnte tidak error)
        $phone = $request->phone;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+62')) {
            $phone = substr($phone, 1);
        }

        // 3. Simpan User
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role_id'         => $role->id,
            'phone'           => $phone,
            'nis'             => $request->nis,
            'major'           => $request->major,
            'is_active'       => true,
            'gender'          => $request->gender ?? null,
            'graduation_year' => $request->graduation_year ?? null,
        ]);

        // 4. Berikan Role Spatie (sinkronisasi dengan trait HasRoles)
        $user->assignRole($role->name);

        // 🔔 5. Kirim notif ke admin & super admin
        $admins = User::whereHas('role', function($q){
            $q->whereIn('name', ['super_admin', 'admin_bkk']);
        })->get();

        // Pastikan UserRegistered Notification sudah kamu buat
        try {
            Notification::send($admins, new UserRegistered($user));
        } catch (\Exception $e) {
            // Lanjutkan proses jika notifikasi gagal (agar user tidak stuck)
        }

        return response()->json([
            'message' => 'User berhasil daftar',
            'data'    => $user
        ]);
    }
} 