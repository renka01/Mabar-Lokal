<aside class="flex flex-col gap-6 py-6 overflow-y-auto hide-scrollbar scrim-sidebar-fixed" style="width: 260px; min-width: 260px; max-width: 260px;">

  <!-- Card 1: Temukan Turnamen -->
  <div
    id="sidebar-cari-turnamen"
    class="w-[260px] max-w-[260px] shrink-0 flex flex-col border-2 p-3 rounded-md box-border scrim-sidebar-card"
    style="border-color: #232d39; width: 260px; max-width: 260px;"
  >
    <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
      Temukan Turnamen
    </h2>

    <div class="flex flex-col gap-3 mt-4">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Kategori
      </h2>
      <div class="grid grid-cols-2 gap-2">
        <button
          type="button"
          class="turnamen-filter active flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all cursor-pointer"
          data-filter-group="category"
          data-category="esports"
          aria-pressed="true"
        >
          <i class="ph-fill ph-game-controller text-lg text-red"></i>
          Esports
        </button>
        <button
          type="button"
          class="turnamen-filter flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-[#19d66b] hover:text-white cursor-pointer"
          data-filter-group="category"
          data-category="olahraga"
          aria-pressed="false"
        >
          <i class="ph-fill ph-basketball text-lg"></i>
          Olahraga
        </button>
      </div>
    </div>

    <div class="game-dropdown-wrapper mt-3">
      <?php require __DIR__ . '/../../include/game_dropdown.php'; ?>
    </div>

    <div class="sport-dropdown-wrapper hidden mt-3">
      <?php require __DIR__ . '/../../include/sport_dropdown.php'; ?>
    </div>

    <div class="flex flex-col gap-3 mt-5">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Kota
      </h2>
      <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
    </div>

    <button
      type="button"
      id="btn-cari-turnamen"
      class="mt-5 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all active:scale-[0.98] hover:bg-red/10 cursor-pointer"
    >
      Cari Turnamen
    </button>
  </div>

  <!-- Card 2: Buat Sesi Turnamen -->
  <div
    id="sidebar-buat-turnamen"
    class="w-[260px] max-w-[260px] shrink-0 flex flex-col border-2 p-3 rounded-md box-border scrim-sidebar-card"
    style="border-color: #232d39; width: 260px; max-width: 260px;"
  >
    <div class="flex flex-col gap-3 mt-1">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Buat Sesi Turnamen
      </h2>
      <div class="grid grid-cols-2 gap-2">
        <button
          type="button"
          class="turnamen-filter active flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all cursor-pointer"
          data-filter-group="create-category"
          data-category="esports"
          aria-pressed="true"
        >
          <i class="ph-fill ph-game-controller text-lg text-red"></i>
          Esports
        </button>
        <button
          type="button"
          class="turnamen-filter flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-[#19d66b] hover:text-white cursor-pointer"
          data-filter-group="create-category"
          data-category="olahraga"
          aria-pressed="false"
        >
          <i class="ph-fill ph-basketball text-lg"></i>
          Olahraga
        </button>
      </div>
    </div>

    <div class="flex flex-col gap-2 mt-4">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Nama Turnamen
      </h2>
      <input
        type="text"
        id="input-nama-turnamen"
        placeholder="Masukkan nama turnamen"
        class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
      />
    </div>

    <div class="game-dropdown-wrapper mt-3">
      <?php require __DIR__ . '/../../include/game_dropdown.php'; ?>
    </div>

    <div class="sport-dropdown-wrapper hidden mt-3">
      <?php require __DIR__ . '/../../include/sport_dropdown.php'; ?>
    </div>

    <div class="flex flex-col gap-2 mt-4">
      <div class="flex items-center justify-between">
        <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
          Kota
        </h2>
        <span id="turnamen-kota-hint" class="text-[10px] text-muted font-normal lowercase">(opsional / online)</span>
      </div>
      <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
    </div>

    <div class="flex flex-col gap-2 mt-4">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Tanggal Pelaksanaan
      </h2>
      <div class="grid grid-cols-2 gap-2">
        <div class="flex flex-col gap-1">
          <span class="text-[10px] text-muted font-medium">Mulai</span>
          <div class="date-input-wrapper h-11 w-full rounded-md border border-border bg-card-alt px-2.5 flex items-center justify-between transition-colors focus-within:border-red">
            <input
              type="text"
              id="tanggal-mulai"
              placeholder="dd/mm/yy"
              class="flatpickr-date w-full bg-transparent text-[12px] text-muted outline-none cursor-pointer"
              readonly
            />
            <i class="date-icon ph ph-calendar text-muted text-sm shrink-0"></i>
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <span class="text-[10px] text-muted font-medium">Selesai</span>
          <div class="date-input-wrapper h-11 w-full rounded-md border border-border bg-card-alt px-2.5 flex items-center justify-between transition-colors focus-within:border-red">
            <input
              type="text"
              id="tanggal-selesai"
              placeholder="dd/mm/yy"
              class="flatpickr-date w-full bg-transparent text-[12px] text-muted outline-none cursor-pointer"
              readonly
            />
            <i class="date-icon ph ph-calendar text-muted text-sm shrink-0"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col gap-2 mt-4">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Prize Pool
      </h2>
      <input
        type="text"
        id="input-prize-turnamen"
        placeholder="Contoh: Rp500.000"
        class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
      />
    </div>

    <div class="flex flex-col gap-2 mt-4">
      <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
        Poster / Foto
      </h2>
      <label
        id="poster-upload-label"
        for="poster-upload"
        class="relative flex flex-col items-center justify-center gap-1.5 w-full rounded-md border-2 border-dashed border-border bg-card-alt cursor-pointer transition-colors hover:border-red group p-3 text-center"
        style="min-height: 90px;"
      >
        <img
          id="poster-preview"
          src=""
          alt="Preview poster"
          class="hidden absolute inset-0 w-full h-full object-cover rounded-md"
        />

        <div id="poster-placeholder" class="flex flex-col items-center gap-1 z-10">
          <i class="ph ph-image text-2xl text-muted group-hover:text-red transition-colors"></i>
          <span class="text-[11px] text-muted group-hover:text-white transition-colors leading-tight">
            Klik untuk upload foto / poster
          </span>
          <span class="text-[9.5px] text-muted/60">JPG, PNG, WEBP — maks. 5MB</span>
        </div>

        <input
          id="poster-upload"
          type="file"
          accept="image/jpeg,image/png,image/webp"
          class="hidden"
        />
      </label>

      <button
        id="poster-remove"
        type="button"
        class="hidden flex items-center justify-center gap-1 text-[11px] text-muted hover:text-red transition-colors mt-1"
      >
        <i class="ph ph-trash text-xs"></i> Hapus Foto
      </button>
    </div>

    <button
      type="button"
      id="btn-submit-buat-turnamen"
      class="mt-5 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all active:scale-[0.98] hover:bg-red/10 cursor-pointer"
    >
      <i class="ph ph-plus text-lg font-bold"></i>
      <span>Buat Sesi</span>
    </button>
  </div>
</aside>
