<?php
/**
 * pages/cari_lawan/bagian_match.php — Panel Roster & Match Info
 * Menampilkan susunan lineup kedua tim (2 Kolom Seimbang) dan pedoman fair play.
 */
?>
<div class="flex flex-col gap-6">

  <!-- Susunan Roster Kedua Tim (Side-by-side 2 Kolom Rapi) -->
  <div class="scrim-roster-grid-2col">

    <!-- ROSTER TIM A (HOST / PENANTANG) -->
    <div class="scrim-roster-card">
      <div class="flex items-center justify-between pb-3 border-b border-[#232d39]">
        <div class="flex items-center gap-2">
          <i class="ph-bold ph-shield text-red text-base"></i>
          <h3 class="text-sm font-bold text-white uppercase tracking-wider">
            Lineup <?= htmlspecialchars($scrim['nama_tim']) ?>
          </h3>
          <?php if ($isHost): ?>
          <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase tracking-wide">
            ⭐ Tim Kamu
          </span>
          <?php endif; ?>
        </div>
        <span style="color: #19d66b; background: rgba(25, 214, 107, 0.12); border: 1px solid rgba(25, 214, 107, 0.3); font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px;">
          5/5 Ready
        </span>
      </div>

      <!-- Player List Tim A -->
      <div class="flex flex-col gap-2.5">
        <!-- Player 1: Kapten -->
        <div class="scrim-player-row-item">
          <div class="flex items-center gap-3">
            <div class="scrim-player-avatar-circle">
              <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="text-[13px] font-bold text-white"><?= htmlspecialchars($scrim['kapten']) ?></span>
                <span class="badge-host">Host</span>
              </div>
              <span class="text-[11px] text-muted"><?= htmlspecialchars($scrim['kapten_rank']) ?></span>
            </div>
          </div>
          <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
            <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
          </span>
        </div>

        <!-- Player 2 -->
        <div class="scrim-player-row-item">
          <div class="flex items-center gap-3">
            <div class="scrim-player-avatar-circle">
              <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
            </div>
            <div>
              <span class="text-[13px] font-bold text-white">Rivaldi "Viper"</span>
              <span class="text-[11px] text-muted block">Duelist • Ascendant 1</span>
            </div>
          </div>
          <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
            <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
          </span>
        </div>

        <!-- Player 3 -->
        <div class="scrim-player-row-item">
          <div class="flex items-center gap-3">
            <div class="scrim-player-avatar-circle">
              <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
            </div>
            <div>
              <span class="text-[13px] font-bold text-white">Fahmi "Sage"</span>
              <span class="text-[11px] text-muted block">Sentinel • Diamond 3</span>
            </div>
          </div>
          <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
            <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
          </span>
        </div>

        <!-- Player 4 -->
        <div class="scrim-player-row-item">
          <div class="flex items-center gap-3">
            <div class="scrim-player-avatar-circle">
              <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
            </div>
            <div>
              <span class="text-[13px] font-bold text-white">Bagus "Omen"</span>
              <span class="text-[11px] text-muted block">Controller • Ascendant 2</span>
            </div>
          </div>
          <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
            <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
          </span>
        </div>

        <!-- Player 5 -->
        <div class="scrim-player-row-item">
          <div class="flex items-center gap-3">
            <div class="scrim-player-avatar-circle">
              <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
            </div>
            <div>
              <span class="text-[13px] font-bold text-white">Yudha "Sova"</span>
              <span class="text-[11px] text-muted block">Initiator • Diamond 2</span>
            </div>
          </div>
          <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
            <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
          </span>
        </div>
      </div>
    </div>

    <!-- ROSTER TIM B (TIM LAWAN / CHALLENGER) -->
    <div class="scrim-roster-card justify-between">
      <div>
        <div class="flex items-center justify-between pb-3 border-b border-[#232d39]">
          <div class="flex items-center gap-2">
            <i class="ph-bold ph-shield text-muted text-base"></i>
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">
              <?= $isMatched ? ('Lineup ' . htmlspecialchars($challengerTeamName)) : 'Lineup Tim Lawan' ?>
            </h3>
            <?php if ($isChallenger): ?>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase tracking-wide">
              ⭐ Tim Kamu
            </span>
            <?php endif; ?>
          </div>
          <?php if ($isMatched): ?>
          <span id="scrim-roster-b-ready" style="color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px;">
            Standby
          </span>
          <?php else: ?>
          <span style="color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px;">
            Slot Terbuka
          </span>
          <?php endif; ?>
        </div>

        <?php if ($isMatched): ?>
        <!-- Player List Tim B jika Matched -->
        <div class="flex flex-col gap-2.5 mt-3">
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3">
              <div class="scrim-player-avatar-circle">
                <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
              </div>
              <div>
                <span class="text-[13px] font-bold text-white">Dimas Arya</span>
                <span class="text-[11px] text-muted block">Kapten • Ascendant 3</span>
              </div>
            </div>
            <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
              <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
            </span>
          </div>
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3">
              <div class="scrim-player-avatar-circle">
                <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
              </div>
              <div>
                <span class="text-[13px] font-bold text-white">Aldo "Jett"</span>
                <span class="text-[11px] text-muted block">Duelist • Immortal 1</span>
              </div>
            </div>
            <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
              <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
            </span>
          </div>
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3">
              <div class="scrim-player-avatar-circle">
                <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
              </div>
              <div>
                <span class="text-[13px] font-bold text-white">Reza "Cypher"</span>
                <span class="text-[11px] text-muted block">Sentinel • Ascendant 2</span>
              </div>
            </div>
            <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
              <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
            </span>
          </div>
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3">
              <div class="scrim-player-avatar-circle">
                <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
              </div>
              <div>
                <span class="text-[13px] font-bold text-white">Tio "Omen"</span>
                <span class="text-[11px] text-muted block">Controller • Ascendant 1</span>
              </div>
            </div>
            <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
              <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
            </span>
          </div>
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3">
              <div class="scrim-player-avatar-circle">
                <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
              </div>
              <div>
                <span class="text-[13px] font-bold text-white">Kevin "Fade"</span>
                <span class="text-[11px] text-muted block">Initiator • Diamond 3</span>
              </div>
            </div>
            <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;">
              <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
            </span>
          </div>
        </div>
        <?php if (!empty($paramMsg) || !empty($paramContact)): ?>
        <!-- Pesan & kontak kapten penantang (hasil input modal tantang) -->
        <div class="mt-3">
          <?php if (!empty($paramMsg)): ?>
          <span class="text-[10px] text-muted uppercase tracking-wider font-bold">Pesan dari Kapten <?= $isChallenger ? 'Kamu' : 'Tim Lawan' ?></span>
          <div class="scrim-quote-box <?= $isOlahraga ? 'olahraga' : '' ?>">
            "<?= htmlspecialchars($paramMsg) ?>"
          </div>
          <?php endif; ?>
          <?php if (!empty($paramContact)): ?>
          <div class="flex items-center gap-2 mt-3 text-[11.5px]">
            <i class="ph-bold ph-phone text-muted"></i>
            <span class="text-muted">Kontak Kapten:</span>
            <span class="text-white font-semibold"><?= htmlspecialchars($paramContact) ?></span>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <!-- Empty State Slot Lawan Belum Ada -->
        <div class="scrim-empty-slot-panel my-3">
          <?php
            $innerBtnClass = $isOlahraga
              ? 'border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]'
              : 'border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]';
          ?>
          <?php if ($isHost): ?>
          <div class="w-12 h-12 rounded-xl flex items-center justify-center text-amber-400 text-xl mb-3" style="background-color: #142030; border: 1px solid #23354a;">
            <i class="ph-bold ph-hourglass-high"></i>
          </div>
          <h4 class="text-sm font-bold text-white mb-1">
            Menunggu Tim Lawan Menantang
          </h4>
          <p class="text-xs text-muted max-w-xs mb-4 leading-relaxed">
            Tantangan tim kamu sudah aktif di daftar publik. Bagikan link match ini agar calon lawan dapat mendaftar.
          </p>
          <button
            type="button"
            onclick="salinLinkMatch()"
            class="inline-flex items-center justify-center h-9 px-5 rounded-md text-xs font-semibold tracking-wide transition-all duration-200 active:scale-95 cursor-pointer gap-1.5 <?= $innerBtnClass ?>"
          >
            <i class="ph-bold ph-share-network"></i>
            <span>Salin Link Tantangan</span>
          </button>
          <?php else: ?>
          <div class="w-12 h-12 rounded-xl flex items-center justify-center text-muted text-xl mb-3" style="background-color: #142030; border: 1px solid #23354a;">
            <i class="ph-bold ph-users-three"></i>
          </div>
          <h4 class="text-sm font-bold text-white mb-1">
            Belum Ada Tim yang Menerima Tantangan
          </h4>
          <p class="text-xs text-muted max-w-xs mb-4">
            Punya tim seimbang? Daftarkan roster kamu sekarang dan jadwalkan sparring resmi!
          </p>
          <button
            type="button"
            onclick="bukaModalTantang(<?= htmlspecialchars(json_encode($scrim)) ?>)"
            class="inline-flex items-center justify-center h-9 px-5 rounded-md text-xs font-semibold tracking-wide transition-all duration-200 active:scale-95 cursor-pointer <?= $innerBtnClass ?>"
          >
            Tantang Tim Ini Sekarang
          </button>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Quick Share Link Match -->
      <div class="pt-3 border-t border-[#232d39] flex items-center justify-between text-[11.5px] text-muted">
        <span>Bagikan match ini ke grup tim kamu</span>
        <button
          type="button"
          onclick="salinLinkMatch()"
          class="text-white hover:text-red transition-colors font-semibold flex items-center gap-1 cursor-pointer"
        >
          <i class="ph-bold ph-share-network"></i> Salin Link
        </button>
      </div>
    </div>

  </div>

  <!-- Catatan & Rules dari Kapten -->
  <div class="scrim-roster-card">
    <div class="flex items-center gap-2">
      <i class="ph-bold ph-scroll text-red text-base"></i>
      <h3 class="text-sm font-bold text-white uppercase tracking-wider">
        Catatan & Pedoman Fair Play
      </h3>
    </div>
    <div class="scrim-quote-box <?= $isOlahraga ? 'olahraga' : '' ?>" id="scrim-info-catatan">
      "<?= htmlspecialchars($scrim['catatan']) ?>"
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-[#232d39] text-[12px] text-muted">
      <div>
        <span class="text-muted block text-[11px]">Format Pertandingan</span>
        <span class="text-white font-semibold" id="scrim-info-format"><?= htmlspecialchars($scrim['format']) ?></span>
      </div>
      <div>
        <span class="text-muted block text-[11px]">Venue / Server</span>
        <span class="text-white font-semibold" id="scrim-info-venue"><?= htmlspecialchars($scrim['lokasi']) ?></span>
      </div>
      <div>
        <span class="text-muted block text-[11px]">Sanksi Pelanggaran</span>
        <span class="text-red font-semibold">Pengurangan Skor Reputasi</span>
      </div>
    </div>
  </div>

</div>
