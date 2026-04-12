<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlumniStory;
use Illuminate\Http\Request;

class AlumniStoryController extends Controller
{
    public function index()
    {
        return response()->json(AlumniStory::latest()->take(20)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'job' => 'required|string|max:255',
            'story' => 'required|string|max:2000',
        ]);

        $story = AlumniStory::create($validated);

        return response()->json([
            'message' => 'Cerita berhasil disimpan',
            'data' => $story,
        ], 201);
    }

    public function destroy($id)
    {
        try {
            $story = AlumniStory::findOrFail($id);
            $story->delete();
            return redirect()->back()->with('success', 'Cerita alumni berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus cerita alumni');
        }
    }
}
