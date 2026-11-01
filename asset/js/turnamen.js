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

      // Tab mapping section visibility di index Figma
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
        sidebarCatBtns.forEach((b) => {
          b.classList.remove("active", "border-red", "text-white");
          b.classList.add("border-border", "text-muted");
          b.setAttribute("aria-pressed", "false");
          const icon = b.querySelector("i");
          if (icon) icon.className = icon.className.replace("text-red", "");
        });

        btn.classList.add("active", "border-red", "text-white");
        btn.classList.remove("border-border", "text-muted");
        btn.setAttribute("aria-pressed", "true");
        const activeIcon = btn.querySelector("i");
        if (activeIcon && btn.dataset.category === "esports") {
          activeIcon.classList.add("text-red");
        }

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

  // 4. BUAT TURNAMEN DARI SIDEBAR -> REDIRECT KE DETAIL
  const btnBuatTurnamen = document.getElementById("btn-submit-buat-turnamen");
  if (btnBuatTurnamen) {
    btnBuatTurnamen.addEventListener("click", (e) => {
      e.preventDefault();

      const namaInput = document.getElementById("input-nama-turnamen");
      const prizeInput = document.getElementById("input-prize-turnamen");
      const cityInput = document.querySelector("#sidebar-buat-turnamen .city-combobox-input");
      const tglMulai = document.getElementById("tanggal-mulai");
      const tglSelesai = document.getElementById("tanggal-selesai");

      const namaVal = namaInput ? namaInput.value.trim() : "";
      const prizeVal = prizeInput ? prizeInput.value.trim() : "Rp5.000.000";
      const kotaVal = cityInput ? cityInput.value.trim() : "Malang";

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

      const newTrnId = "trn-new-" + Date.now();

      // Simpan poster yang diunggah ke sessionStorage
      const posterPreview = document.getElementById("poster-preview");
      if (posterPreview && posterPreview.src && posterPreview.src.startsWith("data:image")) {
        try {
          sessionStorage.setItem("mabar_uploaded_poster", posterPreview.src);
          sessionStorage.setItem("mabar_uploaded_poster_" + newTrnId, posterPreview.src);
        } catch (err) {
          console.warn("Storage quota exceeded", err);
        }
      } else {
        sessionStorage.removeItem("mabar_uploaded_poster");
      }

      const isInsidePages = window.location.pathname.includes("/pages/");
      const basePath = isInsidePages ? "" : "pages/turnamen/";
      let url = basePath + "detail.php?created=1&role=host";
      url += "&id=" + encodeURIComponent(newTrnId);
      url += "&nama=" + encodeURIComponent(namaVal);
      url += "&game=" + encodeURIComponent(gameVal);
      url += "&prize=" + encodeURIComponent(prizeVal);
      url += "&kota=" + encodeURIComponent(kotaVal);
      url += "&tanggal=" + encodeURIComponent(tanggalVal);
      url += "&kategori=" + encodeURIComponent(activeCreateCat);

      window.location.href = url;
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

  // 6. INISIALISASI BAGAN CHALLONGE DI HALAMAN DETAIL (DETAIL.PHP)
  const boardEl = document.getElementById("challonge-board");
  if (boardEl) {
    const defaultCount = parseInt(document.getElementById("bracket-team-count-select")?.value || "8", 10);
    window.regenerateBracket(defaultCount);
  }

  // 7. RESTORE POSTER DARI SESSION STORAGE DI HALAMAN DETAIL
  const detailPosterImg = document.getElementById("detail-trn-poster-img");
  if (detailPosterImg) {
    const urlParams = new URLSearchParams(window.location.search);
    const trnId = urlParams.get("id") || "";
    const isCreated = urlParams.get("created") === "1";
    const savedPoster = sessionStorage.getItem("mabar_uploaded_poster_" + trnId) || (isCreated ? sessionStorage.getItem("mabar_uploaded_poster") : null);

    if (savedPoster) {
      detailPosterImg.src = savedPoster;
      const modalPosterImg = document.getElementById("modal-poster-img");
      const fullPosterPreviewImg = document.getElementById("full-poster-preview-img");
      const editPosterPreview = document.getElementById("edit-poster-preview");

      if (modalPosterImg) modalPosterImg.src = savedPoster;
      if (fullPosterPreviewImg) fullPosterPreviewImg.src = savedPoster;
      if (editPosterPreview) editPosterPreview.src = savedPoster;
    }
  }

  // 8. UPLOAD / GANTI POSTER OLEH HOST DI TAB PENGATURAN
  const editPosterUpload = document.getElementById("edit-poster-upload");
  if (editPosterUpload) {
    editPosterUpload.addEventListener("change", function () {
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
        const newPoster = e.target.result;
        const detailPoster = document.getElementById("detail-trn-poster-img");
        const modalPoster = document.getElementById("modal-poster-img");
        const fullPoster = document.getElementById("full-poster-preview-img");
        const editPoster = document.getElementById("edit-poster-preview");

        if (detailPoster) detailPoster.src = newPoster;
        if (modalPoster) modalPoster.src = newPoster;
        if (fullPoster) fullPoster.src = newPoster;
        if (editPoster) editPoster.src = newPoster;

        const urlParams = new URLSearchParams(window.location.search);
        const trnId = urlParams.get("id") || "trn-active";
        try {
          sessionStorage.setItem("mabar_uploaded_poster_" + trnId, newPoster);
          sessionStorage.setItem("mabar_uploaded_poster", newPoster);
        } catch (err) {
          console.warn("Storage quota exceeded", err);
        }

        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Poster turnamen berhasil diperbarui!", "success");
        }
      };
      reader.readAsDataURL(file);
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

// Data Pool Tim Bagan
const BRACKET_TEAM_POOL = [
  "Surabaya Wolves", "Astra Orion", "Phantom Legion ID", "Malang Knights",
  "Garuda Esports", "Batu United", "Blitar Apex", "Kediri Roar",
  "Sidoarjo Strike", "Mojokerto Vipers", "Pasuruan Titans", "Gresik Warriors",
  "Jember Cyber", "Banyuwangi Storm", "Madiun Blaze", "Probolinggo Force"
];

let currentBracketState = {
  teamCount: 8,
  rounds: []
};

// Generator Bagan Single Elimination (Challonge Engine)
window.regenerateBracket = function (teamCount = 8) {
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

  const teams = [...BRACKET_TEAM_POOL].slice(0, teamCount);
  const numRounds = roundNames.length;
  currentBracketState.rounds = [];

  let matchesInRound = teamCount / 2;
  for (let r = 0; r < numRounds; r++) {
    const roundMatches = [];
    for (let m = 0; m < matchesInRound; m++) {
      if (r === 0) {
        const t1 = teams[m * 2] || `Tim ${m * 2 + 1}`;
        const t2 = teams[m * 2 + 1] || `Tim ${m * 2 + 2}`;
        roundMatches.push({
          id: `r${r}-m${m}`,
          team1: t1,
          score1: 2,
          team2: t2,
          score2: 1,
          winner: 1
        });
      } else {
        const prevRound = currentBracketState.rounds[r - 1];
        const prevMatch1 = prevRound ? prevRound[m * 2] : null;
        const prevMatch2 = prevRound ? prevRound[m * 2 + 1] : null;

        const advTeam1 = prevMatch1 ? (prevMatch1.winner === 1 ? prevMatch1.team1 : prevMatch1.team2) : `Pemenang M${m * 2 + 1}`;
        const advTeam2 = prevMatch2 ? (prevMatch2.winner === 1 ? prevMatch2.team1 : prevMatch2.team2) : `Pemenang M${m * 2 + 2}`;

        roundMatches.push({
          id: `r${r}-m${m}`,
          team1: advTeam1,
          score1: r === numRounds - 1 ? 3 : 2,
          team2: advTeam2,
          score2: r === numRounds - 1 ? 2 : 1,
          winner: 1
        });
      }
    }
    currentBracketState.rounds.push(roundMatches);
    matchesInRound = matchesInRound / 2;
  }

  renderBracketUI(roundNames);
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
        <span class="text-xs text-muted">Juara 1 • Trophy Resmi</span>
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

  if (typeof window.tampilkanNotifikasi === "function") {
    const winnerName = winnerTeamNum === 1 ? match.team1 : match.team2;
    window.tampilkanNotifikasi(`${winnerName} maju ke babak berikutnya!`, "success");
  }
};

// Acak Tim Bracket
window.shuffleBracketTeams = function () {
  BRACKET_TEAM_POOL.sort(() => Math.random() - 0.5);
  window.regenerateBracket(currentBracketState.teamCount);
  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Seeding tim dalam bagan berhasil diacak!", "info");
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
  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Skor pertandingan bagan telah direset.", "info");
  }
};

// Verifikasi Pendaftar oleh Host
window.verifikasiTimHost = function (btn, teamName, isAccepted) {
  const row = btn.closest(".p-4");
  if (!row) return;

  if (isAccepted) {
    row.innerHTML = `
      <div class="flex items-center gap-2 text-emerald-400 font-bold text-xs py-2">
        <i class="ph-bold ph-check-circle text-base"></i>
        <span>Tim "${teamName}" telah disetujui & dimasukkan ke daftar Roster resmi turnamen!</span>
      </div>
    `;
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(`Tim "${teamName}" berhasil diverifikasi!`, "success");
    }
  } else {
    row.innerHTML = `
      <div class="flex items-center gap-2 text-red font-semibold text-xs py-2">
        <i class="ph-bold ph-x-circle text-base"></i>
        <span>Pendaftaran tim "${teamName}" telah ditolak.</span>
      </div>
    `;
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(`Tim "${teamName}" telah ditolak.`, "info");
    }
  }
};

// Batalkan Turnamen oleh Host
window.batalTurnamenHost = function () {
  if (confirm("Apakah Anda yakin ingin membatalkan dan menghapus sesi turnamen ini?")) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Turnamen berhasil dibatalkan.", "info");
    }
    setTimeout(() => {
      window.location.href = "index.php";
    }, 500);
  }
};

