<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Publik;
use App\Models\Major;
use App\Models\GraduationYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserRegistered;

class RegisterController extends Controller
{
    /**
     * Menampilkan form registrasi dengan data Jurusan dan Tahun Lulus
     */
    public function showRegistrationForm()
    {
        $majors = Major::all();
        $years  = GraduationYear::orderBy('year', 'desc')->get();
        return view('auth.register', compact('majors', 'years'));
    }

    /**
     * Menangani proses registrasi user baru
     */
    public function register(Request $request)
    {
        // 1. Validasi Umum
        $request->validate([
            'role'          => 'required|in:siswa,alumni,publik',
            'name'          => 'required|string|max:255|unique:users,name',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'nisn'          => 'required|string|max:20',
            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_lulus'   => 'required|digits:4|integer',
            'phone'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'foto_profile'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'role.required'          => 'Silakan pilih daftar sebagai Siswa, Alumni, atau Publik.',
            'name.unique'            => 'Username sudah digunakan.',
            'email.unique'           => 'Email sudah terdaftar.',
            'password.min'           => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi kata sandi tidak cocok.',
            'nisn.required'          => 'NISN/NIK wajib diisi.',
            'foto_profile.max'       => 'Ukuran foto maksimal 2MB.',
        ]);

        // 2. Validasi Khusus Alumni (Jurusan)
        if ($request->role === 'alumni') {
            $request->validate([
                'jurusan' => 'required|string|max:100',
            ], [
                'jurusan.required' => 'Jurusan wajib diisi untuk alumni.',
            ]);
        }

        // 3. Cek Unique NISN berdasarkan tabel tujuan
        if ($request->role === 'publik') {
            $request->validate(['nisn' => 'unique:publik,nisn'], ['nisn.unique' => 'NIK/NISN sudah terdaftar.']);
        } else {
            $request->validate(['nisn' => 'unique:students,nis'], ['nisn.unique' => 'NIS sudah terdaftar.']);
        }

        // 4. Ambil Role dari Database
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return back()->withErrors(['role' => 'Role tidak ditemukan di database.'])->withInput();
        }

        // 5. Format nomor HP ke 62 (Fonnte/WhatsApp ready)
        $phone = $request->phone;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+62')) {
            $phone = substr($phone, 2);
        }

        // 6. Upload Foto Profile
        $fotoPath = null;
        if ($request->hasFile('foto_profile')) {
            $fotoPath = $request->file('foto_profile')->store('foto_profile', 'public');
        }

        // 7. Simpan User (Base Account)
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role_id'       => $role->id,
            'phone'         => $phone,
            'is_active'     => true,
            'userable_id'   => 0, // Placeholder
            'userable_type' => 'App\Models\User',
        ]);

        // 8. Berikan Role Spatie (HasRoles)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role->name);
        }

        // 9. Buat Profil Polymorphic
        if ($request->role === 'publik') {
            $profil = Publik::create([
                'nisn'          => $request->nisn,
                'nama_lengkap'  => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tahun_lulus'   => $request->tahun_lulus,
                'no_hp'         => $phone,
                'alamat'        => $request->alamat,
                'foto_profile'  => $fotoPath,
            ]);
        } else {
            $profil = Student::create([
                'user_id'         => $user->id,
                'nis'             => $request->nisn,
                'full_name'       => $request->nama_lengkap,
                'gender'          => $request->jenis_kelamin,
                'birth_info'      => $request->tempat_lahir . ', ' . $request->tanggal_lahir,
                'major'           => $request->jurusan ?? '-',
                'graduation_year' => $request->tahun_lulus,
                'phone'           => $phone,
                'address'         => $request->alamat,
                'profile_picture' => $fotoPath,
                'alumni_flag'     => ($request->role === 'alumni'),
                'status'          => 'active',
            ]);
        }

        // 10. Update Polymorphic Link
        $user->update([
            'userable_id'   => $profil->id,
            'userable_type' => get_class($profil),
        ]);

        // 11. Kirim Notifikasi ke Admin
        $admins = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['super_admin', 'admin_bkk']);
        })->get();

        try {
            Notification::send($admins, new UserRegistered($user));
        } catch (\Exception $e) {
            // Biarkan lanjut jika mail/notif belum siap
        }

        // 12. Response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Registrasi berhasil',
                'data'    => $user->load('role', 'userable'),
            ]);
        }

        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }
}