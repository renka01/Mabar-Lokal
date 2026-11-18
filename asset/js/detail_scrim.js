(function () {
  function getScrimSlug() {
    return document.getElementById("scrim-detail-main")?.dataset?.scrimSlug || "";
  }

  function getBasePath() {
    return typeof window.MABAR_BASE_PATH === "string" ? window.MABAR_BASE_PATH : "../../";
  }

  async function postScrimApi(endpoint, payload) {
    const res = await fetch(`${getBasePath()}api/scrims/${endpoint}`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      credentials: "same-origin",
      body: JSON.stringify(payload || {})
    });
    const data = await res.json();
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || "Terjadi kesalahan pada server.");
    }
    return data;
  }

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

  window.simpanPengaturanScrim = async function (e) {
    if (e) e.preventDefault();

    const scrimSlug = getScrimSlug();
    const dateVal = document.getElementById("scrim-setting-date")?.value.trim() || "Hari ini";
    const timeVal = document.getElementById("scrim-setting-time")?.value.trim() || "20:30 WIB";
    const formatSelect = document.getElementById("scrim-setting-format");
    const formatVal = formatSelect?.value || "";
    const formatLabel = formatSelect?.selectedOptions[0]?.textContent.trim() || formatVal;
    const venueVal = document.getElementById("scrim-setting-venue")?.value.trim() || "Online";
    const notesVal = document.getElementById("scrim-setting-notes")?.value.trim() || "";
    const roomId = document.getElementById("scrim-setting-room-id")?.value.trim() || "";
    const roomPassword = document.getElementById("scrim-setting-room-pass")?.value.trim() || "";

    const submitBtn = document.querySelector("#form-scrim-settings button[type='submit']");
    const prevHtml = submitBtn ? submitBtn.innerHTML : "Simpan Pengaturan Match";
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin mr-1.5"></i> Menyimpan...`;
    }

    try {
      const data = await postScrimApi("update.php", {
        scrim_id: scrimSlug,
        date: dateVal,
        time: timeVal,
        format_short: formatVal,
        format_label: formatLabel,
        venue: venueVal,
        notes: notesVal,
        room_id: roomId,
        room_password: roomPassword
      });

      const setText = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
      };

      setText("detail-scrim-jadwal-display", data.jadwal || `${dateVal}, ${timeVal}`);
      if (formatVal) {
        setText("detail-scrim-format-display", formatVal);
        setText("scrim-info-format", formatLabel);
      }
      setText("detail-scrim-lokasi-display", venueVal);
      setText("scrim-info-venue", venueVal);

      const notesText = notesVal ? `"${notesVal}"` : '"Tidak ada catatan khusus."';
      setText("detail-scrim-catatan-display", notesText);
      setText("scrim-info-catatan", notesText);

      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Pengaturan jadwal tanding & rules berhasil disimpan!", "sukses");
      }
      setTimeout(() => window.gantiTabScrim("info"), 350);
    } catch (err) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal menyimpan pengaturan match.", "error");
      }
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = prevHtml;
      }
    }
  };

  window.salinLinkMatch = function () {
    navigator.clipboard.writeText(window.location.href).then(() => {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Link ruang match berhasil disalin ke clipboard!", "sukses");
      }
    }).catch(() => {
      prompt("Salin link ini:", window.location.href);
    });
  };

  window.batalTantanganHost = function () {
    window.konfirmasiModal({
      judul: "Batalkan Tantangan?",
      pesan: "Tantangan akan dihapus permanen dari daftar terbuka. Apakah kamu yakin?",
      teksYa: "Ya, Batalkan"
    }).then(async (ya) => {
      if (!ya) return;
      try {
        const data = await postScrimApi("delete.php", { scrim_id: getScrimSlug() });
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi(data.message || "Tantangan sparring berhasil dibatalkan.", "sukses");
        }
        setTimeout(() => { window.location.href = data.redirect || "index.php"; }, 450);
      } catch (err) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi(err.message || "Gagal membatalkan tantangan.", "error");
        }
      }
    });
  };

  window.konfirmasiMatchHost = async function () {
    const btn = document.getElementById("btn-host-confirm-scrim");
    if (btn) btn.disabled = true;
    try {
      const data = await postScrimApi("ready.php", {
        scrim_id: getScrimSlug(),
        action: "host_confirm"
      });
      if (btn) {
        btn.innerHTML = `<i class="ph-bold ph-check-circle text-base mr-1.5"></i><span>Match Terkonfirmasi</span>`;
      }
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Match resmi terkonfirmasi!", "sukses");
      }
    } catch (err) {
      if (btn) btn.disabled = false;
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal mengonfirmasi match.", "error");
      }
    }
  };

  window.toggleReadyChallenger = async function (btn) {
    if (!btn) return;
    const nextReady = btn.dataset.ready !== "true";
    const rosterBadge = document.getElementById("scrim-roster-b-ready");
    btn.disabled = true;

    try {
      const data = await postScrimApi("ready.php", {
        scrim_id: getScrimSlug(),
        action: "challenger_ready",
        is_ready: nextReady
      });

      btn.dataset.ready = nextReady ? "true" : "false";
      if (nextReady) {
        btn.innerHTML = '<i class="ph-bold ph-check text-base mr-1.5"></i> <span>Tim Siap (Ready)</span>';
        btn.className = "inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] transition-all active:scale-95 cursor-pointer";
        if (rosterBadge) {
          rosterBadge.textContent = "Siap Tanding";
          rosterBadge.style.color = "#19d66b";
          rosterBadge.style.background = "rgba(25, 214, 107, 0.12)";
          rosterBadge.style.borderColor = "rgba(25, 214, 107, 0.3)";
        }
      } else {
        btn.innerHTML = '<i class="ph-bold ph-clock text-base mr-1.5"></i> <span>Standby (Belum Ready)</span>';
        btn.className = "inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-amber-500/60 text-amber-400 bg-amber-500/10 hover:bg-amber-500 hover:text-black hover:shadow-[0_0_16px_rgba(245,158,11,0.35)] transition-all active:scale-95 cursor-pointer";
        if (rosterBadge) {
          rosterBadge.textContent = "Standby";
          rosterBadge.style.color = "#f59e0b";
          rosterBadge.style.background = "rgba(245, 158, 11, 0.12)";
          rosterBadge.style.borderColor = "rgba(245, 158, 11, 0.3)";
        }
      }

      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || (nextReady ? "Status tim kamu: Ready." : "Status tim kamu: Standby."), "sukses");
      }
    } catch (err) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal memperbarui status kesiapan.", "error");
      }
    } finally {
      btn.disabled = false;
    }
  };
})();
