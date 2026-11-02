<?php
/**
 * pages/kuesioner.php
 * Kuesioner Preferensi Onboarding Mabar Lokal
 * Desain modern, fokus, bebas distraksi (tanpa navbar berat & tanpa footer copyright).
 */
$basePath = '../';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Preferensi Mabar - Mabar Lokal</title>
  <link rel="stylesheet" href="../asset/css/output.css" />
  <link rel="stylesheet" href="../asset/css/styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400..900;1,400..900&family=Sora:wght@100..800&display=swap"
    rel="stylesheet"
  />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <style>
    .pref-card {
      transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
      cursor: pointer;
    }
    .pref-card:hover {
      transform: translateY(-3px);
      border-color: rgba(239, 35, 60, 0.45);
    }
    .pref-card.active-esports {
      border-color: #ef233c !important;
      background: linear-gradient(135deg, rgba(239, 35, 60, 0.14) 0%, rgba(13, 22, 34, 0.95) 100%) !important;
      box-shadow: 0 0 24px rgba(239, 35, 60, 0.28) !important;
    }
    .pref-card.active-olahraga {
      border-color: #19d66b !important;
      background: linear-gradient(135deg, rgba(25, 214, 107, 0.14) 0%, rgba(13, 22, 34, 0.95) 100%) !important;
      box-shadow: 0 0 24px rgba(25, 214, 107, 0.28) !important;
    }
    .pref-card.active-keduanya {
      border-color: #f59e0b !important;
      background: linear-gradient(135deg, rgba(245, 158, 11, 0.14) 0%, rgba(13, 22, 34, 0.95) 100%) !important;
      box-shadow: 0 0 24px rgba(245, 158, 11, 0.28) !important;
    }

    .game-chip {
      transition: all 0.18s ease;
      cursor: pointer;
      user-select: none;
    }
    .game-chip:hover {
      border-color: rgba(255, 255, 255, 0.35);
      transform: translateY(-1px);
    }
    .game-chip.selected {
      border-color: #ef233c !important;
      background-color: rgba(239, 35, 60, 0.14) !important;
      box-shadow: 0 0 14px rgba(239, 35, 60, 0.28) !important;
    }
    .game-chip.selected.sport-chip {
      border-color: #19d66b !important;
      background-color: rgba(25, 214, 107, 0.14) !important;
      box-shadow: 0 0 14px rgba(25, 214, 107, 0.28) !important;
    }
  </style>
</head>
<body
  class="min-h-screen text-white font-sans text-sm flex flex-col justify-between selection:bg-red selection:text-white"
  style="background: radial-gradient(circle at 50% 12%, rgba(239, 35, 60, 0.08) 0%, rgba(8, 15, 24, 0.98) 60%), #080f18;"
