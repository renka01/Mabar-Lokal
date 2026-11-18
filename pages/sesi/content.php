<?php
require_once __DIR__ . '/../../config/gameAndolaraga_fields.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mabar_helpers.php';

$paramId    = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
$initialTab = isset($_GET['tab']) && $_GET['tab'] === 'detail' ? 'detail' : 'lobby';

$pdo       = tryGetDB();
$dbSession = null;

if ($pdo instanceof PDO) {
  if ($paramId !== '') {
    $dbSession = mabarFindSession($pdo, $paramId);
  } else {
    $firstSlug = $pdo->query("SELECT slug FROM mabar_sessions WHERE status IN ('open', 'full') ORDER BY is_featured DESC, created_at DESC LIMIT 1")->fetchColumn();
    if ($firstSlug) {
      $dbSession = mabarFindSession($pdo, (string) $firstSlug);
    }
  }
}

if (!$dbSession) {
  ?>
  <main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col items-center justify-center py-12 px-4">
    <div class="max-w-md w-full rounded-xl border border-border bg-card p-8 text-center shadow-xl">
      <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-card-alt border border-border flex items-center justify-center text-3xl text-muted">
        <i class="ph ph-game-controller"></i>
      </div>
      <h1 class="text-xl font-bold text-white mb-2">Sesi Mabar Tidak Ditemukan</h1>
      <p class="text-xs text-muted leading-relaxed mb-6">
        Sesi yang Anda cari mungkin sudah dibatalkan, selesai, atau tautannya tidak valid.
      </p>
      <a href="<?= $basePath ?>index.php" class="inline-flex items-center gap-2 px-5 h-10 rounded-md bg-red hover:bg-red-bright text-white text-xs font-bold transition-colors">
        <i class="ph-bold ph-arrow-left"></i>
        <span>Kembali ke Cari Mabar</span>
      </a>
    </div>
  </main>
  <?php
  return;
}

$sessionId     = (int) $dbSession['id'];
$sessionSlug   = (string) $dbSession['slug'];
$selectedGame  = (string) $dbSession['game'];
$isOlahraga    = strtolower((string) $dbSession['category']) === 'olahraga';
$kategoriLabel = $isOlahraga ? 'Olahraga' : 'Esports';
$themeColor    = $isOlahraga ? '#19d66b' : '#ef233c';
$themeTextOnBg = $isOlahraga ? '#06160d' : '#ffffff';
$themeGlow     = $isOlahraga ? 'rgba(25, 214, 107, 0.15)' : 'rgba(239, 35, 60, 0.15)';

$pesertaLobby = mabarFetchParticipants($pdo, $sessionId);

$currentUserId  = currentUserId();
$participantIds = array_map(fn($p) => (int) $p['user_id'], $pesertaLobby);

$isHost    = ($currentUserId !== null && $currentUserId === (int) $dbSession['host_user_id']);
$isMember  = (!$isHost && $currentUserId !== null && in_array($currentUserId, $participantIds, true));
$isVisitor = (!$isHost && !$isMember);
if (!$isHost) {
  $initialTab = 'lobby';
}

$savedRules = [];
if (!empty($dbSession['rules_json'])) {
  $decodedRules = json_decode((string) $dbSession['rules_json'], true);
  if (is_array($decodedRules)) {
    $savedRules = array_values(array_filter(array_map('strval', $decodedRules)));
  }
}

$savedSpecs = [];
if (!empty($dbSession['specs_json'])) {
  $decodedSpecs = json_decode((string) $dbSession['specs_json'], true);
  if (is_array($decodedSpecs)) {
    $savedSpecs = $decodedSpecs;
  }
}

$standardMaxSlot = mabarGameCapacity($selectedGame, $isOlahraga ? 'olahraga' : 'esports');
$totalSlot       = (int) $dbSession['max_slot'];
$filledSlot      = count($pesertaLobby);
$isFull          = $filledSlot >= $totalSlot;

$sesi = [
  'id'          => $sessionId,
  'slug'        => $sessionSlug,
  'nama'        => (string) $dbSession['title'],
  'game'        => $selectedGame,
  'kota'        => (string) ($dbSession['place_label'] ?: $dbSession['city']),
  'deskripsi'   => (string) ($dbSession['description'] ?? ''),
  'total_slot'  => $totalSlot,
  'terisi_slot' => $filledSlot,
  'jam_mulai'   => (string) ($dbSession['start_time'] ?: '19:00'),
  'jam_selesai' => (string) ($dbSession['end_time'] ?: '22:00'),
  'host'        => (string) ($dbSession['host_name'] ?: 'Host'),
  'leader'      => (string) ($dbSession['host_name'] ?: 'Host'),
  'join_rule'   => (string) ($dbSession['join_rule'] ?: 'Ask For Join'),
  'mode'        => (string) ($dbSession['mode'] ?: ($isOlahraga ? 'Fun Game' : 'Ranked')),
  'aturan_sesi' => $savedRules,
];

