<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use App\Models\Major; // Import model Major
use App\Models\GraduationYear; // Import model GraduationYear
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Menampilkan halaman registrasi dengan data jurusan dan tahun dari database
     */
    public function showRegister()
    {
        // Ambil data jurusan (A-Z) dan tahun lulus (Terbaru ke Lama) dari database
        $majors = Major::orderBy('name', 'asc')->get();
        $years = GraduationYear::orderBy('year', 'desc')->get();

        return view('auth.register', compact('majors', 'years'));
    }

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

        // 1. Ambil Role 'siswa'
        $siswas_role = Role::where('name', 'siswa')->first();

        // 2. Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $siswas_role->id,
            'is_active' => true,
        ]);

        // 3. LOGIKA OTOMATIS ALUMNI
        // Jika tahun lulus <= tahun sekarang, maka dia adalah ALUMNI
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

        return redirect()->route('student.home')->with('success', 'Registrasi berhasil! Data Anda telah masuk sistem.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $user = Auth::user();
            $adminRoles = ['super_admin', 'admin_bkk', 'kepala_bkk', 'perusahaan'];

            if (in_array($user->role->name, $adminRoles)) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role->name === 'siswa') {
                return redirect()->route('student.home');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah!']);
    }

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
