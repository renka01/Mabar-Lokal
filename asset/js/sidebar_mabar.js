/**
 * asset/js/sidebar_mabar.js — Logika Filter Kategori & Validasi Form Sidebar
 * Menangani pemilihan kategori (Esports merah / Olahraga hijau), filter turnamen,
 * serta validasi lengkap sebelum user dialihkan ke pembuatan sesi.
 */

document.addEventListener("DOMContentLoaded", () => {
  // ── 0. KAMUS BATASAN SLOT RESMI PER GAME / OLAHRAGA ──
  const GAME_CAPACITY_MAP = {
    // Esports
    "valorant": { min: 2, max: 5 },
    "mobile legends": { min: 2, max: 5 },
    "honor of kings": { min: 2, max: 5 },
    "pubg mobile": { min: 2, max: 4 },
    "pubg pc": { min: 2, max: 4 },
    "free fire": { min: 2, max: 4 },
    "dota 2": { min: 2, max: 5 },
    "counter-strike 2": { min: 2, max: 5 },
    "league of legends": { min: 2, max: 5 },
    "apex legends": { min: 2, max: 3 },
    // Olahraga
    "futsal": { min: 2, max: 10 },
    "mini soccer": { min: 2, max: 14 },
    "sepak bola": { min: 2, max: 22 },
    "basket": { min: 2, max: 10 },
    "badminton": { min: 2, max: 4 },
    "tenis lapangan": { min: 2, max: 4 },
    "tenis meja": { min: 2, max: 4 },
    "voli": { min: 2, max: 12 },
    "biliar": { min: 2, max: 4 }
  };

  function updateBuatSesiSlotLimit(gameOrSportName, isOlahraga = false) {
    const slotInput = document.getElementById("buat-sesi-slot");
    const slotHint = document.getElementById("buat-sesi-slot-hint");
    if (!slotInput) return;

    let cleanName = (gameOrSportName || "").toLowerCase().trim();
    let limit = GAME_CAPACITY_MAP[cleanName];

    if (!limit) {
      for (const [key, val] of Object.entries(GAME_CAPACITY_MAP)) {
        if (cleanName.includes(key)) {
          limit = val;
          break;
        }
      }
    }

    if (!limit) {
      limit = isOlahraga ? { min: 2, max: 10 } : { min: 2, max: 5 };
    }

    slotInput.min = limit.min;
    slotInput.max = limit.max;
    slotInput.placeholder = String(limit.max);

    if (slotHint) {
      slotHint.textContent = `(${limit.min} - ${limit.max} pemain)`;
    }

    // Sesuaikan nilai saat ini agar tidak melebihi batas baru
    let curVal = parseInt(slotInput.value, 10);
    if (isNaN(curVal) || curVal > limit.max) {
      slotInput.value = limit.max;
    } else if (curVal < limit.min) {
      slotInput.value = limit.min;
    }
  }

  // ── 1. TAB FILTERS (CARI MABAR SIDEBAR) ──
  document.querySelectorAll(".filter-tab").forEach((btn) => {
    btn.addEventListener("click", () => {
      const parentCard = btn.closest(".border-2") || document;

      parentCard
        .querySelectorAll(`[data-group="${btn.dataset.group}"]`)
        .forEach((item) => {
          item.classList.remove("border-red", "border-[#19d66b]", "text-white", "bg-card-alt");
          item.style.borderColor = "";
          item.classList.add("text-muted", "border-border");
        });

      const isOlahraga = btn.dataset.category === "olahraga";
      btn.classList.remove("text-muted", "border-border");
      if (isOlahraga) {
        btn.classList.add("border-[#19d66b]", "text-white", "bg-card-alt");
        btn.style.borderColor = "#19d66b";
      } else {
        btn.classList.add("border-red", "text-white", "bg-card-alt");
        btn.style.borderColor = "";
      }

      // Toggle dropdown game vs sport berdasarkan kategori yang dipilih
      const category = btn.dataset.category;
      if (category) {
        const card = btn.closest(".border-2");
        if (card) {
          const gameWrapper = card.querySelector(".game-dropdown-wrapper");
          const sportWrapper = card.querySelector(".sport-dropdown-wrapper");

          const kotaHint = card.querySelector("#buat-sesi-kota-hint");
          const cityInput = card.querySelector(".city-combobox-input");

          if (category === "esports") {
            gameWrapper?.classList.remove("hidden");
            sportWrapper?.classList.add("hidden");
            if (kotaHint) {
              kotaHint.textContent = "(opsional / online)";
              kotaHint.className = "text-[10px] text-muted font-normal lowercase";
            }
            updateBuatSesiSlotLimit("Valorant", false);
            if (cityInput && (!cityInput.value || cityInput.value.includes("lapangan"))) {
              cityInput.placeholder = "Online / Cari kota (Opsional)...";
            }
          } else if (category === "olahraga") {
            gameWrapper?.classList.add("hidden");
            sportWrapper?.classList.remove("hidden");
            if (kotaHint) {
              kotaHint.textContent = "(wajib diisi)";
              kotaHint.className = "text-[10px] text-red font-semibold lowercase";
            }
            updateBuatSesiSlotLimit("Futsal", true);
            if (cityInput) {
              cityInput.placeholder = "Pilih kota lapangan...";
              if (cityInput.value.toLowerCase().includes("online") || cityInput.value.toLowerCase().includes("bebas")) {
                cityInput.value = "";
                cityInput.classList.remove("text-white");
                cityInput.classList.add("text-muted");
              }
            }
          }
        }
      }
    });
  });


  // ── 2. FILTER SIDEBAR TURNAMEN ──
  document.querySelectorAll(".turnamen-filter").forEach((btn) => {
    btn.addEventListener("click", () => {
      const group = btn.dataset.filterGroup;
      const card = btn.closest(".border-2") || document;
      card
        .querySelectorAll(`.turnamen-filter[data-filter-group="${group}"]`)
        .forEach((item) => {
          item.classList.remove("active", "border-red", "border-[#19d66b]", "bg-card-alt", "text-white");
          item.classList.add("border-border", "text-muted");
          item.style.borderColor = "";
          item.setAttribute("aria-pressed", "false");
        });

      const isOlahraga = btn.dataset.category === "olahraga";
      btn.classList.remove("border-border", "text-muted");
      if (isOlahraga) {
        btn.classList.add("active", "border-[#19d66b]", "bg-card-alt", "text-white");
        btn.style.borderColor = "#19d66b";
      } else {
        btn.classList.add("active", "border-red", "bg-card-alt", "text-white");
        btn.style.borderColor = "";
      }
      btn.setAttribute("aria-pressed", "true");

      const category = btn.dataset.category;
      if (category && card) {
        const gameWrapper = card.querySelector(".game-dropdown-wrapper");
        const sportWrapper = card.querySelector(".sport-dropdown-wrapper");
        const cityInput = card.querySelector(".city-combobox-input");

        if (category === "esports") {
          gameWrapper?.classList.remove("hidden");
          sportWrapper?.classList.add("hidden");
          if (cityInput && (!cityInput.value || cityInput.value.includes("lapangan"))) {
            cityInput.placeholder = "Online / Cari kota (Opsional)...";
          }
        } else if (category === "olahraga") {
          gameWrapper?.classList.add("hidden");
          sportWrapper?.classList.remove("hidden");
          if (cityInput) {
            cityInput.placeholder = "Pilih kota lapangan...";
            if (cityInput.value.toLowerCase().includes("online") || cityInput.value.toLowerCase().includes("bebas")) {
              cityInput.value = "";
              cityInput.classList.remove("text-white");
              cityInput.classList.add("text-muted");
            }
          }
        }
      }
    });
  });

  document.querySelectorAll(".tournament-tab").forEach((tab) => {
    tab.addEventListener("click", () => {
      document.querySelectorAll(".tournament-tab").forEach((item) => {
        item.classList.remove("active", "border-red", "text-white");
        item.classList.add("border-transparent", "text-muted");
        item.setAttribute("aria-selected", "false");
      });

      tab.classList.remove("border-transparent", "text-muted");
      tab.classList.add("active", "border-red", "text-white");
      tab.setAttribute("aria-selected", "true");
    });
  });


  // ── 3. VALIDASI & PEMBUATAN SESI DARI SIDEBAR ──
  const buatSesiCards = document.querySelectorAll("aside .border-2");
  buatSesiCards.forEach((card) => {
    const header = card.querySelector("h2");
    if (header && header.textContent.trim().toUpperCase() === "BUAT SESI") {
      const btn = card.querySelector("button.mt-2");
      if (btn) {
        btn.addEventListener("click", (e) => {
          e.preventDefault();

          // 1. Cek Kategori Aktif
          const activeCatBtn = card.querySelector("[data-group='category'].border-red, [data-group='category'].border-\\[\\#19d66b\\], [data-group='category'].text-white");
          const isOlahraga = activeCatBtn && activeCatBtn.dataset.category === "olahraga";

          // 2. Cek Nama Sesi & Kebutuhan Slot
          const namaInput = card.querySelector("#buat-sesi-nama") || card.querySelector("input[type='text']:not(.city-combobox-input)");
          const namaVal = namaInput ? namaInput.value.trim() : "";

          const slotInput = card.querySelector("#buat-sesi-slot");
          const slotVal = slotInput ? slotInput.value.trim() : "";

          // 3. Cek Game / Olahraga Terpilih
          let gameVal = "";
          let dropdownBtn = null;
          if (isOlahraga) {
            dropdownBtn = card.querySelector(".sport-dropdown-wrapper .custom-dropdown button") || card.querySelector(".sport-dropdown-wrapper button");
            const sportVal = card.querySelector(".sport-dropdown-wrapper .dropdown-value");
            if (sportVal && !sportVal.textContent.includes("Pilih") && !sportVal.textContent.includes("--")) {
              gameVal = sportVal.textContent.trim();
            }
          } else {
            dropdownBtn = card.querySelector(".game-dropdown-wrapper .custom-dropdown button") || card.querySelector(".game-dropdown-wrapper button");
            const gameValEl = card.querySelector(".game-dropdown-wrapper .dropdown-value");
            if (gameValEl && !gameValEl.textContent.includes("Pilih") && !gameValEl.textContent.includes("--")) {
              gameVal = gameValEl.textContent.trim();
            }
          }

          // 4. Cek Kota
          const cityInput = card.querySelector(".city-combobox-input");
          let kotaVal = cityInput ? cityInput.value.trim() : "";

          // Helper highlight error pada field yang belum diisi
          function triggerInputError(el, message) {
            if (el) {
              el.style.borderColor = "#ef233c";
              el.style.boxShadow = "0 0 10px rgba(239, 35, 60, 0.4)";
              el.focus?.();
              setTimeout(() => {
                el.style.borderColor = "";
                el.style.boxShadow = "";
              }, 2200);
            }
            if (typeof window.tampilkanNotifikasi === "function") {
              window.tampilkanNotifikasi(message, "error");
            }
          }

          // VALIDASI 1: Nama Sesi Wajib Diisi
          if (!namaVal) {
            triggerInputError(namaInput, "Harap isi Nama Sesi terlebih dahulu!");
            return;
          }

          // VALIDASI 1.5: Validasi Kebutuhan Slot jika diisi
          if (slotVal) {
            const numSlot = parseInt(slotVal, 10);
            if (isNaN(numSlot) || numSlot < 2) {
              triggerInputError(slotInput?.parentElement, "Kebutuhan slot minimal 2 pemain!");
              return;
            }
          }

          // VALIDASI 2: Game / Olahraga Wajib Dipilih
          if (!gameVal) {
            triggerInputError(dropdownBtn, `Harap pilih ${isOlahraga ? "Olahraga" : "Game"} yang dimainkan!`);
            return;
          }

          // VALIDASI 3: Kota (Wajib untuk Olahraga, Opsional / Online untuk Esports)
          if (isOlahraga) {
            if (!kotaVal || kotaVal.toLowerCase().includes("online") || kotaVal.toLowerCase().includes("bebas")) {
              triggerInputError(cityInput?.parentElement, "Untuk sesi Olahraga, wajib memilih kota lapangan!");
              return;
            }
          } else {
            // Untuk Esports, jika kosong atau pilih Online, set ke Online
            if (!kotaVal || kotaVal.toLowerCase().includes("online") || kotaVal.toLowerCase().includes("bebas")) {
              kotaVal = "Online";
            }
          }

          // Tentukan basePath dinamis (aman dipanggil dari root maupun subfolder /pages/)
          const isInsidePages = window.location.pathname.includes("/pages/");
          const basePath = isInsidePages ? "../../" : "";

          // Semua validasi berhasil -> Langsung redirect ke Halaman Detail Sesi
          let url = basePath + "pages/sesi/index.php?created=1";
          url += "&nama=" + encodeURIComponent(namaVal);
          url += "&game=" + encodeURIComponent(gameVal);
          url += "&kota=" + encodeURIComponent(kotaVal);
          url += "&kategori=" + (isOlahraga ? "olahraga" : "esports");
          if (slotVal) {
            url += "&slot=" + encodeURIComponent(slotVal);
          }

          window.location.href = url;
        });
      }
    }
  });

  // ── 4. LOGIKA STEPPER SLOT & SINKRONISASI DROPDOWN GAME ──
  const btnSlotMinus = document.getElementById("btn-slot-minus");
  const btnSlotPlus = document.getElementById("btn-slot-plus");
  const buatSesiSlotInput = document.getElementById("buat-sesi-slot");

  btnSlotMinus?.addEventListener("click", () => {
    if (!buatSesiSlotInput) return;
    const min = parseInt(buatSesiSlotInput.min, 10) || 2;
    let val = parseInt(buatSesiSlotInput.value, 10);
    if (isNaN(val)) val = min;
    if (val > min) {
      buatSesiSlotInput.value = val - 1;
    }
  });

  btnSlotPlus?.addEventListener("click", () => {
    if (!buatSesiSlotInput) return;
    const max = parseInt(buatSesiSlotInput.max, 10) || 5;
    let val = parseInt(buatSesiSlotInput.value, 10);
    if (isNaN(val)) val = 2;
    if (val < max) {
      buatSesiSlotInput.value = val + 1;
    }
  });

  // Validasi otomatis saat user mengetik manual pada input slot
  buatSesiSlotInput?.addEventListener("input", () => {
    const max = parseInt(buatSesiSlotInput.max, 10) || 5;
    let val = parseInt(buatSesiSlotInput.value, 10);
    if (!isNaN(val) && val > max) {
      buatSesiSlotInput.value = max;
    }
  });

  buatSesiSlotInput?.addEventListener("blur", () => {
    const min = parseInt(buatSesiSlotInput.min, 10) || 2;
    const max = parseInt(buatSesiSlotInput.max, 10) || 5;
    let val = parseInt(buatSesiSlotInput.value, 10);
    if (isNaN(val) || val < min) {
      buatSesiSlotInput.value = min;
    } else if (val > max) {
      buatSesiSlotInput.value = max;
    }
  });

  // Listener dropdown game / olahraga di Buat Sesi untuk update kapasitas slot real-time
  const buatSesiCard = document.getElementById("sidebar-buat-sesi");
  if (buatSesiCard) {
    buatSesiCard.querySelectorAll(".dropdown-option").forEach((opt) => {
      opt.addEventListener("click", () => {
        const isOlahraga = buatSesiCard.querySelector("[data-group='category'].text-white")?.dataset?.category === "olahraga";
        const gameName = opt.dataset.name || opt.textContent.trim();
        updateBuatSesiSlotLimit(gameName, isOlahraga);
      });
    });
  }

  // Pastikan state sidebar & form selalu bersih dan aktif saat rollback / back dari browser (bfcache)
  window.addEventListener("pageshow", () => {
    const createBtn = document.querySelector("#sidebar-buat-sesi button.mt-2");
    if (createBtn) {
      createBtn.innerHTML = `<i class="ph ph-plus text-lg font-bold"></i> Buat Sesi`;
      createBtn.style.pointerEvents = "";
    }
  });
});
