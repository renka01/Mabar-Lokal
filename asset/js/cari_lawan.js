document.addEventListener("DOMContentLoaded", () => {
  const scrimGrid = document.getElementById("scrim-grid");
  if (!scrimGrid) return;

  const basePath = "../../";

  function notif(pesan, tipe = "sukses") {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(pesan, tipe);
    } else if (typeof window.kirimNotif === "function") {
      window.kirimNotif(pesan, tipe === "sukses" ? "success" : "error");
    } else {
      alert(pesan);
    }
  }

  let activeFilterCategory = "esports";
  const filterCatBtns = document.querySelectorAll(".scrim-filter-cat");
  const gameSelectWrapper = document.querySelector(".scrim-game-select");
  const sportSelectWrapper = document.querySelector(".scrim-sport-select");

  filterCatBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterCatBtns.forEach((b) => {
        b.classList.remove("border-red", "border-[#19d66b]", "bg-card-alt", "text-white");
        b.classList.add("border-border", "text-muted");
      });

      const cat = btn.dataset.category;
      activeFilterCategory = cat;

      if (cat === "esports") {
        btn.classList.add("border-red", "bg-card-alt", "text-white");
        btn.classList.remove("border-border", "text-muted");
        if (gameSelectWrapper) gameSelectWrapper.classList.remove("hidden");
        if (sportSelectWrapper) sportSelectWrapper.classList.add("hidden");
      } else {
        btn.classList.add("border-[#19d66b]", "bg-card-alt", "text-white");
        btn.classList.remove("border-border", "text-muted");
        if (gameSelectWrapper) gameSelectWrapper.classList.add("hidden");
        if (sportSelectWrapper) sportSelectWrapper.classList.remove("hidden");
      }

      jalankanFilter();
    });
  });

  let activeCreateCategory = "esports";
  const createCatBtns = document.querySelectorAll(".scrim-create-cat");
  const createGameSelect = document.querySelector(".scrim-create-game-select");
  const createSportSelect = document.querySelector(".scrim-create-sport-select");

  createCatBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      createCatBtns.forEach((b) => {
        b.classList.remove("border-red", "border-[#19d66b]", "bg-card-alt", "text-white");
        b.classList.add("border-border", "text-muted");
      });

      const cat = btn.dataset.category;
      activeCreateCategory = cat;

      if (cat === "esports") {
        btn.classList.add("border-red", "bg-card-alt", "text-white");
        btn.classList.remove("border-border", "text-muted");
        if (createGameSelect) createGameSelect.classList.remove("hidden");
        if (createSportSelect) createSportSelect.classList.add("hidden");
      } else {
        btn.classList.add("border-[#19d66b]", "bg-card-alt", "text-white");
        btn.classList.remove("border-border", "text-muted");
        if (createGameSelect) createGameSelect.classList.add("hidden");
        if (createSportSelect) createSportSelect.classList.remove("hidden");
      }

      const formatSelect = document.getElementById("scrim-post-format");
      const formatHint = document.getElementById("scrim-format-hint");
      if (formatSelect) {
        if (cat === "esports") {
          if (formatHint) formatHint.textContent = "(maks. BO5)";
          formatSelect.innerHTML = `
            <option value="BO1">BO1 (Best of 1)</option>
            <option value="BO3" selected>BO3 (Best of 3 - Turnamen)</option>
            <option value="BO5">BO5 (Best of 5 - Final)</option>
          `;
        } else {
          if (formatHint) formatHint.textContent = "(durasi match)";
          formatSelect.innerHTML = `
            <option value="2x20M" selected>2 x 20 Menit (Futsal)</option>
            <option value="2x25M">2 x 25 Menit (Futsal/Mini Soccer)</option>
            <option value="4 QTR">4 Quarter x 10M (Basket)</option>
            <option value="3 SET">3 Set 21 Poin (Badminton)</option>
          `;
        }
      }
    });
  });

  let activeTimeTab = "semua";
  const timeTabs = document.querySelectorAll(".scrim-time-tab");

  timeTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      timeTabs.forEach((t) => {
        t.classList.remove("active", "border-red", "text-white");
        t.classList.add("border-transparent", "text-muted");
      });

      tab.classList.add("active", "border-red", "text-white");
      tab.classList.remove("border-transparent", "text-muted");

      activeTimeTab = tab.dataset.tab;
      jalankanFilter();
    });
  });

  const mingguIniCheckbox = document.getElementById("filter-scrim-minggu-ini");
  const btnApplyFilter = document.getElementById("btn-apply-scrim-filter");
  const btnEmptyReset = document.getElementById("btn-empty-reset");
  const filteredCountEl = document.getElementById("scrim-filtered-count");
  const emptyStateEl = document.getElementById("scrim-empty-state");

  function jalankanFilter() {
    const cards = scrimGrid.querySelectorAll(".scrim-card-item");
    const onlyThisWeek = mingguIniCheckbox ? mingguIniCheckbox.checked : false;

    // Ambil game yang sedang dipilih dari dropdown (jika ada)
    let selectedGame = "";
    if (activeFilterCategory === "esports") {
      const gInput = gameSelectWrapper?.querySelector(".dropdown-value");
      if (gInput && !gInput.textContent.includes("Pilih") && !gInput.textContent.includes("--")) {
        selectedGame = gInput.textContent.trim().toLowerCase();
      }
    } else {
      const sInput = sportSelectWrapper?.querySelector(".dropdown-value");
      if (sInput && !sInput.textContent.includes("Pilih") && !sInput.textContent.includes("--")) {
        selectedGame = sInput.textContent.trim().toLowerCase();
      }
    }

    // Ambil kota yang dipilih
    let selectedCity = "";
    const cityInput = document.querySelector("#sidebar-filter-scrim .city-combobox-input");
    if (cityInput && cityInput.value && !cityInput.value.toLowerCase().includes("cari kota")) {
      selectedCity = cityInput.value.trim().toLowerCase();
    }

    let matchCount = 0;

    cards.forEach((card) => {
      const cardCategory = card.dataset.kategori;
      const cardGame = (card.dataset.game || "").toLowerCase();
      const cardKota = (card.dataset.kota || "").toLowerCase();
      const cardHari = card.dataset.hari || "";
      const cardTipe = card.dataset.tipe || "";

      let match = true;

      // 1. Kategori (Esports vs Olahraga)
      if (cardCategory !== activeFilterCategory) {
        match = false;
      }

      // 2. Sub-Tab Waktu / Tipe
      if (match && activeTimeTab !== "semua") {
        if (activeTimeTab === "hari_ini" && cardHari !== "hari_ini") match = false;
        if (activeTimeTab === "weekend" && cardHari !== "weekend") match = false;
        if (activeTimeTab === "online" && cardTipe !== "online") match = false;
        if (activeTimeTab === "offline" && cardTipe !== "offline") match = false;
      }

      // 3. Jadwal Minggu Ini
      if (match && onlyThisWeek && cardHari !== "hari_ini" && cardHari !== "minggu_ini") {
        match = false;
      }

      // 4. Game
      if (match && selectedGame && !cardGame.includes(selectedGame)) {
        match = false;
      }

      // 5. Kota
      if (match && selectedCity && !cardKota.includes(selectedCity)) {
        match = false;
      }

      if (match) {
        card.style.display = "";
        matchCount++;
      } else {
        card.style.display = "none";
      }
    });

    // Update count & empty state
    if (filteredCountEl) filteredCountEl.textContent = matchCount;

    if (matchCount === 0) {
      if (emptyStateEl) emptyStateEl.classList.remove("hidden");
      scrimGrid.classList.add("hidden");
    } else {
      if (emptyStateEl) emptyStateEl.classList.add("hidden");
      scrimGrid.classList.remove("hidden");
    }
  }

  if (btnApplyFilter) btnApplyFilter.addEventListener("click", jalankanFilter);
  if (mingguIniCheckbox) mingguIniCheckbox.addEventListener("change", jalankanFilter);

  // Reset Filter
  function resetFilterSemua() {
    if (mingguIniCheckbox) mingguIniCheckbox.checked = false;
    activeTimeTab = "semua";
    timeTabs.forEach((t, i) => {
      if (i === 0) {
        t.classList.add("active", "border-red", "text-white");
        t.classList.remove("border-transparent", "text-muted");
      } else {
        t.classList.remove("active", "border-red", "text-white");
        t.classList.add("border-transparent", "text-muted");
      }
    });
    jalankanFilter();
    notif("Filter scrim telah direset.", "sukses");
  }

  if (btnEmptyReset) btnEmptyReset.addEventListener("click", resetFilterSemua);

  const btnPostOpenScrim = document.getElementById("btn-post-open-scrim");
  if (btnPostOpenScrim) {
    btnPostOpenScrim.addEventListener("click", async () => {
      const auth = window.MABAR_AUTH || {};
      if (!auth.authenticated) {
        notif("Silakan masuk akun terlebih dahulu untuk membuka tantangan scrim!", "error");
        setTimeout(() => { window.location.href = `${basePath}pages/auth.php`; }, 750);
        return;
      }

      const teamNameInput = document.getElementById("scrim-post-team-name");
      const teamName = teamNameInput ? teamNameInput.value.trim() : "";

      if (!teamName) {
        teamNameInput?.focus();
        teamNameInput?.classList.add("border-red");
        setTimeout(() => teamNameInput?.classList.remove("border-red"), 2500);
        notif("Harap isi nama tim kamu terlebih dahulu!", "error");
        return;
      }

      const formatVal = document.getElementById("scrim-post-format")?.value || "BO3";
      const isEsports = activeCreateCategory === "esports";

      let gameVal = "";
      if (isEsports) {
        const gameValEl = document.querySelector(".scrim-create-game-select .dropdown-value");
        gameVal = gameValEl && !gameValEl.classList.contains("text-muted") ? gameValEl.textContent.trim() : "Valorant";
      } else {
        const sportValEl = document.querySelector(".scrim-create-sport-select .dropdown-value");
        gameVal = sportValEl && !sportValEl.classList.contains("text-muted") ? sportValEl.textContent.trim() : "Futsal";
      }

      const cityInput = document.querySelector("#sidebar-buat-scrim .city-combobox-input");
      const kotaVal = cityInput && cityInput.value.trim() ? cityInput.value.trim() : (isEsports ? "Online" : "Kota Malang");

      const prevHtml = btnPostOpenScrim.innerHTML;
      btnPostOpenScrim.disabled = true;
      btnPostOpenScrim.innerHTML = `<i class="ph ph-circle-notch animate-spin text-base"></i><span>Membuka Tantangan...</span>`;

      try {
        const res = await fetch(`${basePath}api/scrims/create.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({
            category: activeCreateCategory,
            team_name: teamName,
            game: gameVal,
            format_short: formatVal,
            city: kotaVal
          })
        });
        const data = await res.json();
        if (!res.ok || !data || data.ok === false) {
          throw new Error((data && data.message) || "Gagal membuka tantangan scrim.");
        }

        notif(data.message || `Membuka ruang tanding untuk "${teamName}"...`, "sukses");
        setTimeout(() => {
          window.location.href = data.redirect || `detail.php?id=${encodeURIComponent(data.slug)}`;
        }, 400);
      } catch (err) {
        btnPostOpenScrim.disabled = false;
        btnPostOpenScrim.innerHTML = prevHtml;
        notif(err.message || "Gagal membuka tantangan scrim.", "error");
      }
    });
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      window.tutupModalTantang();
    }
  });
});

let currentTargetScrim = null;

window.bukaModalTantang = function (scrimData) {
  if (!scrimData) return;
  currentTargetScrim = scrimData;

  const modal = document.getElementById("modal-tantang-scrim");
  if (!modal) return;

  const badgeEl = document.getElementById("modal-target-badge");
  const gameEl = document.getElementById("modal-target-game");
  const highlightEl = document.getElementById("modal-target-team-highlight");
  const metaEl = document.getElementById("modal-target-meta");
  const formatEl = document.getElementById("modal-target-format");
  const idEl = document.getElementById("modal-target-scrim-id");

  if (badgeEl) {
    const isOlahraga = scrimData.kategori === "olahraga";
    badgeEl.textContent = isOlahraga ? "Olahraga" : "Esports";
    badgeEl.className = isOlahraga
      ? "font-bold text-[#19d66b] uppercase tracking-wider text-[10px] px-2 py-0.5 rounded bg-[#19d66b]/10 border border-[#19d66b]/20"
      : "font-bold text-red uppercase tracking-wider text-[10px] px-2 py-0.5 rounded bg-red/10 border border-red/20";
  }
  if (gameEl) gameEl.textContent = scrimData.game || "";
  if (highlightEl) highlightEl.textContent = scrimData.nama_tim || "Tim Lawan";
  if (metaEl) metaEl.textContent = `${scrimData.tier || '-'} • ${scrimData.kota || ''}`;
  if (formatEl) formatEl.textContent = scrimData.format_short || scrimData.format || "BO3";
  if (idEl) idEl.value = scrimData.id || "";

  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";

  setTimeout(() => {
    const inputTeam = document.getElementById("input-challenger-team");
    if (inputTeam) inputTeam.focus();
  }, 100);
};

window.tutupModalTantang = function () {
  const modal = document.getElementById("modal-tantang-scrim");
  if (!modal) return;
  modal.classList.add("hidden");
  document.body.style.overflow = "";
};

window.kirimTantanganScrim = async function (e) {
  if (e) e.preventDefault();

  const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  const auth = window.MABAR_AUTH || {};
  if (!auth.authenticated) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Silakan masuk akun terlebih dahulu untuk menantang tim scrim!", "error");
    }
    setTimeout(() => { window.location.href = `${basePath}pages/auth.php`; }, 750);
    return;
  }

  const targetName = currentTargetScrim ? currentTargetScrim.nama_tim : "Tim Lawan";
  const myTeamName = document.getElementById("input-challenger-team")?.value.trim() || "";
  const myContact  = document.getElementById("input-challenger-contact")?.value.trim() || "";
  const myMessage  = document.getElementById("input-challenger-message")?.value.trim() || "";
  const targetId   = document.getElementById("modal-target-scrim-id")?.value || (currentTargetScrim ? currentTargetScrim.id : "");

  const submitBtn = document.querySelector("#form-tantang-scrim button[type='submit']");
  const prevHtml = submitBtn ? submitBtn.innerHTML : "Kirim Tantangan";
  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin mr-1.5"></i> Mengirim...`;
  }

  try {
    const res = await fetch(`${basePath}api/scrims/challenge.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      credentials: "same-origin",
      body: JSON.stringify({
        scrim_id: targetId,
        team_name: myTeamName,
        contact: myContact,
        message: myMessage
      })
    });
    const data = await res.json();
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || "Gagal mengirim tantangan scrim.");
    }

    window.tutupModalTantang();

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(
        data.message || `Tantangan dari "${myTeamName}" berhasil dikirim ke kapten ${targetName}!`,
        "sukses"
      );
    }

    setTimeout(() => {
      window.location.href = data.redirect || `detail.php?id=${encodeURIComponent(data.slug || targetId)}`;
    }, 500);
  } catch (err) {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = prevHtml;
    }
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(err.message || "Gagal mengirim tantangan.", "error");
    }
  }
};
