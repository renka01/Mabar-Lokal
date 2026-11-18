document.addEventListener("DOMContentLoaded", () => {
  // 1. FILTER TABS DI HALAMAN UTAMA (INDEX.PHP)
  const timeTabs = document.querySelectorAll(".tournament-time-tab");
  const tournamentCards = document.querySelectorAll(".tournament-card-item");
  const countEl = document.getElementById("turnamen-filtered-count");

  if (timeTabs.length && tournamentCards.length) {
    timeTabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        timeTabs.forEach((t) => {
          t.classList.remove("active", "border-red", "text-white");
          t.classList.add("border-transparent", "text-muted");
        });
        tab.classList.add("active", "border-red", "text-white");
        tab.classList.remove("border-transparent", "text-muted");

        const targetTab = tab.dataset.tab;
        filterTournaments(targetTab);
      });
    });

    function filterTournaments(tabFilter = "semua") {
      let visible = 0;
      const activeCat = document.querySelector('#sidebar-cari-turnamen .turnamen-filter.active')?.dataset.category || "all";

      const secUnggulan = document.getElementById("section-unggulan");
      const secAkanDatang = document.getElementById("section-akandatang");
      const secBerlangsung = document.getElementById("section-berlangsung");
      const secSelesai = document.getElementById("section-selesai");

      if (secUnggulan) {
        if (tabFilter === "semua" || tabFilter === "berlangsung") secUnggulan.classList.remove("hidden");
        else secUnggulan.classList.add("hidden");
      }
      if (secAkanDatang) {
        if (tabFilter === "semua" || tabFilter === "akandatang") secAkanDatang.classList.remove("hidden");
        else secAkanDatang.classList.add("hidden");
      }
      if (secBerlangsung) {
        if (tabFilter === "semua" || tabFilter === "berlangsung") secBerlangsung.classList.remove("hidden");
        else secBerlangsung.classList.add("hidden");
      }
      if (secSelesai) {
        if (tabFilter === "semua" || tabFilter === "selesai") secSelesai.classList.remove("hidden");
        else secSelesai.classList.add("hidden");
      }

      tournamentCards.forEach((card) => {
        const cardStatus = card.dataset.status || "";
        const cardKat = card.dataset.kategori || "";

        let statusMatch = true;
        if (tabFilter === "akandatang") statusMatch = cardStatus === "upcoming";
        else if (tabFilter === "berlangsung") statusMatch = cardStatus === "live";
        else if (tabFilter === "selesai") statusMatch = cardStatus === "done";

        let catMatch = true;
        if (activeCat !== "all" && activeCat) {
          catMatch = cardKat === activeCat;
        }

        if (statusMatch && catMatch) {
          card.classList.remove("hidden");
          visible++;
        } else {
          card.classList.add("hidden");
        }
      });

      if (countEl) countEl.textContent = visible;
    }

    // 2. FILTER SIDEBAR (KATEGORI ESPORTS / OLAHRAGA)
    const sidebarCatBtns = document.querySelectorAll('#sidebar-cari-turnamen .turnamen-filter');
    sidebarCatBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        const activeTab = document.querySelector(".tournament-time-tab.active")?.dataset.tab || "semua";
        filterTournaments(activeTab);
      });
    });

    // 3. TOMBOL CARI TURNAMEN
    const btnCari = document.getElementById("btn-cari-turnamen");
    if (btnCari) {
      btnCari.addEventListener("click", () => {
        const activeTab = document.querySelector(".tournament-time-tab.active")?.dataset.tab || "semua";
        filterTournaments(activeTab);

        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Menampilkan daftar turnamen sesuai filter.", "info");
        }
      });
    }
  }

  // 4. BUAT TURNAMEN DARI SIDEBAR -> POSTGRESQL (MENDUKUNG UPLOAD POSTER)
  const btnBuatTurnamen = document.getElementById("btn-submit-buat-turnamen");
  if (btnBuatTurnamen) {
    btnBuatTurnamen.addEventListener("click", async (e) => {
      e.preventDefault();

      const basePath = typeof window.MABAR_BASE_PATH === "string"
        ? window.MABAR_BASE_PATH
        : (window.location.pathname.includes("/pages/") ? "../../" : "");
      const auth = window.MABAR_AUTH || {};

      if (!auth.authenticated) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Silakan masuk akun terlebih dahulu untuk membuat turnamen!", "error");
        }
        setTimeout(() => { window.location.href = `${basePath}pages/auth.php`; }, 750);
        return;
      }

      const namaInput = document.getElementById("input-nama-turnamen");
      const prizeInput = document.getElementById("input-prize-turnamen");
      const cityInput = document.querySelector("#sidebar-buat-turnamen .city-combobox-input");
      const tglMulai = document.getElementById("tanggal-mulai");
      const tglSelesai = document.getElementById("tanggal-selesai");
      const posterFileInput = document.getElementById("poster-upload");

      const namaVal = namaInput ? namaInput.value.trim() : "";
      const prizeVal = prizeInput && prizeInput.value.trim() ? prizeInput.value.trim() : "Gratis";
      const kotaVal = cityInput && cityInput.value.trim() ? cityInput.value.trim() : "Malang";

      let tanggalVal = "Pekan Ini";
      if (tglMulai && tglMulai.value.trim()) {
        tanggalVal = tglMulai.value.trim() + (tglSelesai && tglSelesai.value.trim() ? " - " + tglSelesai.value.trim() : "");
      }

      const activeCreateCat = document.querySelector('#sidebar-buat-turnamen .turnamen-filter.active')?.dataset.category || "esports";
      const isOlahraga = activeCreateCat === "olahraga";

      let gameVal = "";
      if (isOlahraga) {
        const sportEl = document.querySelector("#sidebar-buat-turnamen .sport-dropdown-wrapper .dropdown-value");
        if (sportEl && !sportEl.textContent.includes("Pilih")) gameVal = sportEl.textContent.trim();
        else gameVal = "Futsal";
      } else {
        const gameEl = document.querySelector("#sidebar-buat-turnamen .game-dropdown-wrapper .dropdown-value");
        if (gameEl && !gameEl.textContent.includes("Pilih")) gameVal = gameEl.textContent.trim();
        else gameVal = "Valorant";
      }

      if (!namaVal) {
        if (namaInput) {
          namaInput.style.borderColor = "#ef233c";
          namaInput.focus();
          setTimeout(() => (namaInput.style.borderColor = ""), 2000);
        }
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Harap masukkan Nama Turnamen terlebih dahulu!", "error");
        }
        return;
      }

      const prevHtml = btnBuatTurnamen.innerHTML;
      btnBuatTurnamen.disabled = true;
      btnBuatTurnamen.innerHTML = `<i class="ph ph-circle-notch animate-spin text-base"></i><span>Membuat Turnamen...</span>`;

      try {
        const formData = new FormData();
        formData.append("category", activeCreateCat);
        formData.append("title", namaVal);
        formData.append("game", gameVal);
        formData.append("city", kotaVal);
        formData.append("schedule_text", tanggalVal);
        formData.append("prize_pool", prizeVal);
        if (posterFileInput && posterFileInput.files && posterFileInput.files[0]) {
          formData.append("poster", posterFileInput.files[0]);
        }

        const res = await fetch(`${basePath}api/tournaments/create.php`, {
          method: "POST",
          credentials: "same-origin",
          body: formData
        });
        const data = await res.json();
        if (!res.ok || !data || data.ok === false) {
          throw new Error((data && data.message) || "Gagal membuat turnamen baru.");
        }

        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi(data.message || "Turnamen berhasil dibuat!", "sukses");
        }

        const detailPrefix = window.location.pathname.includes("/pages/turnamen/") ? "" : "pages/turnamen/";
        window.location.href = detailPrefix + (data.redirect || ("detail.php?id=" + encodeURIComponent(data.slug)));
      } catch (err) {
        btnBuatTurnamen.disabled = false;
        btnBuatTurnamen.innerHTML = prevHtml;
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi(err.message || "Gagal membuat turnamen.", "error");
        }
      }
    });
  }

  // 5. UPLOAD POSTER PREVIEW DI SIDEBAR BUAT TURNAMEN
  const posterInput = document.getElementById("poster-upload");
  const posterPreview = document.getElementById("poster-preview");
  const posterPlaceholder = document.getElementById("poster-placeholder");
  const posterRemove = document.getElementById("poster-remove");

  if (posterInput && posterPreview && posterPlaceholder) {
    posterInput.addEventListener("change", function () {
      const file = this.files && this.files[0];
      if (!file) return;

      if (file.size > 5 * 1024 * 1024) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Ukuran file poster maksimal 5MB!", "error");
        }
        this.value = "";
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        posterPreview.src = e.target.result;
        posterPreview.classList.remove("hidden");
        posterPlaceholder.classList.add("hidden");
        if (posterRemove) posterRemove.classList.remove("hidden");
      };
      reader.readAsDataURL(file);
    });

    if (posterRemove) {
      posterRemove.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        posterInput.value = "";
        posterPreview.src = "";
        posterPreview.classList.add("hidden");
        posterPlaceholder.classList.remove("hidden");
        posterRemove.classList.add("hidden");
      });
    }
  }

  const boardEl = document.getElementById("challonge-board");
  if (boardEl) {
    const trnData = window.MABAR_TOURNAMENT_DATA || {};
    if (trnData.bracket && Array.isArray(trnData.bracket.rounds) && trnData.bracket.rounds.length > 0) {
      currentBracketState = trnData.bracket;
      const tc = Number(currentBracketState.teamCount) || 8;
      const selectEl = document.getElementById("bracket-team-count-select");
      if (selectEl) selectEl.value = String(tc);
      const rNames = tc === 16 ? ["Babak 16 Besar", "Perempat Final", "Semifinal", "Grand Final"]
        : (tc === 8 ? ["Perempat Final", "Semifinal", "Grand Final"] : ["Semifinal", "Grand Final"]);
      renderBracketUI(rNames);
    } else {
      const defaultCount = parseInt(document.getElementById("bracket-team-count-select")?.value || "8", 10);
      window.regenerateBracket(defaultCount, false);
    }
  }

  // 7. UPLOAD / GANTI POSTER OLEH HOST DI TAB PENGATURAN -> SERVER (asset/uploads/posters/)
  const editPosterUpload = document.getElementById("edit-poster-upload");
  if (editPosterUpload) {
    editPosterUpload.addEventListener("change", async function () {
      const file = this.files && this.files[0];
      if (!file) return;

      if (file.size > 5 * 1024 * 1024) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Ukuran file poster maksimal 5MB!", "error");
        }
        this.value = "";
        return;
      }

      const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
      const slug = window.MABAR_TOURNAMENT_DATA?.slug || new URLSearchParams(window.location.search).get("id") || "";
      const previewUrl = URL.createObjectURL(file);

      const applyPosterSrc = (src) => {
        ["detail-trn-poster-img", "modal-poster-img", "full-poster-preview-img", "edit-poster-preview"].forEach((id) => {
          const el = document.getElementById(id);
          if (el) el.src = src;
        });
      };

      applyPosterSrc(previewUrl);

      try {
        const formData = new FormData();
        formData.append("tournament_id", slug);
        formData.append("poster", file);

        const res = await fetch(`${basePath}api/tournaments/update.php`, {
          method: "POST",
          credentials: "same-origin",
          body: formData
        });
        const data = await res.json();
        if (!res.ok || !data || data.ok === false) {
          throw new Error((data && data.message) || "Gagal memperbarui poster.");
        }

        if (data.poster_path) {
          applyPosterSrc(basePath + data.poster_path);
        }
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi(data.message || "Poster turnamen berhasil diperbarui!", "sukses");
        }
      } catch (err) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi(err.message || "Gagal mengunggah poster turnamen.", "error");
        }
      } finally {
        URL.revokeObjectURL(previewUrl);
        this.value = "";
      }
    });
  }
});

