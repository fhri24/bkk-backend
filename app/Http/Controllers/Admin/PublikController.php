<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publik;
use Illuminate\Support\Facades\Storage;

class PublikController extends Controller
{
    /**
     * Dashboard Kelola Publik
     */
    public function index()
    {
        $dataPublik = Publik::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.publik.index', compact('dataPublik'));
    }

    /**
     * Hapus Data Publik
     */
    public function destroy($id)
    {
        $publik = Publik::findOrFail($id);

        if ($publik->foto_profile) {
            Storage::disk('public')->delete($publik->foto_profile);
        }

        if ($publik->user) {
            $publik->user->delete();
        }

        $publik->delete();

        return redirect()
            ->route('admin.publik.index')
            ->with('success', 'Data dihapus.');
    }
}