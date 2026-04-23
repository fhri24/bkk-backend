<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\Event;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::latest()->get();
        
        $query = EventRegistration::query();
        
        // Filter berdasarkan Event yang dipilih
        if ($request->filled('event_slug')) {
            $query->where('event_id', $request->event_slug);
        }
        
        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $registrations = $query->latest('registered_at')->get();

        return view('admin.events.registrations', compact('events', 'registrations'));
    }

    public function update(Request $request, $id)
    {
        $registration = EventRegistration::findOrFail($id);
        $registration->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);
        return back()->with('success', 'Status partisipan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        EventRegistration::findOrFail($id)->delete();
        return back()->with('success', 'Data peserta berhasil dihapus.');
    }
}