// Tab Switcher
window.switchDetailTab = function (tabKey) {
  document.querySelectorAll(".trn-nav-tab").forEach((tab) => {
    tab.classList.remove("active", "border-red", "text-white");
    tab.classList.add("border-transparent", "text-muted");
  });

  const activeBtn = document.getElementById(`tab-btn-${tabKey}`);
  if (activeBtn) {
    activeBtn.classList.add("active", "border-red", "text-white");
    activeBtn.classList.remove("border-transparent", "text-muted");
  }

  document.querySelectorAll(".trn-tab-content").forEach((view) => {
    view.classList.add("hidden");
  });

  const activeView = document.getElementById(`view-tab-${tabKey}`);
  if (activeView) {
    activeView.classList.remove("hidden");
  }
};

function getLiveBracketTeamPool(teamCount) {
  const realTeams = Array.isArray(window.MABAR_TOURNAMENT_DATA?.teamNames)
    ? [...window.MABAR_TOURNAMENT_DATA.teamNames]
    : [];
  const pool = [];
  for (let i = 0; i < teamCount; i++) {
    pool.push(realTeams[i] || `Slot Tim #${i + 1}`);
  }
  return pool;
}

async function persistBracketIfHost() {
  const trnData = window.MABAR_TOURNAMENT_DATA || {};
  if (!trnData.isHost || !trnData.slug) return;
  const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  try {
    await fetch(`${basePath}api/tournaments/update.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      credentials: "same-origin",
      body: JSON.stringify({
        tournament_id: trnData.slug,
        bracket: currentBracketState
      })
    });
  } catch (e) {}
}

let currentBracketState = {
  teamCount: 8,
  rounds: []
};

// Generator Bagan Single Elimination (Challonge Engine)
window.regenerateBracket = function (teamCount = 8, shouldPersist = true) {
  const board = document.getElementById("challonge-board");
  if (!board) return;

  currentBracketState.teamCount = teamCount;

  let roundNames = [];
  if (teamCount === 16) {
    roundNames = ["Babak 16 Besar", "Perempat Final", "Semifinal", "Grand Final"];
  } else if (teamCount === 8) {
    roundNames = ["Perempat Final", "Semifinal", "Grand Final"];
  } else {
    roundNames = ["Semifinal", "Grand Final"];
  }

  const teams = getLiveBracketTeamPool(teamCount);
  const numRounds = roundNames.length;
  currentBracketState.rounds = [];

  let matchesInRound = teamCount / 2;
  for (let r = 0; r < numRounds; r++) {
    const roundMatches = [];
    for (let m = 0; m < matchesInRound; m++) {
      if (r === 0) {
        const t1 = teams[m * 2] || `Slot Tim #${m * 2 + 1}`;
        const t2 = teams[m * 2 + 1] || `Slot Tim #${m * 2 + 2}`;
        roundMatches.push({
          id: `r${r}-m${m}`,
          team1: t1,
          score1: 0,
          team2: t2,
          score2: 0,
          winner: 1
        });
      } else {
        roundMatches.push({
          id: `r${r}-m${m}`,
          team1: `Pemenang M${m * 2 + 1}`,
          score1: 0,
          team2: `Pemenang M${m * 2 + 2}`,
          score2: 0,
          winner: 1
        });
      }
    }
    currentBracketState.rounds.push(roundMatches);
    matchesInRound = matchesInRound / 2;
  }

  renderBracketUI(roundNames);
  if (shouldPersist) persistBracketIfHost();
};

