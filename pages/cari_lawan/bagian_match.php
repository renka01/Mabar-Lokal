<?php
$creatorAvatarSrc    = $scrim['creator_avatar'] !== '' ? ($basePath . $scrim['creator_avatar']) : '';
$challengerAvatarSrc = $scrim['challenger_avatar'] !== '' ? ($basePath . $scrim['challenger_avatar']) : '';
?>
<div class="flex flex-col gap-6">

  <!-- Susunan Kedua Tim (Side-by-side 2 Kolom Rapi) -->
  <div class="scrim-roster-grid-2col">

    <!-- TIM A (HOST / PEMBUKA TANTANGAN) -->
    <div class="scrim-roster-card">
      <div class="scrim-roster-main">
        <div class="scrim-roster-header">
          <div class="flex items-center gap-2 min-w-0">
            <i class="ph-bold ph-shield text-red text-base shrink-0"></i>
            <h3 class="scrim-roster-title truncate">
              Tim <?= htmlspecialchars($scrim['nama_tim'], ENT_QUOTES, 'UTF-8') ?>
            </h3>
            <?php if ($isHost): ?>
            <span class="scrim-tag-neutral shrink-0">Tim kamu</span>
            <?php endif; ?>
          </div>
          <span class="scrim-status scrim-status-ready shrink-0">Siap tanding</span>
        </div>

        <div class="scrim-roster-body">
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
                  <span class="text-[14px] font-bold text-white truncate <?= $isHost ? 'lobby-self-name' : '' ?>"><?= htmlspecialchars($scrim['kapten'], ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="badge-host">Host</span>
                </div>
                <span class="text-[12px] text-muted block truncate"><?= htmlspecialchars($scrim['kapten_rank'], ENT_QUOTES, 'UTF-8') ?></span>
              </div>
            </div>
          </div>

          <dl class="scrim-match-facts">
            <div>
              <dt>Domisili</dt>
              <dd><?= htmlspecialchars($scrim['kota'], ENT_QUOTES, 'UTF-8') ?></dd>
            </div>
            <div>
              <dt>Target tier</dt>
              <dd><?= htmlspecialchars($scrim['tier'], ENT_QUOTES, 'UTF-8') ?></dd>
            </div>
            <div class="scrim-match-fact-full">
              <dt>Tipe pertandingan</dt>
              <dd><?= $scrim['tipe_match'] === 'offline' ? 'Offline / Lapangan' : 'Online (Custom Room)' ?></dd>
            </div>
          </dl>
        </div>
      </div>

      <div class="scrim-roster-footer">
        <span>Hubungi kapten untuk koordinasi</span>
        <?php if (!$isHost): ?>
        <button
          type="button"
          onclick="window.MabarChat && window.MabarChat.openWith('<?= addslashes($scrim['kapten']) ?>', 'Kapten <?= addslashes($scrim['nama_tim']) ?>')"
          class="scrim-roster-action"
        >
          <i class="ph-bold ph-chat-circle-dots"></i> Chat kapten
        </button>
        <?php else: ?>
        <span class="scrim-roster-state">Tantangan aktif</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- TIM B (TIM PENANTANG / CHALLENGER) -->
    <div class="scrim-roster-card">
      <div class="scrim-roster-main">
        <div class="scrim-roster-header">
          <div class="flex items-center gap-2 min-w-0">
            <i class="ph-bold ph-shield text-muted text-base shrink-0"></i>
            <h3 class="scrim-roster-title truncate">
              <?= $isMatched ? ('Tim ' . htmlspecialchars($challengerTeamName, ENT_QUOTES, 'UTF-8')) : 'Tim penantang' ?>
            </h3>
            <?php if ($isChallenger): ?>
            <span class="scrim-tag-neutral shrink-0">Tim kamu</span>
            <?php endif; ?>
          </div>
          <?php if ($isMatched): ?>
          <span
            id="scrim-roster-b-ready"
            class="scrim-status <?= $challengerReady ? 'scrim-status-ready' : 'scrim-status-waiting' ?> shrink-0"
          >
            <?= $challengerReady ? 'Siap tanding' : 'Standby' ?>
          </span>
          <?php else: ?>
          <span class="scrim-status scrim-status-open shrink-0">Slot terbuka</span>
          <?php endif; ?>
        </div>

        <?php if ($isMatched): ?>
        <div class="scrim-roster-body">
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
                <span class="text-[14px] font-bold text-white truncate block <?= $isChallenger ? 'lobby-self-name' : '' ?>">
                  <?= htmlspecialchars($scrim['challenger_name'] ?: 'Kapten Penantang', ENT_QUOTES, 'UTF-8') ?>
                </span>
                <span class="text-[12px] text-muted block">Kapten tim penantang</span>
              </div>
            </div>
          </div>

          <?php if ($challengerMessage !== ''): ?>
          <div>
            <span class="scrim-message-label">Pesan dari kapten <?= $isChallenger ? 'kamu' : 'penantang' ?></span>
            <div class="scrim-quote-box <?= $isOlahraga ? 'olahraga' : '' ?>">
              "<?= htmlspecialchars($challengerMessage, ENT_QUOTES, 'UTF-8') ?>"
            </div>
          </div>
          <?php endif; ?>

          <?php if ($challengerContact !== ''): ?>
          <div class="scrim-challenger-contact">
            <i class="ph-bold ph-phone"></i>
            <span>Kontak kapten:</span>
            <strong><?= htmlspecialchars($challengerContact, ENT_QUOTES, 'UTF-8') ?></strong>
          </div>
          <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="scrim-empty-slot-panel">
          <?php
            $innerBtnClass = $isOlahraga
              ? 'scrim-empty-action-sport'
              : 'scrim-empty-action-red';
          ?>
          <?php if ($isHost): ?>
          <h4 class="scrim-empty-title">Menunggu tim lawan menantang</h4>
          <p class="scrim-empty-copy">
            Tantangan tim kamu sudah aktif di daftar publik. Bagikan link match ini agar calon lawan dapat mendaftar.
          </p>
          <button
            type="button"
            onclick="salinLinkMatch()"
            class="scrim-empty-action <?= $innerBtnClass ?>"
          >
            <i class="ph-bold ph-share-network"></i>
            <span>Salin link tantangan</span>
          </button>
          <?php else: ?>
          <h4 class="scrim-empty-title">Belum ada tim penantang</h4>
          <p class="scrim-empty-copy">
            Daftarkan tim kamu untuk mengisi slot lawan dan menjadwalkan sparring resmi.
          </p>
          <button
            type="button"
            onclick="bukaModalTantang(<?= htmlspecialchars(json_encode($scrim, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)"
            class="scrim-empty-action <?= $innerBtnClass ?>"
          >
            Tantang tim ini sekarang
          </button>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="scrim-roster-footer">
        <span>Bagikan match ini ke grup tim kamu</span>
        <button
          type="button"
          onclick="salinLinkMatch()"
          class="scrim-roster-action"
        >
          <i class="ph-bold ph-share-network"></i> Salin link
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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-[#232d39] text-[12px] text-muted">
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