$gameIcon = $basePath . mabarGameIcon($selectedGame, $isOlahraga ? 'olahraga' : 'esports');

$currentGameFields = $GAME_FIELDS[$selectedGame] ?? [];
if (empty($currentGameFields)) {
  $currentGameFields = [
    ['name' => 'level', 'label' => 'Level / Rank', 'type' => 'select', 'icon' => 'ph-medal', 'options' => ['Semua Level', 'Pemula', 'Menengah', 'Pro']],
    ['name' => 'venue', 'label' => 'Server / Lokasi', 'type' => 'text', 'icon' => 'ph-map-pin', 'placeholder' => 'Server atau Venue Lapangan'],
    ['name' => 'kebutuhan_slot', 'label' => 'Kebutuhan Slot', 'type' => 'number', 'icon' => 'ph-users', 'min' => 2, 'max' => max($standardMaxSlot, $totalSlot), 'placeholder' => '2 – ' . $standardMaxSlot],
  ];
}

$initialReadyCount = 0;
foreach ($pesertaLobby as $p) {
  if (!empty($p['isReady'])) {
    $initialReadyCount++;
  }
}
?>

<!-- MAIN CONTENT (CENTER COLUMN) -->
<main
  id="sesi-main-container"
  data-theme-color="<?= htmlspecialchars($themeColor, ENT_QUOTES, 'UTF-8') ?>"
  data-session-slug="<?= htmlspecialchars($sessionSlug, ENT_QUOTES, 'UTF-8') ?>"
  data-session-id="<?= $sessionId ?>"
  data-is-host="<?= $isHost ? '1' : '0' ?>"
  data-is-member="<?= $isMember ? '1' : '0' ?>"
  class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-5 py-6"
