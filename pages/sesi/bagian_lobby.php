<?php
/**
 * pages/sesi/bagian_lobby.php — Komponen Tab Lobby Sesi
 * Menampilkan daftar anggota/pemain, status kesiapan (ready), dan 3 kartu ringkasan aturan.
 */
?>
<!-- TAB CONTENT: LOBBY -->
<div id="tab-content-lobby" style="display: <?= $initialTab === 'lobby' ? 'flex' : 'none' ?>; flex-direction: column; gap: 20px;">

  <!-- Daftar Anggota / Player List Card -->
  <div class="rounded-xl border p-4 sm:p-5 flex flex-col gap-3 shadow-md" style="background-color: #0d1622; border-color: #232d39;">
    
    <!-- Header Bar: Info Pemain & Indikator Kesiapan Tim -->
    <div class="flex items-center justify-between pb-3 border-b border-[#232d39]/70">
      <div class="flex items-center gap-2">
        <span class="text-xs sm:text-[13px] font-bold text-white uppercase tracking-wider">Daftar Pemain</span>
        <span class="text-[11px] font-medium text-muted">(<span id="player-count"><?= count($pesertaLobby) ?></span>/<?= $sesi['total_slot'] ?>)</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-[11px] font-medium text-muted">Kesiapan:</span>
        <span id="lobby-ready-summary" class="inline-flex items-center gap-1.5 text-xs">
          <?php if ($initialReadyCount === count($pesertaLobby)): ?>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[11px] font-bold text-[#19d66b] bg-[#19d66b]/10 border border-[#19d66b]/30">
              <span class="w-1.5 h-1.5 rounded-full bg-[#19d66b]"></span>
              Semua Siap
            </span>
          <?php else: ?>
            <span class="text-xs text-muted font-medium">
              <span id="ready-count-text" class="text-white font-bold"><?= $initialReadyCount ?></span> dari <?= count($pesertaLobby) ?> pemain siap
            </span>
          <?php endif; ?>
        </span>
      </div>
    </div>

    <!-- List Pemain -->
    <div id="player-roster-list" class="flex flex-col gap-3">
      <?php foreach ($pesertaLobby as $idx => $p): 
        $isLeaderOrHost = !empty($p['isLeader']) || !empty($p['isHost']);
        $isSelf = ($isHost && $isLeaderOrHost) || ($isMember && str_contains($p['nama'], '(You)'));
      ?>
      <div class="player-row flex items-center justify-between p-4 sm:p-4.5 rounded-xl border transition-colors hover:border-muted/50"
           data-player-row="1"
           data-player-id="<?= $idx ?>"
           data-player-name="<?= htmlspecialchars($p['nama']) ?>"
           data-player-rank="<?= htmlspecialchars($p['rank']) ?>"
           data-player-is-host="<?= $isLeaderOrHost ? '1' : '0' ?>"
           data-player-avatar="<?= htmlspecialchars($p['avatar'] ?? '') ?>"
           data-player-ready="<?= !empty($p['isReady']) ? '1' : '0' ?>"
           style="background-color: #111b28; border-color: #253142;">
        
        <!-- Player Info dengan Avatar Lebih Gagah (48px / h-12 w-12) -->
        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
          <div class="btn-open-player-card relative h-12 w-12 shrink-0 rounded-full border cursor-pointer group transition-transform active:scale-95" 
               style="background-color: #111b28; border-color: #253142;"
               title="Klik untuk melihat profil <?= htmlspecialchars($p['nama']) ?>">
            <div class="h-full w-full rounded-full overflow-hidden flex items-center justify-center <?= $isSelf ? 'lobby-self-avatar' : '' ?>" style="background-color: #1a2636; color: #9ba5b4;">
              <?php if (!empty($p['avatar'])): ?>
                <img src="<?= htmlspecialchars($p['avatar']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>" class="w-full h-full object-cover" />
              <?php else: ?>
                <i class="ph-fill ph-user text-2xl group-hover:text-white transition-colors"></i>
              <?php endif; ?>
            </div>
            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2" style="background-color: #19d66b; border-color: #111b28;"></div>
          </div>
          <div class="min-w-0 flex flex-col justify-center">
            <h4 class="text-[15px] font-bold text-white leading-tight truncate flex items-center gap-2.5 mb-1.5">
              <span class="btn-open-player-card hover:underline cursor-pointer <?= $isSelf ? 'lobby-self-name' : '' ?>" title="Klik untuk melihat profil">
                <?= htmlspecialchars($p['nama']) ?>
              </span>
              <?php if ($isLeaderOrHost): ?>
              <span class="player-host-tag rounded px-2.5 py-0.5 text-[11px] font-semibold text-white/90 bg-[#1c293a] border border-[#2d3e54] shadow-sm">Host</span>
              <?php endif; ?>
            </h4>
            <span class="text-[12px] font-medium" style="color: #9ba5b4;">
              <?= htmlspecialchars($p['rank']) ?>
            </span>
          </div>
        </div>

        <!-- Status Ready & Dropdown Titik Tiga -->
        <div class="flex items-center gap-3.5 shrink-0">
          <?php if ($isSelf): ?>
          <!-- Tombol Interaktif Kesiapan Pengguna Sendiri -->
          <button
            type="button"
            class="btn-toggle-my-ready inline-flex items-center gap-2 px-3.5 py-1.5 rounded-md text-xs font-semibold border transition-all select-none cursor-pointer active:scale-95 shadow-sm"
            data-ready="<?= !empty($p['isReady']) ? '1' : '0' ?>"
            data-is-user="1"
            style="
              background-color: <?= !empty($p['isReady']) ? 'rgba(25, 214, 107, 0.1)' : 'rgba(35, 45, 57, 0.4)' ?>;
              color: <?= !empty($p['isReady']) ? '#19d66b' : '#9ba5b4' ?>;
              border-color: <?= !empty($p['isReady']) ? 'rgba(25, 214, 107, 0.35)' : '#253142' ?>;
            "
            title="Klik untuk mengubah status kesiapan Anda"
          >
            <span class="ready-dot w-2 h-2 rounded-full" style="background-color: <?= !empty($p['isReady']) ? '#19d66b' : '#526071' ?>;"></span>
            <span class="ready-label"><?= !empty($p['isReady']) ? 'Saya Siap' : 'Belum Siap' ?></span>
          </button>
          <?php else: ?>
          <!-- Status Pemain Lainnya (Gaming status dot & text) -->
          <div class="inline-flex items-center gap-2 text-xs font-medium">
            <span class="w-2 h-2 rounded-full" style="background-color: <?= !empty($p['isReady']) ? '#19d66b' : '#526071' ?>;"></span>
            <span class="ready-label" style="color: <?= !empty($p['isReady']) ? '#19d66b' : '#7e8c9f' ?>;">
              <?= !empty($p['isReady']) ? 'Siap' : 'Menunggu' ?>
            </span>
          </div>
          <?php endif; ?>

          <?php if (!$isSelf): ?>
          <!-- Menu Dropdown Titik 3 (Hanya untuk Pemain Lain) -->
          <div class="relative player-dropdown-container">
            <button
              type="button"
              class="btn-player-menu p-1.5 rounded-md hover:bg-[#1a2839] hover:text-white transition-colors cursor-pointer text-muted"
              title="Opsi Pemain"
            >
              <i class="ph-bold ph-dots-three-vertical text-lg"></i>
            </button>

            <!-- Dropdown Menu Box -->
            <div class="player-dropdown-menu hidden absolute right-0 top-full mt-1.5 z-40 w-48 rounded-xl border border-[#243346] bg-[#0c141f] shadow-2xl py-1 text-xs animate-in fade-in zoom-in-95 duration-100">
              
              <!-- 1. Opsi Lihat Profil (Untuk Semua) -->
              <button
                type="button"
                class="btn-view-profile-menu w-full text-left px-3.5 py-2.5 text-white/90 hover:bg-[#162232] hover:text-white flex items-center gap-2.5 cursor-pointer transition-colors"
              >
                <i class="ph ph-user text-sm text-muted"></i>
                <span>Lihat Profil</span>
              </button>

              <!-- 2. Opsi Kirim Pesan (DM) -->
              <button
                type="button"
                class="btn-send-dm-menu w-full text-left px-3.5 py-2.5 text-white/90 hover:bg-[#162232] hover:text-white flex items-center gap-2.5 cursor-pointer transition-colors"
                data-name="<?= htmlspecialchars($p['nama']) ?>"
              >
                <i class="ph ph-chat-circle-dots text-sm text-muted"></i>
                <span>Kirim Pesan</span>
              </button>

              <?php if ($isHost): ?>
              <div class="my-1 border-t border-[#1e2a38]"></div>

              <!-- 3. Opsi Jadikan Host (Khusus Host) -->
              <button
                type="button"
                class="btn-transfer-host-menu w-full text-left px-3.5 py-2.5 text-white/90 hover:bg-[#162232] hover:text-white flex items-center gap-2.5 cursor-pointer transition-colors"
                data-name="<?= htmlspecialchars($p['nama']) ?>"
              >
                <i class="ph ph-crown text-sm text-amber-400"></i>
                <span>Jadikan Host</span>
              </button>

              <!-- 4. Opsi Keluarkan dari Tim (Khusus Host) -->
              <button
                type="button"
                class="btn-kick-player-menu w-full text-left px-3.5 py-2.5 text-red hover:bg-red/10 flex items-center gap-2.5 cursor-pointer transition-colors font-semibold"
                data-name="<?= htmlspecialchars($p['nama']) ?>"
              >
                <i class="ph ph-user-minus text-sm text-red"></i>
                <span>Keluarkan dari Tim</span>
              </button>
              <?php endif; ?>

            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <?php 
        $totalSlotCount = (int)$sesi['total_slot'];
        $currentFilled = count($pesertaLobby);
        $emptySlotsNeeded = max(0, $totalSlotCount - $currentFilled);
      ?>
      <div id="empty-slots-container" class="flex flex-col gap-3">
        <?php for ($s = 0; $s < $emptySlotsNeeded; $s++): ?>
        <!-- Slot Kosong Sesuai Total Kapasitas Sesi (48px / h-12 w-12) -->
        <div class="empty-slot-row flex items-center justify-between p-4 sm:p-4.5 rounded-xl border border-dashed" style="border-color: #1e2a38; background-color: rgba(13, 22, 34, 0.3);">
          <div class="flex items-center gap-4 sm:gap-5 min-w-0">
            <div class="h-12 w-12 shrink-0 rounded-full border border-dashed flex items-center justify-center text-muted/40" style="border-color: #263547; background-color: #101824;">
              <i class="ph ph-user text-xl"></i>
            </div>
            <div class="min-w-0">
              <span class="text-sm font-semibold text-muted/80">Slot Kosong</span>
            </div>
          </div>
          <span class="text-xs text-muted/50 font-medium px-2 py-0.5">Tersedia</span>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>

  <!-- 3 Bottom Cards (Pengaturan Sesi, Aturan Sesi Dinamis, Aturan Permainan / Olahraga Dinamis) -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Card 1: Pengaturan Sesi (Sinkron Dinamis dengan Detail Sesi) -->
    <div class="rounded-xl border p-4 sm:p-5 flex flex-col gap-3 shadow-md" style="background-color: #0d1622; border-color: #232d39;">
      <h3 class="text-[12px] font-bold text-white tracking-wider uppercase">Pengaturan Sesi</h3>
      <ul class="flex flex-col gap-2.5 text-[12px]">
        <li class="flex items-center justify-between">
          <span style="color: #9ba5b4;" class="font-medium">Join</span>
          <span id="lobby-join-rule" class="text-white font-semibold"><?= htmlspecialchars($sesi['join_rule'] ?: '-') ?></span>
        </li>
        <li class="flex items-center justify-between">
          <span style="color: #9ba5b4;" class="font-medium">Jam Mulai</span>
          <span id="lobby-jam-mulai" class="text-white font-semibold"><?= htmlspecialchars($sesi['jam_mulai'] ?: '-') ?></span>
        </li>
        <li class="flex items-center justify-between">
          <span style="color: #9ba5b4;" class="font-medium">Jam Selesai</span>
          <span id="lobby-jam-selesai" class="text-white font-semibold"><?= htmlspecialchars($sesi['jam_selesai'] ?: '-') ?></span>
        </li>
        <li class="flex items-center justify-between">
          <span style="color: #9ba5b4;" class="font-medium">Host</span>
          <span id="lobby-leader" class="text-white font-semibold"><?= htmlspecialchars($sesi['host'] ?? $sesi['leader'] ?? '-') ?></span>
        </li>
      </ul>
    </div>

    <!-- Card 2: Aturan Sesi (Input Dinamis dari User, Boleh Kosong) -->
    <div class="rounded-xl border p-4 sm:p-5 flex flex-col gap-3 shadow-md" style="background-color: #0d1622; border-color: #232d39;">
      <h3 class="text-[12px] font-bold text-white tracking-wider uppercase">Aturan Sesi</h3>
      <ul id="lobby-aturan-list" class="flex flex-col gap-2 text-[12px] font-medium" style="color: #9ba5b4;">
        <?php if (empty($sesi['aturan_sesi'])): ?>
          <li class="flex items-center gap-2 italic text-[12px]" style="color: #64748b;">
            <span class="h-1.5 w-1.5 rounded-full shrink-0" style="background-color: #475569;"></span>
            <span>Tidak ada aturan khusus</span>
          </li>
        <?php else: ?>
          <?php foreach ($sesi['aturan_sesi'] as $aturan): ?>
          <li class="flex items-center gap-2">
            <span class="h-1.5 w-1.5 rounded-full shrink-0" style="background-color: <?= $themeColor ?>;"></span>
            <span class="text-white/90"><?= htmlspecialchars($aturan) ?></span>
          </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Card 3: Aturan Dinamis Game / Olahraga (Sesuai config/gameAndolaraga_fields.php) -->
    <div class="rounded-xl border p-4 sm:p-5 flex flex-col gap-3 shadow-md" style="background-color: #0d1622; border-color: #232d39;">
      <h3 class="text-[12px] font-bold text-white tracking-wider uppercase">
        Aturan <?= htmlspecialchars($sesi['game']) ?>
      </h3>
      <ul class="flex flex-col gap-2.5 text-[12px]">
        <?php foreach ($currentGameFields as $f): 
          $val = $_GET[$f['name']] ?? $mockFieldValues[$f['name']] ?? '';
          $displayVal = ($val !== '' && $val !== null) ? $val : 'Bebas';
        ?>
        <li class="flex items-center justify-between gap-3">
          <span style="color: #9ba5b4;" class="font-medium truncate"><?= htmlspecialchars($f['label']) ?></span>
          <span id="lobby-spec-<?= htmlspecialchars($f['name']) ?>" class="text-white font-semibold truncate text-right">
            <?= htmlspecialchars($displayVal) ?>
          </span>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

</div>
