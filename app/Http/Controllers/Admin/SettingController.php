<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GraduationYear;
use App\Models\Major;
use App\Models\SchoolProfile;
use App\Services\SchoolProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Pintu masuk utama settings, diarahkan ke Profile
     */
    public function index()
    {
        return $this->profile();
    }

    /**
     * Helper untuk cek tabel
     */
    protected function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    protected function missingTableResponse(string $table, string $route = 'admin.settings.profile')
    {
        return Redirect::route($route)->with('error', "Tabel {$table} belum dibuat. Jalankan php artisan migrate.");
    }

    /* ===== PROFIL SEKOLAH ===== */

    public function profile()
    {
        if (!$this->tableExists('school_profiles')) {
            $profile = new SchoolProfile();
            return view('admin.settings.profile', compact('profile'))
                ->with('tableMissing', 'school_profiles');
        }

        $profile = SchoolProfile::first();

        if (!$profile) {
            $profile = SchoolProfile::create([
                'school_name'    => 'Nama Sekolah',
                'school_address' => 'Alamat sekolah...',
            ]);
        }

        return view('admin.settings.profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        if (!$this->tableExists('school_profiles')) {
            return $this->missingTableResponse('school_profiles');
        }

        $profile = SchoolProfile::first() ?: new SchoolProfile();

        $validated = $request->validate([
            // Field lama
            'school_name'      => 'nullable|string|max:255',
            'school_address'   => 'nullable|string|max:1000',

            // Field baru
            'site_title'       => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'tagline'          => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'facebook'         => 'nullable|url|max:255',
            'instagram'        => 'nullable|url|max:255',
            'twitter'          => 'nullable|url|max:255',
            'youtube'          => 'nullable|url|max:255',
            'logo'             => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        // ===== Pertahankan school_name lama jika tidak dikirim =====
        if (empty($validated['school_name'])) {
            unset($validated['school_name']);
        }
        // ===== Upload Logo =====
        if ($request->hasFile('logo')) {
            // Hapus logo lama kalau ada
            $oldLogo = $profile->logo_path ?? $profile->logo ?? null;
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $validated['logo_path'] = $request->file('logo')->store('school-logos', 'public');
            $validated['logo']      = $validated['logo_path']; // sync dua kolom
        }

        $profile->fill($validated);
        $profile->save();

        // ===== Hapus cache agar perubahan langsung tampil =====
        SchoolProfileService::clear();

        return Redirect::route('admin.settings.profile')
            ->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    /* ===== MANAJEMEN JURUSAN (MAJORS) ===== */

    public function majorsIndex()
    {
        if (!$this->tableExists('majors')) {
            return view('admin.settings.majors.index', ['majors' => collect()])
                ->with('tableMissing', 'majors');
        }

        $majors = Major::orderBy('name')->get();
        return view('admin.settings.majors.index', compact('majors'));
    }

    public function storeMajor(Request $request)
    {
        if (!$this->tableExists('majors')) {
            return $this->missingTableResponse('majors', 'admin.settings.majors.index');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:150|unique:majors,name',
            'description' => 'nullable|string|max:1000',
        ]);

        Major::create($data);

        return Redirect::route('admin.settings.majors.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function editMajor(Major $major)
    {
        return view('admin.settings.majors.edit', compact('major'));
    }

    public function updateMajor(Request $request, Major $major)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150|unique:majors,name,' . $major->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $major->update($data);

        return Redirect::route('admin.settings.majors.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroyMajor(Major $major)
    {
        $major->delete();

        return Redirect::route('admin.settings.majors.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    /* ===== MANAJEMEN TAHUN LULUS (YEARS) ===== */

    public function yearsIndex()
    {
        if (!$this->tableExists('graduation_years')) {
            return view('admin.settings.years.index', ['years' => collect()])
                ->with('tableMissing', 'graduation_years');
        }

        $years = GraduationYear::orderByDesc('year')->get();
        return view('admin.settings.years.index', compact('years'));
    }

    public function storeYear(Request $request)
    {
        if (!$this->tableExists('graduation_years')) {
            return $this->missingTableResponse('graduation_years', 'admin.settings.years.index');
        }

        $data = $request->validate([
            'year'  => 'required|integer|min:1900|max:2100|unique:graduation_years,year',
            'label' => 'nullable|string|max:255',
        ]);

        GraduationYear::create($data);

        return Redirect::route('admin.settings.years.index')
            ->with('success', 'Tahun lulus berhasil ditambahkan.');
    }

    public function editYear(GraduationYear $year)
    {
        return view('admin.settings.years.edit', compact('year'));
    }

    public function updateYear(Request $request, GraduationYear $year)
    {
        $data = $request->validate([
            'year'  => 'required|integer|min:1900|max:2100|unique:graduation_years,year,' . $year->id,
            'label' => 'nullable|string|max:255',
        ]);

        $year->update($data);

        return Redirect::route('admin.settings.years.index')
            ->with('success', 'Tahun lulus berhasil diperbarui.');
    }

    public function destroyYear(GraduationYear $year)
    {
        $year->delete();

        return Redirect::route('admin.settings.years.index')
            ->with('success', 'Tahun lulus berhasil dihapus.');
    }
}
