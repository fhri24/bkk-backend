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

class AuthController extends Controller
{
    public function index()
    {
        // Jika sudah login, langsung lempar ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Menampilkan halaman registrasi dengan data jurusan dan tahun dari database
     */
    public function showRegister()
    {
        
        $majors = Major::orderBy('name', 'asc')->get();
        $years = GraduationYear::orderBy('year', 'desc')->get();

        return view('auth.register', compact('majors', 'years'));
    }

    /**
     * Proses Registrasi Siswa/Alumni
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nis' => 'nullable|string|max:20',
            'major' => 'required|string|max:100', 
            'graduation_year' => 'required|integer', 
            'gender' => 'nullable|string|in:L,P',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'graduation_year.required' => 'Tahun lulus wajib diisi untuk pendataan alumni.',
        ]);

        // 1. Ambil Role 'siswa' (Pastikan di database nama role-nya adalah 'siswa')
        $siswas_role = Role::where('name', 'siswa')->first();

        if (!$siswas_role) {
            return back()->with('error', 'Role siswa tidak ditemukan di database.');
        }

        // 2. Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $siswas_role->id,
            'is_active' => true,
        ]);

        // 3. LOGIKA OTOMATIS ALUMNI

        $currentYear = date('Y');
        $alumniFlag = ($validated['graduation_year'] <= $currentYear);

        // 4. Create Student Profile
        Student::create([
            'user_id' => $user->id,
            'nis' => $validated['nis'] ?? null,
            'full_name' => $validated['name'],
            'major' => $validated['major'],
            'graduation_year' => $validated['graduation_year'],
            'gender' => $validated['gender'] ?? 'L',
            'status' => 'active',
            'alumni_flag' => $alumniFlag, 
        ]);

        // Auto login
        Auth::login($user);

        // Menggunakan redirectUserByRole agar konsisten
        return $this->redirectUserByRole($user)->with('success', 'Registrasi berhasil!');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log aktivitas login
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $this->redirectUserByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }

    /**
     * Helper function untuk mengalihkan user berdasarkan role
     * PERBAIKAN: Menggunakan Named Routes (route()) alih-alih path manual
     */
    private function redirectUserByRole($user)
    {
        $adminRoles = ['super_admin', 'admin_bkk', 'kepala_bkk', 'perusahaan'];
        
        // Memuat relasi role jika belum ada
        $roleName = $user->role->name ?? '';

        if (in_array($roleName, $adminRoles)) {
            // Mengarah ke route('admin.dashboard') sesuai web.php
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($roleName === 'siswa') {
            // Mengarah ke route('student.home') sesuai web.php
            return redirect()->intended(route('student.home'));
        }

        return redirect('/');
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Logout',
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
