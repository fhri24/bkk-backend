<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use App\Models\TipStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $featured     = Tip::where('is_featured', true)->where('is_published', true)->get();

        return view('admin.tips.index', compact('tips', 'kategoriList', 'featured'));
    }

    public function create()
    {
        $kategoriList = Tip::kategoriList();
        return view('admin.tips.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'                => 'required|string|max:255',
            'kategori'             => 'required|in:' . implode(',', Tip::kategoriList()),
            'ringkasan'            => 'required|string|max:500',
            'konten'               => 'nullable|string',
            'icon'                 => 'nullable|string|max:100',
            'urutan'               => 'nullable|integer|min:0',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'steps'                => 'nullable|array',
            'steps.*.title'        => 'required_with:steps|string|max:255',
            'steps.*.description'  => 'nullable|string',
            'pro_tips'             => 'nullable|array',
            'pro_tips.*'           => 'nullable|string|max:500',
            'avoid_mistakes'       => 'nullable|array',
            'avoid_mistakes.*'     => 'nullable|string|max:500',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tips', 'public');
        }

        // Filter poin kosong
        $proTips       = collect($request->pro_tips ?? [])->filter()->values()->toArray();
        $avoidMistakes = collect($request->avoid_mistakes ?? [])->filter()->values()->toArray();

        $tip = Tip::create([
            'judul'          => $request->judul,
            'slug'           => Str::slug($request->judul) . '-' . Str::random(5),
            'kategori'       => $request->kategori,
            'ringkasan'      => $request->ringkasan,
            'konten'         => $request->konten ?? '',
            'pro_tips'       => !empty($proTips) ? $proTips : null,
            'avoid_mistakes' => !empty($avoidMistakes) ? $avoidMistakes : null,
            'icon'           => $request->icon ?? Tip::defaultIcon($request->kategori),
            'image'          => $imagePath,
            'is_featured'    => $request->boolean('is_featured'),
            'is_published'   => $request->boolean('is_published', true),
            'urutan'         => $request->urutan ?? 0,
        ]);

        if ($request->has('steps')) {
            foreach ($request->steps as $i => $step) {
                if (!empty($step['title'])) {
                    $tip->steps()->create([
                        'step_order'  => $i + 1,
                        'title'       => $step['title'],
                        'description' => $step['description'] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('admin.tips.index')->with('success', 'Tips berhasil ditambahkan!');
    }

    public function edit(Tip $tip)
    {
        $tip->load('steps');
        $kategoriList = Tip::kategoriList();
        return view('admin.tips.edit', compact('tip', 'kategoriList'));
    }

    public function update(Request $request, Tip $tip)
    {
        $request->validate([
            'judul'                => 'required|string|max:255',
            'kategori'             => 'required|in:' . implode(',', Tip::kategoriList()),
            'ringkasan'            => 'required|string|max:500',
            'konten'               => 'nullable|string',
            'icon'                 => 'nullable|string|max:100',
            'urutan'               => 'nullable|integer|min:0',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'steps'                => 'nullable|array',
            'steps.*.title'        => 'required_with:steps|string|max:255',
            'steps.*.description'  => 'nullable|string',
            'pro_tips'             => 'nullable|array',
            'pro_tips.*'           => 'nullable|string|max:500',
            'avoid_mistakes'       => 'nullable|array',
            'avoid_mistakes.*'     => 'nullable|string|max:500',
        ]);

        $imagePath = $tip->image;

        if ($request->hasFile('image')) {
            if ($tip->image) {
                Storage::disk('public')->delete($tip->image);
            }
            $imagePath = $request->file('image')->store('tips', 'public');
        }

        if ($request->boolean('remove_image')) {
            if ($tip->image) {
                Storage::disk('public')->delete($tip->image);
            }
            $imagePath = null;
        }

        $proTips       = collect($request->pro_tips ?? [])->filter()->values()->toArray();
        $avoidMistakes = collect($request->avoid_mistakes ?? [])->filter()->values()->toArray();

        $tip->update([
            'judul'          => $request->judul,
            'kategori'       => $request->kategori,
            'ringkasan'      => $request->ringkasan,
            'konten'         => $request->konten ?? '',
            'pro_tips'       => !empty($proTips) ? $proTips : null,
            'avoid_mistakes' => !empty($avoidMistakes) ? $avoidMistakes : null,
            'icon'           => $request->icon ?? Tip::defaultIcon($request->kategori),
            'image'          => $imagePath,
            'is_featured'    => $request->boolean('is_featured'),
            'is_published'   => $request->boolean('is_published'),
            'urutan'         => $request->urutan ?? 0,
        ]);

        $tip->steps()->delete();
        if ($request->has('steps')) {
            foreach ($request->steps as $i => $step) {
                if (!empty($step['title'])) {
                    $tip->steps()->create([
                        'step_order'  => $i + 1,
                        'title'       => $step['title'],
                        'description' => $step['description'] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('admin.tips.index')->with('success', 'Tips berhasil diperbarui!');
    }

    public function destroy(Tip $tip)
    {
        if ($tip->image) {
            Storage::disk('public')->delete($tip->image);
        }
        $tip->steps()->delete();
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
