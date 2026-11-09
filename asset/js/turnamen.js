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
  window.konfirmasiModal({
    judul: "Batalkan Turnamen?",
    pesan: "Apakah Anda yakin ingin membatalkan dan menghapus sesi turnamen ini?",
    teksYa: "Ya, Batalkan"
  }).then((ya) => {
    if (!ya) return;
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Turnamen berhasil dibatalkan.", "info");
    }
    setTimeout(() => {
      window.location.href = "index.php";
    }, 500);
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

// Sinkronisasi data tim pendaftar ke tab Roster & tab Status Pendaftaran
window.sinkronkanTimTerdaftar = function (trnId) {
  if (!trnId) {
    trnId = document.getElementById("page-trn-id")?.value || new URLSearchParams(window.location.search).get("id") || "trn-val-01";
  }

  const storedData = localStorage.getItem("mabar_trn_reg_" + trnId);
  const teamGrid = document.getElementById("trn-team-cards-grid");
  const panelMember = document.getElementById("panel-member-registered");
  const panelVisitor = document.getElementById("panel-visitor-registration");

  if (!storedData) {
    const urlParams = new URLSearchParams(window.location.search);
    const isMemberParam = urlParams.get("joined") === "1" || urlParams.get("role") === "member";
    if (!isMemberParam) {
      if (panelMember) panelMember.classList.add("hidden");
      if (panelVisitor) panelVisitor.classList.remove("hidden");
    }
    return;
  }

  let teamData;
  try {
    teamData = JSON.parse(storedData);
  } catch (err) {
    return;
  }

  // 1. Tampilkan Panel Member Terdaftar & Perbarui Datanya
  if (panelMember && panelVisitor) {
    panelMember.classList.remove("hidden");
    panelVisitor.classList.add("hidden");

    const titleEl = document.getElementById("reg-ticket-team-title");
    const nameEl = document.getElementById("reg-ticket-team-name");
    const capEl = document.getElementById("reg-ticket-captain-name");
    const rosterListEl = document.getElementById("reg-ticket-roster-list");

    if (titleEl) titleEl.textContent = teamData.nama;
    if (nameEl) nameEl.textContent = teamData.nama;
    if (capEl) capEl.textContent = `${teamData.kapten} (You)`;

    if (rosterListEl && Array.isArray(teamData.roster)) {
      rosterListEl.innerHTML = teamData.roster.map((player, idx) => `
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#08120f] border border-emerald-500/30 text-white text-xs font-semibold">
          <span class="${idx === 0 ? 'text-amber-400 font-black' : 'text-muted font-bold'} text-[10px]">
            ${idx === 0 ? 'C' : '#' + (idx + 1)}
          </span> 
          ${escapeHtml(player)}
        </span>
      `).join('');
    }
  }

  // 2. Render / Pasang Tim Kamu di bagian paling atas Tab Roster
  if (teamGrid) {
    const existingUserCard = document.getElementById("card-user-registered-team");
    if (existingUserCard) existingUserCard.remove();

    // Sembunyikan default mock "Surabaya Wolves" jika user sudah mendaftar timnya sendiri
    const firstMock = teamGrid.querySelector(".trn-team-card");
    if (firstMock && teamData.nama.toLowerCase() !== "surabaya wolves") {
      firstMock.style.display = "none";
    }

    const cardEl = document.createElement("div");
    cardEl.id = "card-user-registered-team";
    cardEl.className = "trn-team-card rounded-xl p-5 flex flex-col justify-between gap-4 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl";
    cardEl.style.cssText = "background: linear-gradient(180deg, #0e1e17 0%, #08120d 100%); border: 1.5px solid rgba(25, 214, 107, 0.55); box-shadow: 0 4px 24px rgba(25, 214, 107, 0.15);";

    const rosterHtml = (teamData.roster || []).map((player, idx) => {
      const isCap = idx === 0;
      return `
        <button
          type="button"
          class="flex items-center gap-2 px-2.5 py-2 rounded-lg border text-left transition-all hover:border-emerald-400 hover:bg-[#14261d] cursor-pointer group shadow-sm"
          style="background-color: #0b1510; border-color: rgba(25, 214, 107, 0.25);"
          onclick="window.bukaProfilPemainTurnamen && window.bukaProfilPemainTurnamen('${escapeJs(player)}', '${isCap ? 'Kapten / Duelist' : 'Roster Utama'}', ${isCap}, '${escapeJs(teamData.nama)}', '${escapeJs(teamData.kota)}')"
          title="Klik untuk melihat profil ${escapeHtml(player)}"
        >
          <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-[10px] font-black ${isCap ? 'bg-amber-400/20 text-amber-400 border border-amber-400/50' : 'bg-slate-800 text-slate-300 border border-slate-700'}">
            ${isCap ? 'C' : (idx + 1)}
          </div>
          <span class="text-[12px] font-bold text-white/90 truncate group-hover:text-white transition-colors" title="${escapeHtml(player)}">
            ${escapeHtml(player)}
          </span>
        </button>
      `;
    }).join('');

    cardEl.innerHTML = `
      <div class="flex flex-col gap-3.5">
        <!-- Card Header: Clean Info + Seed -->
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <h4 class="text-[17px] font-extrabold text-white tracking-tight truncate leading-tight">${escapeHtml(teamData.nama)}</h4>
              <i class="ph-fill ph-seal-check text-emerald-400 text-base" title="Tim Resmi Terverifikasi"></i>
            </div>
            <div class="flex items-center gap-2 text-[12px] text-muted font-medium mt-1">
              <span class="flex items-center gap-1">
                <i class="ph-bold ph-map-pin text-red text-xs"></i>
                <span>${escapeHtml(teamData.kota)}</span>
              </span>
              <span class="text-white/20">&bull;</span>
              <span class="text-emerald-400 font-semibold flex items-center gap-1 text-[11px]">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Terverifikasi Siap Tanding
              </span>
            </div>
          </div>

          <!-- Badges Right -->
          <div class="flex flex-col items-end gap-1.5 shrink-0">
            <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shadow-sm" style="background-color: #19d66b; color: #06160d;">
              Tim Kamu
            </span>
            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold text-slate-300 shadow-sm" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
              ${escapeHtml(teamData.seed || "Seed #1")}
            </span>
          </div>
        </div>

        <!-- Captain Bar (Clickable) -->
        <div class="flex items-center justify-between px-3.5 py-2.5 rounded-lg border transition-all cursor-pointer hover:border-amber-400/60 group"
             style="background: rgba(245, 158, 11, 0.06); border: 1px solid rgba(245, 158, 11, 0.22);"
             onclick="window.bukaProfilPemainTurnamen && window.bukaProfilPemainTurnamen('${escapeJs(teamData.kapten)}', 'Kapten Tim', true, '${escapeJs(teamData.nama)}', '${escapeJs(teamData.kota)}')"
             title="Klik untuk melihat profil Kapten Tim">
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
              <i class="ph-fill ph-crown text-sm"></i>
            </div>
            <div class="min-w-0">
              <div class="text-[10px] uppercase font-bold text-amber-400 tracking-wider">Kapten Tim</div>
              <div class="text-[13px] font-bold text-white truncate leading-tight group-hover:text-amber-300 transition-colors">
                ${escapeHtml(teamData.kapten)} (You)
              </div>
            </div>
          </div>
          <span class="text-[11px] font-semibold text-amber-400/90 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
            Lihat Profil <i class="ph-bold ph-arrow-up-right text-xs"></i>
          </span>
        </div>
      </div>

      <!-- Lineup Roster -->
      <div class="pt-3 border-t" style="border-color: rgba(255, 255, 255, 0.08);">
        <div class="flex items-center justify-between mb-2.5">
          <span class="text-[11px] text-muted font-bold uppercase tracking-wider flex items-center gap-1.5">
            <i class="ph-bold ph-users-three text-xs"></i>
            <span>Lineup Roster (5 Pemain)</span>
          </span>
          <span class="text-[11px] text-emerald-400 font-semibold flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> 5/5 Terdaftar
          </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
          ${rosterHtml}
        </div>
      </div>
    `;

    teamGrid.prepend(cardEl);
  }
};

// Kirim Pendaftaran Tim Baru oleh Visitor
window.submitPendaftaranTurnamen = function (e) {
  e.preventDefault();
  const trnId = document.getElementById("page-trn-id")?.value || "trn-val-01";
  const teamInput = document.getElementById("reg-team-name");
  const captainInput = document.getElementById("reg-captain-name");
  const contactInput = document.getElementById("reg-captain-contact");

  const r1 = document.getElementById("reg-roster-1")?.value.trim() || captainInput?.value.trim() || "Kapten";
  const r2 = document.getElementById("reg-roster-2")?.value.trim() || "Roster 2";
  const r3 = document.getElementById("reg-roster-3")?.value.trim() || "Roster 3";
  const r4 = document.getElementById("reg-roster-4")?.value.trim() || "Roster 4";
  const r5 = document.getElementById("reg-roster-5")?.value.trim() || "Roster 5";

  const teamName = teamInput?.value.trim() || "Tim Kamu";
  const captainName = captainInput?.value.trim() || "Kapten";

  const newTeam = {
    nama: teamName,
    kapten: captainName,
    kontak: contactInput?.value.trim() || "",
    roster: [r1, r2, r3, r4, r5],
    kota: localStorage.getItem("mabar_profile_location") || "Malang",
    status: "Verified",
    seed: "Seed #1",
    isUserTeam: true,
    registeredAt: new Date().toISOString()
  };

  const btn = document.getElementById("btn-submit-reg-trn");
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = `<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan Tim...`;
  }

  setTimeout(() => {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = `<i class="ph-bold ph-paper-plane-tilt"></i> <span>Kirim Pendaftaran Tim</span>`;
    }

    // Simpan ke localStorage
    localStorage.setItem("mabar_trn_reg_" + trnId, JSON.stringify(newTeam));

    // Beri notifikasi visual
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(`Pendaftaran tim "${teamName}" berhasil! Tim kamu langsung masuk ke Roster turnamen.`, "success");
    }

    // Jalankan sinkronisasi DOM langsung
    window.sinkronkanTimTerdaftar(trnId);

    // Otomatis arahkan user ke Tab Roster
    window.switchDetailTab("teams");
  }, 600);
};

