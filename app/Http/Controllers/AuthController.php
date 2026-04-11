<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index() 
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nis' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:100',
            'graduation_year' => 'nullable|integer|min:1995|max:2100',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login di akun Anda.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.confirmed' => 'Kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        // Cari role 'siswa'
        $siswas_role = \App\Models\Role::where('name', 'siswa')->first();
        
        // Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $siswas_role->id,
            'is_active' => true,
        ]);

        // Create Student Profile
       
Student::create([
    'user_id' => $user->id,
    'nis' => $validated['nis'] ?? null,
    'full_name' => $validated['name'],
    'major' => $validated['major'] ?? null,
    'graduation_year' => $validated['graduation_year'] ?? 2024,
    'gender' => $validated['gender'] ?? 'L', // Tambahkan ini (Contoh default 'L')
    'status' => 'active',
    'alumni_flag' => false,
]);

        // Auto login after register
        Auth::login($user);
        
        return redirect()->route('student.home')->with('success', 'Registrasi berhasil!');
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

            // Redirect ke admin jika admin
            if (in_array($user->role->name, $adminRoles)) {
                return redirect()->route('admin.dashboard');
            }
            
            // Redirect ke student jika siswa
            if ($user->role->name === 'siswa') {
                return redirect()->route('student.home');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah!']);
    }

    public function logout(Request $request) 
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Logout',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}