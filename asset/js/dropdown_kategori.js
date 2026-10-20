/**
 * asset/js/dropdown_kategori.js — Logika Dropdown Game, Sport, dan Combobox Kota
 * Menangani pemilihan game terdaftar, opsi kustom "Game lainnya...", dan pencarian kota Jawa Timur.
 */

document.addEventListener("DOMContentLoaded", () => {
  // ── 1. CUSTOM DROPDOWN (GAME & SPORT) ──
  const closeDropdowns = (except = null) => {
    document.querySelectorAll(".dropdown").forEach((dropdown) => {
      if (dropdown === except) return;
      dropdown.querySelector(".dropdown-menu")?.classList.add("hidden");
      dropdown
        .querySelector(".dropdown-toggle")
        ?.setAttribute("aria-expanded", "false");
    });
  };

  document.querySelectorAll(".dropdown").forEach((dropdown) => {
    const toggle = dropdown.querySelector(".dropdown-toggle");
    const menu = dropdown.querySelector(".dropdown-menu");
    const value = dropdown.querySelector(".dropdown-value");
    if (!toggle || !menu || !value) return;

    // Ambil input "lainnya" yang berelasi dengan dropdown ini
    const dropdownId = dropdown.dataset.dropdownId;
    const otherInput = dropdownId
      ? document.querySelector(`.dropdown-other-input[data-for="${dropdownId}"]`)
      : null;

    toggle.addEventListener("click", () => {
      const isOpen = !menu.classList.contains("hidden");
      closeDropdowns();
      menu.classList.toggle("hidden", isOpen);
      toggle.setAttribute("aria-expanded", String(!isOpen));
    });

    dropdown.querySelectorAll(".dropdown-option").forEach((option) => {
      option.addEventListener("click", () => {
        const isOther = option.dataset.name === "__other__";

        // Reset semua opsi
        dropdown.querySelectorAll(".dropdown-option").forEach((item) => {
          item.classList.remove("active", "bg-border", "text-white");
          item.classList.add("text-muted");
          item.setAttribute("aria-selected", "false");
        });

        if (isOther) {
          // Opsi "Game Lainnya" dipilih
          value.textContent = "Game lainnya...";
          value.classList.remove("text-muted");
          value.classList.add("text-white");

          // Sembunyikan img icon, tampilkan icon pensil placeholder
          const iconWrapper = toggle.querySelector(".dropdown-icon-wrapper");
          const placeholderIcon = toggle.querySelector(".dropdown-placeholder-icon");
          if (iconWrapper) iconWrapper.classList.add("hidden");
          if (placeholderIcon) {
            placeholderIcon.classList.remove("hidden");
            placeholderIcon.className = placeholderIcon.className.replace("ph-game-controller", "ph-pencil-simple");
          }

          // Tampilkan input kustom
          if (otherInput) {
            otherInput.classList.remove("hidden");
            setTimeout(() => otherInput.focus(), 50);
          }
        } else {
          // Opsi game terdaftar dipilih
          const gameName = option.dataset.name || option.textContent.trim();
          const gameIcon = option.dataset.icon;

          value.textContent = gameName;
          value.classList.remove("text-muted");
          value.classList.add("text-white");

          // Tampilkan img icon, sembunyikan placeholder icon
          const iconWrapper = toggle.querySelector(".dropdown-icon-wrapper");
          const placeholderIcon = toggle.querySelector(".dropdown-placeholder-icon");
          const currentIcon = toggle.querySelector(".dropdown-current-icon");

          if (iconWrapper) iconWrapper.classList.remove("hidden");
          if (placeholderIcon) placeholderIcon.classList.add("hidden");
          if (currentIcon && gameIcon) {
            currentIcon.src = gameIcon;
            currentIcon.alt = gameName;
          }

          // Sembunyikan input kustom
          if (otherInput) {
            otherInput.classList.add("hidden");
            otherInput.value = "";
          }
        }

        option.classList.remove("text-muted");
        option.classList.add("active", "bg-border", "text-white");
        option.setAttribute("aria-selected", "true");
        menu.classList.add("hidden");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  });

  document.addEventListener("click", (event) => {
    if (!event.target.closest(".dropdown")) closeDropdowns();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") closeDropdowns();
  });


  // ── 2. COMBOBOX KOTA (JAWA TIMUR & BEBAS DOMISILI) ──
  const KOTA_JATIM = [
    "Online / Bebas Domisili",
    "Kota Surabaya", "Kota Malang", "Kota Kediri", "Kota Blitar",
    "Kota Madiun", "Kota Mojokerto", "Kota Probolinggo", "Kota Pasuruan", "Kota Batu",
    "Kab. Gresik", "Kab. Sidoarjo", "Kab. Lamongan", "Kab. Tuban", "Kab. Bojonegoro",
    "Kab. Ngawi", "Kab. Madiun", "Kab. Magetan", "Kab. Nganjuk", "Kab. Jombang",
    "Kab. Mojokerto", "Kab. Kediri", "Kab. Tulungagung", "Kab. Blitar", "Kab. Malang",
    "Kab. Lumajang", "Kab. Jember", "Kab. Bondowoso", "Kab. Situbondo",
    "Kab. Banyuwangi", "Kab. Probolinggo", "Kab. Pasuruan", "Kab. Trenggalek",
    "Kab. Ponorogo", "Kab. Pacitan", "Kab. Sampang", "Kab. Pamekasan",
    "Kab. Sumenep", "Kab. Bangkalan"
  ];

  document.querySelectorAll(".city-combobox").forEach((combobox) => {
    const input  = combobox.querySelector(".city-combobox-input");
    const list   = combobox.querySelector(".city-combobox-list");
    const caret  = combobox.querySelector(".city-combobox-caret");
    let selected = "";

    const getActiveCategory = () => {
      const card = combobox.closest(".border-2") || combobox.closest("aside > div") || document;
      const activeSportBtn = card.querySelector(
        '[data-category="olahraga"].text-white, [data-category="olahraga"].active, [data-category="olahraga"][aria-pressed="true"], [data-category="olahraga"].border-\\[\\#19d66b\\]'
      );
      return activeSportBtn ? "olahraga" : "esports";
    };

    const renderList = (filter = "") => {
      const q = filter.toLowerCase();
      const isOlahraga = getActiveCategory() === "olahraga";
      const citySource = isOlahraga
        ? KOTA_JATIM.filter(k => !k.toLowerCase().includes("online") && !k.toLowerCase().includes("bebas"))
        : KOTA_JATIM;

      const matches = citySource.filter(k => k.toLowerCase().includes(q));
      list.innerHTML = matches.length
        ? matches.map(k => `<li class="city-option px-3 py-2 text-[13px] cursor-pointer transition-colors hover:bg-border ${k === selected ? "text-white font-medium" : "text-muted hover:text-white"}" role="option" data-value="${k}">${k}</li>`).join("")
        : `<li class="px-3 py-2 text-[13px] text-muted italic">Kota tidak ditemukan</li>`;
    };

    const openList = () => {
      renderList(input.value);
      list.classList.remove("hidden");
      if (caret) caret.style.transform = "rotate(180deg)";
    };

    const closeList = () => {
      list.classList.add("hidden");
      if (caret) caret.style.transform = "";
    };

    input.addEventListener("focus", () => openList());
    input.addEventListener("input", () => openList());

    list.addEventListener("click", (e) => {
      const opt = e.target.closest(".city-option");
      if (!opt) return;
      selected = opt.dataset.value;
      input.value = selected;
      input.classList.remove("text-muted");
      input.classList.add("text-white");
      closeList();
    });

    document.addEventListener("click", (e) => {
      if (!combobox.contains(e.target)) closeList();
    });
  });
});
