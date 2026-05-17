<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TipController extends Controller
{
    public function index(Request $request)
    {
        $query = Tip::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $tips         = $query->orderBy('urutan')->orderByDesc('created_at')->paginate(15)->withQueryString();
        $kategoriList = Tip::kategoriList();

        return view('admin.tips.index', compact('tips', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = Tip::kategoriList();
        return view('admin.tips.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'kategori'  => 'required|in:' . implode(',', Tip::kategoriList()),
            'ringkasan' => 'required|string|max:500',
            'konten'    => 'required|string',
            'icon'      => 'nullable|string|max:100',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        Tip::create([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul) . '-' . Str::random(5),
            'kategori'     => $request->kategori,
            'ringkasan'    => $request->ringkasan,
            'konten'       => $request->konten,
            'icon'         => $request->icon ?? Tip::defaultIcon($request->kategori),
            'is_featured'  => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published', true),
            'urutan'       => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.tips.index')->with('success', 'Tips berhasil ditambahkan!');
    }

    public function edit(Tip $tip)
    {
        $kategoriList = Tip::kategoriList();
        return view('admin.tips.edit', compact('tip', 'kategoriList'));
    }

    public function update(Request $request, Tip $tip)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'kategori'  => 'required|in:' . implode(',', Tip::kategoriList()),
            'ringkasan' => 'required|string|max:500',
            'konten'    => 'required|string',
            'icon'      => 'nullable|string|max:100',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $tip->update([
            'judul'        => $request->judul,
            'kategori'     => $request->kategori,
            'ringkasan'    => $request->ringkasan,
            'konten'       => $request->konten,
            'icon'         => $request->icon ?? Tip::defaultIcon($request->kategori),
            'is_featured'  => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
            'urutan'       => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.tips.index')->with('success', 'Tips berhasil diperbarui!');
    }

    public function destroy(Tip $tip)
    {
        $tip->delete();
        return redirect()->route('admin.tips.index')->with('success', 'Tips berhasil dihapus.');
    }

    public function togglePublish(Tip $tip)
    {
        $tip->update(['is_published' => !$tip->is_published]);
        return back()->with('success', 'Status tips diperbarui.');
    }

    public function toggleFeatured(Tip $tip)
    {
        $tip->update(['is_featured' => !$tip->is_featured]);
        return back()->with('success', 'Status featured diperbarui.');
    }
}