>

  <!-- Top Minimal Bar: Seamless & Distraction-Free (No Heavy Navbar Border) -->
  <header class="w-full max-w-5xl mx-auto px-6 py-6 flex items-center justify-between shrink-0 z-20">
    <a href="../index.php" class="flex items-center gap-2.5 group" title="Kembali ke Beranda">
      <img src="../asset/icon/Logo1.svg" alt="Mabar Lokal" class="w-8 h-8 object-contain transition-transform group-hover:scale-105" />
      <span class="font-extrabold tracking-tight text-white text-base font-heading">
        MABAR<span class="text-red">LOKAL</span>
      </span>
    </a>

    <!-- Subtle Skip Link -->
    <a
      href="../index.php"
      class="inline-flex items-center gap-1.5 text-xs text-muted hover:text-white transition-colors group px-3 py-1.5 rounded-md hover:bg-white/5"
    >
      <span>Lewati untuk sekarang</span>
      <i class="ph-bold ph-arrow-right text-xs transition-transform group-hover:translate-x-0.5"></i>
    </a>
  </header>

  <!-- Main Container -->
  <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-4 sm:py-8 flex flex-col justify-center">

    <!-- ══════════════ LANGKAH 1: PILIH KATEGORI ══════════════ -->
    <section id="step-1-container" class="flex flex-col gap-6">
      
      <!-- Unified Header + Step Indicator -->
      <div class="text-center max-w-xl mx-auto flex flex-col items-center">
        <!-- Centered Segmented Step Progress -->
        <div class="flex items-center gap-2 mb-3">
          <div id="progress-step-1" class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide transition-all bg-red/15 text-red border border-red/30 shadow-sm">
            <span class="w-4 h-4 rounded-full bg-red text-white flex items-center justify-center text-[10px] font-black">1</span>
            <span>Kategori Minat</span>
          </div>
          <div id="progress-divider" class="w-6 h-0.5 bg-border rounded-full transition-all"></div>
          <div id="progress-step-2" class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide transition-all bg-card-alt text-muted border border-border">
            <span class="w-4 h-4 rounded-full bg-card border border-border text-muted flex items-center justify-center text-[10px]">2</span>
            <span>Pilihan Game</span>
          </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-heading tracking-tight">
          Apa yang Paling Kamu Minati?
        </h1>
        <p class="text-xs sm:text-sm text-muted mt-2 leading-relaxed">
          Pilih fokus aktivitas kamu di Mabar Lokal agar kami bisa menyajikan room mabar dan scrim yang relevan.
        </p>
      </div>

      <!-- 3 Category Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
        
        <!-- Esports Card -->
        <div
          class="pref-card p-6 rounded-xl border border-border bg-card flex flex-col justify-between gap-4 select-none"
          onclick="selectCategory('esports')"
          id="card-cat-esports"
        >
          <div class="flex flex-col gap-3">
            <div class="w-12 h-12 rounded-lg bg-red/10 border border-red/30 text-red flex items-center justify-center text-2xl shrink-0">
              <i class="ph-bold ph-game-controller"></i>
            </div>
            <div>
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-red">Esports</span>
              <h3 class="text-lg font-bold text-white mt-0.5">Game Online</h3>
              <p class="text-xs text-muted mt-1.5 leading-relaxed">
                Valorant, Mobile Legends, CS2, PUBG Mobile, Dota 2, Honor of Kings.
              </p>
            </div>
          </div>
          <div class="pt-3 border-t border-border/50 flex items-center justify-between text-xs font-semibold text-muted">
            <span>Kompetitif & Scrim</span>
            <div class="w-5 h-5 rounded-full border border-border flex items-center justify-center cat-check-icon">
              <i class="ph-bold ph-check text-xs hidden"></i>
            </div>
          </div>
        </div>

        <!-- Olahraga Fisik Card -->
        <div
          class="pref-card p-6 rounded-xl border border-border bg-card flex flex-col justify-between gap-4 select-none"
          onclick="selectCategory('olahraga')"
          id="card-cat-olahraga"
        >
          <div class="flex flex-col gap-3">
            <div class="w-12 h-12 rounded-lg bg-[#19d66b]/10 border border-[#19d66b]/30 text-[#19d66b] flex items-center justify-center text-2xl shrink-0">
              <i class="ph-bold ph-trophy"></i>
            </div>
            <div>
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#19d66b]">Fisik</span>
              <h3 class="text-lg font-bold text-white mt-0.5">Olahraga Nyata</h3>
              <p class="text-xs text-muted mt-1.5 leading-relaxed">
                Futsal, Bulu Tangkis, Basket, Billiard, Padel, Lari bareng komunitas.
              </p>
            </div>
          </div>
          <div class="pt-3 border-t border-border/50 flex items-center justify-between text-xs font-semibold text-muted">
            <span>Komunitas Lapangan</span>
            <div class="w-5 h-5 rounded-full border border-border flex items-center justify-center cat-check-icon">
              <i class="ph-bold ph-check text-xs hidden"></i>
            </div>
          </div>
        </div>

        <!-- Keduanya Card -->
        <div
          class="pref-card p-6 rounded-xl border border-border bg-card flex flex-col justify-between gap-4 select-none"
          onclick="selectCategory('keduanya')"
          id="card-cat-keduanya"
        >
          <div class="flex flex-col gap-3">
            <div class="w-12 h-12 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center text-2xl shrink-0">
              <i class="ph-bold ph-sparkle"></i>
            </div>
            <div>
              <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-400">Paling Populer</span>
              <h3 class="text-lg font-bold text-white mt-0.5">Keduanya</h3>
              <p class="text-xs text-muted mt-1.5 leading-relaxed">
                Aktif main game online sekaligus hobi olahraga fisik di akhir pekan.
              </p>
            </div>
          </div>
          <div class="pt-3 border-t border-border/50 flex items-center justify-between text-xs font-semibold text-muted">
            <span>Akses Semua Sesi</span>
            <div class="w-5 h-5 rounded-full border border-border flex items-center justify-center cat-check-icon">
              <i class="ph-bold ph-check text-xs hidden"></i>
            </div>
          </div>
        </div>

      </div>

      <!-- Action Button Step 1 -->
      <div class="flex justify-center mt-4">
        <button
          type="button"
          id="btn-next-step1"
          onclick="goToStep2()"
          disabled
          class="inline-flex items-center justify-center h-11 px-8 rounded-lg border border-border text-muted bg-card-alt text-[13px] font-bold tracking-wide transition-all opacity-50 cursor-not-allowed gap-2"
        >
          <span>Lanjutkan ke Pilihan Game</span>
          <i class="ph-bold ph-arrow-right"></i>
        </button>
      </div>
    </section>

    <!-- ══════════════ LANGKAH 2: PILIH GAME / CABOR ══════════════ -->
    <section id="step-2-container" class="hidden flex flex-col gap-6">
      
      <!-- Unified Header + Step Indicator -->
      <div class="text-center max-w-xl mx-auto flex flex-col items-center">
        <!-- Centered Segmented Step Progress -->
        <div class="flex items-center gap-2 mb-3">
          <div id="progress-step-1-completed" class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide transition-all bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm cursor-pointer" onclick="backToStep1()" title="Klik untuk mengubah kategori">
            <i class="ph-bold ph-check text-xs"></i>
            <span id="label-cat-chosen">Kategori Terpilih</span>
          </div>
          <div class="w-6 h-0.5 bg-emerald-500 rounded-full"></div>
          <div id="progress-step-2-active" class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide transition-all bg-red/15 text-red border border-red/30 shadow-sm">
            <span class="w-4 h-4 rounded-full bg-red text-white flex items-center justify-center text-[10px] font-black">2</span>
            <span>Pilihan Game</span>
          </div>
        </div>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white font-heading tracking-tight">
          Pilih Game / Cabor yang Kamu Mainkan
        </h2>
        <p class="text-xs sm:text-sm text-muted mt-2 leading-relaxed">
          Pilih satu atau lebih game yang biasa kamu mainkan. Kamu bisa mengubahnya kapan saja di halaman profil.
        </p>
      </div>

      <!-- Grid Pilihan Game (Esports) -->
      <div id="wrapper-games-esports" class="flex flex-col gap-3">
        <div class="flex items-center gap-2">
          <i class="ph-fill ph-game-controller text-red text-base"></i>
          <h4 class="text-xs font-bold uppercase tracking-wider text-white">Game Esports Online</h4>
          <span class="text-[11px] text-muted">(Bisa pilih lebih dari satu)</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
          
          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Valorant', this)">
            <img src="../asset/icon/Valoran1.svg" alt="Valorant" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Valorant</div>
              <div class="text-[10px] text-muted">Tactical FPS</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Mobile Legends', this)">
            <img src="../asset/icon/ml-logo.jpeg" alt="Mobile Legends" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Mobile Legends</div>
              <div class="text-[10px] text-muted">MOBA Mobile</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('CS2', this)">
            <img src="../asset/icon/cs2-logo.jpeg" alt="CS2" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Counter-Strike 2</div>
              <div class="text-[10px] text-muted">Tactical FPS</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('PUBG Mobile', this)">
            <img src="../asset/icon/pubgm-logo.jpeg" alt="PUBG Mobile" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">PUBG Mobile</div>
              <div class="text-[10px] text-muted">Battle Royale</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Dota 2', this)">
            <img src="../asset/icon/dota2-logo.jpeg" alt="Dota 2" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Dota 2</div>
              <div class="text-[10px] text-muted">MOBA PC</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Honor of Kings', this)">
            <img src="../asset/icon/hok-logo.png" alt="Honor of Kings" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Honor of Kings</div>
              <div class="text-[10px] text-muted">MOBA Mobile</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('League of Legends', this)">
            <img src="../asset/icon/lol-logo.jpeg" alt="LoL" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">League of Legends</div>
              <div class="text-[10px] text-muted">MOBA PC</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('PUBG PC', this)">
            <img src="../asset/icon/pubg-pc-logo.jpeg" alt="PUBG PC" class="w-8 h-8 rounded object-cover shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">PUBG Battlegrounds</div>
              <div class="text-[10px] text-muted">Battle Royale PC</div>
            </div>
            <i class="ph-bold ph-check text-red text-xs opacity-0 chip-check"></i>
          </div>

        </div>
      </div>

      <!-- Grid Pilihan Olahraga Fisik -->
      <div id="wrapper-games-olahraga" class="flex flex-col gap-3">
        <div class="flex items-center gap-2">
          <i class="ph-fill ph-trophy text-[#19d66b] text-base"></i>
          <h4 class="text-xs font-bold uppercase tracking-wider text-white">Cabang Olahraga Fisik</h4>
          <span class="text-[11px] text-muted">(Bisa pilih lebih dari satu)</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
          
          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Futsal', this, true)">
            <img src="../asset/icon/Futsal.png" alt="Futsal" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Futsal</div>
              <div class="text-[10px] text-muted">Tim Lapangan</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Bulu Tangkis', this, true)">
            <img src="../asset/icon/Bulu Tangkis.png" alt="Bulu Tangkis" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Bulu Tangkis</div>
              <div class="text-[10px] text-muted">Ganda / Tunggal</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Basket', this, true)">
            <img src="../asset/icon/Basket.png" alt="Basket" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Basket</div>
              <div class="text-[10px] text-muted">3x3 / 5x5</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Billiard', this, true)">
            <img src="../asset/icon/Billiard.png" alt="Billiard" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Billiard</div>
              <div class="text-[10px] text-muted">Pool & Snooker</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Padel', this, true)">
            <img src="../asset/icon/padel.png" alt="Padel" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Padel</div>
              <div class="text-[10px] text-muted">Raket Lapangan</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Lari', this, true)">
            <img src="../asset/icon/lARI.png" alt="Lari" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Running / Lari</div>
              <div class="text-[10px] text-muted">Komunitas Lari</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Sepak Bola', this, true)">
            <img src="../asset/icon/sepak-bola.svg" alt="Sepak Bola" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Sepak Bola</div>
              <div class="text-[10px] text-muted">Mini Soccer</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

          <div class="game-chip sport-chip p-3 rounded-lg border border-border bg-card flex items-center gap-3" onclick="toggleGame('Tenis', this, true)">
            <img src="../asset/icon/tenis.svg" alt="Tenis" class="w-8 h-8 rounded object-contain shrink-0" />
            <div class="min-w-0 flex-1">
              <div class="text-xs font-bold text-white truncate">Tenis Lapangan</div>
              <div class="text-[10px] text-muted">Tunggal / Ganda</div>
            </div>
            <i class="ph-bold ph-check text-[#19d66b] text-xs opacity-0 chip-check"></i>
          </div>

        </div>
      </div>

      <!-- Action Buttons Step 2 -->
      <div class="flex items-center justify-between pt-4 border-t border-border/50">
        <button
          type="button"
          onclick="backToStep1()"
          class="inline-flex items-center gap-2 px-5 h-11 rounded-lg border border-border bg-card-alt text-muted hover:text-white text-xs font-semibold transition-colors cursor-pointer"
        >
          <i class="ph-bold ph-arrow-left"></i>
          <span>Kembali</span>
        </button>

        <button
          type="button"
          id="btn-finish"
          onclick="finishQuestionnaire()"
          class="inline-flex items-center justify-center h-11 px-8 rounded-lg bg-red hover:bg-[#ff3048] text-white text-[13px] font-bold tracking-wide transition-all active:scale-95 shadow-[0_0_20px_rgba(239,35,60,0.35)] cursor-pointer gap-2"
        >
          <i class="ph-bold ph-check-circle text-base"></i>
          <span>Selesaikan & Mulai Mabar</span>
        </button>
      </div>
    </section>

  </main>

  <!-- Spacing bottom (Zero boilerplate footer) -->
  <div class="h-8 shrink-0"></div>

  <script>
    let selectedCategory = '';
    const selectedGames = new Set();

    function selectCategory(cat) {
      selectedCategory = cat;

      // Update UI cards
      document.querySelectorAll('.pref-card').forEach(c => {
        c.classList.remove('active-esports', 'active-olahraga', 'active-keduanya');
        const icon = c.querySelector('.cat-check-icon');
        if (icon) {
          icon.classList.remove('bg-red', 'bg-[#19d66b]', 'bg-amber-500', 'text-white');
          icon.querySelector('i')?.classList.add('hidden');
        }
      });

      const activeCard = document.getElementById(`card-cat-${cat}`);
      if (activeCard) {
        if (cat === 'esports') activeCard.classList.add('active-esports');
        else if (cat === 'olahraga') activeCard.classList.add('active-olahraga');
        else activeCard.classList.add('active-keduanya');

        const icon = activeCard.querySelector('.cat-check-icon');
        if (icon) {
          icon.classList.add(cat === 'esports' ? 'bg-red' : (cat === 'olahraga' ? 'bg-[#19d66b]' : 'bg-amber-500'), 'text-white');
          icon.querySelector('i')?.classList.remove('hidden');
        }
      }

      // Enable Next Button with Solid Glow Styling
      const btnNext = document.getElementById('btn-next-step1');
      if (btnNext) {
        btnNext.disabled = false;
        btnNext.className = "inline-flex items-center justify-center h-11 px-8 rounded-lg bg-red hover:bg-[#ff3048] text-white text-[13px] font-bold tracking-wide transition-all active:scale-95 shadow-[0_0_20px_rgba(239,35,60,0.35)] cursor-pointer gap-2";
      }
    }

    function goToStep2() {
      if (!selectedCategory) return;

      document.getElementById('step-1-container').classList.add('hidden');
      document.getElementById('step-2-container').classList.remove('hidden');

      // Update category label pill in Step 2 header
      const catLabel = selectedCategory === 'esports' ? 'Esports' : (selectedCategory === 'olahraga' ? 'Olahraga Fisik' : 'Esports & Olahraga');
      const labelChosen = document.getElementById('label-cat-chosen');
      if (labelChosen) labelChosen.textContent = catLabel;

      // Filter displayed lists based on category
      const wrapperEsports = document.getElementById('wrapper-games-esports');
      const wrapperOlahraga = document.getElementById('wrapper-games-olahraga');

      if (selectedCategory === 'esports') {
        wrapperEsports.classList.remove('hidden');
        wrapperOlahraga.classList.add('hidden');
      } else if (selectedCategory === 'olahraga') {
        wrapperEsports.classList.add('hidden');
        wrapperOlahraga.classList.remove('hidden');
      } else {
        wrapperEsports.classList.remove('hidden');
        wrapperOlahraga.classList.remove('hidden');
      }

      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function backToStep1() {
      document.getElementById('step-2-container').classList.add('hidden');
      document.getElementById('step-1-container').classList.remove('hidden');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleGame(gameName, el, isSport = false) {
      const checkIcon = el.querySelector('.chip-check');
      if (selectedGames.has(gameName)) {
        selectedGames.delete(gameName);
        el.classList.remove('selected');
        if (checkIcon) checkIcon.classList.add('opacity-0');
      } else {
        selectedGames.add(gameName);
        el.classList.add('selected');
        if (checkIcon) checkIcon.classList.remove('opacity-0');
      }
    }

    function finishQuestionnaire() {
      // Simpan preferensi ke LocalStorage
      const gamesArray = Array.from(selectedGames);
      localStorage.setItem('mabar_pref_category', selectedCategory || 'keduanya');
      localStorage.setItem('mabar_pref_games', JSON.stringify(gamesArray));
      localStorage.setItem('mabar_questionnaire_completed', 'true');

      // Sinkronkan tab beranda / cari mabar jika user memilih kategori spesifik
      if (selectedCategory === 'esports' || selectedCategory === 'olahraga') {
        localStorage.setItem('mabar_active_category', selectedCategory);
      }

      // Redirect ke Beranda Cari Mabar dengan feedback onboarding selesai
      window.location.href = '../index.php?onboarded=1';
    }

    // Prefill data lama jika user pernah mengisi sebelumnya
    document.addEventListener('DOMContentLoaded', () => {
      const savedCat = localStorage.getItem('mabar_pref_category');
      if (savedCat) {
        selectCategory(savedCat);
      }
    });
  </script>
</body>
</html>
