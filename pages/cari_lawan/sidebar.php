<!-- LEFT SIDEBAR: CARI LAWAN SCRIM (IDENTIK 260PX PERSIS SEPERTI CARI MABAR) -->
<aside class="flex flex-col gap-6 py-6 overflow-y-auto hide-scrollbar scrim-sidebar-fixed" style="width: 260px; min-width: 260px; max-width: 260px;">
  <!-- Card 1: Filter Lawan Scrim -->
  <div
    id="sidebar-filter-scrim"
    class="w-[260px] max-w-[260px] shrink-0 flex flex-col border-2 p-3 rounded-md box-border scrim-sidebar-card"
    style="border-color: #232d39; width: 260px; max-width: 260px;"
  >
    <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
      Temukan Lawan Scrim
    </h2>

    <!-- Categories Tab -->
    <div class="flex flex-col gap-3 mt-4">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Kategori
      </h2>
      <div class="grid grid-cols-2 gap-2">
        <button
          type="button"
          class="scrim-filter-cat flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all cursor-pointer"
          data-category="esports"
        >
          <i class="ph-fill ph-game-controller text-lg"></i>
          Esports
        </button>
        <button
          type="button"
          class="scrim-filter-cat flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-[#19d66b] hover:text-white cursor-pointer"
          data-category="olahraga"
        >
          <i class="ph-fill ph-basketball text-lg"></i>
          Olahraga
        </button>
      </div>
    </div>

    <!-- Game Select Component -->
    <div class="game-dropdown-wrapper scrim-game-select">
      <?php require __DIR__ . '/../../include/game_dropdown.php'; ?>
    </div>

    <!-- Sport Select Component -->
    <div class="sport-dropdown-wrapper scrim-sport-select hidden">
      <?php require __DIR__ . '/../../include/sport_dropdown.php'; ?>
    </div>

    <!-- City Combobox -->
    <div class="flex flex-col gap-3 mt-6">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Kota
      </h2>
      <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
    </div>

    <!-- Checkbox Filter Minggu Ini (Custom Styled Dark Checkbox) -->
    <div class="mt-5 pt-3 border-t border-border/60">
      <label class="custom-checkbox-label">
        <input
          type="checkbox"
          id="filter-scrim-minggu-ini"
          class="custom-checkbox"
        />
        <span class="text-[11.5px] text-muted leading-tight hover:text-white transition-colors">
          Jadwal sparring minggu ini
        </span>
      </label>
    </div>

    <!-- Action Buttons -->
    <button
      type="button"
      id="btn-apply-scrim-filter"
      class="mt-4 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all active:scale-[0.98] hover:bg-red/10 cursor-pointer"
    >
      Cari Lawan
    </button>
  </div>

  <!-- Card 2: Buka Tantangan Scrim (Posting Open Scrim) -->
  <div
    id="sidebar-buat-scrim"
    class="w-[260px] max-w-[260px] shrink-0 border-2 p-3 rounded-md box-border scrim-sidebar-card"
    style="border-color: #232d39; width: 260px; max-width: 260px;"
  >
    <div class="flex flex-col gap-3 mt-1">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Buka Tantangan Tim
      </h2>
      <div class="grid grid-cols-2 gap-2">
        <button
          type="button"
          class="scrim-create-cat flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all cursor-pointer"
          data-category="esports"
        >
          <i class="ph-fill ph-game-controller text-lg"></i>
          Esports
        </button>
        <button
          type="button"
          class="scrim-create-cat flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-[#19d66b] hover:text-white cursor-pointer"
          data-category="olahraga"
        >
          <i class="ph-fill ph-basketball text-lg"></i>
          Olahraga
        </button>
      </div>
    </div>

    <!-- Nama Tim -->
    <div class="flex flex-col gap-3 mt-6">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        NAMA TIM
      </h2>
      <input
        id="scrim-post-team-name"
        type="text"
        placeholder="Masukkan nama tim kamu"
        class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
      />
    </div>

    <!-- Game / Olahraga Select Component -->
    <div class="game-dropdown-wrapper scrim-create-game-select">
      <?php require __DIR__ . '/../../include/game_dropdown.php'; ?>
    </div>
    <div class="sport-dropdown-wrapper scrim-create-sport-select hidden">
      <?php require __DIR__ . '/../../include/sport_dropdown.php'; ?>
    </div>

    <!-- Format Match (Automated Dropdown Max BO5) -->
    <div class="flex flex-col gap-3 mt-6">
      <div class="flex items-center justify-between">
        <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
          Format Tanding
        </h2>
        <span id="scrim-format-hint" class="text-[10px] text-muted font-normal lowercase">(maks. BO5)</span>
      </div>
      <select
        id="scrim-post-format"
        class="h-11 w-full max-w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red font-medium cursor-pointer truncate box-border"
      >
        <option value="BO1">BO1 (Best of 1)</option>
        <option value="BO3" selected>BO3 (Best of 3 - Turnamen)</option>
        <option value="BO5">BO5 (Best of 5 - Final)</option>
      </select>
    </div>

    <!-- Kota / Domisili -->
    <div class="flex flex-col gap-3 mt-6">
      <div class="flex items-center justify-between">
        <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">KOTA / VENUE</h2>
        <span class="text-[10px] text-muted font-normal lowercase">(opsional / online)</span>
      </div>
      <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
    </div>

    <!-- Tombol Posting Scrim -->
    <button
      type="button"
      id="btn-post-open-scrim"
      class="mt-2 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all hover:bg-red/10 cursor-pointer active:scale-95"
    >
      <i class="ph ph-plus text-lg font-bold"></i>
      <span>Buka Tantangan</span>
    </button>
  </div>
</aside>
