<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Student;
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

        $majors = Major::orderBy('name', 'asc')->get();
        $years  = GraduationYear::orderBy('year', 'desc')->get();
        return view('auth.login', compact('majors', 'years'));
    }

    public function showRegister()
    {
        $majors = Major::orderBy('name', 'asc')->get();
        $years  = GraduationYear::orderBy('year', 'desc')->get();
        return view('auth.register', compact('majors', 'years'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|confirmed',
            'nisn'          => 'required|string|max:20|unique:students,nis',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_lulus'   => 'required|digits:4|integer',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'foto_profile'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jurusan'       => 'required|string|max:100',
        ], [
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
            'jurusan.required'       => 'Jurusan wajib diisi.',
            'foto_profile.image'     => 'File harus berupa gambar.',
            'foto_profile.max'       => 'Ukuran foto maksimal 2MB.',
        ]);

        return DB::transaction(function () use ($request) {

            $fotoPath = null;
            if ($request->hasFile('foto_profile')) {
                $fotoPath = $request->file('foto_profile')
                    ->store('foto_profile', 'public');
            }

            $role = Role::where('name', 'alumni')->first();
            if (!$role) {
                throw new \Exception('Role alumni tidak ditemukan di database.');
            }

            $user = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role_id'       => $role->id,
                'userable_id'   => 0,
                'userable_type' => 'App\Models\User',
                'is_active'     => true,
            ]);

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

            $user->update([
                'userable_id'   => $profil->getKey(),
                'userable_type' => get_class($profil),
            ]);

            Auth::login($user);
            return $this->redirectUserByRole($user);
        });
    }

    public function login(Request $request)
    {
        $request->validate([
            'nis'             => ['required', 'string', 'max:50'],
            'graduation_year' => ['nullable', 'digits:4', 'integer'],
            'major'           => ['nullable', 'string', 'max:100'],
            'password'        => ['required'],
        ], [
            'nis.required'           => 'NISN wajib diisi.',
            'graduation_year.digits' => 'Tahun lulus harus 4 digit.',
            'password.required'      => 'Kata sandi wajib diisi.',
        ]);

        // Normalisasi input NISN — hapus spasi, pastikan string
        $nisnInput = trim($request->nis);

        $user = User::where(function ($query) use ($request, $nisnInput) {

            // Login Alumni: cari via relasi student dengan NISN
            $query->where(function ($q) use ($request, $nisnInput) {
                $q->whereHas('role', fn($r) => $r->where('name', 'alumni'))
                    ->whereHas('student', function ($s) use ($request, $nisnInput) {
                        // Cari dengan NISN persis
                        $s->where('nisn', $nisnInput);

                        if ($request->filled('graduation_year')) {
                            $s->where('graduation_year', $request->graduation_year);
                        }
                        if ($request->filled('major')) {
                            $s->where('major', $request->major);
                        }
                    });
            })

                // Login Admin & Perusahaan: pakai email
                ->orWhere(function ($q) use ($nisnInput) {
                    $q->whereHas('role', fn($r) => $r->whereIn('name', [
                        'super_admin',
                        'admin_bkk',
                        'kepala_bkk',
                        'kepala_sekolah',
                        'perusahaan',        // ← tambah ini
                    ]))
                        ->where('email', $nisnInput);
                });
        })->first();

        if ($user && Hash::check($request->password, $user->password)) {

            if (!$user->is_active) {
                return back()->withErrors([
                    'nis' => 'Akun Anda telah dinonaktifkan. Hubungi admin.',
                ])->onlyInput('nis');
            }

            Auth::login($user);
            $request->session()->regenerate();

            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $this->redirectUserByRole(Auth::user());
        }

        return back()->withErrors([
            'nis' => 'NISN, tahun lulus, jurusan, atau password salah.',
        ])->onlyInput('nis');
    }

    private function redirectUserByRole($user)
    {
        $roleName = $user->role->name ?? '';

        return match (true) {
            in_array($roleName, ['super_admin', 'admin_bkk', 'kepala_bkk', 'kepala_sekolah'])
            => redirect()->intended(route('admin.dashboard')),
            $roleName === 'alumni'
            => redirect()->intended(route('alumni.home')),
            $roleName === 'publik'
            => redirect()->intended(route('publik.home')),
            $roleName === 'siswa'
            => redirect()->intended(route('student.home')),
            $roleName === 'perusahaan'
            => redirect()->intended(route('company.dashboard')),
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