function renderBracketUI(roundNames) {
  const board = document.getElementById("challonge-board");
  if (!board) return;

  const userRole = document.getElementById("tournament-detail-page")?.dataset?.userRole || 'visitor';
  const isHost = userRole === 'host';
  const isMember = userRole === 'member';

  board.innerHTML = "";

  currentBracketState.rounds.forEach((roundMatches, rIdx) => {
    const col = document.createElement("div");
    col.className = "challonge-round-col";

    const header = document.createElement("div");
    header.className = "challonge-round-header";
    header.innerHTML = `
      <span class="text-xs font-bold uppercase tracking-wider text-white">${roundNames[rIdx]}</span>
      <span class="text-[10px] text-muted block mt-0.5">${roundMatches.length} Pertandingan</span>
    `;
    col.appendChild(header);

    const matchesList = document.createElement("div");
    matchesList.className = "challonge-matches-list";

    roundMatches.forEach((match, mIdx) => {
      const isFinal = rIdx === currentBracketState.rounds.length - 1;
      const matchNode = document.createElement("div");
      matchNode.className = "challonge-match-node";

      const isTeam1User = isMember && match.team1 === "Surabaya Wolves";
      const isTeam2User = isMember && match.team2 === "Surabaya Wolves";

      const team1UserClass = isTeam1User ? "user-team" : "";
      const team2UserClass = isTeam2User ? "user-team" : "";
      const readOnlyClass = !isHost ? "read-only" : "";

      matchNode.innerHTML = `
        <div class="challonge-match-meta">
          <span>Match ${mIdx + 1}</span>
          <span>${isFinal ? "BO5 Final" : "BO3"}</span>
        </div>

        <div
          class="challonge-team-row ${match.winner === 1 ? 'winner' : ''} ${team1UserClass} ${readOnlyClass}"
          onclick="toggleBracketWinner(${rIdx}, ${mIdx}, 1)"
          title="${isHost ? 'Klik untuk memilih pemenang match' : (isTeam1User ? 'Tim Kamu (Surabaya Wolves)' : '')}"
        >
          <span class="challonge-team-seed">${mIdx * 2 + 1}</span>
          <span class="challonge-team-name" title="${match.team1}">${match.team1} ${isTeam1User ? '<span class="text-[9px] px-1 rounded bg-emerald-500/20 text-emerald-400 font-bold ml-1">KITA</span>' : ''}</span>
          <span class="challonge-team-score">${match.score1}</span>
        </div>

        <div
          class="challonge-team-row ${match.winner === 2 ? 'winner' : ''} ${team2UserClass} ${readOnlyClass}"
          onclick="toggleBracketWinner(${rIdx}, ${mIdx}, 2)"
          title="${isHost ? 'Klik untuk memilih pemenang match' : (isTeam2User ? 'Tim Kamu (Surabaya Wolves)' : '')}"
        >
          <span class="challonge-team-seed">${mIdx * 2 + 2}</span>
          <span class="challonge-team-name" title="${match.team2}">${match.team2} ${isTeam2User ? '<span class="text-[9px] px-1 rounded bg-emerald-500/20 text-emerald-400 font-bold ml-1">KITA</span>' : ''}</span>
          <span class="challonge-team-score">${match.score2}</span>
        </div>
      `;
      matchesList.appendChild(matchNode);
    });

    col.appendChild(matchesList);
    board.appendChild(col);
  });

  // Champion Column (Pemenang Akhir)
  const lastRound = currentBracketState.rounds[currentBracketState.rounds.length - 1];
  const finalMatch = lastRound ? lastRound[0] : null;
  const championTeam = finalMatch ? (finalMatch.winner === 1 ? finalMatch.team1 : finalMatch.team2) : "Astra Orion";

  const champCol = document.createElement("div");
  champCol.className = "challonge-round-col";
  champCol.innerHTML = `
    <div class="challonge-round-header">
      <span class="text-xs font-bold uppercase tracking-wider text-[#F9A826]">Pemenang Turnamen</span>
      <span class="text-[10px] text-muted block mt-0.5">Juara 1 Resmi</span>
    </div>
    <div class="challonge-matches-list">
      <div class="challonge-champion-card">
        <i class="ph-fill ph-trophy text-4xl text-[#F9A826]"></i>
        <span class="text-[11px] font-bold tracking-widest text-[#F9A826] uppercase">CHAMPION</span>
        <h4 class="text-[17px] font-extrabold text-white">${championTeam}</h4>
        <span class="text-xs text-muted">Juara 1</span>
      </div>
    </div>
  `;
  board.appendChild(champCol);
}

