<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        return SuperAdmin::with('user')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_lengkap' => 'required',
            'kontak' => 'nullable'
        ]);

        return SuperAdmin::create($data);
    }

    public function show($id)
    {
        return SuperAdmin::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $superAdmin = SuperAdmin::findOrFail($id);

        $superAdmin->update($request->all());

        return $superAdmin;
    }

    public function destroy($id)
    {
        SuperAdmin::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}