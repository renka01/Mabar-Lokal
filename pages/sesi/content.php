<?php
/**
 * pages/sesi/content.php — Halaman Induk Lobby & Detail Sesi (Modular & Clean)
 * Menyatukan header sesi, navigasi sub-tab, bagian lobby, dan bagian pengaturan.
 */

require_once __DIR__ . '/../../config/gameAndolaraga_fields.php';

// Parameter Query
$paramNama = isset($_GET['nama']) && trim($_GET['nama']) !== '' ? trim($_GET['nama']) : null;
$paramGame = isset($_GET['game']) && trim($_GET['game']) !== '' ? trim($_GET['game']) : null;
$paramKota = isset($_GET['kota']) && trim($_GET['kota']) !== '' ? trim($_GET['kota']) : null;
$initialTab = isset($_GET['tab']) && $_GET['tab'] === 'detail' ? 'detail' : (isset($_GET['created']) ? 'detail' : 'lobby');

// Daftar Game Olahraga
$olahragaList = ['Futsal', 'Basket', 'Badminton', 'Bulu Tangkis', 'Sepak Bola', 'Tenis', 'Voli', 'Biliar', 'Billiard', 'Padel', 'Lari'];
$selectedGame = $paramGame ?? 'Valorant';
$isOlahraga   = in_array($selectedGame, $olahragaList, true);
if (isset($_GET['kategori']) && strtolower($_GET['kategori']) === 'olahraga') {
  $isOlahraga = true;
}
$kategoriLabel = $isOlahraga ? 'Olahraga' : 'Esports';
$themeColor    = $isOlahraga ? '#19d66b' : '#ef233c';
$themeTextOnBg = $isOlahraga ? '#06160d' : '#ffffff';
$themeGlow     = $isOlahraga ? 'rgba(25, 214, 107, 0.15)' : 'rgba(239, 35, 60, 0.15)';

// Deteksi Peran Pengguna (Role):
// 1. Host: pembuat sesi (ada parameter ?created=1)
// 2. Member: anggota yang bergabung (ada parameter ?joined=1)
// 3. Visitor / Pelihat: pengguna yang hanya melihat detail (default)
$isHost    = isset($_GET['created']) && $_GET['created'] == '1';
$isMember  = isset($_GET['joined']) && $_GET['joined'] == '1';
$isVisitor = !$isHost && !$isMember;

// Kapasitas Slot Standar Per Game & Olahraga
$gameCapacityMap = [
  // Esports
  'Mobile Legends'    => 5,
  'Honor of Kings'    => 5,
  'Valorant'          => 5,
  'PUBG Mobile'       => 4,
  'PUBG PC'           => 4,
  'Dota 2'            => 5,
  'Counter-Strike 2'  => 5,
  'League of Legends' => 5,

  // Olahraga
  'Futsal'            => 10,
  'Basket'            => 10,
  'Badminton'         => 4,
  'Bulu Tangkis'      => 4,
  'Sepak Bola'        => 22,
  'Tenis'             => 4,
  'Voli'              => 12,
  'Biliar'            => 4,
  'Billiard'          => 4,
  'Padel'             => 4,
  'Lari'              => 8,
];
$standardMaxSlot = $gameCapacityMap[$selectedGame] ?? 5;

// Tangkap slot dari input user
$paramSlot = isset($_GET['slot']) && is_numeric($_GET['slot']) ? (int)$_GET['slot'] : (isset($_GET['kebutuhan_slot']) && is_numeric($_GET['kebutuhan_slot']) ? (int)$_GET['kebutuhan_slot'] : (isset($_GET['jumlah_pemain']) && is_numeric($_GET['jumlah_pemain']) ? (int)$_GET['jumlah_pemain'] : null));

// Validasi range agar slot tidak minus dan tidak melebihi batas kapasitas game
$initialSlot = $paramSlot ?? $standardMaxSlot;
$initialSlot = max(2, min($initialSlot, $standardMaxSlot));

// Slot terisi: Visitor belum mengisi slot, Member/Host sudah mengisi slot
$defaultTerisiSlot = $isVisitor ? min($initialSlot - 1, 3) : min($initialSlot, 4);

// Setup Mock Sesi Default sesuai Kategori
if ($isOlahraga) {
  $defaultNama      = $paramNama ?? ($selectedGame . ' Malam Santai');
  $defaultDeskripsi = 'Mabar olahraga fun bareng, jaga sportivitas, keringatan sehat & no baperan!';
  $defaultMode      = in_array($selectedGame, ['Futsal', 'Basket'], true) ? '5v5' : 'Fun Game';
} else {
  $defaultNama      = $paramNama ?? ($selectedGame === 'Valorant' ? 'Ranked Duelist' : ($selectedGame . ' Push Rank'));
  $defaultDeskripsi = 'Push rank sampe tuntas. Komunikasi wajib mic, playstyle menyesuaikan tim, no toxic & have fun!';
  $defaultMode      = 'Ranked';
}

