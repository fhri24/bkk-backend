@extends('layouts.admin')

@section('title', 'Broadcast - BKK SMKN 1 Garut')
@section('page_title', 'Broadcast')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Broadcast</h3>
            <p class="text-slate-500">Feed aktivitas terbaru sistem BKK.</p>
        </div>
        <div class="text-sm text-slate-400 bg-white px-4 py-2 rounded-lg border border-slate-200">
            <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('d F Y') }}
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-2 flex flex-wrap gap-2">
        <button onclick="filterFeed('all')" id="tab-all"
            class="tab-btn active-tab px-4 py-2 rounded-xl text-sm font-semibold transition-all">
            <i class="fas fa-th-large mr-1"></i> Semua
        </button>
        <button onclick="filterFeed('job')" id="tab-job"
            class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:bg-slate-100">
            <i class="fas fa-briefcase mr-1"></i> Lowongan
        </button>
        <button onclick="filterFeed('application')" id="tab-application"
            class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:bg-slate-100">
            <i class="fas fa-file-alt mr-1"></i> Lamaran
        </button>
        <button onclick="filterFeed('company')" id="tab-company"
            class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:bg-slate-100">
            <i class="fas fa-building mr-1"></i> Perusahaan
        </button>
        <button onclick="filterFeed('event')" id="tab-event"
            class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:bg-slate-100">
            <i class="fas fa-calendar-alt mr-1"></i> Acara
        </button>
        <button onclick="filterFeed('news')" id="tab-news"
            class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all text-slate-500 hover:bg-slate-100">
            <i class="fas fa-newspaper mr-1"></i> Berita
        </button>
    </div>

    {{-- Feed Container --}}
    <div id="feed-container" class="space-y-4">

        {{-- Lowongan Terbaru --}}
        @forelse($recent_jobs ?? [] as $job)
        <div class="feed-item feed-job bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:border-blue-200 transition-all">
            <div class="p-3 rounded-xl bg-blue-50 text-blue-600 flex-shrink-0">
                <i class="fas fa-briefcase text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Lowongan</span>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}</span>
                </div>
                <p class="font-bold text-slate-800 mt-1">{{ $job->title }}</p>
                <p class="text-sm text-slate-500">{{ $job->company->company_name ?? '-' }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $job->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                    {{ ucfirst($job->status) }}
                </span>
                <a href="{{ route('admin.jobs.edit', $job->job_id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        @empty
        @endforelse

        {{-- Lamaran Terbaru --}}
        @forelse($recent_applications ?? [] as $app)
        <div class="feed-item feed-application bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:border-green-200 transition-all">
            <div class="p-3 rounded-xl bg-green-50 text-green-600 flex-shrink-0">
                <i class="fas fa-file-alt text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Lamaran</span>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($app->created_at)->diffForHumans() }}</span>
                </div>
                <p class="font-bold text-slate-800 mt-1">{{ $app->student->full_name ?? 'N/A' }}</p>
                <p class="text-sm text-slate-500">Melamar ke: {{ $app->job->title ?? '-' }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="px-3 py-1 text-xs rounded-full font-semibold
                    {{ $app->status == 'accepted' ? 'bg-green-100 text-green-600' :
                       ($app->status == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                    {{ strtoupper($app->status) }}
                </span>
                <a href="{{ route('admin.job-applications.index') }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        @empty
        @endforelse

        {{-- Perusahaan Terbaru --}}
        @forelse($recent_companies ?? [] as $company)
        <div class="feed-item feed-company bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:border-orange-200 transition-all">
            <div class="p-3 rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                <i class="fas fa-building text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Perusahaan</span>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($company->created_at)->diffForHumans() }}</span>
                </div>
                <p class="font-bold text-slate-800 mt-1">{{ $company->company_name }}</p>
                <p class="text-sm text-slate-500">{{ $company->industry ?? 'Industri tidak diketahui' }}</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.companies.index') }}" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        @empty
        @endforelse

        {{-- Acara Terbaru --}}
        @forelse($recent_events ?? [] as $event)
        <div class="feed-item feed-event bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:border-purple-200 transition-all">
            <div class="p-3 rounded-xl bg-purple-50 text-purple-600 flex-shrink-0">
                <i class="fas fa-calendar-alt text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Acara</span>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($event->created_at)->diffForHumans() }}</span>
                </div>
                <p class="font-bold text-slate-800 mt-1">{{ $event->title ?? $event->name ?? '-' }}</p>
                <p class="text-sm text-slate-500">
                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location ?? '-' }}
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.events.index') }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        @empty
        @endforelse

        {{-- Berita Terbaru --}}
        @forelse($recent_news ?? [] as $news)
        <div class="feed-item feed-news bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:border-slate-300 transition-all">
            <div class="p-3 rounded-xl bg-slate-100 text-slate-600 flex-shrink-0">
                <i class="fas fa-newspaper text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">Berita</span>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($news->created_at)->diffForHumans() }}</span>
                </div>
                <p class="font-bold text-slate-800 mt-1">{{ $news->title }}</p>
                <p class="text-sm text-slate-500 truncate">{{ Str::limit(strip_tags($news->content ?? ''), 80) }}</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.news.index') }}" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        @empty
        @endforelse

        {{-- Empty State --}}
        <div id="empty-state" class="hidden bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="text-4xl mb-3">📭</div>
            <p class="text-slate-500 font-medium">Belum ada aktivitas di kategori ini.</p>
        </div>

    </div>
</div>

<style>
.active-tab {
    background: #1e3a5f;
    color: white;
}
</style>

<script>
function filterFeed(type) {
    // Update tab styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active-tab', 'text-white');
        btn.classList.add('text-slate-500', 'hover:bg-slate-100');
    });

    const activeTab = document.getElementById('tab-' + type);
    activeTab.classList.add('active-tab', 'text-white');
    activeTab.classList.remove('text-slate-500', 'hover:bg-slate-100');

    // Filter items
    const allItems = document.querySelectorAll('.feed-item');
    let visibleCount = 0;

    allItems.forEach(item => {
        if (type === 'all' || item.classList.contains('feed-' + type)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Show/hide empty state
    const emptyState = document.getElementById('empty-state');
    emptyState.classList.toggle('hidden', visibleCount > 0);
}
</script>
@endsection