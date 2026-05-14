<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyPanelController extends Controller
{
    /**
     * Ambil company_id dari user yang sedang login.
     * Jika tidak punya company_id, tolak akses.
     */
    private function getCompanyId()
    {
        $companyId = Auth::user()->company_id;
        if (!$companyId) {
            abort(403, 'Akun ini belum terhubung ke perusahaan manapun.');
        }
        return $companyId;
    }

    /**
     * Dashboard perusahaan
     */
    public function dashboard()
    {
        $companyId = $this->getCompanyId();
        $company   = Company::where('company_id', $companyId)->firstOrFail();

        $totalLowongan  = Job::where('company_id', $companyId)->count();
        $lowonganAktif  = Job::where('company_id', $companyId)->where('status', 'active')->where('approval_status', 'approved')->count();
        $lowonganPending = Job::where('company_id', $companyId)->where('approval_status', 'pending')->count();
        $totalLamaran   = JobApplication::whereHas('job', fn($q) => $q->where('company_id', $companyId))->count();
        $lamaranBaru    = JobApplication::whereHas('job', fn($q) => $q->where('company_id', $companyId))
                            ->where('status', 'pending')->count();

        $recentApplications = JobApplication::with(['job'])
            ->whereHas('job', fn($q) => $q->where('company_id', $companyId))
            ->latest('application_date')
            ->limit(5)
            ->get();

        $recentJobs = Job::where('company_id', $companyId)
            ->latest()
            ->limit(5)
            ->get();

        return view('company.dashboard', compact(
            'company',
            'totalLowongan',
            'lowonganAktif',
            'lowonganPending',
            'totalLamaran',
            'lamaranBaru',
            'recentApplications',
            'recentJobs'
        ));
    }

    /**
     * Daftar lowongan milik perusahaan ini saja
     */
    public function lowonganIndex()
    {
        $companyId = $this->getCompanyId();
        $company   = Company::where('company_id', $companyId)->firstOrFail();

        $jobs = Job::where('company_id', $companyId)
            ->latest()
            ->get();

        return view('company.lowongan.index', compact('jobs', 'company'));
    }

    /**
     * Form tambah lowongan
     */
    public function lowonganCreate()
    {
        $companyId = $this->getCompanyId();
        $company   = Company::where('company_id', $companyId)->firstOrFail();

        return view('company.lowongan.create', compact('company'));
    }

    /**
     * Simpan lowongan baru — otomatis pending, menunggu approval BKK
     */
    public function lowonganStore(Request $request)
    {
        $companyId = $this->getCompanyId();

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits'         => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'salary'           => 'nullable|string|max:255',
            'job_type'         => 'required|in:Full-time,Part-time,Contract,Internship',
            'expired_at'       => 'required|date|after:today',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['logo'] = $request->file('image')->store('logos', 'public');
        }

        Job::create([
            ...$validated,
            'company_id'      => $companyId,
            'approval_status' => 'pending',   // Menunggu acc BKK
            'status'          => 'inactive',  // Belum aktif sampai di-approve
            'visibility'      => 'public',
            'is_active'       => false,
            'posted_at'       => now(),
        ]);

        return redirect()->route('company.lowongan.index')
            ->with('success', 'Lowongan berhasil dikirim! Menunggu persetujuan Admin BKK.');
    }

    /**
     * Form edit lowongan — hanya bisa edit milik sendiri
     */
    public function lowonganEdit(Job $job)
    {
        $companyId = $this->getCompanyId();

        // Gembok keamanan: pastikan lowongan ini milik perusahaan yang login
        if ($job->company_id !== $companyId) {
            abort(403, 'Akses Ditolak! Ini bukan lowongan perusahaan Anda.');
        }

        // Lowongan yang sudah approved tidak bisa diedit langsung
        if ($job->approval_status === 'approved') {
            return redirect()->route('company.lowongan.index')
                ->with('error', 'Lowongan yang sudah disetujui tidak dapat diedit. Hubungi Admin BKK.');
        }

        $company = Company::where('company_id', $companyId)->firstOrFail();
        return view('company.lowongan.edit', compact('job', 'company'));
    }

    /**
     * Update lowongan — reset ke pending setelah diedit
     */
    public function lowonganUpdate(Request $request, Job $job)
    {
        $companyId = $this->getCompanyId();

        if ($job->company_id !== $companyId) {
            abort(403, 'Akses Ditolak! Ini bukan lowongan perusahaan Anda.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits'         => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'salary'           => 'nullable|string|max:255',
            'job_type'         => 'required|in:Full-time,Part-time,Contract,Internship',
            'expired_at'       => 'required|date',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['logo'] = $request->file('image')->store('logos', 'public');
        }

        // Reset ke pending setelah diedit
        $validated['approval_status'] = 'pending';
        $validated['status']          = 'inactive';
        $validated['is_active']       = false;

        $job->update($validated);

        return redirect()->route('company.lowongan.index')
            ->with('success', 'Lowongan diperbarui dan dikirim ulang untuk persetujuan Admin BKK.');
    }

    /**
     * Hapus lowongan milik sendiri
     */
    public function lowonganDestroy(Job $job)
    {
        $companyId = $this->getCompanyId();

        if ($job->company_id !== $companyId) {
            abort(403, 'Akses Ditolak! Ini bukan lowongan perusahaan Anda.');
        }

        $job->delete();

        return redirect()->route('company.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    /**
     * Daftar lamaran yang masuk ke lowongan perusahaan ini saja
     */
    public function lamaranIndex(Request $request)
    {
        $companyId = $this->getCompanyId();
        $company   = Company::where('company_id', $companyId)->firstOrFail();

        $query = JobApplication::with(['job'])
            ->whereHas('job', fn($q) => $q->where('company_id', $companyId));

        // Filter by job
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest('application_date')->get();

        // Daftar lowongan untuk filter dropdown
        $myJobs = Job::where('company_id', $companyId)->get();

        return view('company.lamaran.index', compact('applications', 'company', 'myJobs'));
    }

    /**
     * Detail lamaran — hanya bisa lihat lamaran ke lowongan sendiri
     */
    public function lamaranShow(JobApplication $application)
    {
        $companyId = $this->getCompanyId();

        // Pastikan lamaran ini masuk ke lowongan milik perusahaan yang login
        if ($application->job->company_id !== $companyId) {
            abort(403, 'Akses Ditolak! Ini bukan lamaran untuk perusahaan Anda.');
        }

        $application->load('job');
        $company = Company::where('company_id', $companyId)->firstOrFail();

        return view('company.lamaran.show', compact('application', 'company'));
    }

    /**
     * Update status lamaran (proses/tolak/terima)
     */
    public function lamaranUpdateStatus(Request $request, JobApplication $application)
    {
        $companyId = $this->getCompanyId();

        if ($application->job->company_id !== $companyId) {
            abort(403, 'Akses Ditolak!');
        }

        $request->validate([
            'status'      => 'required|in:pending,reviewed,accepted,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $application->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
