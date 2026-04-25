<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Tampilan List Berita di Admin Panel
     * Mengarah ke: resources/views/admin/news/index.blade.php
     */
    public function index()
    {
        $news = News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    /**
     * Tampilan List Berita di Halaman Depan (Public/Student)
     * Mengarah ke: resources/views/public/berita.blade.php
     */
   public function index_student()
{
    // Mengambil berita yang dipublikasikan dengan pagination
    $newsItems = News::where('is_published', true)
                ->latest()
                ->paginate(6); // atau gunakan get() jika tidak ingin pagination

    return view('public.berita', compact('newsItems'));
}
    /**
     * Tampilan Form Tambah Berita (Admin)
     */
    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required',
            'tags'    => 'nullable|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['author_id'] = auth()->id();
        $data['excerpt'] = Str::limit(strip_tags($request->content), 150);
        $data['published_at'] = now();
        $data['is_published'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diterbitkan!');
    }

    /**
     * Tampilan Form Edit Berita (Admin)
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required',
            'tags'    => 'nullable|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['excerpt'] = Str::limit(strip_tags($request->content), 150);

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diupdate!');
    }

    /**
     * Tampilan Detail Berita (Public)
     * Mengarah ke: resources/views/public/berita_detail.blade.php
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        $relatedNews = News::where('id', '!=', $news->id)
                            ->where('is_published', true)
                            ->latest()
                            ->take(2)
                            ->get();

        return view('public.berita-detail', compact('news', 'relatedNews'));
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return back()->with('success', 'Berita berhasil dihapus!');
    }
}
