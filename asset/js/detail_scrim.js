/**
 * asset/js/detail_scrim.js — Logika Interaktif Halaman Ruang Tanding Scrim
 * Menangani tab switching antara Lobby Match dan Pengaturan Jadwal/Rules,
 * serta penyimpanan kredensial room.
 */

window.gantiTabScrim = function (tabName) {
  const panelInfo = document.getElementById("panel-scrim-info");
  const panelSettings = document.getElementById("panel-scrim-settings");
  const tabBtnInfo = document.getElementById("tab-btn-match-info");
  const tabBtnSettings = document.getElementById("tab-btn-match-settings");

  if (!panelInfo || !panelSettings || !tabBtnInfo || !tabBtnSettings) return;

  if (tabName === "settings") {
    panelInfo.classList.add("hidden");
    panelSettings.classList.remove("hidden");

    tabBtnSettings.classList.add("active", "border-red", "text-white");
    tabBtnSettings.classList.remove("border-transparent", "text-muted");

    tabBtnInfo.classList.remove("active", "border-red", "text-white");
    tabBtnInfo.classList.add("border-transparent", "text-muted");
  } else {
    panelSettings.classList.add("hidden");
    panelInfo.classList.remove("hidden");

    tabBtnInfo.classList.add("active", "border-red", "text-white");
    tabBtnInfo.classList.remove("border-transparent", "text-muted");

    tabBtnSettings.classList.remove("active", "border-red", "text-white");
    tabBtnSettings.classList.add("border-transparent", "text-muted");
  }
};

window.simpanPengaturanScrim = function (e) {
  if (e) e.preventDefault();

  const dateVal = document.getElementById("scrim-setting-date")?.value.trim() || "Hari ini";
  const timeVal = document.getElementById("scrim-setting-time")?.value.trim() || "20:30 WIB";
  const venueVal = document.getElementById("scrim-setting-venue")?.value.trim() || "Online";

  const jadwalDisplay = document.getElementById("detail-scrim-jadwal-display");
  if (jadwalDisplay) {
    jadwalDisplay.textContent = `${dateVal}, ${timeVal}`;
  }

  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Pengaturan jadwal tanding & rules berhasil diperbarui!", "sukses");
  } else {
    alert("Pengaturan match berhasil disimpan!");
  }

  setTimeout(() => {
    window.gantiTabScrim("info");
  }, 400);
};

window.salinLinkMatch = function () {
  navigator.clipboard.writeText(window.location.href).then(() => {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Link ruang match berhasil disalin ke clipboard!", "sukses");
    } else {
      alert("Link disalin!");
    }
  }).catch(() => {
    prompt("Salin link ini:", window.location.href);
  });
};

window.batalTantanganHost = function () {
  if (confirm("Apakah kamu yakin ingin membatalkan tantangan sparring ini? Tantangan akan dihapus dari daftar terbuka.")) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Tantangan sparring berhasil dibatalkan.", "info");
    }
    setTimeout(() => {
      window.location.href = "index.php";
    }, 600);
  }
};

window.konfirmasiMatchHost = function () {
  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Match resmi terkonfirmasi! Ruang tanding telah dibuka untuk kedua tim.", "sukses");
  } else {
    alert("Match resmi terkonfirmasi!");
  }
};

window.toggleReadyChallenger = function (btn) {
  if (!btn) return;
  const isReady = btn.dataset.ready === "true";
  if (!isReady) {
    btn.dataset.ready = "true";
    btn.innerHTML = '<i class="ph-bold ph-check text-base mr-1.5"></i> <span>Tim Siap (Ready)</span>';
    btn.className = "inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] transition-all active:scale-95 cursor-pointer";
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Status tim kamu: SIAP TANDING (Ready).", "sukses");
    }
  } else {
    btn.dataset.ready = "false";
    btn.innerHTML = '<i class="ph-bold ph-clock text-base mr-1.5"></i> <span>Standby (Belum Ready)</span>';
    btn.className = "inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-amber-500/60 text-amber-400 bg-amber-500/10 hover:bg-amber-500 hover:text-black hover:shadow-[0_0_16px_rgba(245,158,11,0.35)] transition-all active:scale-95 cursor-pointer";
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Status tim kamu: Standby.", "info");
    }
  }
};


