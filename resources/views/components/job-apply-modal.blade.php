{{-- resources/views/components/job-apply-modal.blade.php --}}
<div id="applicationModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4"
     onclick="closeModalOutside(event)">

    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-8 relative">

        {{-- Close --}}
        <button onclick="closeApplicationForm()"
                class="absolute top-5 right-5 w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition">
            <i class="fas fa-times"></i>
        </button>

        <h2 class="text-2xl font-extrabold text-slate-800 mb-2">Lamar Lowongan</h2>
        <p class="text-slate-500 text-sm mb-8">Isi data lamaran kamu dengan benar dan lengkap.</p>

        @auth
        <form action="{{ route('student.lowongan.apply', $job->job_id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Cover Letter --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Surat Lamaran / Cover Letter</label>
                <textarea name="cover_letter" rows="4"
                    placeholder="Ceritakan mengapa kamu cocok untuk posisi ini..."
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"></textarea>
            </div>

            {{-- CV Upload --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Upload CV (PDF, max 2MB)</label>
                <input type="file" name="cv" accept=".pdf"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-black shadow-lg shadow-blue-200 transition">
                <i class="fas fa-paper-plane mr-2"></i> KIRIM LAMARAN
            </button>
        </form>
        @else
        <div class="text-center py-6">
            <p class="text-slate-500 mb-4">Kamu harus login untuk melamar.</p>
            <a href="{{ route('login') }}"
               class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-black">Login Sekarang</a>
        </div>
        @endauth
    </div>
</div>

<style>
    #applicationModal.show { display: flex; }
</style>

<script>
    function closeApplicationForm() {
        document.getElementById('applicationModal').classList.remove('show');
    }
    function closeModalOutside(e) {
        if (e.target === document.getElementById('applicationModal')) closeApplicationForm();
    }
</script>