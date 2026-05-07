<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniStoryController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // PUBLIC — Submit kisah sukses dari halaman beranda
    // ─────────────────────────────────────────────────────────

    /**
     * Simpan cerita baru dari form beranda.
     */
    public function store(Request $request)
    {
        // Harus login
        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Anda harus login terlebih dahulu untuk membagikan kisah sukses!');
        }

        // Validasi
        $request->validate([
            'name'      => 'required|string|max:100',
            'job_title' => 'required|string|max:150',
            'story'     => 'required|string|min:30|max:2000',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'job_title.required' => 'Pekerjaan & instansi wajib diisi.',
            'story.required'     => 'Cerita singkat wajib diisi.',
            'story.min'          => 'Cerita minimal 30 karakter.',
            'photo.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        // Ambil data
        $data = [
            'name'      => $request->name,
            'job_title' => $request->job_title,
            'story'     => $request->story,
            'status'    => 'pending',
            'user_id'   => auth()->id(),
        ];

        // Upload foto jika ada
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('alumni-photos', 'public');
        }

        // Simpan
        AlumniStory::create($data);

        return back()->with(
            'story_success',
            'Terima kasih! Kisah suksesmu telah dikirim dan sedang menunggu persetujuan admin.'
        );
    }

    // ─────────────────────────────────────────────────────────
    // ADMIN — Kelola kisah sukses alumni
    // ─────────────────────────────────────────────────────────

    /**
     * Daftar semua kisah sukses (Admin).
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = AlumniStory::latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $stories = $query->paginate(20)->withQueryString();

        $counts = [
            'all'      => AlumniStory::count(),
            'pending'  => AlumniStory::pending()->count(),
            'approved' => AlumniStory::approved()->count(),
            'rejected' => AlumniStory::where('status', 'rejected')->count(),
        ];

        return view('admin.alumni-stories.index', compact('stories', 'counts', 'status'));
    }

    /**
     * Detail kisah sukses (Admin).
     */
    public function show(AlumniStory $alumniStory)
    {
        return view('admin.alumni-stories.show', compact('alumniStory'));
    }

    /**
     * Toggle featured (Tampilkan di Beranda).
     */
    public function toggleFeatured(AlumniStory $alumniStory)
    {
        $alumniStory->update([
            'is_featured' => !$alumniStory->is_featured
        ]);

        $message = $alumniStory->is_featured ? 'ditampilkan' : 'dilepas';

        return back()->with('success', "Kisah {$alumniStory->name} berhasil {$message} di beranda.");
    }

    /**
     * Approve kisah sukses.
     */
    public function approve(AlumniStory $alumniStory)
    {
        $alumniStory->update(['status' => 'approved']);

        return back()->with('success', "Kisah sukses dari {$alumniStory->name} telah disetujui.");
    }

    /**
     * Reject kisah sukses.
     */
    public function reject(AlumniStory $alumniStory)
    {
        $alumniStory->update(['status' => 'rejected']);

        return back()->with('success', "Kisah sukses dari {$alumniStory->name} telah ditolak.");
    }

    /**
     * Hapus kisah sukses.
     */
    public function destroy(AlumniStory $alumniStory)
    {
        try {
            // Hapus foto fisik jika ada
            if ($alumniStory->photo) {
                Storage::disk('public')->delete($alumniStory->photo);
            }

            $alumniStory->delete();

            return redirect()
                ->route('admin.alumni-stories.index')
                ->with('success', 'Cerita alumni berhasil dihapus permanen.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus cerita: ' . $e->getMessage());
        }
    }
} 