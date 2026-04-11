<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->is_active = $request->boolean('is_active');
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => sprintf('Mengubah status akun %s menjadi %s', $user->email, $user->is_active ? 'aktif' : 'non-aktif'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['target_user_id' => $user->id],
        ]);

        return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
    }
}
