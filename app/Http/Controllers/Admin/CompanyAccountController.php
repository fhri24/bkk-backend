<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyAccountController extends Controller
{
    /**
     * Daftar semua akun perusahaan
     */
    public function index()
    {
        $accounts = User::with('company')
            ->whereHas('role', fn($q) => $q->where('name', 'perusahaan'))
            ->latest()
            ->get();

        return view('admin.company-accounts.index', compact('accounts'));
    }

    /**
     * Form tambah akun perusahaan
     */
    public function create()
    {
        $companies = Company::orderBy('company_name')->get();
        return view('admin.company-accounts.create', compact('companies'));
    }

    /**
     * Simpan akun perusahaan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,company_id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
        ], [
            'email.unique'     => 'Email ini sudah digunakan oleh akun lain.',
            'password.min'     => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $role = Role::where('name', 'perusahaan')->firstOrFail();

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role_id'    => $role->id,
            'company_id' => $request->company_id,
            'is_active'  => true,
        ]);

        return redirect()->route('admin.company-accounts.index')
            ->with('success', "Akun perusahaan untuk \"{$request->name}\" berhasil dibuat.");
    }

    /**
     * Toggle aktif / nonaktif akun
     */
    public function toggle(User $user)
    {
        // Pastikan hanya akun perusahaan yang bisa di-toggle
        if ($user->role?->name !== 'perusahaan') {
            abort(403);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun \"{$user->name}\" berhasil {$status}.");
    }

    /**
     * Halaman reset password
     */
    public function resetPasswordForm(User $user)
    {
        if ($user->role?->name !== 'perusahaan') abort(403);

        return view('admin.company-accounts.reset-password', compact('user'));
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request, User $user)
    {
        if ($user->role?->name !== 'perusahaan') abort(403);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.company-accounts.index')
            ->with('success', "Password akun \"{$user->name}\" berhasil direset.");
    }

    /**
     * Hapus akun perusahaan
     */
    public function destroy(User $user)
    {
        if ($user->role?->name !== 'perusahaan') abort(403);

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.company-accounts.index')
            ->with('success', "Akun \"{$name}\" berhasil dihapus.");
    }
}
