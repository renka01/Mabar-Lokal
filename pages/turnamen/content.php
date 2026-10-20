<?php
/**
 * pages/turnamen/content.php — Halaman Utama Turnamen (Data-Driven & Clean)
 * Membaca data dari config/data_turnamen.php dan me-render kartu turnamen secara dinamis.
 */

require_once __DIR__ . '/../../config/data_turnamen.php';
?>

<!-- MAIN CONTENT -->
<main class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6">
  
  <!-- Header Judul -->
  <div class="mb-5">
    <h1 class="text-[28px] font-bold text-white uppercase tracking-tight mb-1">Turnamen</h1>
    <p class="text-[13px] text-muted">Temukan berbagai turnamen kompetitif Esports & Olahraga lokal</p>
  </div>
  
  <!-- Sub-Tabs Filter Waktu Turnamen -->
  <div class="flex gap-6 border-b border-border text-[13px] font-medium text-muted mb-8">
    <button class="tournament-tab active border-b-[3px] border-red text-white py-2 px-1 relative top-[1px]" aria-selected="true">Semua</button>
    <button class="tournament-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px]" aria-selected="false">Akan Datang</button>
    <button class="tournament-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px]" aria-selected="false">Berlangsung</button>
    <button class="tournament-tab border-b-[3px] border-transparent text-muted py-2 px-1 hover:text-white hover:border-muted transition-colors relative top-[1px]" aria-selected="false">Selesai</button>
  </div>

  <!-- SECTION 1: TURNAMEN UNGGULAN (GRID 3 KOLOM) -->
  <h2 class="text-[24px] font-bold text-white uppercase tracking-wide mb-4">Turnamen Unggulan</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
    <?php foreach ($turnamenUnggulan as $t): ?>
    <article class="flex flex-col overflow-hidden rounded-lg border border-border bg-card transition-colors hover:border-muted">
      <div class="relative h-32 bg-transparent p-4 flex flex-col justify-between">
        <span class="self-start rounded <?= htmlspecialchars($t['statusBg']) ?> px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest">
          <?= htmlspecialchars($t['status']) ?>
        </span>
        <span class="self-start rounded <?= htmlspecialchars($t['gameBg']) ?> mt-auto px-2 py-0.5 text-[9px] font-bold text-white uppercase tracking-widest">
          <?= htmlspecialchars($t['game']) ?>
        </span>
      </div>
      <div class="p-5 flex flex-col gap-4">
        <h3 class="text-[16px] font-bold text-white leading-tight truncate"><?= htmlspecialchars($t['judul']) ?></h3>
        <div class="flex flex-col gap-2 text-[12px] text-muted">
          <div class="flex items-center gap-2"><i class="ph-bold ph-map-pin text-sm"></i> <?= htmlspecialchars($t['kota']) ?></div>
          <div class="flex items-center gap-2"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
        </div>
        <div class="flex justify-between items-end border-t border-border/50 pt-3 mt-1">
          <div>
            <div class="text-[11px] text-muted mb-0.5">Prize Pool</div>
            <div class="text-[15px] font-bold text-[#F9A826]"><?= htmlspecialchars($t['prize']) ?></div>
          </div>
          <div class="flex items-center gap-2 text-[13px] font-medium text-white">
            <i class="ph ph-users text-lg"></i> <?= htmlspecialchars($t['tim']) ?>
          </div>
        </div>
        <button class="mt-4 w-full rounded-md bg-red py-2.5 text-[13px] font-bold text-white transition-colors hover:bg-red-bright">
          Lihat Detail
        </button>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <!-- SECTION 2: TURNAMEN AKAN DATANG (LIST HORIZONTAL) -->
  <h2 class="text-[24px] font-bold text-white uppercase tracking-wide mb-4">Turnamen Akan Datang</h2>
  <div class="flex flex-col gap-3 mb-10">
    <?php foreach ($turnamenAkanDatang as $t): ?>
    <div class="flex flex-col md:flex-row items-center gap-5 rounded-lg border border-border bg-card p-4 transition-colors hover:border-muted">
      <div class="h-[72px] w-[72px] shrink-0 rounded-lg border border-red/20 bg-[#1a2332] flex items-center justify-center p-2 overflow-hidden">
        <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" class="w-full h-full object-contain" />
      </div>
      <div class="flex-1 min-w-0 w-full">
        <span class="inline-block rounded bg-red-dark px-2 py-0.5 text-[9px] font-bold text-white uppercase tracking-widest mb-1.5">
          <?= htmlspecialchars($t['game']) ?>
        </span>
        <h3 class="text-[16px] font-bold text-white truncate"><?= htmlspecialchars($t['judul']) ?></h3>
      </div>
      <div class="flex w-full md:w-auto items-center justify-between gap-6 md:gap-8 text-[12px] text-muted shrink-0 flex-wrap md:flex-nowrap">
        <div class="flex items-center gap-1.5"><i class="ph-bold ph-map-pin text-sm"></i> <?= htmlspecialchars($t['kota']) ?></div>
        <div class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
        <div class="flex items-center gap-1.5"><i class="ph ph-users text-base"></i> <?= htmlspecialchars($t['tim']) ?></div>
        <div class="font-bold text-[#F9A826] text-[14px]"><?= htmlspecialchars($t['prize']) ?></div>
      </div>
      <button class="w-full md:w-auto ml-0 md:ml-4 shrink-0 rounded-md bg-red px-6 py-2.5 text-[13px] font-bold text-white hover:bg-red-bright">
        Daftar
      </button>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- SECTION 3: TURNAMEN BERLANGSUNG (LIST HORIZONTAL) -->
  <h2 class="text-[24px] font-bold text-white uppercase tracking-wide mb-4">Turnamen Berlangsung</h2>
  <div class="flex flex-col gap-3 mb-10">
    <?php foreach ($turnamenBerlangsung as $t): ?>
    <div class="flex flex-col md:flex-row items-center gap-5 rounded-lg border border-border bg-card p-4 transition-colors hover:border-muted">
      <div class="h-[72px] w-[72px] shrink-0 rounded-lg border border-red/20 bg-[#1a2332] flex items-center justify-center p-2 overflow-hidden">
        <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" class="w-full h-full object-contain" />
      </div>
      <div class="flex-1 min-w-0 w-full">
        <span class="inline-block rounded bg-red-dark px-2 py-0.5 text-[9px] font-bold text-white uppercase tracking-widest mb-1.5">
          <?= htmlspecialchars($t['game']) ?>
        </span>
        <h3 class="text-[16px] font-bold text-white truncate"><?= htmlspecialchars($t['judul']) ?></h3>
      </div>
      <div class="flex w-full md:w-auto items-center justify-between gap-6 md:gap-8 text-[12px] text-muted shrink-0 flex-wrap md:flex-nowrap">
        <div class="flex items-center gap-1.5"><i class="ph-bold ph-map-pin text-sm"></i> <?= htmlspecialchars($t['kota']) ?></div>
        <div class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
        <div class="flex items-center gap-1.5"><i class="ph ph-users text-base"></i> <?= htmlspecialchars($t['tim']) ?></div>
        <div class="font-bold text-[#F9A826] text-[14px]"><?= htmlspecialchars($t['prize']) ?></div>
      </div>
      <button class="w-full md:w-auto ml-0 md:ml-4 shrink-0 rounded-md bg-red px-6 py-2.5 text-[13px] font-bold text-white hover:bg-red-bright">
        Lihat
      </button>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- SECTION 4: TURNAMEN SELESAI (GRID DENGAN TROPHY WINNER) -->
  <h2 class="text-[24px] font-bold text-white uppercase tracking-wide mb-4">Turnamen Selesai</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
    <?php foreach ($turnamenSelesai as $t): ?>
    <article class="flex flex-col overflow-hidden rounded-lg border border-border bg-card p-5 transition-colors hover:border-muted">
      <div class="flex items-center gap-4 mb-5">
        <div class="h-[60px] w-[60px] shrink-0 rounded-lg border border-red/20 bg-[#1a2332] flex items-center justify-center p-2 overflow-hidden">
          <img src="<?= $basePath . htmlspecialchars($t['gameIcon']) ?>" alt="<?= htmlspecialchars($t['game']) ?>" class="w-full h-full object-contain" />
        </div>
        <div class="min-w-0 flex-1">
          <span class="inline-block rounded bg-red-dark px-2 py-0.5 text-[9px] font-bold text-white uppercase tracking-widest mb-1">
            <?= htmlspecialchars($t['game']) ?>
          </span>
          <h3 class="text-[16px] font-bold text-white truncate"><?= htmlspecialchars($t['judul']) ?></h3>
        </div>
      </div>
      <div class="flex flex-col gap-2 text-[12px] text-muted mb-5">
        <div class="flex items-center gap-2"><i class="ph-bold ph-map-pin text-sm"></i> <?= htmlspecialchars($t['kota']) ?></div>
        <div class="flex items-center gap-2"><i class="ph-bold ph-calendar-blank text-sm"></i> <?= htmlspecialchars($t['tanggal']) ?></div>
      </div>
      <div class="flex items-center gap-4 mb-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#F9A826]/10 text-[#F9A826] text-[28px]">
          <i class="ph-fill ph-trophy"></i>
        </div>
        <div class="flex flex-col">
          <span class="text-[14px] font-bold text-white"><?= htmlspecialchars($t['pemenang']) ?></span>
          <span class="text-[15px] font-bold text-[#F9A826]"><?= htmlspecialchars($t['prize']) ?></span>
        </div>
      </div>
      <div class="flex items-end justify-between border-t border-border/50 pt-5 mt-auto">
        <div class="text-[12px] text-muted"><?= htmlspecialchars($t['tim']) ?></div>
        <button class="rounded-md bg-red px-5 py-2 text-[13px] font-bold text-white hover:bg-red-bright">
          Lihat Hasil
        </button>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

</main>
