<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    /**
     * Menampilkan daftar registrasi untuk event tertentu
     * GET /api/event-registrations?event_id=jobfair2026
     */
    public function index(Request $request)
    {
        $request->validate([
            'event_id' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Hanya admin/kepala BKK yang bisa melihat semua registrasi
        if ($user && in_array($user->role->name, ['super_admin', 'admin_bkk', 'kepala_bkk'])) {
            $registrations = EventRegistration::where('event_id', $request->event_id)
                ->orderBy('registered_at', 'desc')
                ->get();

            return response()->json([
                'message' => 'Daftar registrasi acara berhasil diambil',
                'event_id' => $request->event_id,
                'total_registrations' => $registrations->count(),
                'data' => $registrations
            ], 200);
        }

        return response()->json([
            'message' => 'Anda tidak berhak mengakses data ini'
        ], 403);
    }

    /**
     * Menampilkan detail satu registrasi
     * GET /api/event-registrations/{id}
     */
    public function show(string $id)
    {
        try {
            $registration = EventRegistration::findOrFail($id);

            return response()->json([
                'message' => 'Detail registrasi berhasil diambil',
                'data' => $registration
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registrasi tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Menyimpan registrasi peserta acara baru
     * POST /api/event-registrations
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        // CEK: Apakah sudah pernah mendaftar ke event yang sama?
        $alreadyRegistered = EventRegistration::where('event_id', $request->event_id)
            ->where('email', $request->email)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'message' => 'Email Anda sudah terdaftar untuk acara ini sebelumnya.'
            ], 400);
        }

        $registration = EventRegistration::create([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'institution' => $request->institution,
            'position' => $request->position,
            'status' => 'pending',
            'registered_at' => now()
        ]);

        return response()->json([
            'message' => 'Registrasi acara berhasil! Anda akan menerima email konfirmasi.',
            'data' => $registration
        ], 201);
    }

    /**
     * Update registrasi (hanya untuk admin/kepala BKK)
     * PUT /api/event-registrations/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::user();
            
            // Cek otorisasi
            if (!$user || !in_array($user->role->name, ['super_admin', 'admin_bkk', 'kepala_bkk'])) {
                return response()->json([
                    'message' => 'Anda tidak berhak mengubah data ini'
                ], 403);
            }

            $registration = EventRegistration::findOrFail($id);

            $request->validate([
                'status' => 'sometimes|in:pending,confirmed,attended,cancelled',
                'admin_notes' => 'sometimes|nullable|string|max:1000',
            ]);

            $registration->update([
                'status' => $request->status ?? $registration->status,
                'admin_notes' => $request->admin_notes ?? $registration->admin_notes,
            ]);

            return response()->json([
                'message' => 'Registrasi berhasil diperbarui',
                'data' => $registration
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registrasi tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Hapus registrasi
     * DELETE /api/event-registrations/{id}
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            
            // Cek otorisasi
            if (!$user || !in_array($user->role->name, ['super_admin', 'admin_bkk', 'kepala_bkk'])) {
                return response()->json([
                    'message' => 'Anda tidak berhak menghapus data ini'
                ], 403);
            }

            $registration = EventRegistration::findOrFail($id);
            $registration->delete();

            return response()->json([
                'message' => 'Registrasi berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registrasi tidak ditemukan'
            ], 404);
        }
    }
}