$sesi = [
  'id'          => 1,
  'nama'        => $defaultNama,
  'game'        => $selectedGame,
  'kota'        => $paramKota ?? 'Malang',
  'deskripsi'   => $defaultDeskripsi,
  'total_slot'  => $initialSlot,
  'terisi_slot' => $defaultTerisiSlot,
  'jam_mulai'   => '15:00',
  'jam_selesai' => '20:00',
  'host'        => $isHost ? 'Wawan (You)' : 'Rian Pratama',
  'leader'      => $isHost ? 'Wawan (You)' : 'Rian Pratama',
  'join_rule'   => 'Ask For Join',
  'mode'        => $defaultMode,
  'aturan_sesi' => [
    'No Toxic & No Blaming',
    'AFK / Cancel Sepihak Kick Langsung',
    'Sportif & Saling Menghargai',
    'Wajib Konfirmasi Kehadiran',
  ],
];

// Mapping Ikon Game & Olahraga Lengkap
$gameIconMap = [
  // Esports
  'Mobile Legends'    => $basePath . 'asset/icon/ml-logo.jpeg',
  'Valorant'          => $basePath . 'asset/icon/Valoran1.svg',
  'PUBG Mobile'       => $basePath . 'asset/icon/pubgm-logo.jpeg',
  'PUBG PC'           => $basePath . 'asset/icon/pubg-pc-logo.jpeg',
  'Dota 2'            => $basePath . 'asset/icon/dota2-logo.jpeg',
  'Counter-Strike 2'  => $basePath . 'asset/icon/cs2-logo.jpeg',
  'League of Legends' => $basePath . 'asset/icon/lol-logo.jpeg',
  'Honor of Kings'    => $basePath . 'asset/icon/hok-logo.png',

  // Olahraga
  'Futsal'            => $basePath . 'asset/icon/Futsal.png',
  'Basket'            => $basePath . 'asset/icon/Basket.png',
  'Badminton'         => $basePath . 'asset/icon/Bulu Tangkis.png',
  'Bulu Tangkis'      => $basePath . 'asset/icon/Bulu Tangkis.png',
  'Sepak Bola'        => $basePath . 'asset/icon/sepak-bola.svg',
  'Tenis'             => $basePath . 'asset/icon/tenis.svg',
  'Voli'              => $basePath . 'asset/icon/voli.svg',
  'Biliar'            => $basePath . 'asset/icon/Billiard.png',
  'Billiard'          => $basePath . 'asset/icon/Billiard.png',
  'Padel'             => $basePath . 'asset/icon/padel.png',
  'Lari'              => $basePath . 'asset/icon/lARI.png',
];
$gameIcon = $gameIconMap[$sesi['game']] ?? ($isOlahraga ? ($basePath . 'asset/icon/Futsal.png') : ($basePath . 'asset/icon/Valoran1.svg'));

// Ambil field dinamis untuk game/olahraga yang dipilih dari config
$currentGameFields = $GAME_FIELDS[$sesi['game']] ?? [];
if (empty($currentGameFields)) {
  $currentGameFields = [
    ['name' => 'level', 'label' => 'Level / Rank', 'type' => 'select', 'icon' => 'ph-medal', 'options' => ['Semua Level', 'Pemula', 'Menengah', 'Pro']],
    ['name' => 'venue', 'label' => 'Server / Lokasi', 'type' => 'text', 'icon' => 'ph-map-pin', 'placeholder' => 'Server atau Venue Lapangan'],
    ['name' => 'kebutuhan_slot', 'label' => 'Kebutuhan Slot', 'type' => 'number', 'icon' => 'ph-users', 'min' => 2, 'max' => $standardMaxSlot, 'placeholder' => '2 – ' . $standardMaxSlot],
  ];
}

// Nilai default untuk field dinamis
$mockFieldValues = [
  'rank'           => 'Diamond',
  'agent_role'     => 'Duelist',
  'server'         => 'Asia (SG)',
  'kebutuhan_slot' => $sesi['total_slot'],
  'role'           => 'Assassin',
  'server_id'      => '123456789 (1234)',
  'tier'           => 'Diamond',
  'squad_type'     => 'Squad',
  'map'            => 'Erangel',
  'level'          => 'Menengah',
  'venue'          => 'GOR Serbaguna Malang',
  'jumlah_pemain'  => $sesi['total_slot'],
  'tipe_permainan' => '5 on 5',
  'tipe'           => 'Ganda (Double)',
  'format'         => '11 vs 11',
  'game_type'      => '9-Ball',
];