// Batalkan Pendaftaran Tim / Ingin Daftar Ulang
window.batalkanPendaftaranTim = function () {
  const trnId = document.getElementById("page-trn-id")?.value || "trn-val-01";
  window.konfirmasiModal({
    judul: "Batalkan Pendaftaran?",
    pesan: "Apakah kamu yakin ingin membatalkan pendaftaran tim ini dan mendaftar ulang?",
    teksYa: "Ya, Batalkan"
  }).then((ya) => {
    if (!ya) return;
    localStorage.removeItem("mabar_trn_reg_" + trnId);
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Pendaftaran tim dibatalkan. Kamu bisa mendaftarkan tim baru.", "info");
    }
    const panelMember = document.getElementById("panel-member-registered");
    const panelVisitor = document.getElementById("panel-visitor-registration");
    if (panelMember) panelMember.classList.add("hidden");
    if (panelVisitor) panelVisitor.classList.remove("hidden");

    const userCard = document.getElementById("card-user-registered-team");
    if (userCard) userCard.remove();

    const teamGrid = document.getElementById("trn-team-cards-grid");
    const firstMock = teamGrid?.querySelector(".trn-team-card");
    if (firstMock) firstMock.style.display = "";

    window.switchDetailTab("daftar");
  });
};

// Auto-sync & pre-populate pendaftaran saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
  if (typeof window.sinkronkanTimTerdaftar === "function") {
    window.sinkronkanTimTerdaftar();
  }

  const captainInput = document.getElementById("reg-captain-name");
  const phoneInput = document.getElementById("reg-captain-contact");
  const r1Input = document.getElementById("reg-roster-1");

  const savedUserName = localStorage.getItem("mabar_profile_name") || "Wawan";
  const savedPhone = localStorage.getItem("mabar_profile_phone") || "0823999955";

  if (captainInput && !captainInput.value) {
    captainInput.value = savedUserName;
  }
  if (phoneInput && !phoneInput.value) {
    phoneInput.value = savedPhone;
  }
  if (r1Input && !r1Input.value) {
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

// ══════════════════════════════════════════════════════════════
// MODAL PROFIL PEMAIN TURNAMEN (INTERAKSI KLIK ROSTER / KAPTEN)
// ══════════════════════════════════════════════════════════════
window.bukaProfilPemainTurnamen = function (nama, rank, isCaptain, teamNama, kota) {
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

  if (modalPlayerName) modalPlayerName.textContent = nama || "Pemain Roster";
  if (modalPlayerRank) modalPlayerRank.textContent = rank || "Roster Kompetitif";
  if (modalPlayerCity) modalPlayerCity.textContent = kota || "Malang";

  if (modalPlayerHostBadge) {
    if (isCaptain) {
      modalPlayerHostBadge.textContent = "Kapten Tim";
      modalPlayerHostBadge.classList.remove("hidden");
    } else {
      modalPlayerHostBadge.classList.add("hidden");
    }
  }

  if (modalPlayerAvatarImg) modalPlayerAvatarImg.classList.add("hidden");
  if (modalPlayerAvatarIcon) modalPlayerAvatarIcon.classList.remove("hidden");

  if (modalPlayerBio) {
    modalPlayerBio.textContent = `Squad ${teamNama || "Kompetitif"} • Siap bertanding dan berkoordinasi secara fair play di turnamen resmi Mabar Lokal.`;
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

  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
};
