<?php
$pageTitle  = 'Ruang Tanding Scrim';
$basePath   = '../../';
$activePage = 'cari_lawan';
require_once __DIR__ . '/../../include/header.php';
require_once __DIR__ . '/../../config/scrim_helpers.php';

$scrimKey = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
$pdo      = tryGetDB();
$scrim    = null;

if ($pdo instanceof PDO) {
  if ($scrimKey !== '') {
    $scrim = scrimFindByKey($pdo, $scrimKey);
  } else {
    $firstSlug = $pdo->query("SELECT slug FROM scrim_matches WHERE status IN ('open', 'matched', 'confirmed') ORDER BY is_featured DESC, created_at DESC LIMIT 1")->fetchColumn();
    if ($firstSlug) {
      $scrim = scrimFindByKey($pdo, (string) $firstSlug);
    }
  }
}

if (!$scrim) {
  ?>
  <div class="flex flex-1 overflow-hidden w-full max-w-[1600px] mx-auto px-4 lg:px-8 gap-6">
    <main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col items-center justify-center py-12 px-4">
      <div class="max-w-md w-full rounded-xl border border-border bg-card p-8 text-center shadow-xl">
        <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-card-alt border border-border flex items-center justify-center text-3xl text-muted">
          <i class="ph ph-sword"></i>
        </div>
        <h1 class="text-xl font-bold text-white mb-2">Tantangan Scrim Tidak Ditemukan</h1>
        <p class="text-xs text-muted leading-relaxed mb-6">
          Tantangan sparring ini mungkin sudah dibatalkan oleh kapten Host atau tautannya tidak valid.
        </p>
        <a href="index.php" class="inline-flex items-center gap-2 px-5 h-10 rounded-md bg-red hover:bg-red-bright text-white text-xs font-bold transition-colors">
          <i class="ph-bold ph-arrow-left"></i>
          <span>Kembali ke Cari Lawan Scrim</span>
        </a>
      </div>
    </main>
    <?php require __DIR__ . '/../../pages/chat.php'; ?>
  </div>
  <?php
  require __DIR__ . '/../../include/footer.php';
  return;
}

$currentUserId = currentUserId();
$isHost        = ($currentUserId !== null && $currentUserId === (int) $scrim['creator_user_id']);
$isChallenger  = (!$isHost && $currentUserId !== null && $scrim['challenger_user_id'] !== null && $currentUserId === (int) $scrim['challenger_user_id']);
$isVisitor     = !$isHost && !$isChallenger;
$isMatched     = $scrim['challenger_team_name'] !== '' || in_array($scrim['status'], ['matched', 'confirmed'], true);
$isConfirmed   = $scrim['status'] === 'confirmed';

$challengerTeamName = $scrim['challenger_team_name'] ?: 'Tim Penantang';
$challengerContact  = $scrim['challenger_contact'] ?: '';
$challengerMessage  = $scrim['challenger_message'] ?: '';
$challengerReady    = !empty($scrim['challenger_ready']);

$isOlahraga = $scrim['kategori'] === 'olahraga';
$themeColor = $isOlahraga ? '#19d66b' : '#ef233c';
$themeGlow  = $isOlahraga ? 'rgba(25, 214, 107, 0.15)' : 'rgba(239, 35, 60, 0.15)';
?>

