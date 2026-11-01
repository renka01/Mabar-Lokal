<?php
$pageTitle  = 'Detail Turnamen';
$basePath   = '../../';
$activePage = 'tournaments';
require_once __DIR__ . '/../../include/header.php';
require_once __DIR__ . '/../../config/data_turnamen.php';

$trnId    = $_GET['id'] ?? 'trn-val-01';
$turnamen = getTurnamenById($trnId);

if (!$turnamen) {
    $paramNama  = $_GET['nama'] ?? 'Turnamen Komunitas';
    $paramGame  = $_GET['game'] ?? 'Valorant';
    $paramPrize = $_GET['prize'] ?? 'Rp5.000.000';
    $paramKota    = $_GET['kota'] ?? 'Malang';
    $paramKat     = $_GET['kategori'] ?? 'esports';
    $paramTanggal = $_GET['tanggal'] ?? 'Pekan Ini';
    $isOlahraga   = $paramKat === 'olahraga';

    $turnamen = [
        'id'            => $trnId,
        'judul'         => $paramNama,
        'game'          => $paramGame,
        'kategori'      => $paramKat,
        'status'        => 'AKAN DATANG',
        'statusBadge'   => 'Pendaftaran Buka',
        'statusType'    => 'upcoming',
        'gameIcon'      => $isOlahraga ? 'asset/icon/Futsal.png' : 'asset/icon/Valoran1.svg',
        'kota'          => $paramKota,
        'lokasi'        => $paramKota . ($isOlahraga ? ' • Lapangan Olahraga' : ' • Online Discord Server'),
        'tanggal'       => $paramTanggal,
        'prize'         => $paramPrize,
        'tim'           => '8 / 16 Tim',
        'biaya'         => 'Gratis',
        'format'        => 'Single Elimination (BO3)',
        'penyelenggara' => 'Komunitas Mabar Lokal',
        'deskripsi'     => 'Turnamen resmi komunitas Mabar Lokal dengan bagan eliminasi single elimination standard.',
        'distribusi'    => [
            'Juara 1' => '60% Total Hadiah + Trophy',
            'Juara 2' => '30% Total Hadiah + Medali',
            'Juara 3' => '10% Total Hadiah',
        ],
    ];
}

$isOlahraga = ($turnamen['kategori'] ?? '') === 'olahraga';
$themeColor = $isOlahraga ? '#19d66b' : '#ef233c';
$themeGlow  = $isOlahraga ? 'rgba(25, 214, 107, 0.15)' : 'rgba(239, 35, 60, 0.15)';

// Deteksi Role Pengguna secara Otomatis (Tanpa Switcher Manual)
// 1. Host: Pembuat sesi turnamen (?created=1 atau role=host)
// 2. Member: Anggota terdaftar (?joined=1 atau role=member)
// 3. Visitor: Penonton umum / calon pendaftar (default)
$isHost    = (isset($_GET['created']) && $_GET['created'] == '1') || (isset($_GET['role']) && $_GET['role'] === 'host');
$isMember  = (isset($_GET['joined']) && $_GET['joined'] == '1') || (isset($_GET['role']) && $_GET['role'] === 'member');
$isVisitor = !$isHost && !$isMember;
$activeRole = $isHost ? 'host' : ($isMember ? 'member' : 'visitor');

// Poster default fallback
$posterSrc = $basePath . htmlspecialchars($turnamen['gameIcon']);
?>

