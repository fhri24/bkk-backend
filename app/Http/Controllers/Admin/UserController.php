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
            'user_id'    => Auth::id(),
            'action'     => sprintf('Mengubah status akun %s menjadi %s', $user->email, $user->is_active ? 'aktif' : 'non-aktif'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata'   => ['target_user_id' => $user->id],
        ]);

        return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    /**
     * Bulk action: aktifkan / non-aktifkan / hapus (soft delete) beberapa user sekaligus
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'   => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $currentUserId = Auth::id();
        $ids = $request->input('user_ids');

        // Cegah admin menghapus/menonaktifkan dirinya sendiri
        if (in_array($currentUserId, $ids)) {
            return redirect()->back()->with('error', 'Kamu tidak bisa melakukan aksi ini pada akun sendiri.');
        }

        $users = User::whereIn('id', $ids)->get();

        match ($request->input('action')) {
            'activate' => $this->bulkActivate($users, $request),
            'deactivate' => $this->bulkDeactivate($users, $request),
            'delete' => $this->bulkDelete($users, $request),
        };

        $count   = $users->count();
        $label   = match ($request->input('action')) {
            'activate'   => "{$count} pengguna berhasil diaktifkan.",
            'deactivate' => "{$count} pengguna berhasil dinonaktifkan.",
            'delete'     => "{$count} pengguna berhasil dihapus.",
        };

        return redirect()->back()->with('success', $label);
    }

    private function bulkActivate($users, Request $request): void
    {
        foreach ($users as $user) {
            $user->update(['is_active' => true]);
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => "Mengaktifkan akun {$user->email} (bulk action)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata'   => ['target_user_id' => $user->id],
            ]);
        }
    }

    private function bulkDeactivate($users, Request $request): void
    {
        foreach ($users as $user) {
            $user->update(['is_active' => false]);
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => "Menonaktifkan akun {$user->email} (bulk action)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata'   => ['target_user_id' => $user->id],
            ]);
        }
    }

    private function bulkDelete($users, Request $request): void
    {
        foreach ($users as $user) {
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => "Menghapus akun {$user->email} (bulk action / soft delete)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata'   => ['target_user_id' => $user->id],
            ]);
            $user->delete(); // SoftDelete
        }
    }
}
