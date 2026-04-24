@extends('layouts.app')

@section('title', 'Tracer Study - BKK SMKN 1 Garut')

@section('extra_css')
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in-out;
  }

  .modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-content {
    background: white;
    border-radius: 24px;
    width: 90%;
    max-width: 900px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }
</style>
@endsection

@section('content')
<div class="page-transition container mx-auto px-6 py-16">
  <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
    <div>
      <h2 class="text-4xl font-extrabold text-[#001f3f] mb-6">Tracer Study & <br />Laporan Karir</h2>
      <p class="text-slate-500 text-lg leading-relaxed mb-8">Sistem pelacakan jejak alumni untuk memetakan kualitas pendidikan dan kebutuhan dunia industri yang dinamis.</p>
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-blue-600 p-6 rounded-2xl text-white shadow-lg">
          <div class="text-3xl font-bold mb-1">92%</div>
          <div class="text-[10px] font-bold uppercase tracking-widest opacity-80">Data Terverifikasi</div>
        </div>
        <div class="bg-slate-800 p-6 rounded-2xl text-white shadow-lg">
          <div class="text-3xl font-bold mb-1">450+</div>
          <div class="text-[10px] font-bold uppercase tracking-widest opacity-80">User Surveyed</div>
        </div>
      </div>
    </div>

    <div class="bg-white p-8 rounded-[40px] shadow-2xl border border-slate-100 h-[360px]">
      <canvas id="tracerChart" width="400" height="300"></canvas>
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-8">
    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100 group">
      <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition duration-500">
        <i class="fas fa-user-graduate"></i>
      </div>
      <h3 class="text-2xl font-bold text-slate-800 mb-4">Tracer Study Report</h3>
      <p class="text-slate-500 mb-8 leading-relaxed">Laporan lengkap hasil pelacakan alumni mencakup masa tunggu kerja, rata-rata pendapatan, dan relevansi kurikulum sekolah dengan dunia kerja.</p>
      <button class="bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition">Lihat Laporan Alumni</button>
    </div>

    <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100 group">
      <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-8 group-hover:bg-green-600 group-hover:text-white transition duration-500">
        <i class="fas fa-building-user"></i>
      </div>
      <h3 class="text-2xl font-bold text-slate-800 mb-4">User Study Report (DUDI)</h3>
      <p class="text-slate-500 mb-8 leading-relaxed">Survei tingkat kepuasan mitra industri terhadap performa kerja alumni SMKN 1 Garut sebagai bahan evaluasi pengembangan sekolah.</p>
      <button onclick="openIndustryReport()" class="block bg-slate-100 text-slate-800 px-8 py-3.5 rounded-xl font-bold hover:bg-green-600 hover:text-white transition text-center">Lihat Laporan Industri</button>
    </div>
  </div>
</div>

