/**
 * asset/js/halaman_sesi.js — Logika Interaktif Halaman Lobby & Detail Sesi
 * Mengatur pergantian tab, kesiapan tim (ready check), dan sinkronisasi form ke lobby.
 */

// Validasi input angka real-time agar tidak minus dan tidak melebihi batas slot
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
  const themeColor = container?.dataset?.themeColor || '#ef233c';

  const tabBtnLobby      = document.getElementById('tab-btn-lobby');
  const tabBtnDetail     = document.getElementById('tab-btn-detail');
  const tabContentLobby  = document.getElementById('tab-content-lobby');
  const tabContentDetail = document.getElementById('tab-content-detail');

  // 1. Logika Pergantian Tab (Lobby <-> Detail Sesi)
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

  // 2. Update Counter Kesiapan Tim di Header Bar
  function updateReadyCounter() {
    const allRows = document.querySelectorAll('[data-player-row="1"]');
    const totalPlayers = allRows.length;
    let readyCount = 0;

    allRows.forEach(row => {
      if (row.dataset.playerReady === '1') readyCount++;
    });

    const readySummary = document.getElementById('lobby-ready-summary');
    if (!readySummary) return;

    if (totalPlayers > 0 && readyCount === totalPlayers) {
      readySummary.innerHTML = `
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[11px] font-bold text-[#19d66b] bg-[#19d66b]/10 border border-[#19d66b]/30">
          <span class="w-1.5 h-1.5 rounded-full bg-[#19d66b]"></span>
          Semua Siap
        </span>
      `;
    } else if (readyCount === 0) {
      readySummary.innerHTML = `
        <span class="text-xs text-muted font-medium">
          <span class="text-red font-semibold">0</span> dari ${totalPlayers} pemain siap <span class="text-[11px] text-muted italic">(Belum Siap)</span>
        </span>
      `;
    } else {
      readySummary.innerHTML = `
        <span class="text-xs text-muted font-medium">
          <span id="ready-count-text" class="text-white font-bold">${readyCount}</span> dari ${totalPlayers} pemain siap
        </span>
      `;
    }
  }

  // Helper untuk menampilkan notifikasi toast
  function kirimNotif(pesan, tipe = 'success') {
    if (typeof window.showGlobalToast === 'function') {
      window.showGlobalToast(pesan, tipe === 'unready' || tipe === 'error' ? 'error' : 'success');
    }
  }

  // 3. Toggle Status Ready Pengguna Sendiri di Lobby
  const userReadyBtn = document.querySelector('.btn-toggle-my-ready');
  if (userReadyBtn) {
    userReadyBtn.addEventListener('click', () => {
      const isCurrentlyReady = userReadyBtn.dataset.ready === '1';
      const playerRow = userReadyBtn.closest('[data-player-row="1"]');
      const dot = userReadyBtn.querySelector('.ready-dot');
      const label = userReadyBtn.querySelector('.ready-label');

      if (isCurrentlyReady) {
        // Toggle ke Unready (Belum Siap)
        userReadyBtn.dataset.ready = '0';
        if (playerRow) playerRow.dataset.playerReady = '0';
        userReadyBtn.style.backgroundColor = 'rgba(35, 45, 57, 0.4)';
        userReadyBtn.style.color = '#9ba5b4';
        userReadyBtn.style.borderColor = '#253142';
        if (dot) dot.style.backgroundColor = '#526071';
        if (label) label.textContent = 'Belum Siap';
        kirimNotif('Status: Belum Siap', 'error');
      } else {
        // Toggle ke Ready (Saya Siap)
        userReadyBtn.dataset.ready = '1';
        if (playerRow) playerRow.dataset.playerReady = '1';
        userReadyBtn.style.backgroundColor = 'rgba(25, 214, 107, 0.1)';
        userReadyBtn.style.color = '#19d66b';
        userReadyBtn.style.borderColor = 'rgba(25, 214, 107, 0.35)';
        if (dot) dot.style.backgroundColor = '#19d66b';
        if (label) label.textContent = 'Saya Siap';
        kirimNotif('Status: Siap Bertanding!', 'success');
      }

      updateReadyCounter();
    });
  }

  // Helper escape HTML agar aman saat render dynamic aturan sesi
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // 4. Logika Simpan Perubahan Detail Sesi
  const btnSimpan = document.getElementById('btn-simpan-detail');
  btnSimpan?.addEventListener('click', () => {
    const namaVal = document.getElementById('detail-nama-input')?.value.trim();
    const descVal = document.getElementById('detail-desc-input')?.value.trim();
    const jamMulaiVal = document.getElementById('detail-jam-mulai-input')?.value.trim();
    const jamSelesaiVal = document.getElementById('detail-jam-selesai-input')?.value.trim();
    const leaderVal = document.getElementById('detail-leader-input')?.value.trim();
    const joinVal = document.getElementById('detail-join-input')?.value;

    // 4.1 Update Header Judul & Deskripsi
    if (namaVal) {
      const headerNama = document.getElementById('header-nama-display');
      if (headerNama) headerNama.textContent = namaVal;
    }
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

    // 4.2 Update Card 1: Pengaturan Sesi
    const joinTarget = document.getElementById('lobby-join-rule');
    if (joinTarget) joinTarget.textContent = joinVal || '-';

    const jamMulaiTarget = document.getElementById('lobby-jam-mulai');
    if (jamMulaiTarget) jamMulaiTarget.textContent = jamMulaiVal || '-';

    const jamSelesaiTarget = document.getElementById('lobby-jam-selesai');
    if (jamSelesaiTarget) jamSelesaiTarget.textContent = jamSelesaiVal || '-';

    const leaderTarget = document.getElementById('lobby-leader');
    if (leaderTarget) leaderTarget.textContent = leaderVal || '-';

    // 4.3 Update Card 2: Aturan Sesi (Dinamis dari input user, boleh kosong)
    const aturanInput = document.getElementById('detail-aturan-input');
    const aturanList = document.getElementById('lobby-aturan-list');
    if (aturanInput && aturanList) {
      const rawLines = aturanInput.value.split('\n');
      const rules = rawLines.map(l => l.trim()).filter(l => l.length > 0);

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

    // 4.4 Update Card 3: Aturan Game / Olahraga (Dinamis dari config)
    document.querySelectorAll('[id^="detail-spec-"]').forEach(input => {
      const fieldName = input.id.replace('detail-spec-', '');
      const lobbyTarget = document.getElementById('lobby-spec-' + fieldName);
      if (lobbyTarget) {
        const val = input.value ? input.value.trim() : '';
        lobbyTarget.textContent = val || 'Bebas';
      }
    });

    // 4.5 Sinkronisasi Slot Dinamis ke Header Counter (misal: "3/4")
    const slotInput = document.getElementById('detail-spec-kebutuhan_slot') || document.getElementById('detail-spec-jumlah_pemain');
    if (slotInput) {
      let newSlot = parseInt(slotInput.value, 10);
      const min = parseInt(slotInput.min, 10) || 2;
      const max = parseInt(slotInput.max, 10) || 20;
      if (isNaN(newSlot) || newSlot < min) newSlot = min;
      if (newSlot > max) newSlot = max;
      slotInput.value = newSlot;

      // Update Slot Counter Total di Kanan Header (misal: "3/4")
      const headerSlotTotal = document.getElementById('header-slot-total');
      if (headerSlotTotal) headerSlotTotal.textContent = newSlot;
    }

    kirimNotif('Perubahan sesi berhasil disimpan!', 'success');
  });

  // 5. Tombol Batal berpindah kembali ke Lobby
  document.getElementById('btn-batal-detail')?.addEventListener('click', () => {
    switchTab('lobby');
  });

  // 6. Tombol Hapus Sesi
  document.getElementById('btn-hapus-sesi')?.addEventListener('click', () => {
    if (confirm('Apakah Anda yakin ingin menghapus sesi ini? Sesi yang dihapus tidak dapat dipulihkan.')) {
      window.location.href = '../../index.php';
    }
  });

  // 7. Tombol Keluar Sesi
  document.getElementById('btn-keluar-sesi')?.addEventListener('click', () => {
    if (confirm('Apakah Anda yakin ingin keluar dari sesi ini?')) {
      window.location.href = '../../index.php';
    }
  });

  // ════════════════════════════════════════════════════════════
  // 8. LOGIKA DROPDOWN MENU TITIK 3 PEMAIN
  // ════════════════════════════════════════════════════════════
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-player-menu');
    const allMenus = document.querySelectorAll('.player-dropdown-menu');

    if (trigger) {
      e.stopPropagation();
      const currentMenu = trigger.closest('.player-dropdown-container')?.querySelector('.player-dropdown-menu');
      allMenus.forEach(m => {
        if (m !== currentMenu) m.classList.add('hidden');
      });
      if (currentMenu) {
        currentMenu.classList.toggle('hidden');
      }
    } else {
      // Klik di luar dropdown -> tutup semua dropdown
      allMenus.forEach(m => m.classList.add('hidden'));
    }
  });

  // ════════════════════════════════════════════════════════════
  // 9. LOGIKA MODAL PROFIL RINGKAS PEMAIN (PLAYER CARD)
  // ════════════════════════════════════════════════════════════
  const modalPlayerProfile = document.getElementById('modal-player-profile');
  const modalPlayerName = document.getElementById('modal-player-name');
  const modalPlayerRank = document.getElementById('modal-player-rank');
  const modalPlayerHostBadge = document.getElementById('modal-player-host-badge');
  const modalPlayerAvatarImg = document.getElementById('modal-player-avatar-img');
  const modalPlayerAvatarIcon = document.getElementById('modal-player-avatar-icon');
  let activeModalPlayerName = '';

  function bukaModalProfil(nama, rank, isHost, avatar) {
    if (!modalPlayerProfile) return;
    activeModalPlayerName = nama || 'Pemain';

    if (modalPlayerName) modalPlayerName.textContent = activeModalPlayerName;
    if (modalPlayerRank) modalPlayerRank.textContent = rank || '-';

    if (modalPlayerHostBadge) {
      if (isHost === '1' || isHost === true) {
        modalPlayerHostBadge.classList.remove('hidden');
      } else {
        modalPlayerHostBadge.classList.add('hidden');
      }
    }

    if (avatar && modalPlayerAvatarImg) {
      modalPlayerAvatarImg.src = avatar;
      modalPlayerAvatarImg.classList.remove('hidden');
      modalPlayerAvatarIcon?.classList.add('hidden');
    } else {
      modalPlayerAvatarImg?.classList.add('hidden');
      modalPlayerAvatarIcon?.classList.remove('hidden');
    }

    modalPlayerProfile.classList.remove('hidden');
  }

  function tutupModalProfil() {
    if (modalPlayerProfile) {
      modalPlayerProfile.classList.add('hidden');
    }
  }

  // Buka modal profil saat klik avatar atau nama pemain di baris lobby
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-open-player-card');
    if (trigger) {
      const row = trigger.closest('[data-player-row="1"]');
      if (row) {
        bukaModalProfil(
          row.dataset.playerName,
          row.dataset.playerRank,
          row.dataset.playerIsHost,
          row.dataset.playerAvatar
        );
      }
    }
  });

  // Buka modal profil dari menu titik 3 ("Lihat Profil")
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-view-profile-menu');
    if (trigger) {
      const row = trigger.closest('[data-player-row="1"]');
      if (row) {
        bukaModalProfil(
          row.dataset.playerName,
          row.dataset.playerRank,
          row.dataset.playerIsHost,
          row.dataset.playerAvatar
        );
      }
    }
  });

  // Tutup modal profil (Tombol X, Tombol Tutup, atau klik Backdrop)
  document.getElementById('btn-close-player-modal')?.addEventListener('click', tutupModalProfil);
  document.getElementById('btn-modal-close-action')?.addEventListener('click', tutupModalProfil);
  document.querySelector('.modal-profile-backdrop')?.addEventListener('click', tutupModalProfil);

  // Aksi Kirim Pesan (DM) dari Modal & Menu Titik 3
  function handleKirimPesan(namaTarget) {
    const chatSidebar = document.getElementById('chat-sidebar');
    if (chatSidebar) {
      chatSidebar.classList.remove('chat-hidden');
    }
    tutupModalProfil();
    kirimNotif(`Membuka obrolan pribadi dengan ${namaTarget}...`, 'success');
  }

  document.getElementById('btn-modal-send-dm')?.addEventListener('click', () => {
    handleKirimPesan(activeModalPlayerName);
  });

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-send-dm-menu');
    if (trigger) {
      const targetName = trigger.dataset.name || 'Pemain';
      handleKirimPesan(targetName);
    }
  });

  // ════════════════════════════════════════════════════════════
  // 10. AKSI MODERASI HOST: KELUARKAN PEMAIN (KICK) & ALIKAN HOST
  // ════════════════════════════════════════════════════════════

  // Keluarkan Pemain (Kick)
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-kick-player-menu');
    if (trigger) {
      const targetName = trigger.dataset.name || 'Pemain';
      const row = trigger.closest('[data-player-row="1"]');

      if (confirm(`Apakah Anda yakin ingin mengeluarkan ${targetName} dari tim sesi ini?`)) {
        if (row) {
          row.remove();

          // Tambahkan slot kosong baru agar kapasitas lobby tetap utuh
          const emptyContainer = document.getElementById('empty-slots-container');
          if (emptyContainer) {
            const newEmptySlot = document.createElement('div');
            newEmptySlot.className = 'empty-slot-row flex items-center justify-between p-4 sm:p-4.5 rounded-xl border border-dashed transition-all animate-in fade-in duration-200';
            newEmptySlot.style.borderColor = '#1e2a38';
            newEmptySlot.style.backgroundColor = 'rgba(13, 22, 34, 0.3)';
            newEmptySlot.innerHTML = `
              <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="h-12 w-12 shrink-0 rounded-full border border-dashed flex items-center justify-center text-muted/40" style="border-color: #263547; background-color: #101824;">
                  <i class="ph ph-user text-xl"></i>
                </div>
                <div class="min-w-0">
                  <span class="text-sm font-semibold text-muted/80">Slot Kosong</span>
                </div>
              </div>
              <span class="text-xs text-muted/50 font-medium px-2 py-0.5">Tersedia</span>
            `;
            emptyContainer.appendChild(newEmptySlot);
          }

          // Update Counter Pemain di Header Bar & Header Card
          const remainingRows = document.querySelectorAll('[data-player-row="1"]');
          const countDisplay = document.getElementById('player-count');
          const headerSlotTerisi = document.getElementById('header-slot-terisi');

          if (countDisplay) countDisplay.textContent = remainingRows.length;
          if (headerSlotTerisi) headerSlotTerisi.textContent = remainingRows.length;

          updateReadyCounter();
          kirimNotif(`${targetName} telah dikeluarkan dari sesi mabar.`, 'error');
        }
      }
    }
  });

  // Alihkan Host (Jadikan Host Baru)
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.btn-transfer-host-menu');
    if (trigger) {
      const targetName = trigger.dataset.name || 'Pemain';
      const row = trigger.closest('[data-player-row="1"]');

      if (confirm(`Apakah Anda yakin ingin mengalihkan kepemimpinan Host ke ${targetName}?`)) {
        // Hapus badge host lama
        document.querySelectorAll('.player-host-tag').forEach(tag => tag.remove());
        document.querySelectorAll('[data-player-is-host]').forEach(r => r.dataset.playerIsHost = '0');

        // Tambah badge host baru ke pemain ini
        if (row) {
          row.dataset.playerIsHost = '1';
          const nameContainer = row.querySelector('h4');
          if (nameContainer) {
            const newTag = document.createElement('span');
            newTag.className = 'player-host-tag rounded px-2.5 py-0.5 text-[11px] font-semibold text-white/90 bg-[#1c293a] border border-[#2d3e54] shadow-sm';
            newTag.textContent = 'Host';
            nameContainer.appendChild(newTag);
          }
        }

        // Update teks Host di kartu pengaturan sesi di bawah
        const lobbyLeader = document.getElementById('lobby-leader');
        if (lobbyLeader) lobbyLeader.textContent = targetName;

        kirimNotif(`Kepemimpinan Host berhasil dialihkan ke ${targetName}!`, 'success');
      }
    }
  });
});
