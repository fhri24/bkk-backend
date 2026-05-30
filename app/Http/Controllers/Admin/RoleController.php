<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'menus'])->get();
        $permissions = Permission::all();
        $menus = Menu::orderBy('order')->get()->unique('name')->values();

        return view('admin.roles.index', compact('roles', 'permissions', 'menus'));
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

    public function updateMenus(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $menuIds = $request->input('menus', []);
        $role->menus()->sync($menuIds);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => sprintf('Memperbarui akses menu role %s', $role->display_name),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['role_id' => $role->id, 'menus' => $menuIds],
        ]);

        return redirect()->back()->with('success', 'Menu role '.$role->display_name.' berhasil disimpan.');
    }
}