// Mock Peserta Lobby Berdasarkan Peran Pengguna
if ($isOlahraga) {
  if ($isHost) {
    $pesertaLobby = [
      ['nama' => 'Wawan (You)', 'rank' => 'Menengah', 'isReady' => true,  'isLeader' => true,  'avatar' => null],
      ['nama' => 'Dimas',       'rank' => 'Semi-Pro', 'isReady' => true,  'isLeader' => false, 'avatar' => null],
      ['nama' => 'Rehan',       'rank' => 'Menengah', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
      ['nama' => 'Fajar P.',    'rank' => 'Menengah', 'isReady' => true,  'isLeader' => false, 'avatar' => null],
    ];
  } elseif ($isMember) {
    $pesertaLobby = [
      ['nama' => 'Rian Pratama', 'rank' => 'Semi-Pro', 'isReady' => true,  'isLeader' => true,  'avatar' => null],
      ['nama' => 'Dimas',        'rank' => 'Menengah', 'isReady' => true,  'isLeader' => false, 'avatar' => null],
      ['nama' => 'Rehan',        'rank' => 'Menengah', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
      ['nama' => 'Wawan (You)',  'rank' => 'Pemula',   'isReady' => false, 'isLeader' => false, 'avatar' => null],
    ];
  } else { // Visitor
    $pesertaLobby = [
      ['nama' => 'Rian Pratama', 'rank' => 'Semi-Pro', 'isReady' => true,  'isLeader' => true,  'avatar' => null],
      ['nama' => 'Dimas',        'rank' => 'Menengah', 'isReady' => true,  'isLeader' => false, 'avatar' => null],
      ['nama' => 'Rehan',        'rank' => 'Menengah', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
    ];
  }
} else {
  if ($isHost) {
    $pesertaLobby = [
      ['nama' => 'Wawan (You)', 'rank' => 'Gold III', 'isReady' => true,  'isLeader' => true,  'avatar' => null],
      ['nama' => 'Dimas',       'rank' => 'Gold II',  'isReady' => true,  'isLeader' => false, 'avatar' => null],
      ['nama' => 'Rehan',       'rank' => 'Plat III', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
      ['nama' => 'XxxKillerxx', 'rank' => 'Iron I',   'isReady' => true,  'isLeader' => false, 'avatar' => null],
    ];
  } elseif ($isMember) {
    $pesertaLobby = [
      ['nama' => 'Rian Pratama', 'rank' => 'Diamond I','isReady' => true,  'isLeader' => true,  'avatar' => null],
      ['nama' => 'Dimas',        'rank' => 'Plat II',  'isReady' => true,  'isLeader' => false, 'avatar' => null],
      ['nama' => 'Rehan',        'rank' => 'Plat III', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
      ['nama' => 'Wawan (You)',  'rank' => 'Gold III', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
    ];
  } else { // Visitor
    $pesertaLobby = [
      ['nama' => 'Rian Pratama', 'rank' => 'Diamond I','isReady' => true,  'isLeader' => true,  'avatar' => null],
      ['nama' => 'Dimas',        'rank' => 'Plat II',  'isReady' => true,  'isLeader' => false, 'avatar' => null],
      ['nama' => 'Rehan',        'rank' => 'Plat III', 'isReady' => false, 'isLeader' => false, 'avatar' => null],
    ];
  }
}

$initialReadyCount = 0;
foreach ($pesertaLobby as $p) {
  if (!empty($p['isReady'])) $initialReadyCount++;
}
?>

<!-- MAIN CONTENT (CENTER COLUMN) -->
<main id="sesi-main-container" data-theme-color="<?= htmlspecialchars($themeColor) ?>" class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6">

  <!-- ════════════════════════════════════════════════════════════
       1. HEADER SESI CARD (Clean Aesthetic, Tanpa Watermark Nyaru)
  ════════════════════════════════════════════════════════════ -->
  <article class="relative shrink-0 overflow-hidden rounded-xl border p-6 shadow-xl"
           style="background-color: #0d1622; border-color: <?= $themeColor ?>; box-shadow: 0 4px 24px <?= $themeGlow ?>;">

    <!-- Background Watermark: Logo Mabar Lokal -->
    <div style="position: absolute; right: 155px; top: 50%; transform: translateY(-50%); opacity: 0.10; pointer-events-none; user-select: none; z-index: 0;" class="hidden sm:block">
      <img src="<?= $basePath ?>asset/icon/Logo1.svg" alt="Mabar Lokal Watermark" style="width: 160px; height: 160px; object-fit: contain; display: block;" />
    </div>

    <!-- Content Row -->
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 w-full z-10">
      
      <!-- Left: Logo Game (84x84px) + Info Sesi -->
      <div class="flex items-center gap-5 min-w-0 flex-1">
        
        <div class="shrink-0 rounded-xl border p-2 flex items-center justify-center shadow-lg"
             style="width: 84px; height: 84px; min-width: 84px; min-height: 84px; max-width: 84px; max-height: 84px; background-color: #080f18; border-color: <?= $themeColor ?>; overflow: hidden;">
          <img src="<?= $gameIcon ?>" alt="<?= htmlspecialchars($sesi['game']) ?>"
               style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" />
        </div>

        <div class="min-w-0 flex-1">
          <!-- Kategori Chip & Nama Game -->
          <div class="flex items-center gap-2 mb-1.5 flex-wrap">
            <span class="rounded px-2 py-0.5 text-[9px] font-extrabold tracking-widest uppercase shadow-sm" style="background-color: <?= $themeColor ?>; color: <?= $themeTextOnBg ?>;">
              <?= htmlspecialchars($kategoriLabel) ?>
            </span>
            <span class="text-[12px] font-semibold text-muted tracking-wide">
              <?= htmlspecialchars($sesi['game']) ?>
            </span>
          </div>

          <!-- Judul Sesi -->
          <h1 id="header-nama-display" class="text-xl sm:text-[24px] font-bold tracking-tight text-white leading-tight truncate">
            <?= htmlspecialchars($sesi['nama']) ?>
          </h1>

          <!-- Lokasi / Kota -->
          <div class="flex items-center gap-1.5 text-xs text-muted font-medium mt-1 mb-1.5">
            <i class="ph-bold ph-map-pin text-sm" style="color: <?= $themeColor ?>;"></i>
            <span id="header-kota-display"><?= htmlspecialchars($sesi['kota']) ?></span>
          </div>

          <!-- Deskripsi Singkat -->
          <p id="header-desc-display" class="text-xs leading-relaxed text-muted line-clamp-2 max-w-xl">
            <?= htmlspecialchars($sesi['deskripsi']) ?>
          </p>
        </div>
      </div>

      <!-- Right: Slot Counter Dinamis & Tombol Aksi Sesuai Peran Pengguna -->
      <div class="flex flex-col items-center sm:items-end justify-center shrink-0 sm:pl-4 self-center">
        <div class="text-3xl sm:text-[34px] font-bold leading-none text-white mb-3">
          <span id="header-slot-terisi"><?= $sesi['terisi_slot'] ?></span><span class="text-2xl font-normal text-muted">/<span id="header-slot-total"><?= $sesi['total_slot'] ?></span></span>
        </div>

        <?php if ($isHost): ?>
          <button id="btn-keluar-sesi" class="inline-flex items-center justify-center rounded-md px-5 py-2.5 text-xs sm:text-[13px] font-bold text-red border border-red/40 bg-red/10 hover:bg-red hover:text-white transition-all shadow-sm active:scale-95 cursor-pointer">
            Batalkan Sesi
          </button>
        <?php elseif ($isMember): ?>
          <a id="btn-keluar-sesi" href="?<?= http_build_query(array_merge($_GET, ['joined' => 0])) ?>" class="inline-flex items-center justify-center rounded-md px-5 py-2.5 text-xs sm:text-[13px] font-bold text-red border border-red/40 bg-red/10 hover:bg-red hover:text-white transition-all shadow-sm active:scale-95 cursor-pointer">
            Keluar Sesi
          </a>
        <?php else: ?>
          <a id="btn-gabung-sesi" href="?<?= http_build_query(array_merge($_GET, ['joined' => 1])) ?>" class="inline-flex items-center justify-center rounded-md px-6 py-2.5 text-xs sm:text-[13px] font-bold transition-all shadow-md active:scale-95 cursor-pointer" style="background-color: <?= $themeColor ?>; color: <?= $themeTextOnBg ?>;">
            Gabung Sesi
          </a>
        <?php endif; ?>
      </div>

    </div>
  </article>

  <!-- ════════════════════════════════════════════════════════════
       2. SUB-TABS NAVIGATION (Hanya untuk Host / Pembuat Sesi)
  ════════════════════════════════════════════════════════════ -->
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

  <!-- ════════════════════════════════════════════════════════════
       3. MODULAR KOMPONEN SESI (Lobby untuk Semua, Pengaturan untuk Host)
  ════════════════════════════════════════════════════════════ -->
  <?php require __DIR__ . '/bagian_lobby.php'; ?>
  <?php if ($isHost) require __DIR__ . '/bagian_pengaturan.php'; ?>
  <?php require_once __DIR__ . '/../../include/modal_profil_pemain.php'; ?>

</main>

<!-- Skrip Interaktif Sesi -->
<script src="<?= $basePath ?>asset/js/halaman_sesi.js"></script>
