@extends('layouts.app')

@section('title', ($job->title ?? 'Detail Lowongan') . ' - BKK SMKN 1 Garut')

@section('content')

{{-- ===== INLINE CSS — pasti ke-load tanpa perlu @stack ===== --}}
<style>
.detail-header {
    background: linear-gradient(135deg, rgba(30,58,138,0.92), rgba(0,31,63,0.92)),
        url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80');
    background-size: cover !important;
    background-position: center !important;
    color: #fff;
    padding: 60px 0;
}
.company-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100px; height: 100px;
    background: #fff;
    border-radius: 12px;
    font-size: 48px;
    font-weight: 900;
    color: #001f3f;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
    overflow: hidden;
    flex-shrink: 0;
}
.detail-section { margin-bottom: 40px; }
.detail-section h2 {
    font-size: 22px;
    font-weight: 700;
    color: #001f3f;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 10px;
}
.detail-section h2 i { margin-right: 10px; color: #3b82f6; }
.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}
.requirement-box {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #3b82f6;
}
.requirement-box h4 { font-weight: 700; color: #001f3f; margin-bottom: 10px; font-size: 14px; }
.requirement-box ul { list-style: none; padding: 0; margin: 0; }
.requirement-box li { padding: 5px 0; color: #475569; font-size: 14px; }
.requirement-box li::before { content: "✓ "; color: #3b82f6; font-weight: 700; }
.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 10px;
}
.benefit-item {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 10px 14px;
    border-radius: 8px;
    color: #1e40af;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}
.sidebar-card {
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.sidebar-sticky {
    position: sticky;
    top: 20px;
}
.sidebar-card h3 {
    font-size: 17px;
    font-weight: 700;
    color: #001f3f;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sidebar-card h3 i { color: #3b82f6; }
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 11px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
}
.info-row:last-child { border-bottom: none; }
.info-row .lbl { font-weight: 600; color: #64748b; }
.info-row .val { font-weight: 700; color: #001f3f; text-align: right; max-width: 55%; }
.badge-open   { background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 6px; font-size: 13px; font-weight: 700; }
.badge-closed { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 6px; font-size: 13px; font-weight: 700; }
.responsibilities-list { list-style: none; padding: 0; margin: 0; }
.responsibilities-list li {
    padding: 10px 0;
    color: #475569;
    font-size: 15px;
    display: flex;
    align-items: flex-start;
    border-bottom: 1px solid #f1f5f9;
}
.responsibilities-list li:last-child { border-bottom: none; }
.responsibilities-list li::before {
    content: '';
    width: 8px; height: 8px;
    min-width: 8px;
    background: #3b82f6;
    border-radius: 50%;
    margin-right: 12px;
    margin-top: 6px;
}
.similar-item { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.similar-item:last-child { border-bottom: none; }
.similar-item h4 { font-weight: 600; color: #001f3f; margin-bottom: 2px; font-size: 14px; }
.similar-item p  { font-size: 13px; color: #64748b; margin-bottom: 6px; }
.similar-item a  { color: #3b82f6; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.breadcrumb-bar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 12px 0; font-size: 14px; color: #64748b; }
.breadcrumb-bar a { color: #3b82f6; text-decoration: none; }
.btn-primary   { display: flex; align-items: center; justify-content: center; width: 100%; background: #2563eb; color: #fff; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 15px; border: none; cursor: pointer; margin-bottom: 10px; transition: background .2s; }
.btn-primary:hover { background: #1d4ed8; }
.btn-primary:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
.btn-warning   { display: flex; align-items: center; justify-content: center; width: 100%; background: #fbbf24; color: #1e293b; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 15px; border: none; cursor: pointer; margin-bottom: 10px; transition: background .2s; }
.btn-warning:hover { background: #f59e0b; }
.btn-outline   { display: flex; align-items: center; justify-content: center; width: 100%; background: transparent; color: #475569; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 15px; border: 2px solid #e2e8f0; cursor: pointer; transition: border-color .2s; }
.btn-outline:hover { border-color: #cbd5e1; }
/* Modal */
#applicationModal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,.5);
    align-items: center;
    justify-content: center;
}
#applicationModal.show { display: flex; }
.modal-box {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    width: 90%;
    max-width: 580px;
    max-height: 90vh;
    overflow-y: auto;
}
.form-label { display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; }
.form-input  { width: 100%; padding: 11px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 14px; outline: none; transition: border-color .2s; }
.form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
@media (max-width: 768px) {
    .detail-header { padding: 36px 0; }
    .detail-section h2 { font-size: 18px; }
    .sidebar-sticky { position: static; }
}
</style>

@php
    $companyName = optional($job->company)->company_name ?? ($job->company_name ?? 'BKK');
    $isActive    = $job->status === 'active'
        && (empty($job->expired_at) || \Carbon\Carbon::parse($job->expired_at)->isFuture());

    $logoUrl = null;
    if (!empty($job->logo))
        $logoUrl = Storage::url($job->logo);
    elseif (!empty(optional($job->company)->logo))
        $logoUrl = Storage::url($job->company->logo);

    $responsibilities = array_values(array_filter(array_map('trim', explode("\n", $job->responsibilities ?? ''))));
    $requirements     = array_values(array_filter(array_map('trim', explode("\n", $job->requirements ?? ''))));
    $benefits         = array_values(array_filter(array_map('trim', explode("\n", $job->benefits ?? ''))));
@endphp

{{-- ── BREADCRUMB ── --}}
<div class="breadcrumb-bar">
    <div class="container mx-auto px-6">
        <a href="{{ Route::has('home') ? route('home') : '/' }}">Beranda</a>
        <span class="mx-1 text-slate-300">/</span>
        <a href="{{ Route::has('public.lowongan') ? route('public.lowongan') : '/lowongan' }}">Lowongan</a>
        <span class="mx-1 text-slate-300">/</span>
        <span>{{ $job->title ?? 'Detail Lowongan' }}</span>
    </div>
</div>

{{-- ── HEADER ── --}}
<div class="detail-header">
    <div class="container mx-auto px-6">
        <div style="display:flex; gap:32px; align-items:flex-start; flex-wrap:wrap;">

            {{-- Logo / Badge --}}
            <div class="company-badge">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $companyName }}"
                         style="max-width:100%; max-height:100%; object-fit:contain;">
                @else
                    {{ strtoupper(substr($companyName, 0, 1)) }}
                @endif
            </div>

            <div style="flex:1; min-width:0;">
                <h1 style="font-size:clamp(26px,4vw,44px); font-weight:900; margin:0 0 8px; line-height:1.2;">
                    {{ $job->title ?? '-' }}
                </h1>
                <p style="font-size:20px; color:#bfdbfe; font-weight:700; margin:0 0 20px;">
                    {{ $companyName }}
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:12px;">
                    <span style="background:rgba(255,255,255,.15); padding:8px 16px; border-radius:8px; display:flex; align-items:center; gap:8px; font-size:14px; font-weight:600; backdrop-filter:blur(4px);">
                        <i class="fas fa-map-marker-alt"></i> {{ $job->location ?? '-' }}
                    </span>
                    <span style="background:rgba(255,255,255,.15); padding:8px 16px; border-radius:8px; display:flex; align-items:center; gap:8px; font-size:14px; font-weight:600; backdrop-filter:blur(4px);">
                        <i class="fas fa-briefcase"></i> {{ $job->job_type ?? '-' }}
                    </span>
                    <span style="background:rgba(255,255,255,.15); padding:8px 16px; border-radius:8px; display:flex; align-items:center; gap:8px; font-size:14px; font-weight:600; backdrop-filter:blur(4px);">
                        <i class="fas fa-clock"></i>
                        @if(!empty($job->created_at) && \Carbon\Carbon::parse($job->created_at)->diffInDays(now()) < 7)
                            Baru
                        @elseif(!empty($job->created_at))
                            {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MAIN CONTENT ── --}}
<div class="container mx-auto px-6 py-14">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ════ LEFT COLUMN ════ --}}
        <div class="lg:col-span-2">

            {{-- Deskripsi Pekerjaan --}}
            <div class="detail-section">
                <h2><i class="fas fa-briefcase"></i> Deskripsi Pekerjaan</h2>
                <p style="color:#475569; line-height:1.75; font-size:15px;">
                    {!! nl2br(e($job->description ?? 'Tidak ada deskripsi.')) !!}
                </p>
            </div>

            {{-- Tanggung Jawab --}}
            <div class="detail-section">
                <h2><i class="fas fa-tasks"></i> Tanggung Jawab</h2>
                @if(count($responsibilities))
                <ul class="responsibilities-list">
                    @foreach($responsibilities as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                @else
                <p style="color:#94a3b8; font-style:italic; font-size:14px;">Belum ada informasi tanggung jawab.</p>
                @endif
            </div>

            {{-- Persyaratan --}}
            <div class="detail-section">
                <h2><i class="fas fa-graduation-cap"></i> Persyaratan</h2>
                @if(count($requirements))
                @php
                    $half = (int)ceil(count($requirements) / 2);
                    $col1 = array_slice($requirements, 0, $half);
                    $col2 = array_slice($requirements, $half);
                @endphp
                <div class="requirements-grid">
                    <div class="requirement-box">
                        <h4>Persyaratan Umum</h4>
                        <ul>@foreach($col1 as $r)<li>{{ $r }}</li>@endforeach</ul>
                    </div>
                    @if(count($col2))
                    <div class="requirement-box">
                        <h4>Persyaratan Tambahan</h4>
                        <ul>@foreach($col2 as $r)<li>{{ $r }}</li>@endforeach</ul>
                    </div>
                    @endif
                </div>
                @else
                <p style="color:#94a3b8; font-style:italic; font-size:14px;">Belum ada informasi persyaratan.</p>
                @endif
            </div>

            {{-- Benefit & Tunjangan --}}
            <div class="detail-section">
                <h2><i class="fas fa-gift"></i> Benefit &amp; Tunjangan</h2>
                @if(count($benefits))
                <div class="benefits-grid">
                    @foreach($benefits as $b)
                    <div class="benefit-item"><i class="fas fa-check"></i> {{ $b }}</div>
                    @endforeach
                </div>
                @else
                <p style="color:#94a3b8; font-style:italic; font-size:14px;">Belum ada informasi benefit.</p>
                @endif
            </div>

            {{-- Tentang Perusahaan --}}
            <div class="detail-section">
                <h2><i class="fas fa-building"></i> Tentang Perusahaan</h2>
                <p style="color:#475569; line-height:1.75; font-size:15px; margin-bottom:16px;">
                    {!! nl2br(e(optional($job->company)->description ?? 'Informasi perusahaan tidak tersedia.')) !!}
                </p>
                @if($job->company)
                <a href="#"
                   style="display:inline-flex; align-items:center; gap:8px; background:#2563eb; color:#fff; padding:10px 20px; border-radius:10px; font-weight:700; font-size:14px; text-decoration:none; transition:background .2s;"
                   onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                    <i class="fas fa-external-link-alt"></i> Lihat Profil Perusahaan
                </a>
                @endif
            </div>

        </div>

        {{-- ════ SIDEBAR ════ --}}
        <div class="lg:col-span-1">
            <div class="sidebar-sticky">

                {{-- Informasi Singkat --}}
                <div class="sidebar-card">
                    <h3><i class="fas fa-info-circle"></i> Informasi Singkat</h3>
                    <div class="info-row">
                        <span class="lbl">Gaji</span>
                        <span class="val">{{ $job->salary ?? 'Kompetitif' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Tipe Pekerjaan</span>
                        <span class="val">{{ $job->job_type ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Lokasi</span>
                        <span class="val">{{ $job->location ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Pengalaman</span>
                        <span class="val">{{ $job->experience ?? $job->skill_required ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Batas Akhir</span>
                        <span class="val">
                            @if(!empty($job->expired_at))
                                {{ \Carbon\Carbon::parse($job->expired_at)->translatedFormat('d M Y') }}
                            @else -
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="lbl">Status</span>
                        <span class="val">
                            @if($isActive)
                                <span class="badge-open">Buka</span>
                            @else
                                <span class="badge-closed">Ditutup</span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="sidebar-card">
                    <h3><i class="fas fa-bolt"></i> Aksi</h3>

                    @auth
                        @if($isActive)
                        <button class="btn-primary" onclick="openApplicationForm()">
                            <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Apply Sekarang
                        </button>
                        @else
                        <button class="btn-primary" disabled>
                            <i class="fas fa-lock" style="margin-right:8px;"></i> Lowongan Ditutup
                        </button>
                        @endif

                        <button class="btn-warning" id="saveBtn" onclick="toggleSaveJob()">
                            <i class="fas fa-bookmark" id="saveBtnIcon" style="margin-right:8px;"></i>
                            <span id="saveBtnText">Simpan Lowongan</span>
                        </button>
                    @else
                        <a href="{{ Route::has('login') ? route('login') : '/login' }}" class="btn-primary" style="text-decoration:none;">
                            <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i> Login untuk Melamar
                        </a>
                    @endauth

                    <button class="btn-outline" onclick="shareVacancy()">
                        <i class="fas fa-share-alt" style="margin-right:8px;"></i> Bagikan
                    </button>
                </div>

                {{-- Lowongan Serupa --}}
                @if(isset($similarJobs) && $similarJobs->isNotEmpty())
                <div class="sidebar-card">
                    <h3><i class="fas fa-list"></i> Lowongan Serupa</h3>
                    @foreach($similarJobs as $sim)
                    <div class="similar-item">
                        <h4>{{ $sim->title }}</h4>
                        <p>{{ optional($sim->company)->company_name ?? '-' }}</p>
                        <a href="{{ Route::has('public.lowongan.detail') ? route('public.lowongan.detail', $sim->job_id) : url('/lowongan/'.$sim->job_id) }}">
                            Lihat <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

{{-- ── APPLICATION MODAL ── --}}
<div id="applicationModal">
    <div class="modal-box">
        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#2563eb,#1e40af); padding:28px 32px; border-radius:20px 20px 0 0; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="color:#fff; font-size:20px; font-weight:900; margin:0; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-file-invoice"></i> Formulir Aplikasi Kerja
            </h2>
            <button onclick="closeApplicationForm()"
                style="background:none; border:none; color:#fff; font-size:28px; font-weight:900; cursor:pointer; line-height:1; padding:0;">&times;</button>
        </div>

        {{-- Form --}}
        <form id="applicationForm"
              action="{{ Route::has('public.lowongan.apply') ? route('public.lowongan.apply', $job->job_id) : url('/lowongan/'.$job->job_id.'/apply') }}"
              method="POST"
              enctype="multipart/form-data"
              style="padding:32px; display:flex; flex-direction:column; gap:20px;">
            @csrf
            <input type="hidden" name="job_id" value="{{ $job->job_id }}">

            <div>
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="full_name" class="form-input"
                    value="{{ auth()->user()->name ?? '' }}" required>
            </div>
            <div>
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-input"
                    value="{{ auth()->user()->email ?? '' }}" required>
            </div>
            <div>
                <label class="form-label">Nomor Telepon *</label>
                <input type="tel" name="phone" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Upload CV/Resume (PDF) *</label>
                <input type="file" name="resume" accept=".pdf" class="form-input" required>
                <p style="font-size:12px; color:#94a3b8; margin-top:6px;">Format: PDF, Maks. 5 MB</p>
            </div>
            <div>
                <label class="form-label">Cover Letter (Opsional)</label>
                <textarea name="cover_letter" rows="4" class="form-input"
                    placeholder="Tulis motivasi Anda bergabung dengan kami..."></textarea>
            </div>
            <div>
                <label class="form-label">Pengalaman Singkat (Opsional)</label>
                <textarea name="experience" rows="3" class="form-input"
                    placeholder="Jelaskan pengalaman relevan Anda..."></textarea>
            </div>
            <div style="display:flex; align-items:flex-start; gap:10px;">
                <input type="checkbox" name="agree" id="agreeChk"
                    style="margin-top:3px; width:16px; height:16px; flex-shrink:0;" required>
                <label for="agreeChk" style="font-size:14px; color:#475569;">
                    Saya setuju dengan <a href="#" style="color:#2563eb; font-weight:700;">syarat dan ketentuan</a> yang berlaku
                </label>
            </div>
            <button type="submit" class="btn-primary" style="margin-bottom:0;">
                <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Kirim Aplikasi
            </button>
        </form>
    </div>
</div>

@endsection

@push('extra_js')
<script>
const JOB_ID   = {{ $job->job_id ?? 0 }};
const SAVE_URL = '/student/lowongan/save/' + JOB_ID;
const CSRF     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

function openApplicationForm() {
    document.getElementById('applicationModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeApplicationForm() {
    document.getElementById('applicationModal').classList.remove('show');
    document.body.style.overflow = '';
}

function toggleSaveJob() {
    const btn  = document.getElementById('saveBtn');
    const icon = document.getElementById('saveBtnIcon');
    const text = document.getElementById('saveBtnText');
    if (!btn) return;
    btn.disabled = true;
    fetch(SAVE_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
    })
    .then(r => { if (!r.ok) throw r; return r.json(); })
    .then(d => {
        if (d.saved) {
            icon.className = 'fas fa-bookmark';
            text.innerText = 'Sudah Disimpan';
            btn.style.opacity = '.75';
        } else {
            icon.className = 'far fa-bookmark';
            text.innerText = 'Simpan Lowongan';
            btn.style.opacity = '1';
        }
        showToast(d.message ?? 'Berhasil');
    })
    .catch(() => showToast('Gagal menyimpan. Coba lagi.'))
    .finally(() => { btn.disabled = false; });
}

function shareVacancy() {
    const title = @json($job->title ?? '');
    if (navigator.share) {
        navigator.share({ title, url: location.href });
    } else {
        navigator.clipboard.writeText(location.href)
            .then(() => showToast('Link berhasil disalin!'))
            .catch(() => showToast('Gagal menyalin link.'));
    }
}

function showToast(msg) {
    const t = document.createElement('div');
    t.innerText = msg;
    t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#0f172a;color:#fff;padding:12px 20px;border-radius:14px;font-weight:700;font-size:14px;z-index:99999;box-shadow:0 8px 24px rgba(0,0,0,.2);';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) closeApplicationForm();
});
</script>
@endpush