// Interaksi Ganti Pemenang Match di Bagan
window.toggleBracketWinner = function (roundIdx, matchIdx, winnerTeamNum) {
  const userRole = document.getElementById("tournament-detail-page")?.dataset?.userRole || 'visitor';
  if (userRole !== 'host') {
    if (typeof window.tampilkanNotifikasi === "function") {
      if (userRole === 'member') {
        window.tampilkanNotifikasi("Anda berada di mode tim peserta. Hanya Penyelenggara (Host) yang dapat menentukan pemenang.", "info");
      } else {
        window.tampilkanNotifikasi("Bagan resmi ini diperbarui langsung oleh panitia penyelenggara.", "info");
      }
    }
    return;
  }

  const round = currentBracketState.rounds[roundIdx];
  if (!round || !round[matchIdx]) return;

  const match = round[matchIdx];
  match.winner = winnerTeamNum;
  if (winnerTeamNum === 1) {
    match.score1 = 2;
    match.score2 = 0;
  } else {
    match.score1 = 0;
    match.score2 = 2;
  }

  // Update babak berikutnya
  const nextRoundIdx = roundIdx + 1;
  if (nextRoundIdx < currentBracketState.rounds.length) {
    const nextMatchIdx = Math.floor(matchIdx / 2);
    const nextMatch = currentBracketState.rounds[nextRoundIdx][nextMatchIdx];
    const winningTeamName = winnerTeamNum === 1 ? match.team1 : match.team2;

    if (matchIdx % 2 === 0) {
      nextMatch.team1 = winningTeamName;
    } else {
      nextMatch.team2 = winningTeamName;
    }
  }

  const teamCount = currentBracketState.teamCount;
  let roundNames = teamCount === 16 ? ["Babak 16 Besar", "Perempat Final", "Semifinal", "Grand Final"] :
                   teamCount === 8 ? ["Perempat Final", "Semifinal", "Grand Final"] : ["Semifinal", "Grand Final"];

  renderBracketUI(roundNames);
  persistBracketIfHost();

  if (typeof window.tampilkanNotifikasi === "function") {
    const winnerName = winnerTeamNum === 1 ? match.team1 : match.team2;
    window.tampilkanNotifikasi(`${winnerName} maju ke babak berikutnya!`, "sukses");
  }
};

