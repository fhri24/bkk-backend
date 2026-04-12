<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniStory;
use Illuminate\Http\Request;

class AlumniStoryController extends Controller
{
    public function index()
    {
        $stories = AlumniStory::latest()->paginate(20);
        return view('admin.alumni-stories.index', compact('stories'));
    }

    public function destroy($id)
    {
        try {
            $story = AlumniStory::findOrFail($id);
            $story->delete();
            return redirect()->route('admin.alumni-stories.index')->with('success', 'Cerita alumni berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.alumni-stories.index')->with('error', 'Gagal menghapus cerita alumni');
        }
    }
}
