<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Publik;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegistered;

class RegisterController extends Controller
{
    public function showForm()
    {
        $majors = \App\Models\Major::all();
        $years  = \App\Models\GraduationYear::orderBy('year', 'desc')->get();
        return view('auth.register', compact('majors', 'years'));
    }

    public function register(Request $request)
{
    // Validasi umum
    $request->validate([
            'role'          => 'required|in:siswa,alumni,publik',
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email',
        'password'      => 'required|min:6|confirmed',
            'nisn'          => 'required|string|max:20',
        'nama_lengkap'  => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'tempat_lahir'  => 'required|string|max:100',
        'tanggal_lahir' => 'required|date',
        'tahun_lulus'   => 'required|digits:4|integer',  // ← pastikan nama ini
        'no_hp'         => 'required|string|max:20',
        'alamat'        => 'required|string',
        'foto_profile'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        // Pesan error custom — supaya tidak muncul pesan bahasa Inggris
            'role.required'          => 'Silakan pilih daftar sebagai Siswa, Alumni, atau Publik.',
        'name.required'          => 'Nama pengguna wajib diisi.',
        'email.required'         => 'Email wajib diisi.',
        'email.unique'           => 'Email sudah terdaftar.',
        'password.required'      => 'Kata sandi wajib diisi.',
        'password.min'           => 'Kata sandi minimal 6 karakter.',
        'password.confirmed'     => 'Konfirmasi kata sandi tidak cocok.',
        'nisn.required'          => 'NISN wajib diisi.',
        'nisn.unique'            => 'NISN sudah terdaftar.',
        'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tahun_lulus.required'   => 'Tahun lulus wajib diisi.',
        'tahun_lulus.digits'     => 'Tahun lulus harus 4 digit.',
        'no_hp.required'         => 'Nomor HP wajib diisi.',
        'alamat.required'        => 'Alamat wajib diisi.',
        'foto_profile.image'     => 'File harus berupa gambar.',
        'foto_profile.max'       => 'Ukuran foto maksimal 2MB.',
    ]);

    // Validasi jurusan HANYA untuk alumni
    if ($request->role === 'alumni') {
        $request->validate([
            'jurusan' => 'required|string|max:100',
        ], [
            'jurusan.required' => 'Jurusan wajib diisi untuk alumni.',
        ]);
    }

        // Validasi Unique NISN terpisah agar tidak menyebabkan crash jika tabel belum ada
        if ($request->role === 'publik') {
            $request->validate(['nisn' => 'unique:publik,nisn'], ['nisn.unique' => 'NISN sudah terdaftar.']);
        } else {
            $request->validate(['nisn' => 'unique:students,nis'], ['nisn.unique' => 'NISN sudah terdaftar.']);
        }

        // ===== UPLOAD FOTO =====
        $fotoPath = null;
        if ($request->hasFile('foto_profile')) {
            $fotoPath = $request->file('foto_profile')
                ->store('foto_profile', 'public');
        }

        // ===== AMBIL ROLE DARI DB =====
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return back()->withErrors(['role' => 'Role "' . $request->role . '" belum tersedia di database. Hubungi admin.'])->withInput();
        }

        // ===== BUAT USER + HUBUNGKAN POLYMORPHIC =====
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role_id'       => $role->id,
            'userable_id'   => 0, // Placeholder sementara
            'userable_type' => 'App\Models\User',
        ]);

        // ===== BUAT PROFIL SESUAI ROLE =====
        if ($request->role === 'publik') {
            $profil = Publik::create([
                'nisn'          => $request->nisn,
                'nama_lengkap'  => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tahun_lulus'   => $request->tahun_lulus,
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
                'foto_profile'  => $fotoPath,
            ]);

            $user->update([
                'userable_id'   => $profil->getKey(),
                'userable_type' => get_class($profil),
            ]);
        } else {
            // Role 'siswa' atau 'alumni' (Dua-duanya masuk ke tabel students)
            $profil = Student::create([
                'user_id'         => $user->id,
                'nis'             => $request->nisn,
                'full_name'       => $request->nama_lengkap,
                'gender'          => $request->jenis_kelamin,
                'birth_info'      => $request->tempat_lahir . ', ' . $request->tanggal_lahir,
                'major'           => $request->jurusan ?? '-', // "-" jika siswa tidak mengisi jurusan
                'graduation_year' => $request->tahun_lulus,
                'phone'           => $request->no_hp,
                'address'         => $request->alamat,
                'profile_picture' => $fotoPath,
                'alumni_flag'     => ($request->role === 'alumni') ? true : false,
                'status'          => 'active',
            ]);

            $user->update([
                'userable_id'   => $profil->getKey(),
                'userable_type' => get_class($profil),
            ]);
        }

        // ===== KIRIM NOTIF KE ADMIN (sama persis seperti sebelumnya) =====
        $admins = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['super_admin', 'admin_bkk']);
        })->get();

        Notification::send($admins, new UserRegistered($user));

        // ===== RESPONSE =====
        // Kalau pakai web (blade), pakai redirect
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'User berhasil daftar',
                'data'    => $user->load('role', 'userable'),
            ]);
        }

        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }
}