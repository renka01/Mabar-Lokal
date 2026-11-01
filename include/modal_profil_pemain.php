<?php
/**
 * include/modal_profil_pemain.php — Komponen Global Modal Kartu Profil Pemain (Player Card)
 * Dapat dipanggil dari halaman mana saja (Sesi Mabar, Turnamen, Scrim, Chat Sidebar).
 */

$modalGame       = $sesi['game'] ?? 'Valorant';
$modalCity       = $sesi['kota'] ?? 'Malang';
$modalThemeColor = $themeColor ?? '#ef233c';
$modalThemeText  = $themeTextOnBg ?? '#ffffff';
?>
<!-- MODAL OVERLAY: PLAYER PROFILE CARD (GLOBAL COMPONENT) -->
<div id="modal-player-profile" class="player-modal-overlay hidden">
  
  <!-- Backdrop Click Listener Area -->
  <div class="player-modal-backdrop modal-profile-backdrop"></div>

  <!-- Modal Card Content -->
  <div class="player-modal-card">
    
    <!-- Mini Banner Header -->
    <div class="player-modal-banner">
      <i class="ph-fill ph-game-controller player-modal-banner-icon"></i>
      <!-- Tombol Tutup (X) -->
      <button type="button" id="btn-close-player-modal" class="player-modal-close" title="Tutup Modal">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Body Info Pemain -->
    <div class="player-modal-body">
      
      <!-- Avatar Menumpuk Banner -->
      <div class="player-modal-top">
        <div class="player-modal-avatar">
          <img id="modal-player-avatar-img" src="" alt="Avatar" class="hidden" />
          <i id="modal-player-avatar-icon" class="ph-fill ph-user text-3xl text-muted"></i>
          <span class="player-modal-status-dot"></span>
        </div>
        
        <span id="modal-player-host-badge" class="player-modal-host-badge hidden">
          Host Sesi
        </span>
      </div>

      <!-- Nama Pemain & Lokasi -->
      <div class="player-modal-info">
        <h3 id="modal-player-name" class="player-modal-name">
          Nama Pemain
        </h3>
        <div class="player-modal-meta">
          <i class="ph ph-map-pin text-xs text-red"></i>
          <span id="modal-player-city"><?= htmlspecialchars($modalCity) ?></span>
          <span class="text-muted/40">&bull;</span>
          <span class="text-[#19d66b] font-medium flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#19d66b]"></span> Online
          </span>
        </div>
      </div>

      <!-- Card Spesifikasi Game Pemain -->
      <div class="player-modal-stats">
        <div class="player-stat-row">
          <span class="player-stat-label">Game / Cabor</span>
          <span id="modal-player-game" class="player-stat-value"><?= htmlspecialchars($modalGame) ?></span>
        </div>
        <div class="player-stat-row">
          <span class="player-stat-label">Tingkat / Rank</span>
          <span id="modal-player-rank" class="player-stat-value">-</span>
        </div>
        <div class="player-stat-row">
          <span class="player-stat-label">Sportivitas</span>
          <span class="player-stat-badge">
            <i class="ph-bold ph-shield-check text-xs"></i> 100% Reputasi Positif
          </span>
        </div>
      </div>

      <!-- Bio Singkat -->
      <div class="player-modal-bio">
        <span class="player-modal-bio-label">Biodata Singkat</span>
        <p class="player-modal-bio-box">
          "Main santai, komunikasi aktif, wajib no toxic & siap koordinasi tim!"
        </p>
      </div>

      <!-- Tombol Aksi Modal -->
      <div class="player-modal-actions">
        <button
          type="button"
          id="btn-modal-close-action"
          class="player-btn-close"
        >
          Tutup
        </button>
        <button
          type="button"
          id="btn-modal-send-dm"
          class="player-btn-dm"
        >
          <i class="ph-bold ph-chat-teardrop-text text-sm"></i>
          <span>Kirim Pesan</span>
        </button>
      </div>

    </div>
  </div>
</div>
