<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'nullable|string|max:100',
            'location'        => 'nullable|string|max:255',
            'start_date_day'  => 'required|date',
            'start_date_time' => 'required',
            'end_date_day'    => 'required|date',
            'end_date_time'   => 'required',
            'capacity'        => 'required|integer|min:0',
            'organizer'       => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['start_date']   = $request->start_date_day . ' ' . $request->start_date_time;
        $validated['end_date']     = $request->end_date_day . ' ' . $request->end_date_time;
        $validated['slug']         = Str::slug($request->title) . '-' . time();
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;

        unset($validated['start_date_day'], $validated['start_date_time']);
        unset($validated['end_date_day'], $validated['end_date_time']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil diterbitkan!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'nullable|string|max:100',
            'location'        => 'nullable|string|max:255',
            'start_date_day'  => 'required|date',
            'start_date_time' => 'required',
            'end_date_day'    => 'required|date',
            'end_date_time'   => 'required',
            'capacity'        => 'required|integer|min:0',
            'organizer'       => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['start_date']   = $request->start_date_day . ' ' . $request->start_date_time;
        $validated['end_date']     = $request->end_date_day . ' ' . $request->end_date_time;
        $validated['slug']         = Str::slug($request->title) . '-' . $event->id;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;

        unset($validated['start_date_day'], $validated['start_date_time']);
        unset($validated['end_date_day'], $validated['end_date_time']);

        if ($request->hasFile('thumbnail')) {
            if ($event->thumbnail && Storage::disk('public')->exists($event->thumbnail)) {
                Storage::disk('public')->delete($event->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('events/thumbnails', 'public');
        }

        if ($request->hasFile('image')) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        if ($event->thumbnail && Storage::disk('public')->exists($event->thumbnail)) {
            Storage::disk('public')->delete($event->thumbnail);
        }
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil dihapus!');
    }
}