<!-- App Body (Match Room: Content + Chat Sidebar) -->
<div class="flex flex-1 overflow-hidden w-full max-w-[1600px] mx-auto px-4 lg:px-8 gap-6">

  <!-- Main Match Room Content Area -->
  <main
    id="scrim-detail-main"
    data-scrim-slug="<?= htmlspecialchars($scrim['slug'], ENT_QUOTES, 'UTF-8') ?>"
    data-is-host="<?= $isHost ? '1' : '0' ?>"
    data-is-challenger="<?= $isChallenger ? '1' : '0' ?>"
    class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6"
  >

    <!-- Top Bar: Navigasi Kembali -->
    <div class="flex items-center justify-between gap-4 shrink-0">
      <a href="index.php" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-border bg-card hover:bg-card-alt text-muted hover:text-white text-xs font-semibold transition-all group shadow-sm">
        <i class="ph-bold ph-arrow-left text-sm transition-transform group-hover:-translate-x-1"></i>
        <span>Kembali ke Cari Lawan Scrim</span>
      </a>
    </div>

    <!-- Match Header Card -->
    <article class="relative shrink-0 overflow-hidden rounded-xl border p-6 shadow-xl"
             style="background-color: #0d1622; border-color: <?= $themeColor ?>; box-shadow: 0 4px 24px <?= $themeGlow ?>;">

      <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 w-full z-10">

        <!-- Left: Logo Game + Info Tim -->
        <div class="flex items-center gap-5 min-w-0 flex-1">
          <div class="shrink-0 rounded-xl border p-2 flex items-center justify-center shadow-lg"
               style="width: 84px; height: 84px; min-width: 84px; min-height: 84px; max-width: 84px; max-height: 84px; background-color: #080f18; border-color: <?= $themeColor ?>; overflow: hidden;">
            <img src="<?= $basePath . htmlspecialchars($scrim['game_icon'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($scrim['game'], ENT_QUOTES, 'UTF-8') ?>"
                 style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" />
          </div>

          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
              <span class="rounded px-2 py-0.5 text-[9px] font-extrabold tracking-widest uppercase shadow-sm" style="background-color: <?= $themeColor ?>; color: #ffffff;">
                <?= htmlspecialchars(strtoupper($scrim['kategori']), ENT_QUOTES, 'UTF-8') ?>
              </span>
              <span class="text-[12px] font-semibold text-muted tracking-wide">
                <?= htmlspecialchars($scrim['game'], ENT_QUOTES, 'UTF-8') ?>
              </span>
              <span class="text-muted text-[11px]">•</span>
              <span class="text-[11px] font-semibold text-white/80">
                Target: <?= htmlspecialchars($scrim['tier'], ENT_QUOTES, 'UTF-8') ?>
              </span>
              <?php if ($isConfirmed): ?>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#19d66b]/15 text-[#19d66b] border border-[#19d66b]/30">
                Terkonfirmasi
              </span>
              <?php endif; ?>
            </div>

            <h1 class="text-xl sm:text-[24px] font-bold tracking-tight text-white leading-tight truncate">
              <?= htmlspecialchars($scrim['nama_tim'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <div class="flex items-center gap-3 text-xs text-muted font-medium mt-1 mb-1.5 flex-wrap">
              <span class="flex items-center gap-1.5">
                <i class="ph-bold ph-map-pin text-sm" style="color: <?= $themeColor ?>;"></i>
                <span id="detail-scrim-lokasi-display"><?= htmlspecialchars($scrim['lokasi'], ENT_QUOTES, 'UTF-8') ?></span>
              </span>
              <span>•</span>
              <span class="flex items-center gap-1.5 text-white/90">
                <i class="ph-bold ph-calendar-blank text-sm" style="color: <?= $themeColor ?>;"></i>
                <span id="detail-scrim-jadwal-display"><?= htmlspecialchars($scrim['jadwal'], ENT_QUOTES, 'UTF-8') ?></span>
              </span>
            </div>

            <p id="detail-scrim-catatan-display" class="text-xs leading-relaxed text-muted line-clamp-2 max-w-xl">
              "<?= htmlspecialchars($scrim['catatan'] ?: 'Tidak ada catatan khusus.', ENT_QUOTES, 'UTF-8') ?>"
            </p>
          </div>
        </div>

        <!-- Right: Format Match & Tombol Aksi Sesuai Role -->
        <div class="flex flex-col items-center sm:items-end justify-center shrink-0 sm:pl-4 self-center">
          <div class="flex items-baseline gap-1.5 mb-0.5">
            <span id="detail-scrim-format-display" class="text-3xl sm:text-[34px] font-extrabold leading-none text-white"><?= htmlspecialchars($scrim['format_short'], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="text-[11px] font-bold tracking-wider uppercase text-muted">FORMAT</span>
          </div>
          <span class="text-[11px] text-muted mb-2">Kapten: <strong class="text-white"><?= htmlspecialchars($scrim['kapten'], ENT_QUOTES, 'UTF-8') ?></strong></span>

          <?php
            $btnOutlineTheme = $isOlahraga
              ? 'border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)]'
              : 'border border-red text-red bg-red/10 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)]';
          ?>

          <?php if ($isHost): ?>
            <?php if ($isMatched): ?>
            <div class="flex flex-col items-end gap-1.5">
              <button
                type="button"
                id="btn-host-confirm-scrim"
                onclick="konfirmasiMatchHost()"
                <?= $isConfirmed ? 'disabled' : '' ?>
                class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] transition-all active:scale-95 <?= $isConfirmed ? 'opacity-75 cursor-default' : 'cursor-pointer' ?>"
              >
                <i class="ph-bold ph-check-circle text-base mr-1.5"></i>
                <span><?= $isConfirmed ? 'Match Terkonfirmasi' : 'Konfirmasi Tanding' ?></span>
              </button>
              <button
                type="button"
                onclick="batalTantanganHost()"
                class="text-[11px] text-muted hover:text-red transition-colors cursor-pointer"
              >
                Batalkan Tantangan
              </button>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-end gap-1.5">
              <button
                type="button"
                onclick="salinLinkMatch()"
                class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide transition-all active:scale-95 cursor-pointer <?= $btnOutlineTheme ?>"
              >
                <i class="ph-bold ph-share-network text-base mr-1.5"></i>
                <span>Bagikan Tantangan</span>
              </button>
              <button
                type="button"
                onclick="batalTantanganHost()"
                class="text-[11px] text-muted hover:text-red transition-colors cursor-pointer"
              >
                Batalkan Tantangan
              </button>
            </div>
            <?php endif; ?>
          <?php elseif ($isChallenger): ?>
            <button
              type="button"
              id="btn-challenger-ready"
              onclick="toggleReadyChallenger(this)"
              data-ready="<?= $challengerReady ? 'true' : 'false' ?>"
              class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide transition-all active:scale-95 cursor-pointer <?= $challengerReady ? 'border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d]' : 'border border-amber-500/60 text-amber-400 bg-amber-500/10 hover:bg-amber-500 hover:text-black' ?>"
            >
              <i class="ph-bold <?= $challengerReady ? 'ph-check' : 'ph-clock' ?> text-base mr-1.5"></i>
              <span><?= $challengerReady ? 'Tim Siap (Ready)' : 'Standby (Belum Ready)' ?></span>
            </button>
          <?php else: ?>
            <?php if (!$isMatched): ?>
            <button
              type="button"
              onclick="bukaModalTantang(<?= htmlspecialchars(json_encode($scrim, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>)"
              class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide transition-all active:scale-95 cursor-pointer <?= $btnOutlineTheme ?>"
            >
              <span>Tantang Tim Ini</span>
            </button>
            <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-bold" style="background-color: rgba(25, 214, 107, 0.15); color: #19d66b; border: 1px solid rgba(25, 214, 107, 0.3);">
              <i class="ph-bold ph-shield-check text-base"></i> Lawan: <?= htmlspecialchars($challengerTeamName, ENT_QUOTES, 'UTF-8') ?>
            </span>
            <?php endif; ?>
          <?php endif; ?>
        </div>

      </div>

    </article>

    <!-- Sub-Tabs Navigasi Ruang Match Sesuai Role -->
    <div class="flex gap-6 border-b border-border text-[13px] font-medium text-muted">
      <button
        type="button"
        id="tab-btn-match-info"
        onclick="gantiTabScrim('info')"
        class="scrim-detail-tab active border-b-[3px] border-red text-white py-2 px-1 relative top-[1px] font-semibold cursor-pointer"
      >
        Lobby &amp; Roster Match
      </button>

      <?php if ($isHost): ?>
      <button
        type="button"
        id="tab-btn-match-settings"
        onclick="gantiTabScrim('settings')"
        class="scrim-detail-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] cursor-pointer"
      >
        Pengaturan Jadwal &amp; Rules (Host)
      </button>
      <?php elseif ($isChallenger): ?>
      <button
        type="button"
        id="tab-btn-match-settings"
        onclick="gantiTabScrim('settings')"
        class="scrim-detail-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] cursor-pointer"
      >
        Informasi Jadwal &amp; Room
      </button>
      <?php endif; ?>
    </div>

    <!-- PANEL 1: LOBBY & ROSTER MATCH -->
    <div id="panel-scrim-info">
      <?php require __DIR__ . '/bagian_match.php'; ?>
    </div>

    <!-- PANEL 2: PENGATURAN JADWAL & RULES -->
    <div id="panel-scrim-settings" class="hidden">
      <?php require __DIR__ . '/bagian_pengaturan_scrim.php'; ?>
    </div>

  </main>

  <!-- Chat Koordinasi Antar Tim (Sidebar Kanan) -->
  <?php require __DIR__ . '/../../pages/chat.php'; ?>

</div>

<!-- Modal Tantang Tim -->
<?php require __DIR__ . '/modal_tantang.php'; ?>

<!-- Script Interaksi Detail Scrim -->
<script src="<?= $basePath ?>asset/js/cari_lawan.js?v=<?= filemtime(__DIR__ . '/../../asset/js/cari_lawan.js') ?>"></script>
<script src="<?= $basePath ?>asset/js/detail_scrim.js?v=<?= filemtime(__DIR__ . '/../../asset/js/detail_scrim.js') ?>"></script>

<?php require __DIR__ . '/../../include/footer.php'; ?>
