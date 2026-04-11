<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => sprintf('Memperbarui hak akses role %s', $role->display_name),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['role_id' => $role->id, 'permissions' => $permissionIds],
        ]);

        return redirect()->back()->with('success', 'Hak akses role berhasil disimpan.');
    }
}
