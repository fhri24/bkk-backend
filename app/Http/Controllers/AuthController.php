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
use Illuminate\Support\Facades\DB; // WAJIB TAMBAHKAN INI

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
        $years = GraduationYear::orderBy('year', 'desc')->get();

        return view('auth.register', compact('majors', 'years'));
    }

    /**
     * Proses Registrasi dengan Database Transaction & Validasi NIS Unik
     */
    public function register(Request $request)
    {
        // 1. Validasi Input termasuk NIS unik di tabel students
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nis' => 'nullable|string|max:20|unique:students,nis', 
            'major' => 'required|string|max:100', 
            'graduation_year' => 'required|integer', 
            'gender' => 'nullable|string|in:L,P',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'nis.unique' => 'NIS ini sudah terdaftar, silakan gunakan NIS lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'graduation_year.required' => 'Tahun lulus wajib diisi.',
        ]);

        // 2. Gunakan Transaction agar jika salah satu gagal, semua dibatalkan
        return DB::transaction(function () use ($validated) {
            
            // Ambil Role 'siswa'
            $siswas_role = Role::where('name', 'siswa')->first();

            if (!$siswas_role) {
                // Ini akan memicu rollback otomatis jika error
                throw new \Exception('Role siswa tidak ditemukan di database.');
            }

            // Simpan User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $siswas_role->id,
                'is_active' => true,
            ]);

            // Logika Alumni
            $currentYear = date('Y');
            $alumniFlag = ($validated['graduation_year'] <= $currentYear);

            // Simpan Profil Student
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

            // Auto login setelah sukses
            Auth::login($user);

            return $this->redirectUserByRole($user)->with('success', 'Registrasi berhasil!');
        });
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

            return $this->redirectUserByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->onlyInput('email');
    }


    private function redirectUserByRole($user)
    {
        $adminRoles = ['super_admin', 'admin_bkk', 'kepala_bkk', 'perusahaan'];
        

        $roleName = $user->role->name ?? '';

        if (in_array($roleName, $adminRoles)) {
            
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($roleName === 'siswa') {

            return redirect()->intended(route('student.home'));
        }

        return redirect('/');
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
