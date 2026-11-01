<?php
/**
 * pages/cari_lawan/content.php — Konten Utama Cari Lawan Scrim & Sparring
 * Mengadopsi arsitektur visual seimbang & proporsional persis seperti Cari Mabar.
 */
require_once __DIR__ . '/../../config/data_scrim.php';

// Ambil featured match pertama
$featuredScrim = null;
foreach ($daftarScrim as $s) {
    if (!empty($s['is_featured'])) {
        $featuredScrim = $s;
        break;
    }
}
if (!$featuredScrim && !empty($daftarScrim)) {
    $featuredScrim = $daftarScrim[0];
}
?>

<!-- MAIN CONTENT -->
<main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6">

  <!-- Header Judul Halaman -->
  <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
    <div>
      <h1 class="text-[28px] font-bold text-white uppercase tracking-tight leading-none mb-1.5">
        Cari Lawan Scrim
      </h1>
      <p class="text-[13px] text-muted leading-relaxed">
        Temukan tim penantang seimbang untuk uji taktik, latihan strategi, dan sparring kompetitif di Jawa Timur.
      </p>
    </div>

    <!-- Quick Status Badge -->
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red/10 border border-red/30 text-red text-[11px] font-bold">
        <span class="w-1.5 h-1.5 rounded-full bg-red animate-pulse"></span>
        <span id="scrim-total-count"><?= count($daftarScrim) ?> Tim</span> Siap Sparring
      </span>
    </div>
  </div>

  <!-- Sub-Tabs Filter Waktu & Tipe Match -->
  <div class="flex gap-6 border-b border-border text-[13px] font-medium text-muted overflow-x-auto hide-scrollbar">
    <button type="button" class="scrim-time-tab active border-b-[3px] border-red text-white py-2 px-1 relative top-[1px] font-semibold whitespace-nowrap cursor-pointer" data-tab="semua">
      Semua Match
    </button>
    <button type="button" class="scrim-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="hari_ini">
      Hari Ini
    </button>
    <button type="button" class="scrim-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="weekend">
      Akhir Pekan
    </button>
    <button type="button" class="scrim-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="online">
      Online Saja
    </button>
    <button type="button" class="scrim-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="offline">
      Offline / Lapangan
    </button>
  </div>

  <?php if ($featuredScrim): ?>
  <!-- FEATURED MATCH BANNER (LAYOUT SEBELUMNYA) -->
  <article
    id="featured-match"
    class="relative shrink-0 min-h-[170px] flex overflow-hidden rounded-lg border border-red bg-card p-6 shadow-lg shadow-black/40 transition-colors duration-300 cursor-pointer group"
    onclick="if(!event.target.closest('#featured-challenge-btn')){ window.location.href='detail.php?id=<?= urlencode($featuredScrim['id']) ?>'; }"
    title="Klik kartu untuk masuk ke Ruang Tanding (Match Room)"
  >
    <!-- Background Watermark/Logo -->
    <div class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 select-none opacity-20 transform scale-150 mr-10">
      <i class="ph-fill ph-sword text-[240px] text-red-dark"></i>
    </div>

    <div class="relative flex flex-col justify-between sm:flex-row gap-6 w-full">
      <!-- Game Icon Box -->
      <div class="grid h-24 w-24 shrink-0 place-items-center rounded-lg border border-red bg-bg text-[48px] text-red p-3 transition-colors">
        <img src="<?= $basePath . htmlspecialchars($featuredScrim['game_icon']) ?>" alt="<?= htmlspecialchars($featuredScrim['game']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
      </div>

      <!-- Info Tengah: Tag, Title, Deskripsi -->
      <div class="min-w-0 flex-1 flex flex-col justify-center">
        <div class="mb-3 flex flex-wrap gap-2">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-red/15 text-red border border-red/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase">
            <span class="w-1.5 h-1.5 rounded-full bg-red animate-pulse"></span>
            TANTANGAN UTAMA
          </span>
          <span class="inline-flex items-center rounded-full bg-card-alt border border-border px-3 py-1 text-[10px] font-bold tracking-wider text-muted uppercase">
            <?= htmlspecialchars($featuredScrim['game']) ?>
          </span>
          <span class="inline-flex items-center rounded-full bg-red/15 text-red border border-red/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase">
            <?= htmlspecialchars($featuredScrim['tier']) ?>
          </span>
        </div>

        <h2 class="text-[28px] sm:text-[32px] font-bold tracking-tight text-white mb-1.5 leading-none transition-colors group-hover:text-red">
          <?= htmlspecialchars($featuredScrim['nama_tim']) ?>
        </h2>

        <span class="text-[10px] text-muted mb-1 font-medium tracking-wide">Catatan Kapten (<?= htmlspecialchars($featuredScrim['kapten']) ?>)</span>
        <p class="max-w-[550px] text-[12px] leading-relaxed text-muted line-clamp-2">
          "<?= htmlspecialchars($featuredScrim['catatan']) ?>"
        </p>
      </div>

      <!-- Sisi Kanan: Format & Tombol Tantang -->
      <div class="flex flex-col items-center justify-center shrink-0 max-w-full p-2 sm:p-4 border-t sm:border-t-0 sm:border-l border-border/50">
        <div class="flex flex-col items-center mb-3">
          <div class="text-[32px] font-bold leading-none text-white mb-1">
            <?= htmlspecialchars($featuredScrim['format_short']) ?>
          </div>
          <span class="text-[10px] text-muted uppercase font-bold tracking-widest">FORMAT MATCH</span>
        </div>

        <button
          type="button"
          id="featured-challenge-btn"
          onclick="event.stopPropagation(); bukaModalTantang(<?= htmlspecialchars(json_encode($featuredScrim)) ?>)"
          class="btn-trn-action inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center cursor-pointer gap-2"
        >
          <i class="ph-bold ph-sword"></i>
          <span>Tantang Tim</span>
        </button>
      </div>
    </div>
  </article>
  <?php endif; ?>

  <!-- SECTION: DAFTAR TANTANGAN TERBUKA -->
  <div class="flex items-center justify-between mt-1">
    <h2 class="text-[18px] font-bold text-white uppercase tracking-wide">
      Daftar Tantangan Terbuka
    </h2>
    <span class="text-[12px] text-muted">
      Menampilkan <span id="scrim-filtered-count"><?= count($daftarScrim) ?></span> jadwal
    </span>
  </div>

  <!-- DAFTAR SCRIM (SATU PER SATU KE BAWAH, TENGANIN) -->
  <div id="scrim-grid" class="scrim-grid-container flex flex-col gap-4 w-full">
    <?php foreach ($daftarScrim as $s): 
      $isOlahraga = $s['kategori'] === 'olahraga';
      $boxBg = $isOlahraga ? 'bg-[#0c1a17] border-[#19d66b]/30' : 'bg-[#260f14] border-red/30';
      $hoverTitleColor = $isOlahraga ? 'group-hover:text-[#19d66b]' : 'group-hover:text-red';
      $tagBadge = $isOlahraga
        ? '<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wider bg-[#19d66b]/15 text-[#19d66b] uppercase">Olahraga</span>'
        : '<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wider bg-red/15 text-red uppercase">Esports</span>';
      $actionBtnClass = $isOlahraga
        ? 'border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]'
        : 'border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]';
    ?>
    <article
      class="group relative flex flex-col justify-between rounded-lg border border-border bg-card p-5 transition-all duration-200 hover:-translate-y-1 hover:border-muted hover:shadow-lg hover:shadow-black/40 cursor-pointer scrim-card-item"
      onclick="if(!event.target.closest('.btn-card-challenge')){ window.location.href='detail.php?id=<?= urlencode($s['id']) ?>'; }"
      data-kategori="<?= htmlspecialchars($s['kategori']) ?>"
      data-game="<?= htmlspecialchars(strtolower($s['game'])) ?>"
      data-tier="<?= htmlspecialchars(strtolower($s['tier_badge'])) ?>"
      data-kota="<?= htmlspecialchars(strtolower($s['kota'])) ?>"
      data-hari="<?= htmlspecialchars($s['hari_kategori']) ?>"
      data-tipe="<?= htmlspecialchars($s['tipe_match']) ?>"
      title="Klik untuk masuk ke Ruang Tanding Scrim"
    >
      <!-- Header Kartu: Icon Game + Title + Format -->
      <div>
        <div class="flex items-start gap-4">
          <div class="grid h-12 w-12 shrink-0 place-items-center rounded-lg border <?= $boxBg ?> p-2 text-2xl">
            <img src="<?= $basePath . htmlspecialchars($s['game_icon']) ?>" alt="<?= htmlspecialchars($s['game']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
          </div>
          
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-1">
              <?= $tagBadge ?>
              <span class="text-[11px] font-medium text-muted truncate"><?= htmlspecialchars($s['game']) ?></span>
              <span class="text-[10.5px] text-muted">• <?= htmlspecialchars($s['tier_badge']) ?></span>
            </div>
            <h3 class="text-[16px] font-bold text-white truncate tracking-tight transition-colors <?= $hoverTitleColor ?>" title="<?= htmlspecialchars($s['nama_tim']) ?>">
              <?= htmlspecialchars($s['nama_tim']) ?>
            </h3>
            <p class="text-[11.5px] text-muted truncate mt-0.5">
              Target: <span class="text-white/80 font-medium"><?= htmlspecialchars($s['tier']) ?></span>
            </p>
          </div>

          <div class="text-right shrink-0">
            <div class="text-[18px] font-bold leading-none text-white"><?= htmlspecialchars($s['format_short']) ?></div>
            <div class="text-[10px] text-muted mt-1 uppercase font-semibold tracking-wider">FORMAT</div>
          </div>
        </div>

        <!-- Kutipan Catatan Kapten (Desain Quote Elegan Discord, Anti-Border Putih & Anti-Nabrak) -->
        <p class="scrim-quote-box <?= $isOlahraga ? 'olahraga' : '' ?>">
          "<?= htmlspecialchars($s['catatan']) ?>"
        </p>
      </div>

      <!-- Footer Kartu: Lokasi, Jadwal, Tombol Aksi Bersih -->
      <div class="mt-4 flex items-center justify-between border-t border-border/50 pt-3">
        <div class="flex flex-col gap-1 min-w-0 pr-3">
          <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
            <i class="ph-bold ph-map-pin text-sm text-muted shrink-0"></i>
            <span class="truncate max-w-[210px]" title="<?= htmlspecialchars($s['lokasi']) ?>">
              <?= htmlspecialchars($s['kota']) ?> • <?= htmlspecialchars($s['lokasi']) ?>
            </span>
          </div>
          <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
            <i class="ph-bold ph-calendar-blank text-sm text-muted shrink-0"></i> 
            <span class="truncate"><?= htmlspecialchars($s['jadwal']) ?></span>
          </div>
        </div>

        <div class="shrink-0">
          <button
            type="button"
            onclick="event.stopPropagation(); bukaModalTantang(<?= htmlspecialchars(json_encode($s)) ?>)"
            class="btn-card-challenge btn-trn-action h-8 px-5 rounded-md text-[12px] font-semibold tracking-wide transition-all duration-200 active:scale-95 cursor-pointer <?= $actionBtnClass ?>"
            title="Tantang tim ini untuk sparring"
          >
            <span>Tantang</span>
          </button>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <!-- EMPTY STATE: JIKA FILTER TIDAK MENEMUKAN HASIL -->
  <div id="scrim-empty-state" class="hidden flex-col items-center justify-center py-16 text-center border border-dashed border-border rounded-xl bg-card/50">
    <div class="w-14 h-14 rounded-2xl bg-card-alt border border-border flex items-center justify-center text-muted text-2xl mb-3">
      <i class="ph ph-magnifying-glass"></i>
    </div>
    <h3 class="text-base font-bold text-white mb-1">Tidak Ada Tantangan Scrim yang Cocok</h3>
    <p class="text-xs text-muted max-w-sm mb-4">
      Coba ubah kriteria filter game, level kemampuan, atau domisili kota untuk melihat tim lainnya.
    </p>
    <button
      type="button"
      id="btn-empty-reset"
      class="px-4 py-2 rounded-lg bg-red text-white text-xs font-bold hover:bg-red-bright transition-all cursor-pointer"
    >
      Reset Semua Filter
    </button>
  </div>

</main>
