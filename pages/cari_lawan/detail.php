<?php
/**
 * pages/cari_lawan/detail.php — Halaman Ruang Tanding Scrim & Match Lobby (Duel 2 Tim)
 * Mengadopsi arsitektur modular rapi persis seperti Halaman Sesi Cari Mabar.
 */

$pageTitle  = 'Ruang Tanding Scrim';
$basePath   = '../../';
$activePage = 'cari_lawan';
require_once __DIR__ . '/../../include/header.php';
require_once __DIR__ . '/../../config/data_scrim.php';

// Tangkap parameter query
$scrimId    = $_GET['id'] ?? null;
$paramTeam  = $_GET['team'] ?? $_GET['nama'] ?? null;
$paramGame  = $_GET['game'] ?? 'Valorant';
$paramFormat= $_GET['format'] ?? 'BO3';
$paramKota  = $_GET['kota'] ?? 'Surabaya';
$paramKat   = $_GET['kategori'] ?? 'esports';
$paramChallenger = $_GET['challenger'] ?? null;

// Deteksi Peran Pengguna (Role):
// 1. Host: Pembuat tantangan sparring / Kapten Tim A (?created=1 atau role=host)
// 2. Challenger / Member: Tim penantang yang sudah menantang / Kapten Tim B (?matched=1 atau ?challenger=... atau role=challenger/member)
// 3. Visitor / Pelihat: Pengguna umum yang melihat tantangan (default)
$isHost       = (isset($_GET['created']) && $_GET['created'] == '1') || (isset($_GET['role']) && $_GET['role'] === 'host');
$isChallenger = (isset($_GET['matched']) && $_GET['matched'] == '1') || !empty($paramChallenger) || (isset($_GET['role']) && in_array($_GET['role'], ['challenger', 'member']));
$isVisitor    = !$isHost && !$isChallenger;
$activeRole   = $isHost ? 'host' : ($isChallenger ? 'challenger' : 'visitor');
$isMatched    = $isChallenger || (isset($_GET['matched']) && $_GET['matched'] == '1');
$challengerTeamName = $paramChallenger ?? ($isChallenger ? 'Tim Kamu (Penantang)' : 'Phantom Legion ID');

// Cari data mock jika ada id
$scrim = null;
if ($scrimId) {
    foreach ($daftarScrim as $item) {
        if ($item['id'] === $scrimId) {
            $scrim = $item;
            break;
        }
    }
}

// Fallback jika buat baru dari sidebar
if (!$scrim) {
    $isOlahraga = strtolower($paramKat) === 'olahraga';
    $scrim = [
        'id'          => $scrimId ?? ('scrim-custom-' . time()),
        'kategori'    => $paramKat,
        'game'        => $paramGame,
        'game_icon'   => $isOlahraga ? 'asset/icon/Futsal.png' : 'asset/icon/Valoran1.svg',
        'nama_tim'    => $paramTeam ?? ($isOlahraga ? 'Surabaya Futsal Squad' : 'Surabaya Wolves Esports'),
        'tier'        => 'Menengah / Komunitas',
        'tier_badge'  => 'Menengah',
        'tier_color'  => $isOlahraga ? 'green' : 'red',
        'format'      => $paramFormat . ' (Tournament Match)',
        'format_short'=> $paramFormat,
        'jadwal'      => 'Hari ini, 20:30 WIB',
        'lokasi'      => $paramKota . ($isOlahraga ? ' • Lapangan Futsal' : ' • Online (Discord Server)'),
        'kota'        => $paramKota,
        'kapten'      => 'Anda (Kapten Tim)',
        'kapten_rank' => 'Pemain Utama',
        'catatan'     => 'Tantangan baru dibuka! Roster kami siap tanding. Hubungi kapten untuk konfirmasi jadwal fix.',
        'status'      => $isMatched ? 'matched' : 'open',
    ];
}

$isOlahraga   = $scrim['kategori'] === 'olahraga';
$themeColor   = $isOlahraga ? '#19d66b' : '#ef233c';
$themeGlow    = $isOlahraga ? 'rgba(25, 214, 107, 0.15)' : 'rgba(239, 35, 60, 0.15)';
?>

