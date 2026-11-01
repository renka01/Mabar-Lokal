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
    btn.innerHTML = '<i class="ph-bold ph-check-circle text-sm mr-1.5"></i> Siap Tanding (Ready)';
    btn.className = "inline-flex items-center justify-center rounded-md px-5 py-2.5 text-xs sm:text-[13px] font-bold text-white transition-all shadow-md active:scale-95 cursor-pointer bg-emerald-600 hover:bg-emerald-500";
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Status tim kamu: SIAP TANDING (Ready).", "sukses");
    }
  } else {
    btn.dataset.ready = "false";
    btn.innerHTML = '<i class="ph-bold ph-clock text-sm mr-1.5"></i> Standby (Belum Ready)';
    btn.className = "inline-flex items-center justify-center rounded-md px-5 py-2.5 text-xs sm:text-[13px] font-bold text-white transition-all shadow-md active:scale-95 cursor-pointer bg-amber-600 hover:bg-amber-500";
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Status tim kamu: Standby.", "info");
    }
  }
};


