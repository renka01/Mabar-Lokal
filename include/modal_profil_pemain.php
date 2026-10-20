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
<div id="modal-player-profile" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm transition-opacity duration-200 hidden" style="backdrop-filter: blur(4px);">
  
  <!-- Backdrop Click Listener Area -->
  <div class="modal-profile-backdrop absolute inset-0 cursor-pointer"></div>

  <!-- Modal Card Content -->
  <div class="relative w-full max-w-sm rounded-2xl border border-[#243346] bg-[#0d1622] shadow-2xl overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-150">
    
    <!-- Mini Banner Header -->
    <div class="relative h-24 w-full bg-gradient-to-r from-[#111e2f] via-[#1a2d42] to-[#0c141f] overflow-hidden border-b border-[#202d3e]">
      <div class="absolute inset-0 opacity-20 pointer-events-none">
        <i class="ph-fill ph-game-controller text-8xl absolute -right-4 -bottom-6 text-white/30"></i>
      </div>
      <!-- Tombol Tutup (X) -->
      <button type="button" id="btn-close-player-modal" class="absolute top-3 right-3 h-7 w-7 rounded-full bg-black/50 hover:bg-black/80 text-muted hover:text-white flex items-center justify-center transition-colors cursor-pointer" title="Tutup">
        <i class="ph ph-x text-sm"></i>
      </button>
    </div>

    <!-- Body Info Pemain -->
    <div class="relative px-6 pb-6 pt-0">
      
      <!-- Avatar 64x64px Menumpuk Banner -->
      <div class="relative -mt-10 mb-3 flex items-end justify-between">
        <div class="relative h-16 w-16 rounded-full border-4 border-[#0d1622] shadow-xl overflow-hidden flex items-center justify-center bg-[#1a2636]">
          <img id="modal-player-avatar-img" src="" alt="Avatar" class="w-full h-full object-cover hidden" />
          <i id="modal-player-avatar-icon" class="ph-fill ph-user text-3xl text-muted"></i>
          <span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-[#0d1622] bg-[#19d66b]"></span>
        </div>
        
        <span id="modal-player-host-badge" class="hidden rounded px-2.5 py-1 text-[11px] font-semibold text-white/90 bg-[#1c293a] border border-[#2d3e54] shadow-sm">
          Host Sesi
        </span>
      </div>

      <!-- Nama Pemain & Lokasi -->
      <div class="flex flex-col mb-4">
        <h3 id="modal-player-name" class="text-lg font-bold text-white tracking-tight leading-tight">
          Nama Pemain
        </h3>
        <div class="flex items-center gap-1.5 text-xs text-muted font-medium mt-1">
          <i class="ph ph-map-pin text-xs" style="color: <?= $modalThemeColor ?>;"></i>
          <span id="modal-player-city"><?= htmlspecialchars($modalCity) ?></span>
          <span class="text-muted/40">&bull;</span>
          <span class="text-[#19d66b] font-medium">Online</span>
        </div>
      </div>

      <!-- Card Spesifikasi Game Pemain -->
      <div class="p-3.5 rounded-xl border border-[#212f42] bg-[#101a27] flex flex-col gap-2.5 mb-4 shadow-inner">
        <div class="flex items-center justify-between text-xs pb-2 border-b border-[#1d2a3a]">
          <span class="text-muted font-medium">Game / Cabor</span>
          <span id="modal-player-game" class="text-white font-bold"><?= htmlspecialchars($modalGame) ?></span>
        </div>
        <div class="flex items-center justify-between text-xs pb-2 border-b border-[#1d2a3a]">
          <span class="text-muted font-medium">Tingkat / Rank</span>
          <span id="modal-player-rank" class="text-white font-bold">-</span>
        </div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-muted font-medium">Sportivitas</span>
          <span class="text-[#19d66b] font-bold flex items-center gap-1">
            <i class="ph-bold ph-shield-check text-xs"></i> 100% Reputasi Positif
          </span>
        </div>
      </div>

      <!-- Bio Singkat -->
      <div class="mb-5">
        <span class="text-[10px] uppercase font-bold tracking-wider text-muted block mb-1">Biodata Singkat</span>
        <p class="text-xs text-muted/90 leading-relaxed italic bg-[#101a27]/60 p-2.5 rounded-lg border border-[#1b2737]">
          "Main santai, komunikasi aktif, wajib no toxic & siap koordinasi tim!"
        </p>
      </div>

      <!-- Tombol Aksi Modal -->
      <div class="flex items-center gap-3">
        <button
          type="button"
          id="btn-modal-close-action"
          class="flex-1 py-2 rounded-lg border border-[#243346] bg-[#111c28] hover:bg-[#172535] text-xs font-semibold text-white transition-all cursor-pointer"
        >
          Tutup
        </button>
        <button
          type="button"
          id="btn-modal-send-dm"
          class="flex-1 py-2 rounded-lg text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer text-center"
          style="background-color: <?= $modalThemeColor ?>; color: <?= $modalThemeText ?>;"
        >
          Kirim Pesan
        </button>
      </div>

    </div>
  </div>
</div>
