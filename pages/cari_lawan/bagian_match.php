<?php
$creatorAvatarSrc    = $scrim['creator_avatar'] !== '' ? ($basePath . $scrim['creator_avatar']) : '';
$challengerAvatarSrc = $scrim['challenger_avatar'] !== '' ? ($basePath . $scrim['challenger_avatar']) : '';
?>
<div class="flex flex-col gap-6">

  <!-- Susunan Kedua Tim (Side-by-side 2 Kolom Rapi) -->
  <div class="scrim-roster-grid-2col">

    <!-- TIM A (HOST / PEMBUKA TANTANGAN) -->
    <div class="scrim-roster-card justify-between">
      <div>
        <div class="flex items-center justify-between pb-3 border-b border-[#232d39]">
          <div class="flex items-center gap-2 min-w-0">
            <i class="ph-bold ph-shield text-red text-base shrink-0"></i>
            <h3 class="text-sm font-bold text-white uppercase tracking-wider truncate">
              Tim <?= htmlspecialchars($scrim['nama_tim'], ENT_QUOTES, 'UTF-8') ?>
            </h3>
            <?php if ($isHost): ?>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase tracking-wide shrink-0">
              Tim Kamu
            </span>
            <?php endif; ?>
          </div>
          <span style="color: #19d66b; background: rgba(25, 214, 107, 0.12); border: 1px solid rgba(25, 214, 107, 0.3); font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px;" class="shrink-0">
            Siap Tanding
          </span>
        </div>

        <div class="flex flex-col gap-3 mt-3">
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3 min-w-0">
              <div class="scrim-player-avatar-circle <?= $isHost ? 'lobby-self-avatar' : '' ?>">
                <?php if ($creatorAvatarSrc !== ''): ?>
                  <img src="<?= htmlspecialchars($creatorAvatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($scrim['kapten'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" />
                <?php else: ?>
                  <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
                <?php endif; ?>
              </div>
              <div class="min-w-0">
                <div class="flex items-center gap-2">
                  <span class="text-[13px] font-bold text-white truncate <?= $isHost ? 'lobby-self-name' : '' ?>"><?= htmlspecialchars($scrim['kapten'], ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="badge-host">Host</span>
                </div>
                <span class="text-[11px] text-muted block truncate"><?= htmlspecialchars($scrim['kapten_rank'], ENT_QUOTES, 'UTF-8') ?></span>
              </div>
            </div>
            <span style="color: #19d66b; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;" class="shrink-0">
              <i class="ph-bold ph-check" style="color: #19d66b;"></i> Ready
            </span>
          </div>

          <div class="p-3.5 rounded-lg bg-[#0a121d] border border-[#202d3e] grid grid-cols-2 gap-3 text-xs">
            <div>
              <span class="text-muted text-[10.5px] uppercase font-bold tracking-wider block mb-0.5">Domisili</span>
              <span class="text-white font-semibold"><?= htmlspecialchars($scrim['kota'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div>
              <span class="text-muted text-[10.5px] uppercase font-bold tracking-wider block mb-0.5">Target Tier</span>
              <span class="text-white font-semibold"><?= htmlspecialchars($scrim['tier'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="col-span-2">
              <span class="text-muted text-[10.5px] uppercase font-bold tracking-wider block mb-0.5">Tipe Pertandingan</span>
              <span class="text-white font-semibold"><?= $scrim['tipe_match'] === 'offline' ? 'Offline / Lapangan' : 'Online (Custom Room)' ?></span>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-3 border-t border-[#232d39] flex items-center justify-between text-[11.5px] text-muted mt-3">
        <span>Hubungi kapten untuk koordinasi</span>
        <?php if (!$isHost): ?>
        <button
          type="button"
          onclick="window.MabarChat && window.MabarChat.openWith('<?= addslashes($scrim['kapten']) ?>', 'Kapten <?= addslashes($scrim['nama_tim']) ?>')"
          class="text-white hover:text-red transition-colors font-semibold flex items-center gap-1 cursor-pointer"
        >
          <i class="ph-bold ph-chat-circle-dots"></i> Chat Kapten
        </button>
        <?php else: ?>
        <span class="text-emerald-400 font-semibold">Status: Terbuka</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- TIM B (TIM PENANTANG / CHALLENGER) -->
    <div class="scrim-roster-card justify-between">
      <div>
        <div class="flex items-center justify-between pb-3 border-b border-[#232d39]">
          <div class="flex items-center gap-2 min-w-0">
            <i class="ph-bold ph-shield text-muted text-base shrink-0"></i>
            <h3 class="text-sm font-bold text-white uppercase tracking-wider truncate">
              <?= $isMatched ? ('Tim ' . htmlspecialchars($challengerTeamName, ENT_QUOTES, 'UTF-8')) : 'Tim Penantang' ?>
            </h3>
            <?php if ($isChallenger): ?>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase tracking-wide shrink-0">
              Tim Kamu
            </span>
            <?php endif; ?>
          </div>
          <?php if ($isMatched): ?>
          <span
            id="scrim-roster-b-ready"
            style="color: <?= $challengerReady ? '#19d66b' : '#f59e0b' ?>; background: <?= $challengerReady ? 'rgba(25, 214, 107, 0.12)' : 'rgba(245, 158, 11, 0.12)' ?>; border: 1px solid <?= $challengerReady ? 'rgba(25, 214, 107, 0.3)' : 'rgba(245, 158, 11, 0.3)' ?>; font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px;"
            class="shrink-0"
          >
            <?= $challengerReady ? 'Siap Tanding' : 'Standby' ?>
          </span>
          <?php else: ?>
          <span style="color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 700; font-size: 11px; padding: 2px 8px; border-radius: 4px;" class="shrink-0">
            Slot Terbuka
          </span>
          <?php endif; ?>
        </div>

        <?php if ($isMatched): ?>
        <div class="flex flex-col gap-3 mt-3">
          <div class="scrim-player-row-item">
            <div class="flex items-center gap-3 min-w-0">
              <div class="scrim-player-avatar-circle <?= $isChallenger ? 'lobby-self-avatar' : '' ?>">
                <?php if ($challengerAvatarSrc !== ''): ?>
                  <img src="<?= htmlspecialchars($challengerAvatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($scrim['challenger_name'] ?: 'Kapten Penantang', ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" />
                <?php else: ?>
                  <i class="ph-fill ph-user" style="color: #9ba5b4;"></i>
                <?php endif; ?>
              </div>
              <div class="min-w-0">
                <span class="text-[13px] font-bold text-white truncate block <?= $isChallenger ? 'lobby-self-name' : '' ?>">
                  <?= htmlspecialchars($scrim['challenger_name'] ?: 'Kapten Penantang', ENT_QUOTES, 'UTF-8') ?>
                </span>
                <span class="text-[11px] text-muted block">Kapten Tim Penantang</span>
              </div>
            </div>
            <span style="color: <?= $challengerReady ? '#19d66b' : '#f59e0b' ?>; font-weight: 600; font-size: 11px; display: flex; align-items: center; gap: 4px;" class="shrink-0">
              <i class="ph-bold <?= $challengerReady ? 'ph-check' : 'ph-clock' ?>"></i>
              <?= $challengerReady ? 'Ready' : 'Standby' ?>
            </span>
          </div>

          <?php if ($challengerMessage !== ''): ?>
          <div>
            <span class="text-[10px] text-muted uppercase tracking-wider font-bold">Pesan dari Kapten <?= $isChallenger ? 'Kamu' : 'Tim Penantang' ?></span>
            <div class="scrim-quote-box <?= $isOlahraga ? 'olahraga' : '' ?>">
              "<?= htmlspecialchars($challengerMessage, ENT_QUOTES, 'UTF-8') ?>"
            </div>
          </div>
          <?php endif; ?>

          <?php if ($challengerContact !== ''): ?>
          <div class="flex items-center gap-2 text-[11.5px] p-3 rounded-lg bg-[#0a121d] border border-[#202d3e]">
            <i class="ph-bold ph-phone text-muted"></i>
            <span class="text-muted">Kontak Kapten:</span>
            <span class="text-white font-semibold"><?= htmlspecialchars($challengerContact, ENT_QUOTES, 'UTF-8') ?></span>
          </div>
          <?php endif; ?>
        </div>
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
            Punya tim seimbang? Daftarkan tim kamu sekarang dan jadwalkan sparring resmi!
          </p>
          <button
            type="button"
            onclick="bukaModalTantang(<?= htmlspecialchars(json_encode($scrim, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)"
            class="inline-flex items-center justify-center h-9 px-5 rounded-md text-xs font-semibold tracking-wide transition-all duration-200 active:scale-95 cursor-pointer <?= $innerBtnClass ?>"
          >
            Tantang Tim Ini Sekarang
          </button>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Quick Share Link Match -->
      <div class="pt-3 border-t border-[#232d39] flex items-center justify-between text-[11.5px] text-muted mt-3">
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
        Catatan &amp; Pedoman Fair Play
      </h3>
    </div>
    <div class="scrim-quote-box <?= $isOlahraga ? 'olahraga' : '' ?>" id="scrim-info-catatan">
      "<?= htmlspecialchars($scrim['catatan'] ?: 'Tidak ada catatan khusus.', ENT_QUOTES, 'UTF-8') ?>"
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-[#232d39] text-[12px] text-muted">
      <div>
        <span class="text-muted block text-[11px]">Format Pertandingan</span>
        <span class="text-white font-semibold" id="scrim-info-format"><?= htmlspecialchars($scrim['format'], ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <div>
        <span class="text-muted block text-[11px]">Venue / Server</span>
        <span class="text-white font-semibold" id="scrim-info-venue"><?= htmlspecialchars($scrim['lokasi'], ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <div>
        <span class="text-muted block text-[11px]">Sanksi Pelanggaran</span>
        <span class="text-red font-semibold">Pengurangan Skor Reputasi</span>
      </div>
    </div>
  </div>

</div>