>

  <!-- TOP BAR: NAVIGASI KEMBALI -->
  <div class="flex items-center justify-between gap-4 shrink-0">
    <a href="<?= $basePath ?>index.php" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-border bg-card hover:bg-card-alt text-muted hover:text-white text-xs font-semibold transition-all hover:border-muted group shadow-sm">
      <i class="ph-bold ph-arrow-left text-sm transition-transform group-hover:-translate-x-1"></i>
      <span>Kembali ke Cari Sesi</span>
    </a>
  </div>

  <!-- 1. HEADER SESI CARD -->
  <article class="relative shrink-0 overflow-hidden rounded-xl border p-6 shadow-xl"
           style="background-color: #0d1622; border-color: <?= $themeColor ?>; box-shadow: 0 4px 24px <?= $themeGlow ?>;">

    <!-- Background Watermark: Logo Mabar Lokal -->
    <div style="position: absolute; right: 155px; top: 50%; transform: translateY(-50%); opacity: 0.10; pointer-events: none; user-select: none; z-index: 0;" class="hidden sm:block">
      <img src="<?= $basePath ?>asset/icon/Logo1.svg" alt="Mabar Lokal Watermark" style="width: 160px; height: 160px; object-fit: contain; display: block;" />
    </div>

    <!-- Content Row -->
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 w-full z-10">

      <!-- Left: Logo Game (84x84px) + Info Sesi -->
      <div class="flex items-center gap-5 min-w-0 flex-1">

        <div class="shrink-0 rounded-xl border p-2 flex items-center justify-center shadow-lg"
             style="width: 84px; height: 84px; min-width: 84px; min-height: 84px; max-width: 84px; max-height: 84px; background-color: #080f18; border-color: <?= $themeColor ?>; overflow: hidden;">
          <img src="<?= htmlspecialchars($gameIcon, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($sesi['game'], ENT_QUOTES, 'UTF-8') ?>"
               style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" />
        </div>

        <div class="min-w-0 flex-1">
          <!-- Kategori Chip & Nama Game -->
          <div class="flex items-center gap-2 mb-1.5 flex-wrap">
            <span class="rounded px-2 py-0.5 text-[9px] font-extrabold tracking-widest uppercase shadow-sm" style="background-color: <?= $themeColor ?>; color: <?= $themeTextOnBg ?>;">
              <?= htmlspecialchars($kategoriLabel, ENT_QUOTES, 'UTF-8') ?>
            </span>
            <span class="text-[12px] font-semibold text-muted tracking-wide">
              <?= htmlspecialchars($sesi['game'], ENT_QUOTES, 'UTF-8') ?>
            </span>
          </div>

          <!-- Judul Sesi -->
          <h1 id="header-nama-display" class="text-xl sm:text-[24px] font-bold tracking-tight text-white leading-tight truncate">
            <?= htmlspecialchars($sesi['nama'], ENT_QUOTES, 'UTF-8') ?>
          </h1>

          <!-- Lokasi / Kota -->
          <div class="flex items-center gap-1.5 text-xs text-muted font-medium mt-1 mb-1.5">
            <i class="ph-bold ph-map-pin text-sm" style="color: <?= $themeColor ?>;"></i>
            <span id="header-kota-display"><?= htmlspecialchars($sesi['kota'], ENT_QUOTES, 'UTF-8') ?></span>
          </div>

          <!-- Deskripsi Singkat -->
          <p id="header-desc-display" class="text-xs leading-relaxed text-muted line-clamp-2 max-w-xl">
            <?= $sesi['deskripsi'] !== '' ? htmlspecialchars($sesi['deskripsi'], ENT_QUOTES, 'UTF-8') : 'Tidak ada deskripsi.' ?>
          </p>
        </div>
      </div>

      <!-- Right: Slot Counter Dinamis & Tombol Aksi Sesuai Peran Pengguna -->
      <div class="flex flex-col items-center sm:items-end justify-center shrink-0 sm:pl-4 self-center">
        <div class="text-3xl sm:text-[34px] font-bold leading-none text-white mb-3">
          <span id="header-slot-terisi"><?= $sesi['terisi_slot'] ?></span><span class="text-2xl font-normal text-muted">/<span id="header-slot-total"><?= $sesi['total_slot'] ?></span></span>
        </div>

        <?php if ($isHost): ?>
          <button
            type="button"
            id="btn-keluar-sesi"
            data-action="delete"
            class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-red text-red bg-red/10 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] transition-all active:scale-95 cursor-pointer"
          >
            Batalkan Sesi
          </button>
        <?php elseif ($isMember): ?>
          <button
            type="button"
            id="btn-keluar-sesi"
            data-action="leave"
            class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-red text-red bg-red/10 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] transition-all active:scale-95 cursor-pointer"
          >
            Keluar Sesi
          </button>
        <?php else: ?>
          <?php
            $gabungBtnClass = $isFull
              ? 'border border-border text-muted bg-card-alt opacity-70 cursor-not-allowed'
              : ($isOlahraga
                  ? 'border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] cursor-pointer'
                  : 'border border-red text-red bg-red/10 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] cursor-pointer');
          ?>
          <button
            type="button"
            id="btn-gabung-sesi"
            <?= $isFull ? 'disabled' : '' ?>
            class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide transition-all active:scale-95 <?= $gabungBtnClass ?>"
          >
            <?= $isFull ? 'Slot Penuh' : 'Gabung Sesi' ?>
          </button>
        <?php endif; ?>
      </div>

    </div>
  </article>

  <!-- 2. SUB-TABS NAVIGATION (Hanya untuk Host / Pembuat Sesi) -->
  <?php if ($isHost): ?>
  <div style="display: flex; gap: 32px; border-bottom: 1px solid #232d39; padding-bottom: 0px;">
    <button
      id="tab-btn-lobby"
      type="button"
      style="padding-bottom: 10px; font-size: 13px; font-weight: 600; cursor: pointer; background: transparent; border: none; border-bottom: 2px solid <?= $initialTab === 'lobby' ? $themeColor : 'transparent' ?>; color: <?= $initialTab === 'lobby' ? '#ffffff' : '#9ba5b4' ?>; transition: all 0.2s;"
    >
      Lobby
    </button>
    <button
      id="tab-btn-detail"
      type="button"
      style="padding-bottom: 10px; font-size: 13px; font-weight: 600; cursor: pointer; background: transparent; border: none; border-bottom: 2px solid <?= $initialTab === 'detail' ? $themeColor : 'transparent' ?>; color: <?= $initialTab === 'detail' ? '#ffffff' : '#9ba5b4' ?>; transition: all 0.2s;"
    >
      Pengaturan Sesi
    </button>
  </div>
  <?php endif; ?>

  <!-- 3. MODULAR KOMPONEN SESI -->
  <?php require __DIR__ . '/bagian_lobby.php'; ?>
  <?php if ($isHost) require __DIR__ . '/bagian_pengaturan.php'; ?>
  <?php require_once __DIR__ . '/../../include/modal_profil_pemain.php'; ?>

</main>

<!-- Skrip Interaktif Sesi -->
<script src="<?= $basePath ?>asset/js/halaman_sesi.js?v=<?= filemtime(__DIR__ . '/../../asset/js/halaman_sesi.js') ?>"></script>
