/**
 * asset/js/kartu_sesi.js — Logika Kartu Sesi & Filter Temukan Pertandingan
 * Menangani dataset sesi mock (Esports & Olahraga), interaksi Solusi 1
 * (Klik Kartu = Buka Detail Sesi, Tombol Gabung = Masuk Lobby),
 * banner Hot Match dengan fallback logic, serta filter interaktif.
 */

document.addEventListener("DOMContentLoaded", () => {
  const sessionsContainer = document.querySelector("#sessions");
  if (!sessionsContainer) return;

  // Deteksi basePath (untuk link & icon jika di dalam subfolder /pages/)
  const isInsidePages = window.location.pathname.includes("/pages/");
  const basePath = isInsidePages ? "../../" : "";

  // ── 1. DATASET SESI MOCK (Lengkap Esports & Olahraga) ──
  const daftarSesiSemua = [
    // ── ESPORTS ──
    {
      id: "es-1",
      title: "Ranked Push Immortal",
      game: "Valorant",
      category: "esports",
      icon: basePath + "asset/icon/Valoran1.svg",
      place: "Online (Server Indo)",
      city: "Online",
      date: "Hari ini, 20:00 WIB",
      slot: 3,
      maxSlot: 5,
    },
    {
      id: "es-2",
      title: "Ranked Trio Diamond - Ascendant",
      game: "Valorant",
      category: "esports",
      icon: basePath + "asset/icon/Valoran1.svg",
      place: "Online (Discord Mabar)",
      city: "Online",
      date: "Besok, 19:30 WIB",
      slot: 4,
      maxSlot: 5,
    },
    {
      id: "es-3",
      title: "Mabar Push Rank Mythic Glory",
      game: "Mobile Legends",
      category: "esports",
      icon: basePath + "asset/icon/ml-logo.jpeg",
      place: "Online / Voice Discord",
      city: "Online",
      date: "Malam ini, 21:00 WIB",
      slot: 4,
      maxSlot: 5,
    },
    {
      id: "es-4",
      title: "Push Bintang Santai Weekend",
      game: "Mobile Legends",
      category: "esports",
      icon: basePath + "asset/icon/ml-logo.jpeg",
      place: "Online (Open Mic)",
      city: "Online",
      date: "Sabtu, 20:00 WIB",
      slot: 3,
      maxSlot: 5,
    },
    {
      id: "es-5",
      title: "Party Ranked Legend - Ancient",
      game: "Dota 2",
      category: "esports",
      icon: basePath + "asset/icon/dota2-logo.jpeg",
      place: "Online (SEA Server)",
      city: "Online",
      date: "Malam ini, 22:00 WIB",
      slot: 2,
      maxSlot: 5,
    },
    {
      id: "es-6",
      title: "Squad Erangel Chicken Dinner",
      game: "PUBG Mobile",
      category: "esports",
      icon: basePath + "asset/icon/pubgm-logo.jpeg",
      place: "Online (Discord Voice)",
      city: "Online",
      date: "Minggu, 16:00 WIB",
      slot: 3,
      maxSlot: 4,
    },
    {
      id: "es-7",
      title: "Competitive 5v5 Premier Match",
      game: "Counter-Strike 2",
      category: "esports",
      icon: basePath + "asset/icon/cs2-logo.jpeg",
      place: "Online (Singapore Server)",
      city: "Online",
      date: "Besok, 21:00 WIB",
      slot: 3,
      maxSlot: 5,
    },
    {
      id: "es-8",
      title: "Push Rank Grandmaster - King",
      game: "Honor of Kings",
      category: "esports",
      icon: basePath + "asset/icon/hok-logo.png",
      place: "Online",
      city: "Online",
      date: "Hari ini, 19:00 WIB",
      slot: 2,
      maxSlot: 5,
    },

    // ── OLAHRAGA ──
    {
      id: "ol-1",
      title: "Tennis Pagi Pagi",
      game: "Tenis",
      category: "olahraga",
      icon: basePath + "asset/icon/tenis.svg",
      place: "Lapangan Rampal, Malang",
      city: "Malang",
      date: "Minggu, 27 Sep 2026",
      slot: 6,
      maxSlot: 8,
    },
    {
      id: "ol-2",
      title: "Mabar Tenis Ganda Putra",
      game: "Tenis",
      category: "olahraga",
      icon: basePath + "asset/icon/tenis.svg",
      place: "Court Kodam V Brawijaya, Surabaya",
      city: "Surabaya",
      date: "Sabtu, 07:00 WIB",
      slot: 3,
      maxSlot: 4,
    },
    {
      id: "ol-3",
      title: "Futsal Fun Malam Minggu",
      game: "Futsal",
      category: "olahraga",
      icon: basePath + "asset/icon/Futsal.png",
      place: "Champions Futsal Tidar, Malang",
      city: "Malang",
      date: "Sabtu, 19:00 WIB",
      slot: 7,
      maxSlot: 10,
    },
    {
      id: "ol-4",
      title: "Futsal Santai Akhir Pekan",
      game: "Futsal",
      category: "olahraga",
      icon: basePath + "asset/icon/Futsal.png",
      place: "Viva Futsal Merr, Surabaya",
      city: "Surabaya",
      date: "Minggu, 15:30 WIB",
      slot: 8,
      maxSlot: 10,
    },
    {
      id: "ol-5",
      title: "Mabar Sepak Bola Sore Komunitas",
      game: "Sepak Bola",
      category: "olahraga",
      icon: basePath + "asset/icon/sepak-bola.svg",
      place: "Stadion Gajayana Luar, Malang",
      city: "Malang",
      date: "Minggu, 15:00 WIB",
      slot: 16,
      maxSlot: 22,
    },
    {
      id: "ol-6",
      title: "Badminton Sore Ganda Campuran",
      game: "Bulu Tangkis",
      category: "olahraga",
      icon: basePath + "asset/icon/Bulu Tangkis.png",
      place: "GOR Ken Arok, Malang",
      city: "Malang",
      date: "Sabtu, 16:00 WIB",
      slot: 3,
      maxSlot: 4,
    },
    {
      id: "ol-7",
      title: "Voli Senja Bareng Komunitas",
      game: "Voli",
      category: "olahraga",
      icon: basePath + "asset/icon/voli.svg",
      place: "Lapangan Voli Sawojajar, Malang",
      city: "Malang",
      date: "Minggu, 16:30 WIB",
      slot: 8,
      maxSlot: 12,
    },
    {
      id: "ol-8",
      title: "Basket 3x3 Pick-Up Game",
      game: "Basket Ball",
      category: "olahraga",
      icon: basePath + "asset/icon/Basket.png",
      place: "Lapangan Basket Merdeka, Malang",
      city: "Malang",
      date: "Jumat, 17:00 WIB",
      slot: 4,
      maxSlot: 6,
    },
  ];

  // Kloning data aktif agar penambahan "Muat Lebih Banyak" bersifat dinamis
  let dataSesiAktif = [...daftarSesiSemua];

  // ── 2. TEMPLATE KARTU SESI (SOLUSI 1: KLIK KARTU = DETAIL, TOMBOL = GABUNG) ──
  const templateKartuSesi = (item) => {
    const isOlahraga = item.category === "olahraga";
    const boxBg = isOlahraga ? "bg-[#0c1a17] border-[#19d66b]/30" : "bg-[#260f14] border-red/30";

    const tagBadge = isOlahraga
      ? `<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wider bg-[#19d66b]/15 text-[#19d66b] uppercase">Olahraga</span>`
      : `<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wider bg-red/15 text-red uppercase">Esports</span>`;

    const joinBtnClass = isOlahraga
      ? "border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]"
      : "border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]";

    const hoverTitleColor = isOlahraga ? "group-hover:text-[#19d66b] card-title-sport" : "group-hover:text-red card-title-esport";

    // URL Sesi (melihat lobby & info lengkap sesi)
    const sessionUrl = `${basePath}pages/sesi/index.php?id=${encodeURIComponent(item.id)}&nama=${encodeURIComponent(item.title)}&game=${encodeURIComponent(item.game)}&kota=${encodeURIComponent(item.city)}&kategori=${encodeURIComponent(item.category)}`;

    // URL Gabung (langsung mengambil slot sebagai anggota)
    const joinUrl = `${basePath}pages/sesi/index.php?id=${encodeURIComponent(item.id)}&nama=${encodeURIComponent(item.title)}&game=${encodeURIComponent(item.game)}&kota=${encodeURIComponent(item.city)}&kategori=${encodeURIComponent(item.category)}&joined=1`;

    return `
      <article
        class="group relative flex flex-col justify-between rounded-lg border border-border bg-card p-5 transition-all duration-200 hover:-translate-y-1 hover:border-muted hover:shadow-lg hover:shadow-black/40 cursor-pointer"
        onclick="if(!event.target.closest('.join-session')){ window.location.href='${sessionUrl}'; }"
        title="Klik kartu untuk melihat info sesi & lobby"
      >
        <!-- Header Kartu: Icon + Title + Slot -->
        <div class="flex items-start gap-4">
          <div class="grid h-12 w-12 shrink-0 place-items-center rounded-lg border ${boxBg} p-2 text-2xl">
            <img src="${item.icon}" alt="${item.game}" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-1">
              ${tagBadge}
              <span class="text-[11px] font-medium text-muted truncate">${item.game}</span>
            </div>
            <h3 class="text-[16px] font-bold text-white truncate tracking-tight transition-colors ${hoverTitleColor}" title="${item.title}">${item.title}</h3>
          </div>
          <div class="text-right shrink-0">
            <div class="text-[20px] font-bold leading-none text-white">${item.slot} <span class="text-[14px] text-muted font-normal">/ ${item.maxSlot}</span></div>
            <div class="text-[10px] text-muted mt-1 uppercase font-semibold tracking-wider">slot</div>
          </div>
        </div>
        
        <!-- Footer Kartu: Lokasi, Jadwal, Tombol Aksi Bersih -->
        <div class="mt-5 flex items-center justify-between border-t border-border/50 pt-4">
          <div class="flex flex-col gap-1 min-w-0 pr-3">
            <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
              <i class="ph-bold ph-map-pin text-sm text-muted shrink-0"></i>
              <span class="truncate max-w-[220px]" title="${item.place}">${item.place}</span>
            </div>
            <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
              <i class="ph-bold ph-calendar-blank text-sm text-muted shrink-0"></i> 
              <span class="truncate">${item.date}</span>
            </div>
          </div>
          <div class="shrink-0">
            <a
              href="${joinUrl}"
              onclick="event.stopPropagation();"
              class="join-session inline-flex items-center justify-center h-8 px-5 rounded-md text-[12px] font-semibold tracking-wide transition-all duration-200 active:scale-95 ${joinBtnClass}"
              title="Gabung langsung ke lobby sesi"
            >
              Gabung
            </a>
          </div>
        </div>
      </article>
    `;
  };

  // ── 3. UPDATE FEATURED / HOT MATCH (SEGERA MULAI) ──
  const updateFeaturedMatch = (category) => {
    const featuredMatch = document.getElementById("featured-match");
    if (!featuredMatch) return;

    const iconImg = document.getElementById("featured-icon-img");
    const iconBox = document.getElementById("featured-icon-box");
    const tag1 = document.getElementById("featured-tag-1");
    const tag2 = document.getElementById("featured-tag-2");
    const title = document.getElementById("featured-title");
    const desc = document.getElementById("featured-desc");
    const slotCount = document.getElementById("featured-slot-count");
    const joinBtn = document.getElementById("featured-join-btn");
    const watermark = document.getElementById("featured-watermark");

    if (category === "olahraga") {
      // Tema Hijau untuk Olahraga
      featuredMatch.classList.remove("border-red");
      featuredMatch.style.borderColor = "#19d66b";
      featuredMatch.dataset.detailUrl = `${basePath}pages/sesi/index.php?id=featured-sport&nama=Tennis+Sunday+Morning&game=Tenis&kategori=olahraga&kota=Malang`;

      if (iconBox) {
        iconBox.classList.remove("border-red", "text-red");
        iconBox.style.borderColor = "#19d66b";
        iconBox.style.color = "#19d66b";
      }
      if (iconImg) iconImg.src = basePath + "asset/icon/tenis.svg";

      if (tag1) {
        tag1.className = "inline-flex items-center gap-1.5 rounded-full bg-[#19d66b]/15 text-[#19d66b] border border-[#19d66b]/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase";
        tag1.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-[#19d66b] animate-pulse"></span> SEGERA MULAI`;
      }
      if (tag2) {
        tag2.textContent = "TENIS LAPANGAN";
        tag2.className = "inline-flex items-center rounded-full bg-card-alt border border-border px-3 py-1 text-[10px] font-bold tracking-wider text-muted uppercase";
      }
      if (title) {
        title.textContent = "Tennis Sunday Morning";
        title.className = "text-[32px] font-bold tracking-tight text-white mb-2 leading-none transition-colors group-hover:text-[#19d66b] card-title-sport";
      }
      if (desc) {
        desc.textContent =
          "Sesi mabar tenis santai ganda 4 vs 4 di Lapangan Rampal. Raket cadangan & bola sudah disiapkan. Level pemula sampai menengah dipersilakan!";
      }
      if (slotCount) {
        slotCount.innerHTML = `6<span class="text-[28px] text-muted font-normal">/8</span>`;
      }
      if (joinBtn) {
        joinBtn.className = "inline-flex items-center justify-center h-10 px-6 rounded-md border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] text-center";
        joinBtn.textContent = "Gabung Slot";
        joinBtn.href = `${basePath}pages/sesi/index.php?id=featured-sport&nama=Tennis+Sunday+Morning&game=Tenis&kategori=olahraga&kota=Malang&joined=1`;
      }
      if (watermark) {
        watermark.innerHTML = `<i class="ph-fill ph-trophy text-[240px]" style="color: #0c2518;"></i>`;
      }
    } else {
      // Tema Merah untuk Esports
      featuredMatch.style.borderColor = "";
      featuredMatch.classList.add("border-red");
      featuredMatch.dataset.detailUrl = `${basePath}pages/sesi/index.php?id=featured&nama=Ranked+Duelist&game=Valorant&kategori=esports`;

      if (iconBox) {
        iconBox.style.borderColor = "";
        iconBox.style.color = "";
        iconBox.classList.add("border-red", "text-red");
      }
      if (iconImg) iconImg.src = basePath + "asset/icon/Valoran1.svg";

      if (tag1) {
        tag1.className = "inline-flex items-center gap-1.5 rounded-full bg-red/15 text-red border border-red/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase";
        tag1.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-red animate-pulse"></span> SEGERA MULAI`;
      }
      if (tag2) {
        tag2.textContent = "VALORANT";
        tag2.className = "inline-flex items-center rounded-full bg-card-alt border border-border px-3 py-1 text-[10px] font-bold tracking-wider text-muted uppercase";
      }
      if (title) {
        title.textContent = "Ranked Duelist";
        title.className = "text-[32px] font-bold tracking-tight text-white mb-2 leading-none transition-colors group-hover:text-red card-title-esport";
      }
      if (desc) {
        desc.textContent =
          "-2 rank Plat - Diamond, main duelist. Paling engga paham entry site. Playstyle menyesuaikan in-game. Kalo cocok gas push sampe immortal.";
      }
      if (slotCount) {
        slotCount.innerHTML = `3<span class="text-[28px] text-muted font-normal">/5</span>`;
      }
      if (joinBtn) {
        joinBtn.className = "inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center";
        joinBtn.textContent = "Gabung Slot";
        joinBtn.href = `${basePath}pages/sesi/index.php?id=featured&nama=Ranked+Duelist&game=Valorant&kategori=esports&joined=1`;
      }
      if (watermark) {
        watermark.innerHTML = `<i class="ph-fill ph-triangle text-[240px] text-red-dark"></i>`;
      }
    }
  };

  // ── 4. RENDER LIST KARTU SESI & EMPTY STATE ──
  const renderDaftarSesi = (items) => {
    if (!sessionsContainer) return;

    if (!items || items.length === 0) {
      sessionsContainer.innerHTML = `
        <div class="col-span-full flex flex-col items-center justify-center p-12 text-center rounded-lg border border-border bg-card/60">
          <div class="h-16 w-16 rounded-full bg-card-alt flex items-center justify-center text-3xl text-muted mb-4 border border-border">
            <i class="ph ph-magnifying-glass"></i>
          </div>
          <h3 class="text-[17px] font-bold text-white mb-1.5">Tidak Ada Sesi Ditemukan</h3>
          <p class="text-[13px] text-muted max-w-sm mb-5">
            Belum ada pertandingan yang cocok dengan filter yang Anda pilih. Coba ganti kata kunci atau buat sesi baru!
          </p>
          <button
            id="btn-reset-filter-sesi"
            class="px-4 py-2 rounded-md border border-border bg-card-alt text-[12px] font-semibold text-white hover:border-muted transition-colors"
          >
            Reset Filter
          </button>
        </div>
      `;

      document.getElementById("btn-reset-filter-sesi")?.addEventListener("click", () => {
        resetSemuaFilter();
      });
      return;
    }

    sessionsContainer.innerHTML = items.map(templateKartuSesi).join("");
  };

  // ── 5. LOGIKA FILTERING ──
  const filterSesi = () => {
    const sidebarCari = document.getElementById("sidebar-cari-sesi") || document.querySelector("aside .border-2");

    // 1. Kategori Aktif (Esports vs Olahraga)
    let activeCategory = "esports";
    if (sidebarCari) {
      const activeCatBtn = sidebarCari.querySelector(
        "[data-group='category'].text-white, [data-group='category'].border-red, [data-group='category'].border-\\[\\#19d66b\\]"
      );
      if (activeCatBtn && activeCatBtn.dataset.category) {
        activeCategory = activeCatBtn.dataset.category.toLowerCase();
      }
    }

    // 2. Game / Olahraga Terpilih dari Dropdown
    let selectedGame = "";
    if (sidebarCari) {
      const activeWrapper = activeCategory === "olahraga"
        ? sidebarCari.querySelector(".sport-dropdown-wrapper")
        : sidebarCari.querySelector(".game-dropdown-wrapper");

      const valEl = activeWrapper?.querySelector(".dropdown-value");
      if (valEl && !valEl.textContent.includes("Pilih") && !valEl.textContent.includes("--")) {
        selectedGame = valEl.textContent.trim().toLowerCase();
      }
    }

    // 3. Kota Terpilih dari Combobox
    let selectedCity = "";
    if (sidebarCari) {
      const cityInput = sidebarCari.querySelector(".city-combobox-input");
      if (cityInput && cityInput.value && !cityInput.value.toLowerCase().includes("cari kota")) {
        selectedCity = cityInput.value.trim().toLowerCase();
      }
    }

    // 4. Pencarian Search Input di atas grid
    const searchInput = document.getElementById("search-input");
    const searchQuery = searchInput ? searchInput.value.trim().toLowerCase() : "";

    // Lakukan Filtering
    const hasil = dataSesiAktif.filter((item) => {
      // Filter Kategori
      if (item.category !== activeCategory) return false;

      // Filter Game / Sport spesifik
      if (selectedGame && !item.game.toLowerCase().includes(selectedGame)) {
        return false;
      }

      // Filter Kota (Kecuali jika memilih 'Online' pada game esports)
      if (selectedCity && !selectedCity.includes("online")) {
        if (!item.city.toLowerCase().includes(selectedCity) && !item.place.toLowerCase().includes(selectedCity)) {
          return false;
        }
      }

      // Filter Query Teks Bebas
      if (searchQuery) {
        const fullText = `${item.title} ${item.game} ${item.place} ${item.city}`.toLowerCase();
        if (!fullText.includes(searchQuery)) return false;
      }

      return true;
    });

    // ── FALLBACK LOGIC UNTUK HERO BANNER ──
    // Jika jumlah sesi aktif kurang dari 2, sembunyikan hero banner untuk menghindari duplikasi visual!
    const featuredMatch = document.getElementById("featured-match");
    if (featuredMatch) {
      if (hasil.length < 2) {
        featuredMatch.classList.add("hidden");
      } else {
        featuredMatch.classList.remove("hidden");
        updateFeaturedMatch(activeCategory);
      }
    }

    renderDaftarSesi(hasil);
  };

  // Reset filter ke keadaan default
  const resetSemuaFilter = () => {
    const searchInput = document.getElementById("search-input");
    if (searchInput) searchInput.value = "";

    const sidebarCari = document.getElementById("sidebar-cari-sesi") || document.querySelector("aside .border-2");
    if (sidebarCari) {
      // Reset input kota
      const cityInput = sidebarCari.querySelector(".city-combobox-input");
      if (cityInput) {
        cityInput.value = "";
        cityInput.classList.remove("text-white");
        cityInput.classList.add("text-muted");
      }

      // Reset dropdown values
      sidebarCari.querySelectorAll(".dropdown-value").forEach((el) => {
        if (el.closest(".sport-dropdown-wrapper")) {
          el.textContent = "Pilih Olahraga...";
        } else {
          el.textContent = "Pilih Game...";
        }
        el.classList.add("text-muted");
      });
      sidebarCari.querySelectorAll(".dropdown-icon-wrapper").forEach((el) => el.classList.add("hidden"));
      sidebarCari.querySelectorAll(".dropdown-placeholder-icon").forEach((el) => el.classList.remove("hidden"));
    }

    filterSesi();
  };

  // ── 6. EVENT LISTENERS ──

  // Filter Tab (Kategori di Sidebar Cari Sesi)
  const sidebarCari = document.getElementById("sidebar-cari-sesi") || document.querySelector("aside .border-2");
  if (sidebarCari) {
    sidebarCari.querySelectorAll(".filter-tab").forEach((btn) => {
      btn.addEventListener("click", () => {
        setTimeout(filterSesi, 40);
      });
    });

    // Dropdown Item Click
    sidebarCari.querySelectorAll(".dropdown-option").forEach((opt) => {
      opt.addEventListener("click", () => {
        setTimeout(filterSesi, 40);
      });
    });

    // City Input Change / Click
    const cityInput = sidebarCari.querySelector(".city-combobox-input");
    cityInput?.addEventListener("input", () => {
      setTimeout(filterSesi, 200);
    });

    sidebarCari.querySelectorAll(".city-combobox-item").forEach((item) => {
      item.addEventListener("click", () => {
        setTimeout(filterSesi, 40);
      });
    });

    // Tombol "Cari Sesi"
    const btnFindSession = document.getElementById("find-session");
    btnFindSession?.addEventListener("click", () => {
      filterSesi();
      sessionsContainer.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  }

  // Live Search Input di Atas Grid
  const searchInput = document.getElementById("search-input");
  searchInput?.addEventListener("input", () => {
    filterSesi();
  });

  // Tombol Muat Lebih Banyak (Load More)
  const loadMoreBtn = document.getElementById("load-more");
  loadMoreBtn?.addEventListener("click", () => {
    const isOlahraga = sidebarCari?.querySelector("[data-group='category'].text-white")?.dataset?.category === "olahraga";

    if (isOlahraga) {
      dataSesiAktif.push(
        {
          id: "ol-" + (dataSesiAktif.length + 1),
          title: "Mabar Voli Komunitas Malam",
          game: "Voli",
          category: "olahraga",
          icon: basePath + "asset/icon/voli.svg",
          place: "GOR Bima Sakti, Malang",
          city: "Malang",
          date: "Minggu, 18:00 WIB",
          slot: 6,
          maxSlot: 12,
        },
        {
          id: "ol-" + (dataSesiAktif.length + 2),
          title: "Mini Soccer Malam Kamis",
          game: "Sepak Bola",
          category: "olahraga",
          icon: basePath + "asset/icon/sepak-bola.svg",
          place: "Lapangan Mini Soccer Sudimoro, Malang",
          city: "Malang",
          date: "Kamis, 20:00 WIB",
          slot: 10,
          maxSlot: 14,
        }
      );
    } else {
      dataSesiAktif.push(
        {
          id: "es-" + (dataSesiAktif.length + 1),
          title: "Ranked Push Diamond - Ascendant",
          game: "Valorant",
          category: "esports",
          icon: basePath + "asset/icon/Valoran1.svg",
          place: "Online (Server Indo)",
          city: "Online",
          date: "Hari ini, 23:00 WIB",
          slot: 2,
          maxSlot: 5,
        },
        {
          id: "es-" + (dataSesiAktif.length + 2),
          title: "Trio Push Mythic Glory",
          game: "Mobile Legends",
          category: "esports",
          icon: basePath + "asset/icon/ml-logo.jpeg",
          place: "Online",
          city: "Online",
          date: "Besok, 20:00 WIB",
          slot: 2,
          maxSlot: 3,
        }
      );
    }

    filterSesi();
  });

  // Render awal saat halaman dimuat
  filterSesi();
});