<div class="flex flex-1 overflow-hidden w-full max-w-[1600px] mx-auto px-4 lg:px-8 gap-6" id="tournament-detail-page" data-user-role="<?= htmlspecialchars($activeRole) ?>">
  
  <main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6">
    
    <!-- Top Bar: Navigasi Kembali & Status Pertandingan (Otomatis & Bersih) -->
    <div class="flex items-center justify-between gap-4 shrink-0">
      <a href="index.php" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-border bg-card hover:bg-card-alt text-muted hover:text-white text-xs font-semibold transition-all group shadow-sm">
        <i class="ph-bold ph-arrow-left text-sm transition-transform group-hover:-translate-x-1"></i>
        <span>Kembali ke Turnamen</span>
      </a>

      <!-- Status Badge Otomatis -->
      <div>
        <?php if (($turnamen['statusType'] ?? '') === 'live'): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm" style="background-color: rgba(25, 214, 107, 0.1); border-color: rgba(25, 214, 107, 0.35); color: #19d66b;">
          <span class="w-2 h-2 rounded-full bg-[#19d66b] animate-pulse"></span>
          Turnamen Sedang Berlangsung (Live)
        </span>
        <?php elseif (($turnamen['statusType'] ?? '') === 'done'): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm" style="background-color: rgba(148, 163, 184, 0.1); border-color: rgba(148, 163, 184, 0.3); color: #94a3b8;">
          <i class="ph-bold ph-check-circle"></i>
          Turnamen Selesai (Rekapitulasi)
        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm" style="background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.35); color: #f59e0b;">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          Pendaftaran Terbuka (Slot Tersedia)
        </span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Match Header Card (Menampilkan Poster & Detail Turnamen) -->
    <article
      class="relative shrink-0 overflow-hidden rounded-xl border p-6 shadow-xl"
      style="background-color: #0d1622; border-color: <?= $themeColor ?>; box-shadow: 0 4px 24px <?= $themeGlow ?>;"
    >
      <!-- Background Watermark -->
      <div style="position: absolute; right: 140px; top: 50%; transform: translateY(-50%); opacity: 0.08; pointer-events-none; user-select: none; z-index: 0;" class="hidden sm:block">
        <img src="<?= $basePath ?>asset/icon/Logo1.svg" alt="Mabar Lokal Watermark" style="width: 160px; height: 160px; object-fit: contain; display: block;" />
      </div>

      <!-- Content Row -->
      <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 w-full z-10">
        
        <!-- Left: Tampilan Poster / Banner Turnamen + Info -->
        <div class="flex items-center gap-5 min-w-0 flex-1">
          
          <!-- Poster Thumbnail Frame (Klik untuk Lightbox Penuh) -->
          <div
            id="detail-trn-poster-box"
            class="relative shrink-0 rounded-xl overflow-hidden border border-border group cursor-pointer shadow-lg"
            style="width: 130px; height: 95px; background-color: #080f18; border-color: <?= $themeColor ?>;"
            onclick="bukaModalPoster()"
            title="Klik untuk melihat poster penuh"
          >
            <img
              id="detail-trn-poster-img"
              src="<?= $posterSrc ?>"
              alt="Poster <?= htmlspecialchars($turnamen['judul']) ?>"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1 text-[11px] font-bold text-white">
              <i class="ph-bold ph-arrows-out-simple"></i>
              <span>Poster</span>
            </div>
            <div class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-black/80 text-[8.5px] font-bold text-white uppercase tracking-wider flex items-center gap-1 backdrop-blur-sm">
              <i class="ph-fill ph-image"></i> Poster
            </div>
          </div>

          <div class="min-w-0 flex-1">
            <!-- Kategori Chip & Format -->
            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
              <span
                class="rounded px-2 py-0.5 text-[9px] font-extrabold tracking-widest uppercase shadow-sm text-white"
                style="background-color: <?= $themeColor ?>;"
              >
                <?= $isOlahraga ? 'Olahraga' : 'Esports' ?>
              </span>
              <span class="text-[12px] font-semibold text-muted tracking-wide">
                <?= htmlspecialchars($turnamen['game']) ?>
              </span>
              <span class="text-muted text-[11px]">•</span>
              <span class="text-[11px] font-semibold text-white/80" id="header-trn-format">
                <?= htmlspecialchars($turnamen['format'] ?? 'BO3 Single Elimination') ?>
              </span>

              <?php if ($isHost): ?>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold text-amber-300 bg-amber-500/15 border border-amber-500/30">
                <i class="ph-fill ph-crown"></i> Penyelenggara
              </span>
              <?php elseif ($isMember): ?>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold text-emerald-400 bg-emerald-500/15 border border-emerald-500/30">
                <i class="ph-fill ph-check-circle"></i> Tim Terdaftar
              </span>
              <?php endif; ?>
            </div>

            <!-- Judul Turnamen -->
            <h1 id="header-trn-title" class="text-xl sm:text-[24px] font-bold tracking-tight text-white leading-tight truncate">
              <?= htmlspecialchars($turnamen['judul']) ?>
            </h1>

            <!-- Meta: Penyelenggara, Lokasi, Tanggal -->
            <div class="flex items-center gap-3 text-xs text-muted font-medium mt-1 mb-1 flex-wrap">
              <span class="flex items-center gap-1.5">
                <i class="ph-bold ph-shield-check text-sm" style="color: <?= $themeColor ?>;"></i>
                <span>Penyelenggara: <strong class="text-white font-medium"><?= htmlspecialchars($turnamen['penyelenggara'] ?? 'Mabar Lokal Official') ?></strong></span>
              </span>
              <span>•</span>
              <span class="flex items-center gap-1.5">
                <i class="ph-bold ph-map-pin text-sm" style="color: <?= $themeColor ?>;"></i>
                <span id="header-trn-lokasi"><?= htmlspecialchars($turnamen['kota']) ?> • <?= htmlspecialchars($turnamen['lokasi'] ?? 'Arena Komunitas') ?></span>
              </span>
              <span>•</span>
              <span class="flex items-center gap-1.5 text-white/90">
                <i class="ph-bold ph-calendar text-sm" style="color: <?= $themeColor ?>;"></i>
                <span id="header-trn-tanggal"><?= htmlspecialchars($turnamen['tanggal']) ?></span>
              </span>
            </div>
          </div>
        </div>

        <!-- Right: Prize Pool, Slot & Button Konsisten -->
        <div class="flex flex-col items-center sm:items-end justify-center shrink-0 sm:pl-6 border-t sm:border-t-0 sm:border-l border-border/50 pt-4 sm:pt-0 self-center">
          <div class="text-[10px] text-muted uppercase font-bold tracking-widest mb-0.5">TOTAL HADIAH</div>
          <div id="header-trn-prize" class="text-[26px] sm:text-[30px] font-bold leading-none text-[#F9A826] mb-1.5">
            <?= htmlspecialchars($turnamen['prize']) ?>
          </div>
          <div class="text-xs text-muted mb-3 flex items-center gap-1.5">
            <i class="ph ph-users text-sm"></i>
            <span>Slot: <strong class="text-white"><?= htmlspecialchars($turnamen['tim']) ?></strong></span>
          </div>

          <!-- Tombol Aksi Otomatis Sesuai Peran -->
          <div>
            <?php if ($isHost): ?>
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  onclick="switchDetailTab('pengaturan')"
                  class="btn-trn-action inline-flex items-center justify-center h-10 px-5 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide leading-none transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center cursor-pointer gap-2"
                >
                  <i class="ph-bold ph-note-pencil"></i>
                  <span>Edit Turnamen</span>
                </button>
                <button
                  type="button"
                  onclick="batalTurnamenHost()"
                  class="btn-trn-action inline-flex items-center justify-center h-10 px-3.5 rounded-md border border-border bg-card-alt text-muted hover:border-red hover:text-red text-xs font-semibold leading-none transition-all cursor-pointer"
                  title="Batalkan turnamen ini"
                >
                  <i class="ph-bold ph-trash"></i>
                </button>
              </div>
            <?php elseif ($isMember): ?>
              <div class="flex items-center gap-2">
                <a
                  href="https://discord.gg"
                  target="_blank"
                  class="btn-trn-action inline-flex items-center justify-center h-10 px-5 rounded-md border border-[#5865F2] text-white bg-[#5865F2]/20 text-[13px] font-bold tracking-wide leading-none transition-all active:scale-95 hover:bg-[#5865F2] hover:text-white hover:shadow-[0_0_16px_rgba(88,101,242,0.35)] text-center cursor-pointer gap-2"
                >
                  <i class="ph-bold ph-discord-logo text-base"></i>
                  <span>Lobby Discord</span>
                </a>
              </div>
            <?php else: ?>
              <button
                type="button"
                onclick="switchDetailTab('daftar')"
                class="btn-trn-action inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide leading-none transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center cursor-pointer gap-2"
              >
                <i class="ph-bold ph-ticket text-base"></i>
                <span>Daftar Tim Sekarang</span>
              </button>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </article>

    <!-- Navigation Tabs Otomatis Sesuai Peran Pengguna -->
    <div class="flex gap-6 border-b border-border text-[13px] font-medium text-muted overflow-x-auto hide-scrollbar">
      <button
        type="button"
        id="tab-btn-bracket"
        class="trn-nav-tab active border-b-[3px] border-red text-white py-2 px-1 relative top-[1px] font-semibold whitespace-nowrap cursor-pointer flex items-center gap-2"
        onclick="switchDetailTab('bracket')"
      >
        <i class="ph-bold ph-tree-structure text-base"></i>
        <span>Bagan Pertandingan (Bracket)</span>
      </button>

      <?php if ($isHost): ?>
        <!-- Tab Khusus Host: Kelola Pendaftar & Pengaturan / Edit Informasi & Hadiah -->
        <button
          type="button"
          id="tab-btn-pendaftar"
          class="trn-nav-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer flex items-center gap-2"
          onclick="switchDetailTab('pendaftar')"
        >
          <i class="ph-bold ph-shield-check text-base"></i>
          <span>Kelola Pendaftar</span>
          <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">2</span>
        </button>

        <button
          type="button"
          id="tab-btn-teams"
          class="trn-nav-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer flex items-center gap-2"
          onclick="switchDetailTab('teams')"
        >
          <i class="ph-bold ph-users text-base"></i>
          <span>Tim Peserta (Roster)</span>
        </button>

        <button
          type="button"
          id="tab-btn-pengaturan"
          class="trn-nav-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer flex items-center gap-2"
          onclick="switchDetailTab('pengaturan')"
        >
          <i class="ph-bold ph-note-pencil text-base text-red"></i>
          <span>Pengaturan & Edit Turnamen</span>
        </button>

      <?php else: ?>
        <!-- Tab untuk Member & Visitor -->
        <button
          type="button"
          id="tab-btn-info"
          class="trn-nav-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer flex items-center gap-2"
          onclick="switchDetailTab('info')"
        >
          <i class="ph-bold ph-info text-base"></i>
          <span>Informasi & Hadiah</span>
        </button>

        <button
          type="button"
          id="tab-btn-teams"
          class="trn-nav-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer flex items-center gap-2"
          onclick="switchDetailTab('teams')"
        >
          <i class="ph-bold ph-users text-base"></i>
          <span>Tim Peserta (Roster)</span>
        </button>

        <button
          type="button"
          id="tab-btn-daftar"
          class="trn-nav-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer flex items-center gap-2"
          onclick="switchDetailTab('daftar')"
        >
          <?php if ($isMember): ?>
            <i class="ph-bold ph-check-circle text-base"></i>
            <span>Status Pendaftaran</span>
          <?php else: ?>
            <i class="ph-bold ph-pencil-simple text-base"></i>
            <span>Formulir Pendaftaran</span>
          <?php endif; ?>
        </button>
      <?php endif; ?>
    </div>

    <!-- TAB 1: BAGAN PERTANDINGAN (CHALLONGE STYLE) -->
    <section id="view-tab-bracket" class="trn-tab-content flex flex-col gap-4">
      
      <!-- Generator Bar Bersih (Tanpa Badge Mode Penonton/Admin) -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-lg border border-border bg-card">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-red/10 border border-red/30 text-red text-xl shrink-0">
            <i class="ph-fill ph-tree-structure"></i>
          </div>
          <div>
            <h3 class="text-[14px] font-bold text-white leading-tight">Bagan Sistem Gugur (Challonge Engine)</h3>
            <p class="text-[11.5px] text-muted mt-0.5">
              <?php if ($isHost): ?>
                Klik baris tim pada bagan untuk menentukan pemenang yang lolos ke babak selanjutnya.
              <?php else: ?>
                Bagan turnamen resmi diatur langsung oleh panitia penyelenggara secara real-time.
              <?php endif; ?>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
          <div class="flex items-center gap-2">
            <span class="text-xs text-muted font-medium">Kapasitas:</span>
            <select
              id="bracket-team-count-select"
              class="h-9 rounded-md border border-border bg-card-alt px-3 text-xs font-semibold text-white outline-none focus:border-red cursor-pointer"
              onchange="regenerateBracket(parseInt(this.value, 10))"
              <?= $isHost ? '' : 'disabled title="Hanya host yang dapat mengubah struktur bagan"' ?>
            >
              <option value="4">4 Tim (Semifinal)</option>
              <option value="8" selected>8 Tim (Perempat Final)</option>
              <option value="16">16 Tim (Babak 16 Besar)</option>
            </select>
          </div>

          <?php if ($isHost): ?>
          <button
            type="button"
            onclick="shuffleBracketTeams()"
            class="h-9 px-3.5 rounded-md border border-border bg-card-alt text-muted hover:text-white text-xs font-semibold transition-all inline-flex items-center gap-1.5 cursor-pointer"
            title="Acak posisi tim dalam bagan"
          >
            <i class="ph-bold ph-shuffle"></i>
            <span>Acak Tim</span>
          </button>

          <button
            type="button"
            onclick="resetBracketScores()"
            class="h-9 px-3.5 rounded-md border border-border bg-card-alt text-muted hover:text-white text-xs font-semibold transition-all inline-flex items-center gap-1.5 cursor-pointer"
            title="Reset seluruh skor pertandingan"
          >
            <i class="ph-bold ph-arrows-counter-clockwise"></i>
            <span>Reset Skor</span>
          </button>
          <?php endif; ?>
        </div>
      </div>

      <!-- Challonge Interactive Bracket Board -->
      <div class="challonge-container shadow-xl">
        <div id="challonge-board" class="challonge-bracket-wrapper">
          <!-- Injected dynamically by turnamen.js -->
        </div>
      </div>
    </section>

    <!-- TAB 2 (MEMBER & VISITOR): INFORMASI & HADIAH + SHOWCASE POSTER -->
    <?php if (!$isHost): ?>
    <section id="view-tab-info" class="trn-tab-content hidden flex flex-col gap-5">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Showcase Poster Resmi Turnamen -->
        <div class="p-5 rounded-lg border border-border bg-card flex flex-col gap-3 md:col-span-2">
          <div class="flex items-center justify-between border-b border-border/50 pb-3">
            <h3 class="text-[14px] font-bold text-white uppercase tracking-wider flex items-center gap-2">
              <i class="ph-bold ph-image text-red text-base"></i>
              <span>Poster Resmi Turnamen</span>
            </h3>
            <button
              type="button"
              onclick="bukaModalPoster()"
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-red hover:underline cursor-pointer"
            >
              <i class="ph-bold ph-arrows-out-simple"></i>
              <span>Lihat Ukuran Penuh</span>
            </button>
          </div>
          
          <div class="w-full rounded-xl overflow-hidden bg-[#080f18] border border-border/50 relative flex items-center justify-center p-4 cursor-pointer group" onclick="bukaModalPoster()">
            <img
              id="full-poster-preview-img"
              src="<?= $posterSrc ?>"
              alt="Poster Resmi <?= htmlspecialchars($turnamen['judul']) ?>"
              class="max-h-[300px] w-auto object-contain rounded-lg shadow-md transition-transform duration-300 group-hover:scale-102"
            />
          </div>
        </div>

        <!-- Format & Aturan Tanding -->
        <div class="p-5 rounded-lg border border-border bg-card flex flex-col gap-3">
          <h3 class="text-[14px] font-bold text-white uppercase tracking-wider flex items-center gap-2">
            <i class="ph-bold ph-game-controller text-red text-base"></i>
            <span>Format Pertandingan</span>
          </h3>
          <p class="text-[12.5px] text-muted leading-relaxed" id="info-tab-desc">
            <?= htmlspecialchars($turnamen['deskripsi'] ?? 'Turnamen resmi komunitas Mabar Lokal dengan bagan eliminasi single elimination standard.') ?>
          </p>
          <ul class="text-[12px] text-muted flex flex-col gap-2.5 mt-2 pt-3 border-t border-border/50">
            <li class="flex items-center gap-2">
              <i class="ph-bold ph-check text-emerald-400"></i>
              <span>Sistem Single Elimination (Gugur Tunggal Langsung)</span>
            </li>
            <li class="flex items-center gap-2">
              <i class="ph-bold ph-check text-emerald-400"></i>
              <span>Babak Eliminasi: Best of 3 (BO3)</span>
            </li>
            <li class="flex items-center gap-2">
              <i class="ph-bold ph-check text-emerald-400"></i>
              <span>Grand Final: Best of 5 (BO5) Penentuan Juara</span>
            </li>
            <li class="flex items-center gap-2">
              <i class="ph-bold ph-check text-emerald-400"></i>
              <span>Map Pick & Ban standard kompetitif resmi</span>
            </li>
          </ul>
        </div>

        <!-- Distribusi Hadiah (Prize Pool) -->
        <div class="p-5 rounded-lg border border-border bg-card flex flex-col gap-3">
          <h3 class="text-[14px] font-bold text-white uppercase tracking-wider flex items-center gap-2">
            <i class="ph-fill ph-trophy text-[#F9A826] text-base"></i>
            <span>Distribusi Hadiah (Prize Pool)</span>
          </h3>

          <div class="flex flex-col gap-3 mt-1">
            <div class="p-4 rounded-lg border border-border bg-card-alt flex items-center justify-between gap-4">
              <div class="flex items-center gap-3.5 min-w-0">
                <div class="distribusi-rank-circle rank-1" style="width:32px; height:32px; min-width:32px; min-height:32px; border-radius:50%; flex-shrink:0;">1</div>
                <div class="min-w-0">
                  <div class="text-[13px] font-bold text-white">Juara 1 (Champion)</div>
                  <div class="text-[11px] text-muted truncate">Trophy Fisik Resmi + E-Certificate</div>
                </div>
              </div>
              <div class="text-[15px] font-extrabold text-[#F9A826] shrink-0">60% Total Hadiah</div>
            </div>

            <div class="p-4 rounded-lg border border-border bg-card-alt flex items-center justify-between gap-4">
              <div class="flex items-center gap-3.5 min-w-0">
                <div class="distribusi-rank-circle rank-2" style="width:32px; height:32px; min-width:32px; min-height:32px; border-radius:50%; flex-shrink:0;">2</div>
                <div class="min-w-0">
                  <div class="text-[13px] font-bold text-white">Juara 2 (Runner Up)</div>
                  <div class="text-[11px] text-muted truncate">Medali Perak + E-Certificate</div>
                </div>
              </div>
              <div class="text-[14px] font-bold text-slate-200 shrink-0">30% Total Hadiah</div>
            </div>

            <div class="p-4 rounded-lg border border-border bg-card-alt flex items-center justify-between gap-4">
              <div class="flex items-center gap-3.5 min-w-0">
                <div class="distribusi-rank-circle rank-3" style="width:32px; height:32px; min-width:32px; min-height:32px; border-radius:50%; flex-shrink:0;">3</div>
                <div class="min-w-0">
                  <div class="text-[13px] font-bold text-white">Juara 3</div>
                  <div class="text-[11px] text-muted truncate">E-Certificate Resmi</div>
                </div>
              </div>
              <div class="text-[14px] font-bold text-amber-500 shrink-0">10% Total Hadiah</div>
            </div>
          </div>
        </div>

        <!-- Pedoman Fair Play -->
        <div class="p-5 rounded-lg border border-border bg-card flex flex-col gap-3 md:col-span-2">
          <h3 class="text-[14px] font-bold text-white uppercase tracking-wider flex items-center gap-2">
            <i class="ph-bold ph-shield-check text-[#ef233c] text-base"></i>
            <span>Pedoman Fair Play & Regulasi</span>
          </h3>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-muted">
            <div class="p-3 rounded-md bg-card-alt border border-border flex flex-col gap-1">
              <span class="text-white font-semibold flex items-center gap-1.5"><i class="ph-bold ph-clock text-amber-400"></i> Ketepatan Waktu</span>
              <span>Seluruh tim wajib hadir di room tanding 15 menit sebelum match dimulai. Toleransi keterlambatan 10 menit.</span>
            </div>
            <div class="p-3 rounded-md bg-card-alt border border-border flex flex-col gap-1">
              <span class="text-white font-semibold flex items-center gap-1.5"><i class="ph-bold ph-prohibit text-red"></i> Anti-Cheat & No Toxic</span>
              <span>Dilarang keras memakai script atau cheat engine. Segala bentuk kecurangan berakibat diskualifikasi langsung.</span>
            </div>
            <div class="p-3 rounded-md bg-card-alt border border-border flex flex-col gap-1">
              <span class="text-white font-semibold flex items-center gap-1.5"><i class="ph-bold ph-broadcast text-emerald-400"></i> Wasit & Live Stream</span>
              <span>Setiap match dipandu wasit resmi. Livestreaming diperbolehkan dengan delay 90 detik.</span>
            </div>
          </div>
        </div>

      </div>
    </section>
    <?php endif; ?>

    <!-- TAB 3: TIM PESERTA (ROSTER) -->
    <section id="view-tab-teams" class="trn-tab-content hidden flex flex-col gap-4">
      <div class="flex items-center justify-between">
        <h3 class="text-[15px] font-bold text-white uppercase tracking-wide">
          Daftar Roster Tim Terdaftar
        </h3>
        <span class="text-xs text-muted">Kapasitas: <strong class="text-white"><?= htmlspecialchars($turnamen['tim'] ?? '10 / 16 Tim') ?></strong></span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <?php
        $mockTeams = [
            [
              'nama' => 'Surabaya Wolves',
              'kapten' => 'Rian Pratama',
              'roster' => ['Wolves#123', 'WolvesViper', 'WolvesSage', 'WolvesOmen', 'WolvesSova'],
              'kota' => 'Surabaya',
              'status' => 'Verified',
              'isUserTeam' => $isMember,
            ],
            [
              'nama' => 'Astra Orion',
              'kapten' => 'Dimas Bagus',
              'roster' => ['AstraPrime', 'OrionX', 'StarBoy', 'Vanguard', 'Eclipse'],
              'kota' => 'Malang',
              'status' => 'Verified',
              'isUserTeam' => false,
            ],
            [
              'nama' => 'Phantom Legion ID',
              'kapten' => 'Kevin Sanjaya',
              'roster' => ['Phantom01', 'GhostRider', 'Spectre', 'Shadow', 'Reaper'],
              'kota' => 'Malang',
              'status' => 'Verified',
              'isUserTeam' => false,
            ],
            [
              'nama' => 'Malang Knights',
              'kapten' => 'Fajar Pratama',
              'roster' => ['Knight1', 'Paladin', 'Shield', 'Sword', 'Lancer'],
              'kota' => 'Malang',
              'status' => 'Verified',
              'isUserTeam' => false,
            ],
            [
              'nama' => 'Garuda Esports',
              'kapten' => 'Budi Santoso',
              'roster' => ['GarudaX', 'ElangJawa', 'Merpati', 'Rajawali', 'AlapAlap'],
              'kota' => 'Sidoarjo',
              'status' => 'Verified',
              'isUserTeam' => false,
            ],
            [
              'nama' => 'Batu United',
              'kapten' => 'Hendra Setiawan',
              'roster' => ['BatuRock', 'Boulder', 'Pebble', 'Gravel', 'Granite'],
              'kota' => 'Batu',
              'status' => 'Verified',
              'isUserTeam' => false,
            ],
        ];

        if ($isMember) {
          usort($mockTeams, fn($a, $b) => ($b['isUserTeam'] ? 1 : 0) - ($a['isUserTeam'] ? 1 : 0));
        }

        foreach ($mockTeams as $team):
          $cardBorder = $team['isUserTeam'] ? 'border-emerald-500 bg-[#0d1f18]' : 'border-border bg-card';
        ?>
        <div class="p-5 rounded-lg border <?= $cardBorder ?> flex flex-col justify-between gap-3">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <?php if ($team['isUserTeam']): ?>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-[#06160d] uppercase">
                Tim Kamu
              </span>
              <?php endif; ?>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase">
                <?= htmlspecialchars($team['status']) ?>
              </span>
              <span class="text-xs text-muted"><?= htmlspecialchars($team['kota']) ?></span>
            </div>
            <h4 class="text-[16px] font-bold text-white"><?= htmlspecialchars($team['nama']) ?></h4>
            <p class="text-xs text-muted mt-1">Kapten: <span class="text-white font-medium"><?= htmlspecialchars($team['kapten']) ?></span></p>
          </div>

          <div class="pt-3 border-t border-border/50">
            <span class="text-[11px] text-muted block mb-2 font-medium">Susunan Roster (5 Pemain):</span>
            <div class="flex flex-wrap gap-2">
              <?php foreach ($team['roster'] as $player): ?>
              <span class="px-2.5 py-1 rounded bg-card-alt border border-border text-[11px] text-white/90">
                <?= htmlspecialchars($player) ?>
              </span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- TAB KHUSUS HOST: KELOLA PENDAFTAR -->
    <?php if ($isHost): ?>
    <section id="view-tab-pendaftar" class="trn-tab-content hidden flex flex-col gap-4">
      <div class="p-6 rounded-lg border border-border bg-card flex flex-col gap-4">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-[17px] font-bold text-white flex items-center gap-2">
              <i class="ph-bold ph-shield-check text-amber-400"></i>
              <span>Kelola Verifikasi Pendaftar (Host Panel)</span>
            </h3>
            <p class="text-xs text-muted mt-1 leading-relaxed">
              Konfirmasi tim baru yang mendaftar pada turnamen <strong><?= htmlspecialchars($turnamen['judul']) ?></strong> sebelum dimasukkan ke bagan pertandingan.
            </p>
          </div>
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
            2 Tim Menunggu Review
          </span>
        </div>

        <div class="flex flex-col gap-3 mt-2">
          <!-- Team Pending 1 -->
          <div class="p-4 rounded-lg border border-border bg-card-alt flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-400 uppercase">Menunggu Review</span>
                <span class="text-xs text-muted">Surabaya</span>
              </div>
              <h4 class="text-[14px] font-bold text-white">Viper Alpha Esports</h4>
              <p class="text-xs text-muted">Kapten: Yoga Pratama (WA: 0812-9988-7766) • 5 Roster Terisi</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
              <button
                type="button"
                onclick="verifikasiTimHost(this, 'Viper Alpha Esports', true)"
                class="inline-flex items-center justify-center h-9 px-4 rounded-md border border-emerald-500 text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500 hover:text-white text-xs font-bold transition-all cursor-pointer gap-1.5"
              >
                <i class="ph-bold ph-check"></i>
                <span>Terima Tim</span>
              </button>
              <button
                type="button"
                onclick="verifikasiTimHost(this, 'Viper Alpha Esports', false)"
                class="inline-flex items-center justify-center h-9 px-3 rounded-md border border-border bg-card-alt text-muted hover:border-red hover:text-red text-xs font-semibold transition-all cursor-pointer gap-1"
              >
                <i class="ph-bold ph-x"></i>
                <span>Tolak</span>
              </button>
            </div>
          </div>

          <!-- Team Pending 2 -->
          <div class="p-4 rounded-lg border border-border bg-card-alt flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-400 uppercase">Menunggu Review</span>
                <span class="text-xs text-muted">Malang</span>
              </div>
              <h4 class="text-[14px] font-bold text-white">Brawijaya Phoenix</h4>
              <p class="text-xs text-muted">Kapten: Alif Rahman (WA: 0857-1122-3344) • 5 Roster Terisi</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
              <button
                type="button"
                onclick="verifikasiTimHost(this, 'Brawijaya Phoenix', true)"
                class="inline-flex items-center justify-center h-9 px-4 rounded-md border border-emerald-500 text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500 hover:text-white text-xs font-bold transition-all cursor-pointer gap-1.5"
              >
                <i class="ph-bold ph-check"></i>
                <span>Terima Tim</span>
              </button>
              <button
                type="button"
                onclick="verifikasiTimHost(this, 'Brawijaya Phoenix', false)"
                class="inline-flex items-center justify-center h-9 px-3 rounded-md border border-border bg-card-alt text-muted hover:border-red hover:text-red text-xs font-semibold transition-all cursor-pointer gap-1"
              >
                <i class="ph-bold ph-x"></i>
                <span>Tolak</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TAB KHUSUS HOST: PENGATURAN & EDIT INFORMASI TURNAMEN & HADIAH -->
    <section id="view-tab-pengaturan" class="trn-tab-content hidden flex flex-col gap-5">
      <form id="form-edit-turnamen-host" onsubmit="simpanPengaturanTurnamenHost(event)" class="flex flex-col gap-5">
        
        <!-- SECTION 1: INFORMASI UTAMA TURNAMEN -->
        <div class="rounded-xl border p-5 flex flex-col gap-4 shadow-md bg-card border-border">
          <div class="flex items-center justify-between border-b border-border/50 pb-3">
            <div class="flex items-center gap-2">
              <i class="ph-bold ph-note-pencil text-base text-red"></i>
              <h3 class="text-[13px] font-bold text-white uppercase tracking-wider">Edit Informasi Turnamen</h3>
            </div>
            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded bg-red/10 border border-red/30 text-red">
              Pengaturan Penyelenggara
            </span>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Nama Turnamen -->
            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-nama" class="text-xs font-bold uppercase tracking-wider text-white">
                Nama Turnamen <span class="text-red">*</span>
              </label>
              <input
                type="text"
                id="edit-trn-nama"
                required
                value="<?= htmlspecialchars($turnamen['judul']) ?>"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>

            <!-- Tanggal Pelaksanaan -->
            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-tanggal" class="text-xs font-bold uppercase tracking-wider text-white">
                Tanggal Pelaksanaan <span class="text-red">*</span>
              </label>
              <input
                type="text"
                id="edit-trn-tanggal"
                required
                value="<?= htmlspecialchars($turnamen['tanggal']) ?>"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>

            <!-- Kota -->
            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-kota" class="text-xs font-bold uppercase tracking-wider text-white">
                Kota <span class="text-red">*</span>
              </label>
              <input
                type="text"
                id="edit-trn-kota"
                required
                value="<?= htmlspecialchars($turnamen['kota']) ?>"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>

            <!-- Lokasi Venue / Server -->
            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-lokasi" class="text-xs font-bold uppercase tracking-wider text-white">
                Lokasi Venue / Server Discord
              </label>
              <input
                type="text"
                id="edit-trn-lokasi"
                value="<?= htmlspecialchars($turnamen['lokasi'] ?? 'Arena Komunitas') ?>"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>

            <!-- Deskripsi Turnamen -->
            <div class="flex flex-col gap-1.5 lg:col-span-2">
              <label for="edit-trn-desc" class="text-xs font-bold uppercase tracking-wider text-white">
                Deskripsi & Ketentuan Turnamen
              </label>
              <textarea
                id="edit-trn-desc"
                rows="3"
                class="w-full rounded-md border border-border bg-card-alt p-3 text-[13px] text-white outline-none transition-colors focus:border-red resize-none"
              ><?= htmlspecialchars($turnamen['deskripsi'] ?? '') ?></textarea>
            </div>
          </div>
        </div>

        <!-- SECTION 2: EDIT HADIAH (PRIZE POOL) & BIAYA -->
        <div class="rounded-xl border p-5 flex flex-col gap-4 shadow-md bg-card border-border">
          <div class="flex items-center gap-2 border-b border-border/50 pb-3">
            <i class="ph-fill ph-trophy text-base text-[#F9A826]"></i>
            <h3 class="text-[13px] font-bold text-white uppercase tracking-wider">Hadiah & Biaya Pendaftaran</h3>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-prize" class="text-xs font-bold uppercase tracking-wider text-white">
                Total Hadiah (Prize Pool) <span class="text-red">*</span>
              </label>
              <input
                type="text"
                id="edit-trn-prize"
                required
                value="<?= htmlspecialchars($turnamen['prize']) ?>"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-[#F9A826] font-bold outline-none transition-colors focus:border-red"
              />
            </div>

            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-biaya" class="text-xs font-bold uppercase tracking-wider text-white">
                Biaya Registrasi
              </label>
              <input
                type="text"
                id="edit-trn-biaya"
                value="<?= htmlspecialchars($turnamen['biaya'] ?? 'Gratis') ?>"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>

            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-j1" class="text-xs font-bold uppercase tracking-wider text-white">
                Hadiah Juara 1
              </label>
              <input
                type="text"
                id="edit-trn-j1"
                value="60% Total Hadiah + Trophy"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>

            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-j2" class="text-xs font-bold uppercase tracking-wider text-white">
                Hadiah Juara 2
              </label>
              <input
                type="text"
                id="edit-trn-j2"
                value="30% Total Hadiah + Medali"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red"
              />
            </div>
          </div>
        </div>

        <!-- SECTION 3: UPLOAD / GANTI POSTER TURNAMEN -->
        <div class="rounded-xl border p-5 flex flex-col gap-4 shadow-md bg-card border-border">
          <div class="flex items-center justify-between border-b border-border/50 pb-3">
            <div class="flex items-center gap-2">
              <i class="ph-bold ph-image text-base text-red"></i>
              <h3 class="text-[13px] font-bold text-white uppercase tracking-wider">Poster / Banner Turnamen</h3>
            </div>
            <span class="text-[10px] text-muted">Format: JPG, PNG, WEBP (maks. 5MB)</span>
          </div>

          <div class="flex flex-col sm:flex-row items-center gap-5">
            <!-- Current Poster Preview -->
            <div class="relative shrink-0 rounded-xl overflow-hidden border border-border bg-[#080f18] flex items-center justify-center p-1" style="width: 140px; height: 105px;">
              <img
                id="edit-poster-preview"
                src="<?= $posterSrc ?>"
                alt="Poster Preview"
                class="w-full h-full object-cover rounded-lg"
              />
            </div>

            <!-- Dropzone Ganti Poster -->
            <label
              for="edit-poster-upload"
              class="flex-1 flex flex-col items-center justify-center gap-2 p-5 rounded-lg border-2 border-dashed border-border hover:border-red bg-card-alt cursor-pointer transition-colors w-full text-center group"
            >
              <i class="ph ph-upload-simple text-2xl text-muted group-hover:text-red transition-colors"></i>
              <div>
                <span class="text-xs font-semibold text-white group-hover:text-red transition-colors">Klik untuk upload / ganti poster turnamen</span>
                <span class="text-[11px] text-muted block mt-0.5">Poster ini akan langsung tampil di kartu header turnamen</span>
              </div>
              <input
                type="file"
                id="edit-poster-upload"
                accept="image/jpeg,image/png,image/webp"
                class="hidden"
              />
            </label>
          </div>
        </div>

        <!-- SECTION 4: FORMAT & ATURAN FAIR PLAY -->
        <div class="rounded-xl border p-5 flex flex-col gap-4 shadow-md bg-card border-border">
          <div class="flex items-center gap-2 border-b border-border/50 pb-3">
            <i class="ph-bold ph-shield-check text-base text-red"></i>
            <h3 class="text-[13px] font-bold text-white uppercase tracking-wider">Format & Aturan Pertandingan</h3>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-format" class="text-xs font-bold uppercase tracking-wider text-white">
                Format Pertandingan
              </label>
              <select
                id="edit-trn-format"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none cursor-pointer focus:border-red"
              >
                <option value="Single Elimination (BO3)" selected>Single Elimination (BO3)</option>
                <option value="Draft Pick BO3 (Final BO5)">Draft Pick BO3 (Final BO5)</option>
                <option value="Grup & Gugur (2 x 15 Menit)">Grup & Gugur (2 x 15 Menit)</option>
                <option value="Best of 1 (BO1) Fast Match">Best of 1 (BO1) Fast Match</option>
              </select>
            </div>

            <div class="flex flex-col gap-1.5">
              <label for="edit-trn-rules" class="text-xs font-bold uppercase tracking-wider text-white">
                Aturan Khusus (1 Aturan per Baris)
              </label>
              <textarea
                id="edit-trn-rules"
                rows="3"
                class="w-full rounded-md border border-border bg-card-alt p-3 text-[13px] text-white outline-none transition-colors focus:border-red resize-none"
              >No Toxic & No Blaming&#10;Hadir di room tanding 15 menit sebelum match&#10;Dilarang menggunakan cheat / engine ilegal</textarea>
            </div>
          </div>
        </div>

        <!-- ACTION BAR HOST -->
        <div class="flex items-center justify-between pt-2">
          <button
            type="button"
            onclick="batalTurnamenHost()"
            class="inline-flex items-center justify-center h-10 px-5 rounded-md border border-red text-red bg-transparent text-[13px] font-bold transition-all hover:bg-red/10 cursor-pointer gap-2"
          >
            <i class="ph-bold ph-trash"></i>
            <span>Batalkan Turnamen</span>
          </button>

          <button
            type="submit"
            id="btn-simpan-pengaturan-trn"
            class="btn-trn-action inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] cursor-pointer gap-2"
          >
            <i class="ph-bold ph-check"></i>
            <span>Simpan Perubahan</span>
          </button>
        </div>

      </form>
    </section>
    <?php endif; ?>

    <!-- TAB 4 (MEMBER & VISITOR): FORMULIR / STATUS PENDAFTARAN -->
    <?php if (!$isHost): ?>
    <section id="view-tab-daftar" class="trn-tab-content hidden flex flex-col gap-4">
      
      <?php if ($isMember): ?>
        <!-- Panel Member: Status Pendaftaran Sukses -->
        <div class="p-6 rounded-lg border border-emerald-500/40 bg-[#0c1c17] flex flex-col gap-4 shadow-xl">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-emerald-500/20 text-emerald-400 text-2xl shrink-0">
              <i class="ph-fill ph-check-circle"></i>
            </div>
            <div>
              <h3 class="text-[18px] font-bold text-white">Tim Kamu Telah Terdaftar & Terverifikasi!</h3>
              <p class="text-xs text-muted mt-0.5">
                Pendaftaran untuk <strong class="text-white">Surabaya Wolves</strong> telah dikonfirmasi oleh panitia turnamen.
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-emerald-500/20 text-xs">
            <div class="p-3 rounded-md bg-[#08120f] border border-emerald-500/20">
              <span class="text-muted block text-[10px] uppercase font-bold">Nama Tim</span>
              <span class="text-white font-bold text-[13px]">Surabaya Wolves</span>
            </div>
            <div class="p-3 rounded-md bg-[#08120f] border border-emerald-500/20">
              <span class="text-muted block text-[10px] uppercase font-bold">Kapten Tim</span>
              <span class="text-white font-bold text-[13px]">Rian Pratama (You)</span>
            </div>
            <div class="p-3 rounded-md bg-[#08120f] border border-emerald-500/20">
              <span class="text-muted block text-[10px] uppercase font-bold">Status Bagan</span>
              <span class="text-emerald-400 font-bold text-[13px]">Slot Perempat Final Ready</span>
            </div>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <a
              href="https://discord.gg"
              target="_blank"
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md border border-[#5865F2] text-white bg-[#5865F2]/20 hover:bg-[#5865F2] hover:text-white text-xs font-bold transition-all shadow-md"
            >
              <i class="ph-bold ph-discord-logo text-base"></i>
              <span>Masuk Discord Ruang Tanding</span>
            </a>
            <button
              type="button"
              onclick="switchDetailTab('bracket')"
              class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md border border-border bg-card-alt hover:text-white text-muted text-xs font-semibold transition-all cursor-pointer"
            >
              <i class="ph-bold ph-tree-structure"></i>
              <span>Lihat Lawan di Bagan</span>
            </button>
          </div>
        </div>

      <?php else: ?>
        <!-- Panel Visitor: Formulir Pendaftaran Tim Baru -->
        <div class="max-w-[700px] p-6 rounded-lg border border-border bg-card flex flex-col gap-4">
          <div>
            <h3 class="text-[18px] font-bold text-white">Formulir Pendaftaran Tim</h3>
            <p class="text-xs text-muted mt-1 leading-relaxed">
              Daftarkan tim kamu untuk mengikuti turnamen <strong class="text-white"><?= htmlspecialchars($turnamen['judul']) ?></strong>. Panitia akan memverifikasi roster dan mengonfirmasi jadwal fix via WhatsApp.
            </p>
          </div>

          <form id="form-daftar-turnamen-page" onsubmit="submitPendaftaranTurnamen(event)" class="flex flex-col gap-4 mt-2">
            <input type="hidden" id="page-trn-id" value="<?= htmlspecialchars($turnamen['id']) ?>" />

            <div class="flex flex-col gap-2">
              <label for="reg-team-name" class="text-xs font-bold uppercase tracking-wider text-white">
                Nama Tim Kamu <span class="text-red">*</span>
              </label>
              <input
                type="text"
                id="reg-team-name"
                required
                placeholder="cth. Surabaya Wolves Alpha"
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="flex flex-col gap-2">
                <label for="reg-captain-name" class="text-xs font-bold uppercase tracking-wider text-white">
                  Nama Kapten / Manager <span class="text-red">*</span>
                </label>
                <input
                  type="text"
                  id="reg-captain-name"
                  required
                  placeholder="cth. Rian Pratama"
                  class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
                />
              </div>

              <div class="flex flex-col gap-2">
                <label for="reg-captain-contact" class="text-xs font-bold uppercase tracking-wider text-white">
                  WhatsApp Kapten <span class="text-red">*</span>
                </label>
                <input
                  type="text"
                  id="reg-captain-contact"
                  required
                  placeholder="cth. 0812-3456-7890"
                  class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
                />
              </div>
            </div>

            <div class="flex flex-col gap-2">
              <div class="flex items-center justify-between">
                <label for="reg-roster" class="text-xs font-bold uppercase tracking-wider text-white">
                  Susunan Roster (Min. 5 Pemain) <span class="text-red">*</span>
                </label>
                <span class="text-[11px] text-muted">Format: Nickname #Tagline</span>
              </div>
              <textarea
                id="reg-roster"
                rows="4"
                required
                placeholder="1. Kapten: Wolves#123&#10;2. WolvesViper#456&#10;3. WolvesSage#789&#10;4. WolvesOmen#101&#10;5. WolvesSova#202"
                class="w-full rounded-md border border-border bg-card-alt p-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted resize-none"
              ></textarea>
            </div>

            <div class="flex flex-col gap-2">
              <label for="reg-notes" class="text-xs font-bold uppercase tracking-wider text-white">
                Catatan untuk Panitia
              </label>
              <input
                type="text"
                id="reg-notes"
                placeholder="cth. Roster kami siap standby 30 menit sebelum jadwal tanding."
                class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
              />
            </div>

            <div class="pt-3 border-t border-border/50 flex justify-end">
              <button
                type="submit"
                id="btn-submit-reg-trn"
                class="btn-trn-action inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center cursor-pointer gap-2"
              >
                <i class="ph-bold ph-paper-plane-tilt"></i>
                <span>Kirim Pendaftaran</span>
              </button>
            </div>
          </form>
        </div>
      <?php endif; ?>

    </section>
    <?php endif; ?>

  </main>

  <?php require __DIR__ . '/../../pages/chat.php'; ?>
</div>

<!-- Modal Lightbox Poster Penuh -->
<div id="modal-poster-turnamen" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm" onclick="tutupModalPoster()">
  <div class="relative max-w-2xl w-full bg-[#0d1622] border border-border rounded-xl p-5 shadow-2xl overflow-hidden flex flex-col gap-3" onclick="event.stopPropagation()">
    <div class="flex items-center justify-between border-b border-border/50 pb-3">
      <div class="flex items-center gap-2">
        <i class="ph-bold ph-image text-red text-base"></i>
        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Poster Resmi Turnamen</h3>
      </div>
      <button type="button" onclick="tutupModalPoster()" class="text-muted hover:text-white text-lg p-1 rounded-md transition-colors cursor-pointer">
        <i class="ph-bold ph-x"></i>
      </button>
    </div>
    <div class="w-full max-h-[70vh] rounded-lg overflow-hidden bg-[#080f18] flex items-center justify-center p-2">
      <img id="modal-poster-img" src="<?= $posterSrc ?>" alt="Poster Full" class="max-w-full max-h-[65vh] object-contain rounded" />
    </div>
  </div>
</div>

<script src="<?= $basePath ?>asset/js/turnamen.js?v=20261102"></script>

<?php require __DIR__ . '/../../include/footer.php'; ?>
