      <!-- LEFT SIDEBAR -->
      <aside class="flex flex-col gap-6 py-6 overflow-y-auto hide-scrollbar">

        <!-- TOP BLOCK -->
        <div class="lg:flex w-[260px] shrink-0 flex-col border-2 p-3 rounded-md" style="border-color: #232d39">
          <div class="flex flex-col gap-3">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Kategori</h2>
            <div class="grid grid-cols-2 gap-2">
              <button class="turnamen-filter active flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-colors hover:border-red-bright hover:bg-card-alt" data-filter-group="category" data-category="esports" aria-pressed="true">
                <i class="ph-fill ph-game-controller text-lg"></i> Esports
              </button>
              <button class="turnamen-filter flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-muted hover:bg-card-alt hover:text-white" data-filter-group="category" data-category="olahraga" aria-pressed="false">
                <i class="ph-fill ph-basketball text-lg"></i> Olahraga
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
          <div class="flex flex-col gap-3 mt-1">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Kota</h2>
            <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
          </div>

          <button class="mt-2 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all hover:bg-red/10">
            <i class="ph ph-plus text-lg font-bold"></i> Cari Sesi
          </button>
        </div>
        <!-- <hr class="border-border my-2" /> -->

        <!-- BOTTOM BLOCK -->
        <div class="lg:flex w-[260px] shrink-0 flex-col border-2 p-3 rounded-md" style="border-color: #232d39">

          <!-- Judul -->
          <div class="flex flex-col gap-3 mt-1">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Buat Sesi Turnamen</h2>
            <div class="grid grid-cols-2 gap-2">
              <button class="turnamen-filter active flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-colors hover:border-red-bright hover:bg-card-alt" data-filter-group="create-category" data-category="esports" aria-pressed="true">
                <i class="ph-fill ph-game-controller text-lg"></i> Esports
              </button>
              <button class="turnamen-filter flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-muted hover:bg-card-alt hover:text-white" data-filter-group="create-category" data-category="olahraga" aria-pressed="false">
                <i class="ph-fill ph-basketball text-lg"></i> Olahraga
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

          <!-- Nama Turnamen -->
          <div class="flex flex-col gap-3 mt-4">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Nama Turnamen</h2>
            <input type="text" placeholder="Masukkan nama turnamen" class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted" />
          </div>

          <!-- Kota -->
          <div class="flex flex-col gap-3 mt-4">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Kota</h2>
            <?php require __DIR__ . '/../../include/city_combobox.php'; ?>
          </div>
          
          <!-- Tanggal -->
          <div class="flex flex-col gap-2 mt-4">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Tanggal</h2>
            <div class="grid grid-cols-2 gap-2">

              <!-- Tanggal Mulai -->
              <div class="flex flex-col gap-1">
                <span class="text-[10px] text-muted font-medium">Mulai</span>
                <div class="date-input-wrapper h-11 w-full rounded-md border border-border bg-card-alt px-3 flex items-center transition-colors focus-within:border-red">
                  <input
                    type="text"
                    id="tanggal-mulai"
                    placeholder="dd/mm/yy"
                    class="flatpickr-date w-full bg-transparent text-[13px] text-muted outline-none pr-6 cursor-pointer"
                    readonly
                  />
                  <i class="date-icon ph ph-calendar"></i>
                </div>
              </div>

              <!-- Tanggal Selesai -->
              <div class="flex flex-col gap-1">
                <span class="text-[10px] text-muted font-medium">Selesai</span>
                <div class="date-input-wrapper h-11 w-full rounded-md border border-border bg-card-alt px-3 flex items-center transition-colors focus-within:border-red">
                  <input
                    type="text"
                    id="tanggal-selesai"
                    placeholder="dd/mm/yy"
                    class="flatpickr-date w-full bg-transparent text-[13px] text-muted outline-none pr-6 cursor-pointer"
                    readonly
                  />
                  <i class="date-icon ph ph-calendar"></i>
                </div>
              </div>

            </div>
          </div>


          <!-- Prize Pool -->
          <div class="flex flex-col gap-3 mt-4">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Prize Pool</h2>
            <input type="text" placeholder="Contoh: Rp500.000" class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted" />
          </div>

          <!-- Upload Poster / Foto -->
          <div class="flex flex-col gap-3 mt-4">
            <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">Poster / Foto</h2>

            <!-- Drop zone -->
            <label
              id="poster-upload-label"
              for="poster-upload"
              class="relative flex flex-col items-center justify-center gap-2 w-full rounded-md border-2 border-dashed border-border bg-card-alt cursor-pointer transition-colors hover:border-red group"
              style="min-height: 110px;"
            >
              <!-- Preview gambar (tersembunyi sebelum ada file) -->
              <img
                id="poster-preview"
                src=""
                alt="Preview poster"
                class="hidden absolute inset-0 w-full h-full object-cover rounded-md"
              />

              <!-- Isi placeholder -->
              <div id="poster-placeholder" class="flex flex-col items-center gap-2 py-4 z-10">
                <i class="ph ph-image text-3xl text-muted group-hover:text-red transition-colors"></i>
                <span class="text-[11px] text-muted group-hover:text-white transition-colors text-center leading-relaxed">
                  Klik untuk upload<br/>foto atau poster
                </span>
                <span class="text-[10px] text-muted/60">JPG, PNG, WEBP — maks. 5MB</span>
              </div>

              <input
                id="poster-upload"
                type="file"
                accept="image/jpeg,image/png,image/webp"
                class="hidden"
              />
            </label>

            <!-- Tombol hapus (muncul setelah ada preview) -->
            <button
              id="poster-remove"
              type="button"
              class="hidden flex items-center justify-center gap-2 text-[12px] text-muted hover:text-red transition-colors"
            >
              <i class="ph ph-trash text-sm"></i> Hapus foto
            </button>
          </div>

          <button class="mt-4 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all hover:bg-red/10">
            <i class="ph ph-plus text-lg font-bold"></i> Buat Sesi
          </button>
        </div>
      </aside>