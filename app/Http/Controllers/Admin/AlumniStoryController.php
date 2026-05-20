<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniStory;
use App\Models\User;
use App\Notifications\AlumniStorySubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AlumniStoryController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Anda harus login terlebih dahulu untuk membagikan kisah sukses!');
        }

        $request->validate([
            'name'      => 'required|string|max:100',
            'job_title' => 'required|string|max:150',
            'story'     => 'required|string|min:30|max:2000',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'job_title.required' => 'Pekerjaan & instansi wajib diisi.',
            'story.required'     => 'Cerita singkat wajib diisi.',
            'story.min'          => 'Cerita minimal 30 karakter.',
        ]);

        $data = [
            'user_id'   => auth()->id(), // Mengunci kepemilikan relasi ke tabel students
            'name'      => $request->name,
            'job_title' => $request->job_title,
            'story'     => $request->story,
            'status'    => 'pending',
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('alumni-photos', 'public');
        }

        // Simpan & langsung dapat objeknya
        $story = AlumniStory::create($data);

        // Kirim notifikasi ke semua admin
        $admins = User::whereHas('role', fn($q) =>
            $q->whereIn('name', ['super_admin', 'admin_bkk'])
        )->get();

        try {
            Notification::send($admins, new AlumniStorySubmitted($story));
        } catch (\Exception $e) {
            // Lanjut meski notif gagal
        }

        return back()->with(
            'story_success',
            'Terima kasih! Kisah suksesmu telah dikirim dan sedang menunggu persetujuan admin.'
        );
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = AlumniStory::with('student')->latest(); // Ditambahkan eager load student untuk sisi admin jika perlu

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

    public function show(AlumniStory $alumniStory)
    {
        return view('admin.alumni-stories.show', compact('alumniStory'));
    }

    public function toggleFeatured(AlumniStory $alumniStory)
    {
        $alumniStory->update([
            'is_featured' => !$alumniStory->is_featured
        ]);

        $message = $alumniStory->is_featured ? 'ditampilkan' : 'dilepas';

        return back()->with('success', "Kisah {$alumniStory->name} berhasil {$message} di beranda.");
    }

    public function approve(AlumniStory $alumniStory)
    {
        $alumniStory->update(['status' => 'approved']);

        return back()->with('success', "Kisah sukses dari {$alumniStory->name} telah disetujui.");
    }

    public function reject(AlumniStory $alumniStory)
    {
        $alumniStory->update(['status' => 'rejected']);

        return back()->with('success', "Kisah sukses dari {$alumniStory->name} telah ditolak.");
    }

    public function destroy(AlumniStory $alumniStory)
    {
        try {
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