// Acak Tim Bracket
window.shuffleBracketTeams = function () {
  if (Array.isArray(window.MABAR_TOURNAMENT_DATA?.teamNames)) {
    window.MABAR_TOURNAMENT_DATA.teamNames.sort(() => Math.random() - 0.5);
  }
  window.regenerateBracket(currentBracketState.teamCount, true);
  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Seeding tim dalam bagan berhasil diacak & disimpan!", "sukses");
  }
};

// Reset Skor Bracket
window.resetBracketScores = function () {
  currentBracketState.rounds.forEach((round) => {
    round.forEach((m) => {
      m.score1 = 0;
      m.score2 = 0;
      m.winner = 1;
    });
  });

  const teamCount = currentBracketState.teamCount;
  let roundNames = teamCount === 16 ? ["Babak 16 Besar", "Perempat Final", "Semifinal", "Grand Final"] :
                   teamCount === 8 ? ["Perempat Final", "Semifinal", "Grand Final"] : ["Semifinal", "Grand Final"];

  renderBracketUI(roundNames);
  persistBracketIfHost();
  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Skor pertandingan bagan telah direset.", "sukses");
  }
};

window.verifikasiTimHost = async function (btn, teamId, teamName, isAccepted) {
  const row = btn.closest(".p-4");
  const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  const slug = window.MABAR_TOURNAMENT_DATA?.slug || document.getElementById("page-trn-id")?.value || "";

  btn.disabled = true;
  try {
    const res = await fetch(`${basePath}api/tournaments/verify_team.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      credentials: "same-origin",
      body: JSON.stringify({
        tournament_id: slug,
        team_id: teamId,
        accept: Boolean(isAccepted)
      })
    });
    const data = await res.json();
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || "Gagal memverifikasi tim.");
    }

    if (row) {
      row.innerHTML = isAccepted
        ? `<div class="flex items-center gap-2 text-emerald-400 font-bold text-xs py-2"><i class="ph-bold ph-check-circle text-base"></i><span>Tim "${escapeHtml(teamName)}" telah disetujui &amp; masuk ke Roster resmi!</span></div>`
        : `<div class="flex items-center gap-2 text-red font-semibold text-xs py-2"><i class="ph-bold ph-x-circle text-base"></i><span>Pendaftaran tim "${escapeHtml(teamName)}" telah ditolak.</span></div>`;
    }

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(data.message || "Status pendaftar diperbarui.", isAccepted ? "sukses" : "error");
    }
    setTimeout(() => window.location.reload(), 650);
  } catch (err) {
    btn.disabled = false;
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(err.message || "Gagal memperbarui verifikasi.", "error");
    }
  }
};

window.batalTurnamenHost = function () {
  const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  const slug = window.MABAR_TOURNAMENT_DATA?.slug || document.getElementById("page-trn-id")?.value || "";

  window.konfirmasiModal({
    judul: "Batalkan Turnamen?",
    pesan: "Apakah Anda yakin ingin membatalkan dan menghapus turnamen ini secara permanen?",
    teksYa: "Ya, Batalkan"
  }).then(async (ya) => {
    if (!ya) return;
    try {
      const res = await fetch(`${basePath}api/tournaments/delete.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        credentials: "same-origin",
        body: JSON.stringify({ tournament_id: slug })
      });
      const data = await res.json();
      if (!res.ok || !data || data.ok === false) {
        throw new Error((data && data.message) || "Gagal menghapus turnamen.");
      }
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Turnamen berhasil dibatalkan.", "sukses");
      }
      setTimeout(() => { window.location.href = data.redirect || "index.php"; }, 450);
    } catch (err) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal membatalkan turnamen.", "error");
      }
    }
  });
};

