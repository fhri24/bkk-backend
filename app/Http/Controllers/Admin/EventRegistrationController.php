<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::latest()->get();
        $query = $this->buildRegistrationQuery($request);
        $registrations = $query->latest('registered_at')->get();

        return view('admin.events.registrations', compact('events', 'registrations'));
    }

    public function exportCsv(Request $request)
    {
        $query = $this->buildRegistrationQuery($request);
        $registrations = $query->latest('registered_at')->get();
        $event = null;

        if ($request->filled('event_slug')) {
            $event = Event::find($request->event_slug);
        }

        $filename = 'event_registrations' . ($event ? '_' . str_replace(' ', '_', strtolower($event->title)) : '') . '_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = ['Nama', 'Email', 'Telepon', 'Institusi', 'Posisi', 'Status', 'Tanggal Daftar'];

        $callback = function () use ($registrations, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            foreach ($registrations as $reg) {
                fputcsv($handle, [
                    $reg->name,
                    $reg->email,
                    $reg->phone,
                    $reg->institution ?? '-',
                    $reg->position ?? '-',
                    $reg->status,
                    $reg->registered_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPrint(Request $request)
    {
        $events = Event::latest()->get();
        $query = $this->buildRegistrationQuery($request);
        $registrations = $query->latest('registered_at')->get();
        $selectedEvent = null;

        if ($request->filled('event_slug')) {
            $selectedEvent = Event::find($request->event_slug);
        }

        return view('admin.events.registrations-print', compact('events', 'registrations', 'selectedEvent'));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildRegistrationQuery($request);
        $registrations = $query->latest('registered_at')->get();
        $selectedEvent = null;

        if ($request->filled('event_slug')) {
            $selectedEvent = Event::find($request->event_slug);
        }

        $filename = 'event_registrations' . ($selectedEvent ? '_' . str_replace(' ', '_', strtolower($selectedEvent->title)) : '') . '_' . now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('admin.events.registrations-pdf', compact('registrations', 'selectedEvent'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    private function buildRegistrationQuery(Request $request)
    {
        $query = EventRegistration::query();

        if ($request->filled('event_slug')) {
            $query->where('event_id', $request->event_slug);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
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