@extends('layouts.app')

@section('content')

<style>
  .search-hero {
    background:
      linear-gradient(
        135deg,
        rgba(30, 58, 138, 0.85),
        rgba(0, 31, 63, 0.85)
      ),
      url("https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80");
    background-size: cover;
    background-position: center;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in;
  }

  .modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  .modal-content {
    background-color: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease-out;
  }

  @keyframes slideUp {
    from {
      transform: translateY(50px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .job-item {
    display: none;
  }

  .job-item.show {
    display: flex;
  }

  .saved-badge {
    position: absolute;
    top: 20px;
    right: 20px;
  }

  .saved-icon {
    color: #dc2626;
  }
</style>

<!-- Main Content -->
<div class="search-hero">
  <div class="container mx-auto px-6 text-center text-white w-full">
    <h1 class="text-4xl md:text-5xl font-extrabold mb-3">
      Sistem Informasi Bursa Kerja
    </h1>
    <p class="text-lg md:text-xl opacity-90 mb-10">
      Temukan pekerjaan terbaik sesuai keahlianmu
    </p>
    <div
      class="flex flex-col md:flex-row justify-center items-center gap-3 max-w-xl mx-auto"
    >
      <input
        type="text"
        id="searchInput"
        placeholder="Cari pekerjaan..."
        class="w-full md:flex-1 px-6 py-3.5 rounded-lg text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-amber-400"
      />
      <button
        onclick="performSearch()"
        class="w-full md:w-auto bg-amber-400 hover:bg-amber-500 text-slate-900 px-10 py-3.5 rounded-lg font-bold transition transform hover:scale-105 active:scale-95"
      >
        Cari
      </button>
    </div>
  </div>
</div>

<div class="page-transition container mx-auto px-6 py-16">
  <div
    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6"
  >
    <div>
      <h2 class="text-4xl font-extrabold text-[#001f3f]">
        Lowongan Terbaru
      </h2>
      <p class="text-slate-500 mt-2 font-medium">
        Temukan peluang dari BKK Sekolah & Portal Kemenaker
      </p>
    </div>
    <div
      class="flex bg-white p-1 rounded-xl shadow-sm border border-slate-200"
      id="filterButtons"
    >
      <button
        class="filter-btn px-6 py-2.5 rounded-lg bg-blue-600 text-white font-bold text-sm active-btn"
        data-filter="all"
        onclick="filterJobs('all')"
      >
        Semua
      </button>
      <button
        class="filter-btn px-6 py-2.5 rounded-lg text-slate-600 font-bold text-sm hover:bg-slate-50"
        data-filter="internal"
        onclick="filterJobs('internal')"
      >
        Internal BKK
      </button>
      <button
        class="filter-btn px-6 py-2.5 rounded-lg text-slate-600 font-bold text-sm hover:bg-slate-50"
        data-filter="kemenaker"
        onclick="filterJobs('kemenaker')"
      >
        Kemenaker
      </button>
    </div>
  </div>

  <div class="grid lg:grid-cols-4 gap-8">
    <aside class="space-y-8">
      <div
        class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100"
      >
        <h4 class="font-bold mb-4 flex items-center text-slate-800">
          <i class="fas fa-filter mr-2 text-blue-500"></i> Filter
        </h4>
        <form action="{{ route('student.lowongan') }}" method="GET">
          <div class="mb-4">
            <p class="font-bold mb-2">Tipe Pekerjaan</p>
            <label class="flex items-center space-x-2">
              <input type="radio" name="type" value="Full-time" {{ request('type') == 'Full-time' ? 'checked' : '' }}>
              <span>Full Time</span>
            </label>
            <label class="flex items-center space-x-2">
              <input type="radio" name="type" value="Kontrak" {{ request('type') == 'Kontrak' ? 'checked' : '' }}>
              <span>Kontrak</span>
            </label>
            <label class="flex items-center space-x-2">
              <input type="radio" name="type" value="Magang" {{ request('type') == 'Magang' ? 'checked' : '' }}>
              <span>Magang (PKL)</span>
            </label>
          </div>

          <div class="mb-4">
            <p class="font-bold mb-2">Bidang Keahlian</p>
            <select name="major" class="w-full p-2 border rounded-xl">
              <option value="Semua Jurusan">Pilih Jurusan</option>
              <option value="Teknik Otomotif" {{ request('major') == 'Teknik Otomotif' ? 'selected' : '' }}>Teknik Otomotif</option>
              <option value="Teknik Komputer" {{ request('major') == 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
              <option value="Tata Boga" {{ request('major') == 'Tata Boga' ? 'selected' : '' }}>Tata Boga</option>
            </select>
          </div>

          <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-xl">Terapkan Filter</button>
          <a href="{{ route('student.lowongan') }}" class="block text-center text-sm text-gray-500 mt-2">Reset Filter</a>
        </form>
      </div>

      <div
        class="bg-blue-600 p-6 rounded-2xl text-white shadow-xl relative overflow-hidden"
      >
        <i
          class="fas fa-briefcase absolute -bottom-4 -right-4 text-8xl opacity-10 rotate-12"
        ></i>
        <h4 class="font-bold mb-2">Info Kemenaker</h4>
        <p class="text-xs text-blue-100 leading-relaxed mb-4">
          Akses lowongan resmi dari portal Karirhub Kemnaker RI langsung
          dari sini.
        </p>
        <button
          class="bg-white text-blue-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-50"
        >
          Buka Karirhub
        </button>
      </div>
    </aside>

    <div class="lg:col-span-3 space-y-6" id="jobList">
      @foreach($jobs as $job)
        <div
          class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-6 hover:border-blue-200 hover:shadow-md transition job-item show"
          data-category="{{ $job->category ?? 'internal' }}"
          data-job="{{ $job->title }}"
          data-company="{{ $job->company->company_name ?? 'Perusahaan' }}"
          data-type="{{ $job->type ?? 'Tetap' }}"
          data-skill="{{ $job->skill ?? 'Semua' }}"
        >
          <div
            class="w-20 h-20 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border"
          >
            <img
              src="{{ $job->company->logo ?? 'https://via.placeholder.com/80' }}"
              class="w-12 h-12 object-contain"
              alt="{{ $job->company->company_name ?? 'Logo' }}"
            />
          </div>
          <div class="flex-1 relative">
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-extrabold text-xl text-slate-800">
                {{ $job->title }}
              </h3>
              <span
                class="bg-{{ $job->category == 'kemenaker' ? 'green' : 'blue' }}-100 text-{{ $job->category == 'kemenaker' ? 'green' : 'blue' }}-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase"
                >{{ $job->category == 'kemenaker' ? 'Kemenaker Verified' : 'Internal BKK' }}</span
              >
            </div>
            <p class="text-blue-600 font-bold text-sm mb-4">
              {{ $job->company->company_name ?? 'Nama Perusahaan' }}
            </p>
            <div
              class="flex flex-wrap gap-4 text-sm text-slate-500 font-medium mb-6"
            >
              <span class="flex items-center"
                ><i class="fas fa-map-marker-alt mr-2"></i> {{ $job->location ?? 'Lokasi' }}</span
              >
              <span class="flex items-center"
                ><i class="fas fa-money-bill-wave mr-2"></i> {{ $job->salary ?? 'Gaji' }}</span
              >
              <span class="flex items-center"
                ><i class="fas fa-briefcase mr-2"></i> {{ $job->type ?? 'Tetap' }}</span
              >
            </div>
            <p
              class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-2"
            >
              {{ $job->description ?? 'Deskripsi pekerjaan.' }}
            </p>
            <div class="flex gap-3">
              <a
                href="{{ route('student.lowongan.detail', $job->job_id) }}"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition inline-flex items-center"
              >
                Detail
              </a>
              <form action="{{ route('student.lowongan.save', $job->job_id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
                  <i class="{{ Auth::user()->savedJobs->contains('job_id', $job->job_id) ? 'fas' : 'far' }} fa-bookmark mr-2"></i>Simpan
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal">
  <div class="modal-content">
    <div
      class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 text-white flex justify-between items-start relative"
    >
      <div class="flex gap-6 flex-1">
        <img
          id="modalLogo"
          src=""
          alt="Logo"
          class="w-20 h-20 bg-white rounded-xl p-2 object-contain"
        />
        <div>
          <h2 id="modalJobTitle" class="text-3xl font-extrabold mb-2"></h2>
          <p id="modalCompany" class="text-blue-100 font-bold text-lg"></p>
        </div>
      </div>
      <button
        onclick="closeDetail()"
        class="text-white text-3xl font-bold hover:text-blue-200 transition"
      >
        &times;
      </button>
    </div>

    <div class="p-8 space-y-6">
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-slate-50 p-4 rounded-xl">
          <p class="text-xs text-slate-500 font-bold uppercase mb-1">
            Lokasi
          </p>
          <p id="modalLocation" class="font-bold text-slate-800"></p>
        </div>
        <div class="bg-slate-50 p-4 rounded-xl">
          <p class="text-xs text-slate-500 font-bold uppercase mb-1">
            Gaji
          </p>
          <p id="modalSalary" class="font-bold text-slate-800"></p>
        </div>
        <div class="bg-slate-50 p-4 rounded-xl">
          <p class="text-xs text-slate-500 font-bold uppercase mb-1">
            Tipe
          </p>
          <p id="modalType" class="font-bold text-slate-800"></p>
        </div>
      </div>

      <div>
        <h3 class="font-bold text-lg text-slate-800 mb-3">
          Deskripsi Pekerjaan
        </h3>
        <p id="modalDescription" class="text-slate-600 leading-relaxed"></p>
      </div>

      <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
        <p class="text-sm text-slate-600">
          <span class="font-bold">Catatan:</span> Silakan hubungi BKK SMKN 1
          Garut untuk informasi lebih lanjut atau proses pendaftaran.
        </p>
      </div>

      <div class="flex gap-3">
        <button
          onclick="saveLowonganFromModal()"
          class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition"
        >
          <i class="far fa-bookmark mr-2"></i>Simpan Lowongan
        </button>
        <button
          onclick="closeDetail()"
          class="flex-1 bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition"
        >
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Saved Jobs Modal -->
<div id="savedModal" class="modal">
  <div class="modal-content">
    <div
      class="bg-blue-600 p-6 text-white flex justify-between items-center"
    >
      <h2 class="text-2xl font-extrabold">Lowongan Tersimpan</h2>
      <button
        onclick="closeSavedModal()"
        class="text-white text-3xl font-bold hover:text-blue-200"
      >
        &times;
      </button>
    </div>

    <div class="p-8">
      <div id="savedList" class="space-y-4">
        <p class="text-slate-500 text-center py-8">
          Belum ada lowongan tersimpan
        </p>
      </div>
      <button
        onclick="closeSavedModal()"
        class="w-full mt-6 bg-slate-200 text-slate-800 px-6 py-3 rounded-xl font-bold hover:bg-slate-300 transition"
      >
        Tutup
      </button>
    </div>
  </div>
</div>

<script>
  let currentModalJob = { title: "", company: "" };
  let savedJobs = JSON.parse(localStorage.getItem("savedJobs")) || [];
  let currentFilters = {
    category: "all",
    types: [],
    skill: "Semua",
  };

  function performSearch() {
    const searchTerm = document
      .getElementById("searchInput")
      .value.toLowerCase()
      .trim();

    if (searchTerm === "") {
      alert("Silakan masukkan pencarian");
      return;
    }

    const jobItems = document.querySelectorAll(".job-item");
    let found = false;

    // Reset filters untuk pencarian
    currentFilters = { category: "all", types: [], skill: "Semua" };

    jobItems.forEach((item) => {
      const jobName = item.getAttribute("data-job").toLowerCase();
      const company = item.getAttribute("data-company").toLowerCase();
      const jobHTML = item.innerText.toLowerCase();

      if (
        jobName.includes(searchTerm) ||
        company.includes(searchTerm) ||
        jobHTML.includes(searchTerm)
      ) {
        item.classList.add("show");
        found = true;
      } else {
        item.classList.remove("show");
      }
    });

    if (!found) {
      alert("Lowongan tidak ditemukan. Coba cari dengan keyword lain.");
    }

    // Update filter buttons
    updateFilterButtons();
  }

  function filterJobs(category) {
    currentFilters.category = category;
    applyFilters();
    updateFilterButtons();
  }

  function filterByType(type) {
    const index = currentFilters.types.indexOf(type);
    if (index > -1) {
      currentFilters.types.splice(index, 1);
    } else {
      currentFilters.types.push(type);
    }
    applyFilters();
    updateTypeFilterButtons();
  }

  function filterBySkill(skill) {
    currentFilters.skill = skill;
    applyFilters();
    updateSkillFilterButtons();
  }

  function resetFilters() {
    currentFilters = { category: "all", types: [], skill: "Semua" };
    document.getElementById("searchInput").value = "";
    applyFilters();
    updateFilterButtons();
    updateTypeFilterButtons();
    updateSkillFilterButtons();
  }

  function applyFilters() {
    const jobItems = document.querySelectorAll(".job-item");

    jobItems.forEach((item) => {
      let show = true;

      // Check category filter
      if (currentFilters.category !== "all") {
        if (
          item.getAttribute("data-category") !== currentFilters.category
        ) {
          show = false;
        }
      }

      // Check type filter
      if (currentFilters.types.length > 0) {
        const itemType = item.getAttribute("data-type");
        if (!currentFilters.types.includes(itemType)) {
          show = false;
        }
      }

      // Check skill filter
      if (currentFilters.skill !== "Semua") {
        const itemSkill = item.getAttribute("data-skill");
        if (itemSkill !== currentFilters.skill) {
          show = false;
        }
      }

      if (show) {
        item.classList.add("show");
      } else {
        item.classList.remove("show");
      }
    });
  }

  function updateFilterButtons() {
    document.querySelectorAll(".filter-btn").forEach((btn) => {
      btn.classList.remove("bg-blue-600", "text-white", "active-btn");
      btn.classList.add("text-slate-600", "hover:bg-slate-50");
    });

    const activeBtn = document.querySelector(
      `[data-filter="${currentFilters.category}"]`,
    );
    if (activeBtn) {
      activeBtn.classList.add("bg-blue-600", "text-white", "active-btn");
      activeBtn.classList.remove("text-slate-600", "hover:bg-slate-50");
    }
  }

  function updateTypeFilterButtons() {
    document.querySelectorAll(".type-filter-btn").forEach((btn) => {
      const type = btn.getAttribute("data-type");
      if (currentFilters.types.includes(type)) {
        btn.classList.add("bg-blue-50", "border-blue-300", "text-blue-600");
        btn.classList.remove("border-slate-200", "text-slate-600");
      } else {
        btn.classList.remove(
          "bg-blue-50",
          "border-blue-300",
          "text-blue-600",
        );
        btn.classList.add("border-slate-200", "text-slate-600");
      }
    });
  }

  function updateSkillFilterButtons() {
    document.querySelectorAll(".skill-filter-btn").forEach((btn) => {
      const skill = btn.getAttribute("data-skill");
      if (skill === currentFilters.skill) {
        btn.classList.add("bg-blue-50", "border-blue-300", "text-blue-600");
        btn.classList.remove("border-slate-200", "text-slate-600");
      } else {
        btn.classList.remove(
          "bg-blue-50",
          "border-blue-300",
          "text-blue-600",
        );
        btn.classList.add("border-slate-200", "text-slate-600");
      }
    });
  }

  function openDetail(
    id,
    jobTitle,
    company,
    logo,
    location,
    salary,
    type,
    description,
  ) {
    currentModalJob = { title: jobTitle, company: company };

    document.getElementById("modalLogo").src = logo;
    document.getElementById("modalJobTitle").innerText = jobTitle;
    document.getElementById("modalCompany").innerText = company;
    document.getElementById("modalLocation").innerText = location;
    document.getElementById("modalSalary").innerText = salary;
    document.getElementById("modalType").innerText = type;
    document.getElementById("modalDescription").innerText = description;

    document.getElementById("detailModal").classList.add("show");
  }

  function closeDetail() {
    document.getElementById("detailModal").classList.remove("show");
  }

  function saveLowongan(btn, jobTitle, company) {
    const job = { title: jobTitle, company: company };
    const exists = savedJobs.some(
      (j) => j.title === jobTitle && j.company === company,
    );

    if (!exists) {
      savedJobs.push(job);
      localStorage.setItem("savedJobs", JSON.stringify(savedJobs));

      btn.innerHTML =
        '<i class="fas fa-bookmark mr-2 text-red-600"></i>Tersimpan';
      btn.classList.add("text-red-600");
      btn.classList.remove("text-slate-600");
      updateSavedCount();
    } else {
      savedJobs = savedJobs.filter(
        (j) => !(j.title === jobTitle && j.company === company),
      );
      localStorage.setItem("savedJobs", JSON.stringify(savedJobs));

      btn.innerHTML = '<i class="far fa-bookmark mr-2"></i>Simpan';
      btn.classList.remove("text-red-600");
      btn.classList.add("text-slate-600");
      updateSavedCount();
    }
  }

  function saveLowonganFromModal() {
    const job = currentModalJob;
    const exists = savedJobs.some(
      (j) => j.title === job.title && j.company === job.company,
    );

    if (!exists) {
      savedJobs.push(job);
      localStorage.setItem("savedJobs", JSON.stringify(savedJobs));
      alert("Lowongan berhasil disimpan!");
      updateSavedCount();
    } else {
      alert("Lowongan sudah disimpan sebelumnya!");
    }
  }

  function closeSavedModal() {
    document.getElementById("savedModal").classList.remove("show");
  }

  function showSavedJobs() {
    const savedList = document.getElementById("savedList");

    if (savedJobs.length === 0) {
      savedList.innerHTML =
        '<p class="text-slate-500 text-center py-8">Belum ada lowongan tersimpan</p>';
    } else {
      savedList.innerHTML = savedJobs
        .map(
          (job, index) => `
        <div class="bg-slate-50 p-4 rounded-xl flex justify-between items-center border border-slate-200">
          <div>
            <p class="font-bold text-slate-800">${job.title}</p>
            <p class="text-sm text-slate-500">${job.company}</p>
          </div>
          <button onclick="removeSavedJob(${index})" class="text-red-600 hover:text-red-700 font-bold text-sm"><i class="fas fa-trash mr-1"></i>Hapus</button>
        </div>
      `,
        )
        .join("");
    }

    document.getElementById("savedModal").classList.add("show");
  }

  function removeSavedJob(index) {
    savedJobs.splice(index, 1);
    localStorage.setItem("savedJobs", JSON.stringify(savedJobs));
    showSavedJobs();
    updateSavedCount();
  }

  function updateSavedCount() {
    const count = savedJobs.length;
    const badge = document.getElementById("savedCount");
    if (count > 0) {
      badge.innerText = count;
      badge.style.display = "flex";
    } else {
      badge.style.display = "none";
    }
  }

  // Event listeners
  document
    .getElementById("searchInput")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        performSearch();
      }
    });

  // Update button states on load
  window.addEventListener("load", function () {
    updateSavedCount();
    updateFilterButtons();
    updateTypeFilterButtons();
    updateSkillFilterButtons();

    const jobItems = document.querySelectorAll(".job-item");
    jobItems.forEach((item) => {
      const buttons = item.querySelectorAll("button");
      const lastBtn = buttons[buttons.length - 1];

      if (lastBtn && lastBtn.innerText.includes("Simpan")) {
        const title = item.querySelector("h3").innerText;
        const company = item.querySelector("p.text-blue-600").innerText;
        const exists = savedJobs.some(
          (j) => j.title === title && j.company === company,
        );

        if (exists) {
          lastBtn.innerHTML =
            '<i class="fas fa-bookmark mr-2 text-red-600"></i>Tersimpan';
          lastBtn.classList.add("text-red-600");
          lastBtn.classList.remove("text-slate-600");
        }
      }
    });
  });
</script>
@endsection