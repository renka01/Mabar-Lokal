<!-- LEFT SIDEBAR -->
      <aside class="flex flex-col gap-6 py-6 overflow-y-auto hide-scrollbar">
        <div
          id="sidebar-cari-sesi"
          class="lg:flex w-[260px] shrink-0 flex-col border-2 p-3 rounded-md"
          style="border-color: #232d39"
        >
          <h2
            class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
          >
            Temukan Pertandingan
          </h2>

          <!-- Categories -->
          <div class="flex flex-col gap-3 mt-4">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Kategori
            </h2>
            <div class="grid grid-cols-2 gap-2">
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all"
                data-group="category"
                data-category="esports"
              >
                <i class="ph-fill ph-game-controller text-lg"></i>
                Esports
              </button>
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-[#19d66b] hover:text-white"
                data-group="category"
                data-category="olahraga"
              >
                <i class="ph-fill ph-basketball text-lg"></i>
                Olahraga
              </button>
            </div>
          </div>

          <!-- Game Select Component -->
          <div class="game-dropdown-wrapper">
            <?php require __DIR__ . '/../../include/game_dropdown.php'; ?>
          </div>

          <!-- Sport Select Component -->
          <div class="sport-dropdown-wrapper hidden">
            <?php require __DIR__ . '/../../include/sport_dropdown.php'; ?>
          </div>

          <!-- City Combobox -->
          <div class="flex flex-col gap-3 mt-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Kota</h2>
            <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
          </div>

          <!-- CTA -->
          <button
            id="find-session"
            class="mt-4 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all active:scale-[0.98] hover:bg-red/10"
          >
            <!-- <i class="ph ph-plus text-lg font-bold"></i> -->
            Cari Sesi
          </button>
        </div>

        <!-- Buat Sesi -->
        <div id="sidebar-buat-sesi" class="border-2 p-3 rounded-md" style="border-color: #232d39">
          <div class="flex flex-col gap-3 mt-1">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Buat Sesi
            </h2>
            <div class="grid grid-cols-2 gap-2">
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all"
                data-group="category"
                data-category="esports"
              >
                <i class="ph-fill ph-game-controller text-lg"></i>
                Esports
              </button>
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-[#19d66b] hover:text-white"
                data-group="category"
                data-category="olahraga"
              >
                <i class="ph-fill ph-basketball text-lg"></i>
                Olahraga
              </button>
            </div>
          </div>

          <div class="flex flex-col gap-3 mt-6">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              NAMA SESI
            </h2>
            <input
              type="text"
              placeholder="Masukkan nama sesi"
              class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
            />
          </div>

          <div class="flex flex-col gap-3 mt-6">
            <div class="flex items-center justify-between">
              <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Kota</h2>
              <span id="buat-sesi-kota-hint" class="text-[10px] text-muted font-normal lowercase">(opsional / online)</span>
            </div>
            <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
          </div>

          <!-- Game Select Component -->
          <div class="game-dropdown-wrapper">
            <?php require __DIR__ . '/../../include/game_dropdown.php'; ?>
          </div>

          <!-- Sport Select Component -->
          <div class="sport-dropdown-wrapper hidden">
            <?php require __DIR__ . '/../../include/sport_dropdown.php'; ?>
          </div>

          <button
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all hover:bg-red/10"
          >
            <i class="ph ph-plus text-lg font-bold"></i> Buat Sesi
          </button>
        </div>
      </aside>
