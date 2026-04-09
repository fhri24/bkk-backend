<!-- Footer -->
<footer class="bg-[#001f3f] text-slate-300 pt-20 pb-10">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
      <div>
        <div class="flex items-center space-x-3 mb-6">
          <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
            <i class="fas fa-briefcase text-[#001f3f]"></i>
          </div>
          <h2 class="text-white font-bold text-xl">BKK SMKN 1</h2>
        </div>
        <p class="text-sm leading-relaxed opacity-80 mb-6">Pusat pengembangan karir dan penyaluran tenaga kerja profesional khusus alumni SMKN 1 Garut menuju Indonesia Emas.</p>
        <div class="flex space-x-4">
          <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-instagram"></i></a>
          <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div>
        <h3 class="text-white font-bold mb-6 text-lg">Tautan Cepat</h3>
        <ul class="space-y-4 text-sm">
          <li>
            <a href="{{ route('student.lowongan') }}" class="hover:text-blue-400 transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2"></i> Lowongan Kerja</a>
          </li>
          <li>
            <a href="{{ route('student.acara') }}" class="hover:text-blue-400 transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2"></i> Jadwal Rekrutmen</a>
          </li>
          <li>
            <a href="{{ route('public.tutorial') }}" class="hover:text-blue-400 transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2"></i> Tutorial Pendaftaran</a>
          </li>
          <li>
            <a href="{{ route('student.tracer') }}" class="hover:text-blue-400 transition flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2"></i> Tracer Study</a>
          </li>
        </ul>
      </div>
      <div>
        <h3 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h3>
        <ul class="space-y-4 text-sm">
          <li class="flex items-start">
            <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-500"></i>
            <span>Jl. Cimanuk No. 309 A, Tarogong Kidul, Garut, Jawa Barat 44151</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-phone mr-3 text-blue-500"></i>
            <span>(0262) 233796</span>
          </li>
          <li class="flex items-center">
            <i class="fas fa-envelope mr-3 text-blue-500"></i>
            <span>bkk@smkn1garut.sch.id</span>
          </li>
        </ul>
      </div>
      <div>
        <h3 class="text-white font-bold mb-6 text-lg">Newsletter</h3>
        <p class="text-xs mb-4 opacity-70">Dapatkan info lowongan terbaru langsung di email Anda.</p>
        <div class="flex">
          <input type="email" placeholder="Email Anda" class="bg-white/5 border border-white/10 px-4 py-2.5 rounded-l-lg w-full focus:outline-none focus:border-blue-500" />
          <button class="bg-blue-600 px-4 rounded-r-lg hover:bg-blue-700"><i class="fas fa-paper-plane"></i></button>
        </div>
      </div>
    </div>
    <div class="border-t border-white/10 pt-8 text-center text-xs opacity-50">&copy; 2026 Sistem Informasi BKK Sekolah Menengah Kejuruan Negeri 1 Garut. Seluruh Hak Cipta Dilindungi.</div>
  </div>
</footer>
