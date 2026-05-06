<footer class="bg-[#001f3f] text-slate-300 pt-20 pb-10">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

      {{-- Kolom 1: Logo & Sosmed --}}
      <div>
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center overflow-hidden">
            @if(!empty($schoolProfile->logo_path))
              <img src="{{ asset('storage/' . $schoolProfile->logo_path) }}"
                   class="w-full h-full object-contain p-1" alt="Logo">
            @elseif(!empty($schoolProfile->logo))
              <img src="{{ asset('storage/' . $schoolProfile->logo) }}"
                   class="w-full h-full object-contain p-1" alt="Logo">
            @else
              <i class="fas fa-briefcase text-[#001f3f]"></i>
            @endif
          </div>
          <h2 class="text-white font-bold text-xl">
            {{ $schoolProfile->school_name ?? $schoolProfile->name ?? 'BKK SMKN 1' }}
          </h2>
        </div>

        <p class="text-sm leading-relaxed opacity-80 mb-6">
          {{ $schoolProfile->site_description ?? 'Pusat pengembangan karir dan penyaluran tenaga kerja profesional khusus alumni SMKN 1 Garut menuju Indonesia Emas.' }}
        </p>

        {{-- Sosial Media --}}
        <div class="flex space-x-4">
          @if(!empty($schoolProfile->instagram))
            <a href="{{ $schoolProfile->instagram }}" target="_blank" rel="noopener"
               class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-pink-500 transition">
              <i class="fab fa-instagram"></i>
            </a>
          @else
            <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-pink-500 transition">
              <i class="fab fa-instagram"></i>
            </a>
          @endif

          @if(!empty($schoolProfile->facebook))
            <a href="{{ $schoolProfile->facebook }}" target="_blank" rel="noopener"
               class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-blue-600 transition">
              <i class="fab fa-facebook-f"></i>
            </a>
          @else
            <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-blue-600 transition">
              <i class="fab fa-facebook-f"></i>
            </a>
          @endif

          @if(!empty($schoolProfile->twitter))
            <a href="{{ $schoolProfile->twitter }}" target="_blank" rel="noopener"
               class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-sky-500 transition">
              <i class="fab fa-twitter"></i>
            </a>
          @else
            <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-sky-500 transition">
              <i class="fab fa-linkedin-in"></i>
            </a>
          @endif

          @if(!empty($schoolProfile->youtube))
            <a href="{{ $schoolProfile->youtube }}" target="_blank" rel="noopener"
               class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-red-500 transition">
              <i class="fab fa-youtube"></i>
            </a>
          @endif
        </div>
      </div>

      {{-- Kolom 2: Tautan Cepat --}}
      <div>
        <h3 class="text-white font-bold mb-6 text-lg">Tautan Cepat</h3>
        <ul class="space-y-4 text-sm">
          <li>
            <a href="{{ route('public.lowongan') }}" class="hover:text-blue-400 transition flex items-center">
              <i class="fas fa-chevron-right text-[10px] mr-2"></i> Lowongan Kerja
            </a>
          </li>
          <li>
            <a href="{{ route('public.acara') }}" class="hover:text-blue-400 transition flex items-center">
              <i class="fas fa-chevron-right text-[10px] mr-2"></i> Jadwal Rekrutmen
            </a>
          </li>
          <li>
            <a href="{{ route('public.tutorial') }}" class="hover:text-blue-400 transition flex items-center">
              <i class="fas fa-chevron-right text-[10px] mr-2"></i> Tutorial Pendaftaran
            </a>
          </li>
          <li>
            <a href="{{ route('public.tracer') }}" class="hover:text-blue-400 transition flex items-center">
              <i class="fas fa-chevron-right text-[10px] mr-2"></i> Tracer Study
            </a>
          </li>
        </ul>
      </div>

      {{-- Kolom 3: Hubungi Kami --}}
      <div>
        <h3 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h3>
        <ul class="space-y-4 text-sm">
          <li class="flex items-start">
            <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-500 shrink-0"></i>
            <span>
              {{ $schoolProfile->school_address ?? $schoolProfile->address ?? 'Jl. Cimanuk No. 309 A, Tarogong Kidul, Garut, Jawa Barat 44151' }}
            </span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-phone mr-3 text-blue-500 shrink-0"></i>
            <span>{{ $schoolProfile->phone ?? '(0262) 233796' }}</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-envelope mr-3 text-blue-500 shrink-0"></i>
            @if(!empty($schoolProfile->email))
              <a href="mailto:{{ $schoolProfile->email }}" class="hover:text-blue-400 transition">
                {{ $schoolProfile->email }}
              </a>
            @else
              <span>bkk@smkn1garut.sch.id</span>
            @endif
          </li>
        </ul>
      </div>

      {{-- Kolom 4: Newsletter --}}
      <div>
        <h3 class="text-white font-bold mb-6 text-lg">Newsletter</h3>
        <p class="text-xs mb-4 opacity-70">Dapatkan info lowongan terbaru langsung di email Anda.</p>
        <div class="flex">
          <input type="email" placeholder="Email Anda"
                 class="bg-white/5 border border-white/10 px-4 py-2.5 rounded-l-lg w-full focus:outline-none focus:border-blue-500" />
          <button class="bg-blue-600 px-4 rounded-r-lg hover:bg-blue-700 transition">
            <i class="fas fa-paper-plane"></i>
          </button>
        </div>
      </div>

    </div>

    {{-- Copyright --}}
    <div class="border-t border-white/10 pt-8 text-center text-xs opacity-50">
      &copy; {{ date('Y') }} Sistem Informasi BKK
      {{ $schoolProfile->school_name ?? $schoolProfile->name ?? 'Sekolah Menengah Kejuruan Negeri 1 Garut' }}.
      Seluruh Hak Cipta Dilindungi.
    </div>

  </div>
</footer>