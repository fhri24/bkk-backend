<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Student;
use App\Models\Publik;
use App\Models\Role;
use App\Models\Major;
use App\Models\GraduationYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        $majors = Major::orderBy('name', 'asc')->get();
        $years  = GraduationYear::orderBy('year', 'desc')->get();
        return view('auth.register', compact('majors', 'years'));
    }

    public function register(Request $request)
    {
        // ===== VALIDASI UMUM =====
        $request->validate([
            'role'          => 'required|in:alumni,publik',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|confirmed',
            'nisn'          => 'required|string|max:20',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_lulus'   => 'required|digits:4|integer',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'foto_profile'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'role.required'          => 'Silakan pilih daftar sebagai Alumni atau Publik.',
            'role.in'                => 'Pilihan role tidak valid.',
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

        // ===== VALIDASI JURUSAN khusus alumni =====
        if ($request->role === 'alumni') {
            $request->validate([
                'jurusan' => 'required|string|max:100',
                'nisn'    => 'unique:students,nis',
            ], [
                'jurusan.required' => 'Jurusan wajib diisi untuk alumni.',
                'nisn.unique'      => 'NISN sudah terdaftar.',
            ]);
        } else {
            $request->validate([
                'nisn' => 'unique:publik,nisn',
            ], [
                'nisn.unique' => 'NISN sudah terdaftar.',
            ]);
        }

        return DB::transaction(function () use ($request) {

            // ===== UPLOAD FOTO =====
            $fotoPath = null;
            if ($request->hasFile('foto_profile')) {
                $fotoPath = $request->file('foto_profile')
                    ->store('foto_profile', 'public');
            }

            // ===== AMBIL ROLE =====
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                throw new \Exception('Role ' . $request->role . ' tidak ditemukan di database.');
            }

            // ===== BUAT USER DULU =====
            $user = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role_id'       => $role->id,
                'userable_id'   => 0, // Placeholder
                'userable_type' => 'App\Models\User', // Placeholder
                'is_active'     => true,
            ]);

            // ===== BUAT PROFIL SESUAI ROLE =====
            if ($request->role === 'alumni') {
                $profil = Student::create([
                    'user_id'         => $user->id,
                    'nis'             => $request->nisn,
                    'full_name'       => $request->nama_lengkap,
                    'gender'          => $request->jenis_kelamin,
                    'birth_info'      => $request->tempat_lahir . ', ' . $request->tanggal_lahir,
                    'major'           => $request->jurusan,
                    'graduation_year' => $request->tahun_lulus,
                    'phone'           => $request->no_hp,
                    'address'         => $request->alamat,
                    'profile_picture' => $fotoPath,
                    'alumni_flag'     => true,
                    'status'          => 'active',
                ]);
            } else {
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
            }

            // ===== BUAT USER + POLYMORPHIC =====
            $user->update([
                'userable_id'   => $profil->getKey(),
                'userable_type' => get_class($profil),
            ]);

            // ===== LOGIN =====
            Auth::login($user);

            // ===== REDIRECT SESUAI ROLE =====
            return $this->redirectUserByRole($user);
        });
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Cek apakah akun aktif
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.',
                ])->onlyInput('email');
            }

            // Catat activity log
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $this->redirectUserByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    // ===== REDIRECT SESUAI ROLE =====
    private function redirectUserByRole($user)
    {
        $roleName = $user->role->name ?? '';

        return match(true) {
            in_array($roleName, ['super_admin', 'admin_bkk', 'kepala_bkk', 'kepala_sekolah'])
                => redirect()->intended(route('admin.dashboard')),

            $roleName === 'alumni'
                => redirect()->intended(route('alumni.home')),

            $roleName === 'publik'
                => redirect()->intended(route('publik.home')),

            $roleName === 'siswa'
                => redirect()->intended(route('student.home')),

            $roleName === 'perusahaan'
                => redirect()->intended(route('admin.dashboard')),

            default => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}