// Pendaftaran Tim oleh Visitor
window.submitPendaftaranTurnamen = function (e) {
  e.preventDefault();
  const teamInput = document.getElementById("reg-team-name");
  const captainInput = document.getElementById("reg-captain-name");
  const teamName = teamInput?.value?.trim() || "Tim Kamu";

  const btn = document.getElementById("btn-submit-reg-trn");
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = `<i class="ph-bold ph-spinner animate-spin"></i> Mendaftarkan...`;
  }

  setTimeout(() => {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = `<i class="ph-bold ph-paper-plane-tilt"></i> <span>Kirim Pendaftaran</span>`;
    }

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(`Pendaftaran tim "${teamName}" berhasil dikirim! Menunggu konfirmasi panitia.`, "success");
    }

    document.getElementById("form-daftar-turnamen-page")?.reset();
    window.switchDetailTab("teams");
  }, 700);
};

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

// Simpan Pengaturan & Edit Informasi Turnamen oleh Host
window.simpanPengaturanTurnamenHost = function (e) {
  e.preventDefault();

  const namaInput = document.getElementById("edit-trn-nama");
  const tanggalInput = document.getElementById("edit-trn-tanggal");
  const kotaInput = document.getElementById("edit-trn-kota");
  const lokasiInput = document.getElementById("edit-trn-lokasi");
  const prizeInput = document.getElementById("edit-trn-prize");
  const descInput = document.getElementById("edit-trn-desc");
  const formatInput = document.getElementById("edit-trn-format");

  const headerTitle = document.getElementById("header-trn-title");
  const headerTanggal = document.getElementById("header-trn-tanggal");
  const headerLokasi = document.getElementById("header-trn-lokasi");
  const headerPrize = document.getElementById("header-trn-prize");
  const headerFormat = document.getElementById("header-trn-format");
  const infoDesc = document.getElementById("info-tab-desc");

  if (namaInput && headerTitle && namaInput.value.trim()) {
    headerTitle.textContent = namaInput.value.trim();
  }
  if (tanggalInput && headerTanggal && tanggalInput.value.trim()) {
    headerTanggal.textContent = tanggalInput.value.trim();
  }
  if (kotaInput && lokasiInput && headerLokasi) {
    const k = kotaInput.value.trim();
    const l = lokasiInput.value.trim();
    headerLokasi.textContent = k ? (l ? `${k} • ${l}` : k) : l;
  }
  if (prizeInput && headerPrize && prizeInput.value.trim()) {
    headerPrize.textContent = prizeInput.value.trim();
  }
  if (formatInput && headerFormat) {
    headerFormat.textContent = formatInput.value;
  }
  if (descInput && infoDesc && descInput.value.trim()) {
    infoDesc.textContent = descInput.value.trim();
  }

  const btnSimpan = document.getElementById("btn-simpan-pengaturan-trn");
  if (btnSimpan) {
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = `<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...`;
  }

  setTimeout(() => {
    if (btnSimpan) {
      btnSimpan.disabled = false;
      btnSimpan.innerHTML = `<i class="ph-bold ph-check"></i> <span>Simpan Perubahan</span>`;
    }

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Perubahan informasi turnamen berhasil disimpan!", "success");
    }

    window.switchDetailTab("bracket");
  }, 500);
};
