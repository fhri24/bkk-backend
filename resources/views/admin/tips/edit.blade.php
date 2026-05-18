@extends('layouts.admin')

@section('title', 'Edit Tips')
@section('page_title', 'Edit Tips')

@section('extra_css')
<style>
  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
    margin-bottom: 6px;
  }
  .form-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    transition: border-color .2s;
  }
  .form-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
  }
  .form-textarea {
    min-height: 320px;
    resize: vertical;
    font-family: monospace;
    font-size: 13px;
  }
  input[type=checkbox]:checked ~ .toggle-bg { background: #2563eb; }
  input[type=checkbox]:checked ~ .toggle-dot { transform: translateX(20px); }
</style>
@endsection

@section('content')
<div class="max-w-3xl">

  <a href="{{ route('admin.tips.index') }}"
     class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 text-sm font-semibold mb-6 transition">
    <i class="fas fa-chevron-left text-xs"></i> Kembali ke Daftar Tips
  </a>

  <form method="POST" action="{{ route('admin.tips.update', $tip) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="table-custom p-8 space-y-6">

      <div>
        <label class="form-label">Judul Tips <span class="text-red-500">*</span></label>
        <input type="text" name="judul" value="{{ old('judul', $tip->judul) }}"
               class="form-input" required>
        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid sm:grid-cols-2 gap-6">
        <div>
          <label class="form-label">Kategori <span class="text-red-500">*</span></label>
          <select name="kategori" class="form-input" required onchange="updateIcon(this.value)">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoriList as $kat)
              <option value="{{ $kat }}" {{ old('kategori', $tip->kategori) === $kat ? 'selected' : '' }}>
                {{ $kat }}
              </option>
            @endforeach
          </select>
          @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="form-label">Icon (Font Awesome)</label>
          <div class="flex gap-3 items-center">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
              <i id="iconEl" class="{{ old('icon', $tip->icon) }}"></i>
            </div>
            <input type="text" name="icon" id="iconInput"
                   value="{{ old('icon', $tip->icon) }}"
                   class="form-input flex-1"
                   oninput="document.getElementById('iconEl').className = this.value">
          </div>
          <p class="text-xs text-slate-400 mt-1">
            Cari di <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-500 underline">fontawesome.com</a>
          </p>
        </div>
      </div>

      <div>
        <label class="form-label">
          Ringkasan <span class="text-red-500">*</span>
          <span class="normal-case font-normal text-slate-400 ml-1">(maks 500 karakter)</span>
        </label>
        <textarea name="ringkasan" class="form-input" rows="3"
                  maxlength="500" required>{{ old('ringkasan', $tip->ringkasan) }}</textarea>
        @error('ringkasan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="form-label">Konten Lengkap <span class="text-red-500">*</span></label>
        <p class="text-xs text-slate-400 mb-2">
          Bisa pakai tag HTML: &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;
        </p>
        <textarea name="konten" class="form-input form-textarea"
                  required>{{ old('konten', $tip->konten) }}</textarea>
        @error('konten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid sm:grid-cols-3 gap-6">
        <div>
          <label class="form-label">Urutan</label>
          <input type="number" name="urutan" min="0"
                 value="{{ old('urutan', $tip->urutan) }}" class="form-input">
          <p class="text-xs text-slate-400 mt-1">Angka kecil tampil duluan</p>
        </div>
        <div class="flex flex-col justify-end pb-1">
          <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative">
              <input type="hidden" name="is_published" value="0">
              <input type="checkbox" name="is_published" value="1" class="sr-only"
                     {{ old('is_published', $tip->is_published) ? 'checked' : '' }}>
              <div class="w-11 h-6 bg-slate-200 rounded-full toggle-bg transition"></div>
              <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow toggle-dot transition"></div>
            </div>
            <span class="text-sm font-semibold text-slate-700">Published</span>
          </label>
        </div>
        <div class="flex flex-col justify-end pb-1">
          <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative">
              <input type="hidden" name="is_featured" value="0">
              <input type="checkbox" name="is_featured" value="1" class="sr-only"
                     {{ old('is_featured', $tip->is_featured) ? 'checked' : '' }}>
              <div class="w-11 h-6 bg-slate-200 rounded-full toggle-bg transition"></div>
              <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow toggle-dot transition"></div>
            </div>
            <span class="text-sm font-semibold text-slate-700">Unggulan ⭐</span>
          </label>
        </div>
      </div>

    </div>

    <div class="flex gap-3">
      <button type="submit"
              class="flex-1 bg-blue-600 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition">
        <i class="fas fa-save mr-2"></i>Simpan Perubahan
      </button>
      <a href="{{ route('admin.tips.index') }}"
         class="px-8 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition flex items-center">
        Batal
      </a>
    </div>

  </form>
</div>
@endsection

@section('extra_js')
<script>
  const defaultIcons = {
    'Interview':       'fas fa-comments',
    'Psikotes':        'fas fa-brain',
    'CV & Portofolio': 'fas fa-file-alt',
    'Dunia Kerja':     'fas fa-briefcase',
    'Wirausaha':       'fas fa-store',
    'Lainnya':         'fas fa-lightbulb',
  };
  function updateIcon(kategori) {
    const icon = defaultIcons[kategori] || 'fas fa-lightbulb';
    document.getElementById('iconInput').value = icon;
    document.getElementById('iconEl').className = icon;
  }
</script>
@endsection 