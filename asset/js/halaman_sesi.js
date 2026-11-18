window.validateSlotRange = function(input) {
  const min = parseInt(input.min, 10);
  const max = parseInt(input.max, 10);
  if (isNaN(min) || isNaN(max)) return;

  let val = parseInt(input.value, 10);
  if (isNaN(val)) return;
  if (val < min) {
    input.value = min;
  } else if (val > max) {
    input.value = max;
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('sesi-main-container');
  if (!container) return;

  const themeColor  = container.dataset.themeColor || '#ef233c';
  const sessionSlug = container.dataset.sessionSlug || '';
  const basePath    = typeof window.MABAR_BASE_PATH === 'string' ? window.MABAR_BASE_PATH : '../../';

  const tabBtnLobby      = document.getElementById('tab-btn-lobby');
  const tabBtnDetail     = document.getElementById('tab-btn-detail');
  const tabContentLobby  = document.getElementById('tab-content-lobby');
  const tabContentDetail = document.getElementById('tab-content-detail');

  function kirimNotif(pesan, tipe = 'success') {
    if (typeof window.tampilkanNotifikasi === 'function') {
      window.tampilkanNotifikasi(pesan, tipe === 'unready' || tipe === 'error' ? 'error' : 'sukses');
    } else if (typeof window.showGlobalToast === 'function') {
      window.showGlobalToast(pesan, tipe === 'unready' || tipe === 'error' ? 'error' : 'success');
    }
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = String(text ?? '');
    return div.innerHTML;
  }

  async function postSessionApi(endpoint, payload) {
    const res = await fetch(`${basePath}api/sessions/${endpoint}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      credentials: 'same-origin',
      body: JSON.stringify(payload || {})
    });
    let data = null;
    try {
      data = await res.json();
    } catch (e) {
      data = null;
    }
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || 'Terjadi kesalahan pada server.');
    }
    return data;
  }

  function createEmptySlotEl() {
    const el = document.createElement('div');
    el.className = 'empty-slot-row flex items-center justify-between p-4 sm:p-4.5 rounded-xl border border-dashed';
    el.style.borderColor = '#1e2a38';
    el.style.backgroundColor = 'rgba(13, 22, 34, 0.3)';
    el.innerHTML = `
      <div class="flex items-center gap-4 sm:gap-5 min-w-0">
        <div class="h-12 w-12 shrink-0 rounded-full border border-dashed flex items-center justify-center" style="border-color: #263547; background-color: #101824; color: #475569;">
          <i class="ph ph-user text-xl"></i>
        </div>
        <div class="min-w-0">
          <span class="text-sm font-semibold" style="color: #64748b;">Slot Kosong</span>
        </div>
      </div>
      <span class="lobby-slot-avail-badge">Tersedia</span>
    `;
    return el;
  }

  function syncEmptySlots(maxSlot) {
    const emptyContainer = document.getElementById('empty-slots-container');
    if (!emptyContainer) return;
    const filled = document.querySelectorAll('[data-player-row="1"]').length;
    const needed = Math.max(0, maxSlot - filled);
    emptyContainer.innerHTML = '';
    for (let i = 0; i < needed; i++) {
      emptyContainer.appendChild(createEmptySlotEl());
    }
  }

  // 1. Pergantian Tab (Lobby <-> Pengaturan Sesi)
  function switchTab(tab) {
    if (!tabContentLobby || !tabContentDetail) return;

    if (tab === 'lobby') {
      tabContentLobby.style.display = 'flex';
      tabContentDetail.style.display = 'none';
      if (tabBtnLobby) {
        tabBtnLobby.style.borderBottomColor = themeColor;
        tabBtnLobby.style.color = '#ffffff';
      }
      if (tabBtnDetail) {
        tabBtnDetail.style.borderBottomColor = 'transparent';
        tabBtnDetail.style.color = '#9ba5b4';
      }
    } else {
      tabContentDetail.style.display = 'flex';
      tabContentLobby.style.display = 'none';
      if (tabBtnDetail) {
        tabBtnDetail.style.borderBottomColor = themeColor;
        tabBtnDetail.style.color = '#ffffff';
      }
      if (tabBtnLobby) {
        tabBtnLobby.style.borderBottomColor = 'transparent';
        tabBtnLobby.style.color = '#9ba5b4';
      }
    }
  }

  window.switchTab = switchTab;
  tabBtnLobby?.addEventListener('click', () => switchTab('lobby'));
  tabBtnDetail?.addEventListener('click', () => switchTab('detail'));

  function updateReadyCounter() {
    const allRows = document.querySelectorAll('[data-player-row="1"]');
    const totalPlayers = allRows.length;
    const slotMaxEl = document.getElementById('player-slot-max') || document.getElementById('header-slot-total');
    const totalSlots = Math.max(totalPlayers, parseInt(slotMaxEl?.textContent || String(totalPlayers), 10) || totalPlayers);
    let readyCount = 0;

    allRows.forEach(row => {
      if (row.dataset.playerReady === '1') readyCount++;
    });

    const readySummary = document.getElementById('lobby-ready-summary');
    if (!readySummary) return;

    if (totalSlots > 0 && readyCount >= totalSlots) {
      readySummary.innerHTML = `
        <span class="lobby-ready-pill is-all-ready">
          <span class="lobby-ready-dot" style="background-color: #19d66b;"></span>
          Semua Siap • ${readyCount}/${totalSlots}
        </span>
      `;
    } else if (readyCount === 0) {
      readySummary.innerHTML = `
        <span class="lobby-ready-pill is-none">
          <span class="lobby-ready-dot" style="background-color: #ef233c;"></span>
          0/${totalSlots} Siap
        </span>
      `;
    } else {
      readySummary.innerHTML = `
        <span class="lobby-ready-pill is-waiting">
          <span class="lobby-ready-dot" style="background-color: #f9a826;"></span>
          ${readyCount}/${totalSlots} Siap
        </span>
      `;
    }
  }

  const userReadyBtn = document.querySelector('.btn-toggle-my-ready');
  if (userReadyBtn) {
    userReadyBtn.addEventListener('click', async () => {
      const nextReady = userReadyBtn.dataset.ready !== '1';
      const playerRow = userReadyBtn.closest('[data-player-row="1"]');
      const dot = userReadyBtn.querySelector('.ready-dot');
      const label = userReadyBtn.querySelector('.ready-label');

      userReadyBtn.disabled = true;
      try {
        await postSessionApi('ready.php', {
          session_id: sessionSlug,
          is_ready: nextReady
        });

        userReadyBtn.dataset.ready = nextReady ? '1' : '0';
        if (playerRow) playerRow.dataset.playerReady = nextReady ? '1' : '0';

        if (nextReady) {
          userReadyBtn.style.backgroundColor = 'rgba(25, 214, 107, 0.1)';
          userReadyBtn.style.color = '#19d66b';
          userReadyBtn.style.borderColor = 'rgba(25, 214, 107, 0.35)';
          if (dot) dot.style.backgroundColor = '#19d66b';
          if (label) label.textContent = 'Saya Siap';
          kirimNotif('Status: Siap Bertanding!', 'success');
        } else {
          userReadyBtn.style.backgroundColor = 'rgba(35, 45, 57, 0.4)';
          userReadyBtn.style.color = '#9ba5b4';
          userReadyBtn.style.borderColor = '#253142';
          if (dot) dot.style.backgroundColor = '#526071';
          if (label) label.textContent = 'Belum Siap';
          kirimNotif('Status: Belum Siap', 'error');
        }

        updateReadyCounter();
      } catch (err) {
        kirimNotif(err.message || 'Gagal memperbarui status kesiapan.', 'error');
      } finally {
        userReadyBtn.disabled = false;
      }
    });
  }

  const btnGabungSesi = document.getElementById('btn-gabung-sesi');
  btnGabungSesi?.addEventListener('click', async () => {
    const auth = window.MABAR_AUTH || {};
    if (!auth.authenticated) {
      kirimNotif('Silakan masuk akun terlebih dahulu untuk bergabung ke sesi mabar!', 'error');
      setTimeout(() => {
        window.location.href = `${basePath}pages/auth.php`;
      }, 750);
      return;
    }

    const prevText = btnGabungSesi.innerHTML;
    btnGabungSesi.disabled = true;
    btnGabungSesi.innerHTML = `<i class="ph ph-circle-notch animate-spin mr-1.5"></i> Bergabung...`;

    try {
      const data = await postSessionApi('join.php', { session_id: sessionSlug });
      kirimNotif(data.message || 'Berhasil bergabung ke lobby sesi!', 'success');
      setTimeout(() => window.location.reload(), 450);
    } catch (err) {
      btnGabungSesi.disabled = false;
      btnGabungSesi.innerHTML = prevText;
      kirimNotif(err.message || 'Gagal bergabung ke sesi.', 'error');
    }
  });

  const btnSimpan = document.getElementById('btn-simpan-detail');
  btnSimpan?.addEventListener('click', async () => {
    const namaVal       = document.getElementById('detail-nama-input')?.value.trim() || '';
    const descVal       = document.getElementById('detail-desc-input')?.value.trim() || '';
    const jamMulaiVal   = document.getElementById('detail-jam-mulai-input')?.value.trim() || '';
    const jamSelesaiVal = document.getElementById('detail-jam-selesai-input')?.value.trim() || '';
    const joinVal       = document.getElementById('detail-join-input')?.value || 'Ask For Join';
    const aturanInput   = document.getElementById('detail-aturan-input');

    if (namaVal.length < 3) {
      kirimNotif('Nama sesi minimal 3 karakter!', 'error');
      document.getElementById('detail-nama-input')?.focus();
      return;
    }

    const rules = aturanInput
      ? aturanInput.value.split('\n').map(l => l.trim()).filter(l => l.length > 0)
      : [];

    const specs = {};
    document.querySelectorAll('[id^="detail-spec-"]').forEach(input => {
      const fieldName = input.id.replace('detail-spec-', '');
      specs[fieldName] = input.value ? input.value.trim() : '';
    });

    const slotInput = document.getElementById('detail-spec-kebutuhan_slot') || document.getElementById('detail-spec-jumlah_pemain');
    let maxSlot = parseInt(document.getElementById('header-slot-total')?.textContent || '5', 10);
    if (slotInput && slotInput.value !== '') {
      const parsed = parseInt(slotInput.value, 10);
      if (!isNaN(parsed)) maxSlot = parsed;
    }

    const prevText = btnSimpan.innerHTML;
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = `<i class="ph ph-circle-notch animate-spin mr-1.5"></i> Menyimpan...`;

    try {
      const data = await postSessionApi('update.php', {
        session_id: sessionSlug,
        title: namaVal,
        description: descVal,
        join_rule: joinVal,
        start_time: jamMulaiVal,
        end_time: jamSelesaiVal,
        max_slot: maxSlot,
        rules,
        specs
      });

      const updated = data.session || {};
      const finalMaxSlot = Number(updated.maxSlot) || maxSlot;

      const headerNama = document.getElementById('header-nama-display');
      if (headerNama) headerNama.textContent = namaVal;

      const descEl = document.getElementById('header-desc-display');
      if (descEl) {
        if (descVal) {
          descEl.textContent = descVal;
          descEl.style.fontStyle = 'normal';
          descEl.style.color = '#9ba5b4';
        } else {
          descEl.textContent = 'Tidak ada deskripsi.';
          descEl.style.fontStyle = 'italic';
          descEl.style.color = '#64748b';
        }
      }

      const joinTarget = document.getElementById('lobby-join-rule');
      if (joinTarget) joinTarget.textContent = joinVal;

      const jamMulaiTarget = document.getElementById('lobby-jam-mulai');
      if (jamMulaiTarget) {
        jamMulaiTarget.textContent = jamMulaiVal || 'Belum diatur';
        jamMulaiTarget.className = jamMulaiVal ? 'text-white font-semibold' : 'text-muted italic';
      }

      const jamSelesaiTarget = document.getElementById('lobby-jam-selesai');
      if (jamSelesaiTarget) {
        jamSelesaiTarget.textContent = jamSelesaiVal || 'Belum diatur';
        jamSelesaiTarget.className = jamSelesaiVal ? 'text-white font-semibold' : 'text-muted italic';
      }

      const aturanList = document.getElementById('lobby-aturan-list');
      if (aturanList) {
        aturanList.innerHTML = '';
        if (rules.length === 0) {
          const emptyLi = document.createElement('li');
          emptyLi.className = 'flex items-center gap-2 italic text-[12px]';
          emptyLi.style.color = '#64748b';
          emptyLi.innerHTML = '<span class="h-1.5 w-1.5 rounded-full shrink-0" style="background-color: #475569;"></span><span>Tidak ada aturan khusus</span>';
          aturanList.appendChild(emptyLi);
        } else {
          rules.forEach(rule => {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2';
            li.innerHTML = '<span class="h-1.5 w-1.5 rounded-full shrink-0" style="background-color: ' + themeColor + ';"></span><span class="text-white/90">' + escapeHtml(rule) + '</span>';
            aturanList.appendChild(li);
          });
        }
      }

      Object.entries(specs).forEach(([fieldName, val]) => {
        const lobbyTarget = document.getElementById('lobby-spec-' + fieldName);
        if (lobbyTarget) lobbyTarget.textContent = val || 'Bebas';
      });

      const headerSlotTotal = document.getElementById('header-slot-total');
      const playerSlotMax = document.getElementById('player-slot-max');
      if (headerSlotTotal) headerSlotTotal.textContent = String(finalMaxSlot);
      if (playerSlotMax) playerSlotMax.textContent = String(finalMaxSlot);
      if (slotInput) slotInput.value = String(finalMaxSlot);
      syncEmptySlots(finalMaxSlot);

      kirimNotif(data.message || 'Perubahan sesi berhasil disimpan!', 'success');
    } catch (err) {
      kirimNotif(err.message || 'Gagal menyimpan perubahan sesi.', 'error');
    } finally {
      btnSimpan.disabled = false;
      btnSimpan.innerHTML = prevText;
    }
  });

  // 6. Tombol Batal kembali ke Lobby
  document.getElementById('btn-batal-detail')?.addEventListener('click', () => {
    switchTab('lobby');
  });

  // 7. Tombol Hapus Sesi (Host)
  document.getElementById('btn-hapus-sesi')?.addEventListener('click', () => {
    window.konfirmasiModal({
      judul: 'Hapus Sesi?',
      pesan: 'Sesi yang dihapus tidak dapat dipulihkan. Apakah Anda yakin ingin menghapus sesi ini?',
      teksYa: 'Ya, Hapus'
    }).then(async (ya) => {
      if (!ya) return;
      try {
        await postSessionApi('delete.php', { session_id: sessionSlug });
        kirimNotif('Sesi mabar berhasil dihapus.', 'success');
        setTimeout(() => {
          window.location.href = `${basePath}index.php`;
        }, 400);
      } catch (err) {
        kirimNotif(err.message || 'Gagal menghapus sesi.', 'error');
      }
    });
  });

  // 8. Tombol Batalkan Sesi (Host) / Keluar Sesi (Member)
  document.getElementById('btn-keluar-sesi')?.addEventListener('click', (e) => {
    e.preventDefault();
    const action = e.currentTarget.dataset.action || 'leave';
    const isDelete = action === 'delete';

    window.konfirmasiModal({
      judul: isDelete ? 'Batalkan & Hapus Sesi?' : 'Keluar dari Sesi?',
      pesan: isDelete
        ? 'Membatalkan sesi akan menutup lobby untuk seluruh pemain. Lanjutkan?'
        : 'Apakah Anda yakin ingin keluar dari sesi ini?',
      teksYa: isDelete ? 'Ya, Batalkan Sesi' : 'Ya, Keluar'
    }).then(async (ya) => {
      if (!ya) return;
      try {
        if (isDelete) {
          await postSessionApi('delete.php', { session_id: sessionSlug });
          kirimNotif('Sesi telah dibatalkan.', 'success');
          setTimeout(() => { window.location.href = `${basePath}index.php`; }, 400);
        } else {
          await postSessionApi('leave.php', { session_id: sessionSlug });
          kirimNotif('Anda telah keluar dari sesi.', 'success');
          setTimeout(() => { window.location.reload(); }, 400);
        }
      } catch (err) {
        kirimNotif(err.message || 'Gagal memproses permintaan.', 'error');
      }
    });
  });

  // 9. Dropdown Menu Titik 3 Pemain
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-player-menu');
    const allMenus = document.querySelectorAll('.player-dropdown-menu');

    if (trigger) {
      e.stopPropagation();
      const currentMenu = trigger.closest('.player-dropdown-container')?.querySelector('.player-dropdown-menu');
      allMenus.forEach(m => {
        if (m !== currentMenu) m.classList.add('hidden');
      });
      if (currentMenu) currentMenu.classList.toggle('hidden');
    } else {
      allMenus.forEach(m => m.classList.add('hidden'));
    }
  });

  const modalPlayerProfile    = document.getElementById('modal-player-profile');
  const modalPlayerName       = document.getElementById('modal-player-name');
  const modalPlayerRank       = document.getElementById('modal-player-rank');
  const modalPlayerHostBadge  = document.getElementById('modal-player-host-badge');
  const modalPlayerAvatarImg  = document.getElementById('modal-player-avatar-img');
  const modalPlayerAvatarIcon = document.getElementById('modal-player-avatar-icon');
  const modalPlayerBio        = document.getElementById('modal-player-bio');
  const modalPlayerCity       = document.getElementById('modal-player-city');
  let activeModalPlayerName   = '';

  function bukaModalProfil(row) {
    if (!modalPlayerProfile || !row) return;

    const nama     = row.dataset.playerName || 'Pemain';
    const rank     = row.dataset.playerRank || '-';
    const city     = row.dataset.playerCity || 'Malang';
    const isHost   = row.dataset.playerIsHost === '1';
    const avatar   = row.dataset.playerAvatar || '';
    const bio      = row.dataset.playerBio || '';
    const isSelf   = row.dataset.playerIsSelf === '1';
    let userGames  = [];
    try {
      const parsed = JSON.parse(row.dataset.playerGames || '[]');
      if (Array.isArray(parsed)) userGames = parsed;
    } catch (e) {}

    activeModalPlayerName = nama;

    if (modalPlayerName) modalPlayerName.textContent = nama;
    if (modalPlayerRank) modalPlayerRank.textContent = rank;
    if (modalPlayerCity) modalPlayerCity.textContent = city;

    if (modalPlayerHostBadge) {
      modalPlayerHostBadge.classList.toggle('hidden', !isHost);
    }

    const resolveAsset = typeof window.mabarResolveAsset === 'function'
      ? window.mabarResolveAsset
      : (src) => (src && !/^(?:data:|blob:|https?:\/\/|\/)/i.test(src) ? basePath + src : src);

    const selfAvatar = isSelf ? (localStorage.getItem('mabar_profile_avatar') || avatar) : avatar;
    const finalAvatar = selfAvatar ? resolveAsset(selfAvatar) : '';

    if (finalAvatar && modalPlayerAvatarImg) {
      modalPlayerAvatarImg.src = finalAvatar;
      modalPlayerAvatarImg.classList.remove('hidden');
      modalPlayerAvatarIcon?.classList.add('hidden');
    } else {
      if (modalPlayerAvatarImg) modalPlayerAvatarImg.src = '';
      modalPlayerAvatarImg?.classList.add('hidden');
      modalPlayerAvatarIcon?.classList.remove('hidden');
    }

    if (modalPlayerBio) {
      modalPlayerBio.textContent = bio
        ? `"${bio}"`
        : '"Belum menambahkan bio profil."';
    }

    const modalPlayerInterests = document.getElementById('modal-player-interests');
    if (modalPlayerInterests) {
      const GAME_ICONS = {
        "Valorant": "asset/icon/Valoran1.svg",
        "Mobile Legends": "asset/icon/ml-logo.jpeg",
        "CS2": "asset/icon/cs2-logo.jpeg",
        "PUBG Mobile": "asset/icon/pubgm-logo.jpeg",
        "Dota 2": "asset/icon/dota2-logo.jpeg",
        "Honor of Kings": "asset/icon/hok-logo.png",
        "League of Legends": "asset/icon/lol-logo.jpeg",
        "PUBG PC": "asset/icon/pubg-pc-logo.jpeg",
        "Futsal": "asset/icon/Futsal.png",
        "Bulu Tangkis": "asset/icon/Bulu Tangkis.png",
        "Basket": "asset/icon/Basket.png",
        "Billiard": "asset/icon/Billiard.png",
        "Padel": "asset/icon/padel.png",
        "Lari": "asset/icon/lARI.png",
        "Sepak Bola": "asset/icon/sepak-bola.svg",
        "Tenis": "asset/icon/tenis.svg",
        "Voli": "asset/icon/voli.svg"
      };

      if (userGames.length === 0) {
        modalPlayerInterests.innerHTML = `<span class="text-[11px] text-muted italic">Belum mengatur minat game.</span>`;
      } else {
        modalPlayerInterests.innerHTML = userGames.map(g => {
          const iconFile = GAME_ICONS[g] || "asset/icon/Logo1.svg";
          return `
            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; background-color: #09101a; border: 1px solid #1e293b; font-size: 11px; font-weight: 600; color: #e2e8f0;">
              <img src="${basePath}${iconFile}" alt="${escapeHtml(g)}" style="width: 16px; height: 16px; border-radius: 3px; object-fit: cover;" onerror="this.onerror=null; this.src='${basePath}asset/icon/Logo1.svg';" />
              <span>${escapeHtml(g)}</span>
            </div>
          `;
        }).join('');
      }
    }

    modalPlayerProfile.classList.remove('hidden');
  }

  function tutupModalProfil() {
    modalPlayerProfile?.classList.add('hidden');
  }

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-open-player-card, .btn-view-profile-menu');
    if (trigger) {
      const row = trigger.closest('[data-player-row="1"]');
      if (row) bukaModalProfil(row);
    }
  });

  document.getElementById('btn-close-player-modal')?.addEventListener('click', tutupModalProfil);
  document.getElementById('btn-modal-close-action')?.addEventListener('click', tutupModalProfil);
  document.querySelector('.modal-profile-backdrop')?.addEventListener('click', tutupModalProfil);

  function handleKirimPesan(namaTarget) {
    tutupModalProfil();
    if (window.MabarChat && typeof window.MabarChat.openWith === 'function') {
      window.MabarChat.openWith(namaTarget, 'Pemain Sesi Mabar');
    } else if (typeof window.openChat === 'function') {
      window.openChat();
    }
    kirimNotif(`Membuka obrolan pribadi dengan ${namaTarget}...`, 'success');
  }

  document.getElementById('btn-modal-send-dm')?.addEventListener('click', () => {
    handleKirimPesan(activeModalPlayerName);
  });

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-send-dm-menu');
    if (trigger) {
      handleKirimPesan(trigger.dataset.name || 'Pemain');
    }
  });

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-kick-player-menu');
    if (!trigger) return;

    const targetName   = trigger.dataset.name || 'Pemain';
    const targetUserId = parseInt(trigger.dataset.userId || '0', 10);
    const row          = trigger.closest('[data-player-row="1"]');

    window.konfirmasiModal({
      judul: 'Keluarkan Pemain?',
      pesan: `Apakah Anda yakin ingin mengeluarkan ${targetName} dari tim sesi ini?`,
      teksYa: 'Ya, Keluarkan'
    }).then(async (ya) => {
      if (!ya || !targetUserId) return;
      try {
        await postSessionApi('kick.php', {
          session_id: sessionSlug,
          target_user_id: targetUserId
        });

        row?.remove();
        const remainingRows = document.querySelectorAll('[data-player-row="1"]');
        const countDisplay = document.getElementById('player-count');
        const headerSlotTerisi = document.getElementById('header-slot-terisi');
        const maxSlot = parseInt(document.getElementById('header-slot-total')?.textContent || '5', 10);

        if (countDisplay) countDisplay.textContent = String(remainingRows.length);
        if (headerSlotTerisi) headerSlotTerisi.textContent = String(remainingRows.length);
        syncEmptySlots(maxSlot);
        updateReadyCounter();

        kirimNotif(`${targetName} telah dikeluarkan dari sesi mabar.`, 'error');
      } catch (err) {
        kirimNotif(err.message || 'Gagal mengeluarkan pemain.', 'error');
      }
    });
  });

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-transfer-host-menu');
    if (!trigger) return;

    const targetName   = trigger.dataset.name || 'Pemain';
    const targetUserId = parseInt(trigger.dataset.userId || '0', 10);

    window.konfirmasiModal({
      judul: 'Alihkan Host?',
      pesan: `Apakah Anda yakin ingin mengalihkan kepemimpinan Host ke ${targetName}?`,
      teksYa: 'Ya, Alihkan'
    }).then(async (ya) => {
      if (!ya || !targetUserId) return;
      try {
        const data = await postSessionApi('transfer_host.php', {
          session_id: sessionSlug,
          target_user_id: targetUserId
        });
        kirimNotif(data.message || `Kepemimpinan Host dialihkan ke ${targetName}!`, 'success');
        setTimeout(() => window.location.reload(), 450);
      } catch (err) {
        kirimNotif(err.message || 'Gagal mengalihkan Host.', 'error');
      }
    });
  });
});