// ══════════════════════════════════════════════════════════════
// PENDAFTARAN TIM & SINKRONISASI KE ROSTER TIM
// ══════════════════════════════════════════════════════════════
function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function escapeJs(str) {
  if (!str) return '';
  return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
}

window.submitPendaftaranTurnamen = async function (e) {
  e.preventDefault();

  const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  const auth = window.MABAR_AUTH || {};
  if (!auth.authenticated) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Silakan masuk akun terlebih dahulu untuk mendaftarkan tim!", "error");
    }
    setTimeout(() => { window.location.href = `${basePath}pages/auth.php`; }, 750);
    return;
  }

  const trnId        = document.getElementById("page-trn-id")?.value || window.MABAR_TOURNAMENT_DATA?.slug || "";
  const teamInput    = document.getElementById("reg-team-name");
  const captainInput = document.getElementById("reg-captain-name");
  const contactInput = document.getElementById("reg-captain-contact");
  const cityInput    = document.getElementById("reg-team-city") || document.querySelector("#reg-team-city-wrap .city-combobox-input");

  const r1 = document.getElementById("reg-roster-1")?.value.trim() || captainInput?.value.trim() || "Kapten";
  const r2 = document.getElementById("reg-roster-2")?.value.trim() || "";
  const r3 = document.getElementById("reg-roster-3")?.value.trim() || "";
  const r4 = document.getElementById("reg-roster-4")?.value.trim() || "";
  const r5 = document.getElementById("reg-roster-5")?.value.trim() || "";
  const roster = [r1, r2, r3, r4, r5].filter(Boolean);

  const teamName    = teamInput?.value.trim() || "";
  const captainName = captainInput?.value.trim() || "";
  const contact     = contactInput?.value.trim() || "";
  const teamCity    = cityInput?.value.trim() || "Kota Malang";

  const btn = document.getElementById("btn-submit-reg-trn");
  const prevHtml = btn ? btn.innerHTML : "";
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = `<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan Tim...`;
  }

  try {
    const res = await fetch(`${basePath}api/tournaments/register_team.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      credentials: "same-origin",
      body: JSON.stringify({
        tournament_id: trnId,
        team_name: teamName,
        captain_name: captainName,
        captain_contact: contact,
        city: teamCity,
        roster
      })
    });
    const data = await res.json();
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || "Gagal mengirim pendaftaran tim.");
    }

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(data.message || `Pendaftaran tim "${teamName}" berhasil!`, "sukses");
    }
    setTimeout(() => window.location.reload(), 550);
  } catch (err) {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = prevHtml || `<i class="ph-bold ph-paper-plane-tilt"></i> <span>Kirim Pendaftaran Tim</span>`;
    }
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(err.message || "Gagal mendaftarkan tim.", "error");
    }
  }
};

window.batalkanPendaftaranTim = function () {
  const basePath = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  const trnId = document.getElementById("page-trn-id")?.value || window.MABAR_TOURNAMENT_DATA?.slug || "";

  window.konfirmasiModal({
    judul: "Batalkan Pendaftaran?",
    pesan: "Apakah kamu yakin ingin membatalkan pendaftaran tim ini dan mendaftar ulang?",
    teksYa: "Ya, Batalkan"
  }).then(async (ya) => {
    if (!ya) return;
    try {
      const res = await fetch(`${basePath}api/tournaments/cancel_registration.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        credentials: "same-origin",
        body: JSON.stringify({ tournament_id: trnId })
      });
      const data = await res.json();
      if (!res.ok || !data || data.ok === false) {
        throw new Error((data && data.message) || "Gagal membatalkan pendaftaran.");
      }
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Pendaftaran tim dibatalkan.", "sukses");
      }
      setTimeout(() => window.location.reload(), 450);
    } catch (err) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal membatalkan pendaftaran tim.", "error");
      }
    }
  });
};