<!-- App Body (Match Room: Content + Chat Sidebar) -->
<div class="flex flex-1 overflow-hidden w-full max-w-[1600px] mx-auto px-4 lg:px-8 gap-6">
  
  <!-- Main Match Room Content Area -->
  <main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6">
    
    <!-- Top Bar: Navigasi Kembali & Status Ruang Match -->
    <div class="flex items-center justify-between gap-4 shrink-0">
      <a href="index.php" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-border bg-card hover:bg-card-alt text-muted hover:text-white text-xs font-semibold transition-all group shadow-sm">
        <i class="ph-bold ph-arrow-left text-sm transition-transform group-hover:-translate-x-1"></i>
        <span>Kembali ke Cari Lawan Scrim</span>
      </a>

      <div>
        <?php if ($isHost): ?>
          <?php if ($isMatched): ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold border shadow-sm" style="background-color: rgba(25, 214, 107, 0.1); border-color: rgba(25, 214, 107, 0.35); color: #19d66b;">
            <span class="w-2 h-2 rounded-full bg-[#19d66b]"></span>
            Lawan Ditemukan (Siap Koordinasi)
          </span>
          <?php else: ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold border shadow-sm" style="background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.35); color: #f59e0b;">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Tantangan Terbuka (Menunggu Lawan)
          </span>
          <?php endif; ?>
        <?php elseif ($isChallenger): ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold border shadow-sm" style="background-color: rgba(25, 214, 107, 0.1); border-color: rgba(25, 214, 107, 0.35); color: #19d66b;">
            <span class="w-2 h-2 rounded-full bg-[#19d66b]"></span>
            Tantangan Terkirim (Menunggu Match)
          </span>
        <?php else: ?>
          <?php if ($isMatched): ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold border shadow-sm" style="background-color: rgba(25, 214, 107, 0.1); border-color: rgba(25, 214, 107, 0.35); color: #19d66b;">
            <span class="w-2 h-2 rounded-full bg-[#19d66b]"></span>
            Match Terjadwal (Slot Penuh)
          </span>
          <?php else: ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold border shadow-sm" style="background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.35); color: #f59e0b;">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Open Sparring (Slot Tersedia)
          </span>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Match Header Card (Identik dengan Halaman Sesi Cari Mabar) -->
    <article class="relative shrink-0 overflow-hidden rounded-xl border p-6 shadow-xl"
             style="background-color: #0d1622; border-color: <?= $themeColor ?>; box-shadow: 0 4px 24px <?= $themeGlow ?>;">

      <!-- Content Row -->
      <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 w-full z-10">
        
        <!-- Left: Logo Game (84x84px) + Info Tim -->
        <div class="flex items-center gap-5 min-w-0 flex-1">
          <div class="shrink-0 rounded-xl border p-2 flex items-center justify-center shadow-lg"
               style="width: 84px; height: 84px; min-width: 84px; min-height: 84px; max-width: 84px; max-height: 84px; background-color: #080f18; border-color: <?= $themeColor ?>; overflow: hidden;">
            <img src="<?= $basePath . htmlspecialchars($scrim['game_icon']) ?>" alt="<?= htmlspecialchars($scrim['game']) ?>"
                 style="width: 100%; height: 100%; max-width: 100%; max-height: 100%; object-fit: contain; display: block;" />
          </div>

          <div class="min-w-0 flex-1">
            <!-- Kategori Chip & Nama Game -->
            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
              <span class="rounded px-2 py-0.5 text-[9px] font-extrabold tracking-widest uppercase shadow-sm" style="background-color: <?= $themeColor ?>; color: #ffffff;">
                <?= htmlspecialchars(strtoupper($scrim['kategori'])) ?>
              </span>
              <span class="text-[12px] font-semibold text-muted tracking-wide">
                <?= htmlspecialchars($scrim['game']) ?>
              </span>
              <span class="text-muted text-[11px]">•</span>
              <span class="text-[11px] font-semibold text-white/80">
                Target: <?= htmlspecialchars($scrim['tier']) ?>
              </span>
            </div>

            <!-- Nama Tim Penantang -->
            <h1 class="text-xl sm:text-[24px] font-bold tracking-tight text-white leading-tight truncate">
              <?= htmlspecialchars($scrim['nama_tim']) ?>
            </h1>

            <!-- Lokasi & Jadwal Sparring -->
            <div class="flex items-center gap-3 text-xs text-muted font-medium mt-1 mb-1.5 flex-wrap">
              <span class="flex items-center gap-1.5">
                <i class="ph-bold ph-map-pin text-sm" style="color: <?= $themeColor ?>;"></i>
                <span><?= htmlspecialchars($scrim['lokasi']) ?></span>
              </span>
              <span>•</span>
              <span class="flex items-center gap-1.5 text-white/90">
                <i class="ph-bold ph-calendar-blank text-sm" style="color: <?= $themeColor ?>;"></i>
                <span id="detail-scrim-jadwal-display"><?= htmlspecialchars($scrim['jadwal']) ?></span>
              </span>
            </div>

            <!-- Deskripsi Singkat / Catatan Kapten -->
            <p class="text-xs leading-relaxed text-muted line-clamp-2 max-w-xl">
              "<?= htmlspecialchars($scrim['catatan']) ?>"
            </p>
          </div>
        </div>

        <!-- Right: Format Match Dinamis & Tombol Aksi Sesuai Role -->
        <div class="flex flex-col items-center sm:items-end justify-center shrink-0 sm:pl-4 self-center">
          <div class="flex items-baseline gap-1.5 mb-0.5">
            <span class="text-3xl sm:text-[34px] font-extrabold leading-none text-white"><?= htmlspecialchars($scrim['format_short']) ?></span>
            <span class="text-[11px] font-bold tracking-wider uppercase text-muted">FORMAT</span>
          </div>
          <span class="text-[11px] text-muted mb-2">Kapten: <strong class="text-white"><?= htmlspecialchars($scrim['kapten']) ?></strong></span>

          <?php
            $btnOutlineTheme = $isOlahraga
              ? 'border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)]'
              : 'border border-red text-red bg-red/10 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)]';
          ?>

          <?php if ($isHost): ?>
            <?php if ($isMatched): ?>
            <button
              type="button"
              onclick="konfirmasiMatchHost()"
              class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] transition-all active:scale-95 cursor-pointer"
            >
              <i class="ph-bold ph-check-circle text-base mr-1.5"></i>
              <span>Konfirmasi Tanding</span>
            </button>
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
              data-ready="true"
              class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] transition-all active:scale-95 cursor-pointer"
            >
              <i class="ph-bold ph-check text-base mr-1.5"></i>
              <span>Tim Siap (Ready)</span>
            </button>
          <?php else: ?>
            <?php if (!$isMatched): ?>
            <button
              type="button"
              onclick="bukaModalTantang(<?= htmlspecialchars(json_encode($scrim)) ?>)"
              class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide transition-all active:scale-95 cursor-pointer <?= $btnOutlineTheme ?>"
            >
              <span>Tantang Tim Ini</span>
            </button>
            <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-bold" style="background-color: rgba(25, 214, 107, 0.15); color: #19d66b; border: 1px solid rgba(25, 214, 107, 0.3);">
              <i class="ph-bold ph-shield-check text-base"></i> Lawan: <?= htmlspecialchars($challengerTeamName) ?>
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
        Lobby & Roster Match
      </button>

      <?php if ($isHost): ?>
      <button
        type="button"
        id="tab-btn-match-settings"
        onclick="gantiTabScrim('settings')"
        class="scrim-detail-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] cursor-pointer"
      >
        Pengaturan Jadwal & Rules (Host)
      </button>
      <?php elseif ($isChallenger): ?>
      <button
        type="button"
        id="tab-btn-match-settings"
        onclick="gantiTabScrim('settings')"
        class="scrim-detail-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] cursor-pointer"
      >
        Informasi Jadwal & Room
      </button>
      <?php endif; ?>
    </div>

    <!-- PANEL 1: LOBBY & ROSTER MATCH -->
    <div id="panel-scrim-info">
      <?php require __DIR__ . '/bagian_match.php'; ?>
    </div>

    <!-- PANEL 2: PENGATURAN JADWAL & RULES (KHUSUS KAPTEN) -->
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
<script src="<?= $basePath ?>asset/js/detail_scrim.js"></script>

<?php require __DIR__ . '/../../include/footer.php'; ?>