<div id="industryModal" class="modal">
  <div class="modal-content max-w-4xl max-h-[90vh] overflow-y-auto">
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-8 text-white sticky top-0 flex justify-between items-center">
      <h2 class="text-2xl font-extrabold">Survey Kepuasan Pengguna Lulusan (DUDI)</h2>
      <button onclick="closeIndustryReport()" class="text-white text-2xl hover:text-green-200 transition">&times;</button>
    </div>

    <div class="p-8 space-y-8">
      <div class="bg-green-50 border border-green-200 p-6 rounded-xl">
        <p class="text-slate-700 leading-relaxed mb-4"><strong>Responden Yth.</strong></p>
        <p class="text-slate-700 leading-relaxed">Dalam rangka peningkatan mutu lulusan dan memenuhi kebutuhan untuk kelengkapan akreditasi serta penyiapan kompetensi lulusan agar lebih relevan dengan kebutuhan industri, Bursa Kerja Khusus SMKN 1 Garut melakukan survey terhadap industri pengguna lulusan.</p>
        <p class="text-slate-700 leading-relaxed mt-4">Kami dengan hormat memohon bantuan Bapak/Ibu sebagai atasan langsung untuk memberikan penilaian, masukan, dan saran terhadap kinerja lulusan kami yang bekerja di perusahaan Bapak/Ibu. Data isian tersebut sangat kami perlukan sebagai feedback bagi pengembangan sekolah.</p>
      </div>

      <div>
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-building text-green-600 mr-3"></i>Informasi Perusahaan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-6 rounded-xl">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Perusahaan</label>
            <input type="text" placeholder="Masukkan Nama Perusahaan" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jenis Perusahaan (Badan Hukum)</label>
            <select class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600">
              <option>Pilih Jenis Perusahaan</option>
              <option>PT (Perseroan Terbatas)</option>
              <option>CV (Commanditaire Vennootschap)</option>
              <option>Koperasi</option>
              <option>BUMN</option>
              <option>Lainnya</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Perusahaan</label>
            <input type="text" placeholder="Masukkan Alamat Perusahaan" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Bisnis Utama Perusahaan</label>
            <input type="text" placeholder="Contoh: Manufaktur, Teknologi, dll" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600" />
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-user-tie text-green-600 mr-3"></i>Informasi Responden</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-6 rounded-xl">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Responden</label>
            <input type="text" placeholder="Masukkan Nama Responden" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jabatan Responden</label>
            <input type="text" placeholder="Contoh: HRD Manager, Pimpinan, dll" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Responden</label>
            <input type="email" placeholder="Masukkan Email Responden" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-green-600" />
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-star text-green-600 mr-3"></i>Penilaian Kemampuan Lulusan</h3>
        <p class="text-sm text-slate-600 mb-4">Silahkan isi penilaian kemampuan lulusan SMKN 1 Garut yang bekerja di perusahaan Bapak/Ibu (Skala: 1=Sangat Kurang, 5=Sangat Baik)</p>
        <div class="space-y-3 bg-slate-50 p-6 rounded-xl">
          @foreach(['Integritas (Etika dan Moral)' => 'integritas','Keahlian Berdasarkan Bidang Ilmu' => 'keahlian','Keterampilan Bahasa Inggris' => 'bahasa','Penggunaan Teknologi & TIK' => 'teknologi','Keterampilan Komunikasi' => 'komunikasi','Kerja Sama Tim' => 'teamwork','Pemikiran Analitis & Inovasi' => 'analitis','Kemampuan Kepemimpinan' => 'kepemimpinan','Kemampuan Bekerja Dibawah Tekanan' => 'tekanan'] as $label => $name)
            <div class="flex justify-between items-center py-3 border-b border-slate-200">
              <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
              <div class="flex gap-2">
                @for($i = 1; $i <= 5; $i++)
                  <label><input type="radio" name="{{ $name }}" class="mr-1" /> {{ $i }}</label>
                @endfor
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div>
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center"><i class="fas fa-lightbulb text-green-600 mr-3"></i>Masukan & Saran</h3>
        <div class="bg-slate-50 p-6 rounded-xl">
          <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Berikan saran untuk peningkatan kualitas lulusan SMKN 1 Garut</label>
          <textarea placeholder="Tulis masukan dan saran Bapak/Ibu di sini..." class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-green-600 h-24 resize-none"></textarea>
        </div>
      </div>

      <div class="flex gap-3 sticky bottom-0 bg-white pt-4 border-t border-slate-200">
        <button onclick="submitIndustrySurvey()" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition">Kirim Survey</button>
        <button onclick="closeIndustryReport()" class="flex-1 bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  function openIndustryReport() {
    const modal = document.getElementById('industryModal');
    if (!modal) return;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeIndustryReport() {
    const modal = document.getElementById('industryModal');
    if (!modal) return;
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
  }

  function submitIndustrySurvey() {
    alert('Terima kasih atas partisipasi Bapak/Ibu dalam survey kepuasan pengguna lulusan. Data Anda telah kami terima dan akan digunakan untuk meningkatkan kualitas pendidikan kami.');
    closeIndustryReport();
  }

  function initTracerChart() {
    const ctx = document.getElementById('tracerChart');
    if (!ctx) return;

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Bekerja', 'Kuliah', 'Wirausaha', 'Mencari Kerja'],
        datasets: [{
          data: [65, 15, 12, 8],
          backgroundColor: ['#2563eb', '#9333ea', '#f59e0b', '#94a3b8'],
          borderWidth: 0,
          hoverOffset: 15,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              font: {
                family: "'Plus Jakarta Sans'",
                size: 11,
                weight: 'bold',
              },
            },
          },
        },
      },
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    const industryModal = document.getElementById('industryModal');
    if (industryModal) {
      industryModal.addEventListener('click', function (e) {
        if (e.target === this) {
          closeIndustryReport();
        }
      });
    }
    initTracerChart();
  });
</script>
@endsection