// Auto pre-populate form pendaftaran dari profil user aktif
document.addEventListener("DOMContentLoaded", () => {
  const captainInput = document.getElementById("reg-captain-name");
  const phoneInput = document.getElementById("reg-captain-contact");
  const cityInput = document.getElementById("reg-team-city") || document.querySelector("#reg-team-city-wrap .city-combobox-input");
  const r1Input = document.getElementById("reg-roster-1");

  const authUser = window.MABAR_AUTH?.authenticated ? window.MABAR_AUTH.user : null;
  const savedUserName = authUser?.name || "";
  const savedPhone = authUser?.phone || "";
  const savedLocationRaw = (authUser?.city || "").trim();

  const normalizeCityOption = (loc) => {
    if (!loc || loc.toLowerCase() === "jawa timur") return "";
    const kotaList = ["Surabaya", "Malang", "Kediri", "Blitar", "Madiun", "Mojokerto", "Probolinggo", "Pasuruan", "Batu"];
    const foundKota = kotaList.find((c) => c.toLowerCase() === loc.toLowerCase());
    if (foundKota) return "Kota " + foundKota;
    return loc;
  };
  const savedLocation = normalizeCityOption(savedLocationRaw);

  if (captainInput && !captainInput.value && savedUserName) {
    captainInput.value = savedUserName;
  }
  if (phoneInput && !phoneInput.value && savedPhone) {
    phoneInput.value = savedPhone;
  }
  if (cityInput) {
    cityInput.id = "reg-team-city";
    cityInput.required = true;
    cityInput.placeholder = "Pilih / cari kota tim...";
    if (!cityInput.value && savedLocation) {
      cityInput.value = savedLocation;
    }
  }
  if (r1Input && !r1Input.value && savedUserName) {
    r1Input.value = savedUserName;
  }

  if (captainInput && r1Input) {
    captainInput.addEventListener("input", () => {
      if (!r1Input.dataset.manual) {
        r1Input.value = captainInput.value;
      }
    });
    r1Input.addEventListener("input", () => {
      r1Input.dataset.manual = "1";
    });
  }
});

// Modal Lightbox Poster
window.bukaModalPoster = function () {
  const modal = document.getElementById("modal-poster-turnamen");
  if (modal) {
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }
};

window.tutupModalPoster = function () {
  const modal = document.getElementById("modal-poster-turnamen");
  if (modal) {
    modal.classList.add("hidden");
    document.body.style.overflow = "";
  }
};

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    window.tutupModalPoster();
  }
});

