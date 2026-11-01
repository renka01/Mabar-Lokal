<?php
require_once __DIR__ . '/../../config/data_turnamen.php';
?>

<main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6">

  <!-- Header Judul & Subtitle -->
  <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
    <div>
      <h1 class="text-[28px] font-bold text-white uppercase tracking-tight leading-none mb-1.5">
        Turnamen
      </h1>
      <p class="text-[13px] text-muted leading-relaxed">
        Temukan berbagai kompetisi Esports & Olahraga lokal bergengsi di Jawa Timur.
      </p>
    </div>

    <!-- Quick Status Badge -->
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red/10 border border-red/30 text-red text-[11px] font-bold">
        <span class="w-1.5 h-1.5 rounded-full bg-red animate-pulse"></span>
        <span id="turnamen-filtered-count">10 Turnamen</span> Aktif
      </span>
    </div>
  </div>

  <!-- Sub-Tabs Filter Waktu Turnamen (Figma Style) -->
  <div class="flex gap-6 border-b border-border text-[13px] font-medium text-muted overflow-x-auto hide-scrollbar">
    <button type="button" class="tournament-time-tab active border-b-[3px] border-red text-white py-2 px-1 relative top-[1px] font-semibold whitespace-nowrap cursor-pointer" data-tab="semua">
      Semua
    </button>
    <button type="button" class="tournament-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="akandatang">
      Akan Datang
    </button>
    <button type="button" class="tournament-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="berlangsung">
      Berlangsung
    </button>
    <button type="button" class="tournament-time-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px] whitespace-nowrap cursor-pointer" data-tab="selesai">
      Selesai
    </button>
  </div>

  <!-- SECTION 1: TURNAMEN UNGGULAN (GRID 3 KOLOM) -->
  <section id="section-unggulan" class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <h2 class="text-[18px] font-bold text-white uppercase tracking-wide">
        Turnamen Unggulan
      </h2>
      <span class="text-[12px] text-muted">Kompetisi Utama Pekan Ini</span>
    </div>

    <div class="trn-grid-3col">
      <?php foreach ($turnamenUnggulan as $t): 
        $isOlahraga = ($t['kategori'] ?? '') === 'olahraga';
        $boxBorderClass = $isOlahraga ? 'box-border-olahraga' : 'box-border-esports';
        $badgeClass = $isOlahraga ? 'badge-olahraga' : 'badge-esports';
        $hoverTitleClass = $isOlahraga ? 'hover-title-olahraga' : 'hover-title-esports';
      ?>
      <article
        class="group relative flex flex-col justify-between rounded-lg border border-border bg-card p-5 transition-all duration-200 hover:-translate-y-1 hover:border-muted hover:shadow-lg hover:shadow-black/40 cursor-pointer tournament-card-item"
        data-kategori="<?= htmlspecialchars($t['kategori'] ?? 'esports') ?>"
        data-status="live"
        onclick="window.location.href='detail.php?id=<?= urlencode($t['id']) ?>';"
      >
        <div>
          <!-- Header Kartu: Icon Game + Tags -->
          <div class="flex items-start gap-4 mb-3">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-lg <?= $boxBorderClass ?> p-2 text-2xl">
              <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
            </div>

            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                <span class="inline-flex items-center gap-1 rounded px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest <?= $badgeClass ?>">
                  <span class="w-1.5 h-1.5 rounded-full <?= $isOlahraga ? 'bg-[#19d66b]' : 'bg-red' ?> animate-pulse"></span>
                  <?= htmlspecialchars($t['statusBadge'] ?? 'BERLANGSUNG') ?>
                </span>
                <span class="rounded bg-card-alt border border-border px-2 py-0.5 text-[9px] font-bold text-muted uppercase tracking-wider">
                  <?= htmlspecialchars($t['game']) ?>
                </span>
              </div>
              <h3 class="text-[16px] font-bold text-white truncate tracking-tight transition-colors <?= $hoverTitleClass ?>" title="<?= htmlspecialchars($t['judul']) ?>">
                <?= htmlspecialchars($t['judul']) ?>
              </h3>
            </div>
          </div>

          <!-- Lokasi & Jadwal -->
          <div class="flex flex-col gap-1.5 text-[12px] text-muted mb-4">
            <div class="flex items-center gap-2">
              <i class="ph-bold ph-map-pin text-sm <?= $isOlahraga ? 'text-[#19d66b]' : 'text-red' ?>"></i>
              <span class="truncate"><?= htmlspecialchars($t['kota']) ?> • <?= htmlspecialchars($t['lokasi'] ?? 'Arena Tanding') ?></span>
            </div>
            <div class="flex items-center gap-2">
              <i class="ph-bold ph-calendar-blank text-sm"></i>
              <span><?= htmlspecialchars($t['tanggal']) ?></span>
            </div>
          </div>
        </div>

        <!-- Footer Card: Prize Pool, Slot, & Button -->
        <div>
          <div class="flex justify-between items-end border-t border-border/50 pt-3">
            <div>
              <div class="text-[10px] text-muted mb-0.5 uppercase tracking-wider font-semibold">Total Hadiah</div>
              <div class="text-[16px] font-bold text-[#F9A826]"><?= htmlspecialchars($t['prize']) ?></div>
            </div>
            <div class="flex items-center gap-1.5 text-[12px] font-medium text-white">
              <i class="ph ph-users text-base text-muted"></i>
              <span><?= htmlspecialchars($t['tim']) ?></span>
            </div>
          </div>

          <a
            href="detail.php?id=<?= urlencode($t['id']) ?>"
            onclick="event.stopPropagation();"
            class="mt-4 w-full inline-flex items-center justify-center h-9 px-4 rounded-md text-[13px] font-semibold tracking-wide transition-all duration-200 active:scale-95 text-center cursor-pointer <?= $isOlahraga ? 'border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]' : 'border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]' ?>"
          >
            Lihat Detail
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- SECTION 2: TURNAMEN AKAN DATANG (LIST HORIZONTAL FIGMA) -->
  <section id="section-akandatang" class="flex flex-col gap-4 mt-2">
    <div class="flex items-center justify-between">
      <h2 class="text-[18px] font-bold text-white uppercase tracking-wide">
        Turnamen Akan Datang
      </h2>
      <span class="text-[12px] text-muted">Pendaftaran Masih Terbuka</span>
    </div>

    <div class="flex flex-col gap-3">
      <?php foreach ($turnamenAkanDatang as $t): 
        $isOlahraga = ($t['kategori'] ?? '') === 'olahraga';
        $boxBorderClass = $isOlahraga ? 'box-border-olahraga' : 'box-border-esports';
        $badgeClass = $isOlahraga ? 'badge-olahraga' : 'badge-esports';
        $hoverTitleClass = $isOlahraga ? 'hover-title-olahraga' : 'hover-title-esports';
      ?>
      <div
        class="group flex flex-col md:flex-row items-center gap-4 rounded-lg border border-border bg-card p-4 transition-all hover:border-muted hover:shadow-md cursor-pointer tournament-card-item"
        data-kategori="<?= htmlspecialchars($t['kategori'] ?? 'esports') ?>"
        data-status="upcoming"
        onclick="window.location.href='detail.php?id=<?= urlencode($t['id']) ?>';"
      >
        <div class="h-14 w-14 shrink-0 rounded-lg <?= $boxBorderClass ?> flex items-center justify-center p-2 overflow-hidden">
          <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
        </div>

        <div class="flex-1 min-w-0 w-full">
          <div class="flex items-center gap-2 mb-1">
            <span class="rounded <?= $badgeClass ?> px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest">
              <?= htmlspecialchars($t['game']) ?>
            </span>
            <span class="text-xs text-muted">• Slot Terbuka</span>
          </div>
          <h3 class="text-[16px] font-bold text-white truncate transition-colors <?= $hoverTitleClass ?>"><?= htmlspecialchars($t['judul']) ?></h3>
        </div>

        <div class="flex w-full md:w-auto items-center justify-between gap-6 md:gap-8 text-[12px] text-muted shrink-0 flex-wrap md:flex-nowrap">
          <div class="flex items-center gap-1.5"><i class="ph-bold ph-map-pin text-sm <?= $isOlahraga ? 'text-[#19d66b]' : 'text-red' ?>"></i> <?= htmlspecialchars($t['kota']) ?></div>
          <div class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
          <div class="flex items-center gap-1.5"><i class="ph ph-users text-base text-muted"></i> <?= htmlspecialchars($t['tim']) ?></div>
          <div class="font-bold text-[#F9A826] text-[15px]"><?= htmlspecialchars($t['prize']) ?></div>
        </div>

        <a
          href="detail.php?id=<?= urlencode($t['id']) ?>"
          onclick="event.stopPropagation();"
          class="w-full md:w-auto ml-0 md:ml-2 shrink-0 inline-flex items-center justify-center h-8 px-5 rounded-md text-[12px] font-semibold tracking-wide transition-all duration-200 active:scale-95 text-center cursor-pointer <?= $isOlahraga ? 'border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]' : 'border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]' ?>"
        >
          Daftar
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- SECTION 3: TURNAMEN BERLANGSUNG (LIST HORIZONTAL FIGMA) -->
  <section id="section-berlangsung" class="flex flex-col gap-4 mt-2">
    <div class="flex items-center justify-between">
      <h2 class="text-[18px] font-bold text-white uppercase tracking-wide">
        Turnamen Berlangsung
      </h2>
      <span class="text-[12px] text-muted">Sedang Berlangsung Hari Ini</span>
    </div>

    <div class="flex flex-col gap-3">
      <?php foreach ($turnamenBerlangsung as $t): 
        $isOlahraga = ($t['kategori'] ?? '') === 'olahraga';
        $boxBorderClass = $isOlahraga ? 'box-border-olahraga' : 'box-border-esports';
        $badgeClass = $isOlahraga ? 'badge-olahraga' : 'badge-esports';
        $hoverTitleClass = $isOlahraga ? 'hover-title-olahraga' : 'hover-title-esports';
      ?>
      <div
        class="group flex flex-col md:flex-row items-center gap-4 rounded-lg border border-border bg-card p-4 transition-all hover:border-muted hover:shadow-md cursor-pointer tournament-card-item"
        data-kategori="<?= htmlspecialchars($t['kategori'] ?? 'esports') ?>"
        data-status="live"
        onclick="window.location.href='detail.php?id=<?= urlencode($t['id']) ?>';"
      >
        <div class="h-14 w-14 shrink-0 rounded-lg <?= $boxBorderClass ?> flex items-center justify-center p-2 overflow-hidden">
          <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
        </div>

        <div class="flex-1 min-w-0 w-full">
          <div class="flex items-center gap-2 mb-1">
            <span class="rounded <?= $badgeClass ?> px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest">
              <?= htmlspecialchars($t['game']) ?>
            </span>
            <span class="text-xs text-muted">• Live Match</span>
          </div>
          <h3 class="text-[16px] font-bold text-white truncate transition-colors <?= $hoverTitleClass ?>"><?= htmlspecialchars($t['judul']) ?></h3>
        </div>

        <div class="flex w-full md:w-auto items-center justify-between gap-6 md:gap-8 text-[12px] text-muted shrink-0 flex-wrap md:flex-nowrap">
          <div class="flex items-center gap-1.5"><i class="ph-bold ph-map-pin text-sm <?= $isOlahraga ? 'text-[#19d66b]' : 'text-red' ?>"></i> <?= htmlspecialchars($t['kota']) ?></div>
          <div class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
          <div class="flex items-center gap-1.5"><i class="ph ph-users text-base text-muted"></i> <?= htmlspecialchars($t['tim']) ?></div>
          <div class="font-bold text-[#F9A826] text-[15px]"><?= htmlspecialchars($t['prize']) ?></div>
        </div>

        <a
          href="detail.php?id=<?= urlencode($t['id']) ?>"
          onclick="event.stopPropagation();"
          class="w-full md:w-auto ml-0 md:ml-2 shrink-0 inline-flex items-center justify-center h-8 px-5 rounded-md text-[12px] font-semibold tracking-wide transition-all duration-200 active:scale-95 text-center cursor-pointer <?= $isOlahraga ? 'border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]' : 'border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]' ?>"
        >
          Lihat
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- SECTION 4: TURNAMEN SELESAI (GRID DENGAN TROPHY WINNER FIGMA) -->
  <section id="section-selesai" class="flex flex-col gap-4 mt-2 mb-6">
    <div class="flex items-center justify-between">
      <h2 class="text-[18px] font-bold text-white uppercase tracking-wide">
        Turnamen Selesai
      </h2>
      <span class="text-[12px] text-muted">Arsip Hasil & Pemenang</span>
    </div>

    <div class="trn-grid-3col">
      <?php foreach ($turnamenSelesai as $t): 
        $isOlahraga = ($t['kategori'] ?? '') === 'olahraga';
        $boxBorderClass = $isOlahraga ? 'box-border-olahraga' : 'box-border-esports';
        $badgeClass = $isOlahraga ? 'badge-olahraga' : 'badge-esports';
        $hoverTitleClass = $isOlahraga ? 'hover-title-olahraga' : 'hover-title-esports';
      ?>
      <article
        class="group flex flex-col justify-between overflow-hidden rounded-lg border border-border bg-card p-5 transition-all duration-200 hover:-translate-y-1 hover:border-muted hover:shadow-lg hover:shadow-black/40 cursor-pointer tournament-card-item"
        data-kategori="<?= htmlspecialchars($t['kategori'] ?? 'esports') ?>"
        data-status="done"
        onclick="window.location.href='detail.php?id=<?= urlencode($t['id']) ?>';"
      >
        <div>
          <div class="flex items-center gap-3.5 mb-4">
            <div class="h-12 w-12 shrink-0 rounded-lg <?= $boxBorderClass ?> flex items-center justify-center p-2 overflow-hidden">
              <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
            </div>
            <div class="min-w-0 flex-1">
              <span class="inline-block rounded <?= $badgeClass ?> px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest mb-1">
                <?= htmlspecialchars($t['game']) ?>
              </span>
              <h3 class="text-[15px] font-bold text-white truncate transition-colors <?= $hoverTitleClass ?>"><?= htmlspecialchars($t['judul']) ?></h3>
            </div>
          </div>

          <div class="flex flex-col gap-1.5 text-[12px] text-muted mb-4 pb-3 border-b border-border/50">
            <div class="flex items-center gap-2"><i class="ph-bold ph-map-pin text-sm <?= $isOlahraga ? 'text-[#19d66b]' : 'text-red' ?>"></i> <?= htmlspecialchars($t['kota']) ?></div>
            <div class="flex items-center gap-2"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
          </div>

          <div class="flex items-center gap-3 mb-5 p-3 rounded-lg bg-card-alt border border-border">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#F9A826]/10 text-[#F9A826] text-2xl">
              <i class="ph-fill ph-trophy"></i>
            </div>
            <div class="flex flex-col min-w-0 flex-1">
              <span class="text-[10px] text-muted uppercase font-bold tracking-wider">Juara 1</span>
              <span class="text-[14px] font-bold text-white truncate"><?= htmlspecialchars($t['pemenang']) ?></span>
              <span class="text-[13px] font-bold text-[#F9A826]"><?= htmlspecialchars($t['prize']) ?></span>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between border-t border-border/50 pt-4 mt-auto">
          <div class="text-[12px] text-muted"><?= htmlspecialchars($t['tim']) ?></div>
          <a
            href="detail.php?id=<?= urlencode($t['id']) ?>"
            onclick="event.stopPropagation();"
            class="inline-flex items-center justify-center h-8 px-5 rounded-md text-[12px] font-semibold tracking-wide transition-all duration-200 active:scale-95 text-center cursor-pointer <?= $isOlahraga ? 'border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)]' : 'border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)]' ?>"
          >
            Lihat Hasil
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

</main>