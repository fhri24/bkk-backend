<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();

        return view('admin.news.index', compact('news'));
    }

    public function index_student()
    {
        $newsItems = News::where('is_published', 1)
            ->latest()
            ->paginate(6);

        return view('public.berita', compact('newsItems'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        DB::table('news')->insert([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'author_id' => auth()->id(),
            'content' => $request->content,
            'excerpt' => Str::limit(strip_tags($request->content), 150),
            'tags' => $request->tags,
            'image' => $imagePath,
            'published_at' => now(),
            'is_published' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diterbitkan!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);

        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => Str::limit(strip_tags($request->content), 150),
            'tags' => $request->tags,
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        DB::table('news')->where('id', $id)->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diupdate!');
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        $relatedNews = News::where('id', '!=', $news->id)
            ->where('is_published', 1)
            ->latest()
            ->take(2)
            ->get();

        return view('public.berita_detail', compact('news', 'relatedNews'));
    }

    public function previewJson($id)
    {
        $news = News::with('author')->findOrFail($id);

        return response()->json([
            'title' => $news->title,
            'category' => $news->category ?? 'Umum',
            'body' => $news->content,
            'image' => $news->image ? Storage::disk('public')->url($news->image) : null,
            'author' => optional($news->author)->name ?? 'Admin',
            'created_at' => $news->created_at->format('d M Y'),
        ]);
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