window.simpanPengaturanTurnamenHost = async function (e) {
  e.preventDefault();

  const basePath     = typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  const slug         = window.MABAR_TOURNAMENT_DATA?.slug || document.getElementById("page-trn-id")?.value || "";
  const namaInput    = document.getElementById("edit-trn-nama");
  const tanggalInput = document.getElementById("edit-trn-tanggal");
  const kotaInput    = document.getElementById("edit-trn-kota");
  const lokasiInput  = document.getElementById("edit-trn-lokasi");
  const prizeInput   = document.getElementById("edit-trn-prize");
  const descInput    = document.getElementById("edit-trn-desc");
  const formatInput  = document.getElementById("edit-trn-format");

  const btnSimpan = document.getElementById("btn-simpan-pengaturan-trn");
  if (btnSimpan) {
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = `<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...`;
  }

  try {
    const res = await fetch(`${basePath}api/tournaments/update.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      credentials: "same-origin",
      body: JSON.stringify({
        tournament_id: slug,
        title: namaInput?.value.trim() || "",
        schedule_text: tanggalInput?.value.trim() || "",
        city: kotaInput?.value.trim() || "",
        location: lokasiInput?.value.trim() || "",
        prize_pool: prizeInput?.value.trim() || "",
        format_label: formatInput?.value || "",
        description: descInput?.value.trim() || ""
      })
    });
    const data = await res.json();
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || "Gagal menyimpan perubahan turnamen.");
    }

    const headerTitle   = document.getElementById("header-trn-title");
    const headerTanggal = document.getElementById("header-trn-tanggal");
    const headerLokasi  = document.getElementById("header-trn-lokasi");
    const headerPrize   = document.getElementById("header-trn-prize");
    const headerFormat  = document.getElementById("header-trn-format");
    const infoDesc      = document.getElementById("info-tab-desc");

    if (namaInput && headerTitle && namaInput.value.trim()) headerTitle.textContent = namaInput.value.trim();
    if (tanggalInput && headerTanggal && tanggalInput.value.trim()) headerTanggal.textContent = tanggalInput.value.trim();
    if (kotaInput && lokasiInput && headerLokasi) {
      const k = kotaInput.value.trim();
      const l = lokasiInput.value.trim();
      headerLokasi.textContent = k ? (l ? `${k} • ${l}` : k) : l;
    }
    if (prizeInput && headerPrize && prizeInput.value.trim()) headerPrize.textContent = prizeInput.value.trim();
    if (formatInput && headerFormat) headerFormat.textContent = formatInput.value;
    if (descInput && infoDesc && descInput.value.trim()) infoDesc.textContent = descInput.value.trim();

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(data.message || "Perubahan informasi turnamen berhasil disimpan!", "sukses");
    }
    window.switchDetailTab("bracket");
  } catch (err) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(err.message || "Gagal menyimpan perubahan.", "error");
    }
  } finally {
    if (btnSimpan) {
      btnSimpan.disabled = false;
      btnSimpan.innerHTML = `<i class="ph-bold ph-check"></i> <span>Simpan Perubahan</span>`;
    }
  }
};

// ══════════════════════════════════════════════════════════════
// MODAL PROFIL PEMAIN TURNAMEN (INTERAKSI KLIK ROSTER / KAPTEN)
// ══════════════════════════════════════════════════════════════
window.bukaProfilPemainTurnamen = function (nama, rank, isCaptain, teamNama, kota, isSelf) {
  const modal = document.getElementById("modal-player-profile");
  if (!modal) return;

  const modalPlayerName = document.getElementById("modal-player-name");
  const modalPlayerRank = document.getElementById("modal-player-rank");
  const modalPlayerHostBadge = document.getElementById("modal-player-host-badge");
  const modalPlayerCity = document.getElementById("modal-player-city");
  const modalPlayerBio = document.getElementById("modal-player-bio");
  const modalPlayerAvatarImg = document.getElementById("modal-player-avatar-img");
  const modalPlayerAvatarIcon = document.getElementById("modal-player-avatar-icon");
  const modalPlayerInterests = document.getElementById("modal-player-interests");

  const savedUserName = (localStorage.getItem("mabar_profile_name") || "Wawan").trim();
  const savedUserAvatarRaw = localStorage.getItem("mabar_profile_avatar");
  const savedUserAvatar = savedUserAvatarRaw && typeof window.mabarResolveAsset === "function"
    ? window.mabarResolveAsset(savedUserAvatarRaw)
    : savedUserAvatarRaw;
  const cleanNama = String(nama || "").replace(/\s*\(You\)\s*$/i, "").trim();
  const isSelfPlayer = Boolean(
    isSelf ||
    String(nama || "").includes("(You)") ||
    (savedUserName && cleanNama.toLowerCase() === savedUserName.toLowerCase())
  );

  if (modalPlayerName) modalPlayerName.textContent = isSelfPlayer ? (savedUserName || cleanNama || "Pemain Roster") : (nama || "Pemain Roster");
  if (modalPlayerRank) modalPlayerRank.textContent = rank || "Roster Kompetitif";
  if (modalPlayerCity) {
    const savedLoc = (localStorage.getItem("mabar_profile_location") || "").trim();
    modalPlayerCity.textContent = (isSelfPlayer && savedLoc && savedLoc.toLowerCase() !== "jawa timur") ? savedLoc : (kota || "Malang");
  }

  if (modalPlayerHostBadge) {
    if (isCaptain) {
      modalPlayerHostBadge.textContent = "Kapten Tim";
      modalPlayerHostBadge.classList.remove("hidden");
    } else {
      modalPlayerHostBadge.classList.add("hidden");
    }
  }

  if (isSelfPlayer && savedUserAvatar && modalPlayerAvatarImg) {
    modalPlayerAvatarImg.src = savedUserAvatar;
    modalPlayerAvatarImg.classList.remove("hidden");
    if (modalPlayerAvatarIcon) modalPlayerAvatarIcon.classList.add("hidden");
  } else {
    if (modalPlayerAvatarImg) {
      modalPlayerAvatarImg.src = "";
      modalPlayerAvatarImg.classList.add("hidden");
    }
    if (modalPlayerAvatarIcon) modalPlayerAvatarIcon.classList.remove("hidden");
  }

  if (modalPlayerBio) {
    const userBio = isSelfPlayer ? (localStorage.getItem("mabar_profile_bio") || "").trim() : "";
    modalPlayerBio.textContent = userBio
      ? `"${userBio}"`
      : `Squad ${teamNama || "Kompetitif"} • Siap bertanding dan berkoordinasi secara fair play di turnamen resmi Mabar Lokal.`;
  }

  if (modalPlayerInterests) {
    modalPlayerInterests.innerHTML = `
      <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; background: #16202e; border: 1px solid #233142; font-size: 11px; font-weight: 600; color: #fff;">
        <i class="ph-fill ph-game-controller text-red text-xs"></i> Game Kompetitif
      </span>
      <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; background: #16202e; border: 1px solid #233142; font-size: 11px; font-weight: 600; color: #fff;">
        <i class="ph-fill ph-shield-star text-amber-400 text-xs"></i> 100% Sportif
      </span>
    `;
  }

  const dmBtn = document.getElementById("btn-modal-send-dm");
  if (dmBtn) {
    dmBtn.onclick = function () {
      const targetName = nama || "Pemain Roster";
      const subtitle = teamNama ? `Squad ${teamNama}` : "Roster Turnamen";
      if (typeof window.tutupModalProfil === "function") {
        window.tutupModalProfil();
      }
      if (window.MabarChat && typeof window.MabarChat.openWith === "function") {
        window.MabarChat.openWith(targetName, subtitle);
      } else if (typeof window.openChat === "function") {
        window.openChat();
      }
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(`Membuka obrolan pribadi dengan ${targetName}...`, "success");
      }
    };
  }

  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
};
