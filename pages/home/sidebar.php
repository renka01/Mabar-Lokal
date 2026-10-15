<!-- LEFT SIDEBAR -->
      <aside class="flex flex-col gap-6 py-6 overflow-y-auto hide-scrollbar">
        <div
          class="lg:flex w-[260px] shrink-0 flex-col border-2 p-3 rounded-md"
          style="border-color: #232d39"
        >
          <h2
            class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
          >
            Temukan Pertandingan
          </h2>
          <!-- Toggle -->
          <div
            class="grid grid-cols-2 rounded-md bg-card-alt p-1 border border-border"
          >
            <button
              class="filter-tab rounded bg-red-dark py-2 text-[12px] font-semibold text-white hover:text-white hover:border border-red-500 transition-all"
              data-group="mode"
            >
              Mabar
            </button>
            <button
              class="filter-tab rounded py-2 text-[12px] font-semibold text-muted transition-colors hover:text-white"
              data-group="mode"
            >
              Scrim
            </button>
          </div>

          <!-- Categories -->
          <div class="flex flex-col gap-3 mt-6">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Kategori
            </h2>
            <div class="grid grid-cols-2 gap-2">
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all"
                data-group="category"
              >
                <i class="ph-fill ph-game-controller text-lg"></i>
                Esports
              </button>
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-muted hover:text-white"
                data-group="category"
              >
                <i class="ph-fill ph-basketball text-lg"></i>
                Olahraga
              </button>
            </div>
          </div>

          <!-- Game Select -->
          <div class="relative z-30 flex flex-col gap-3 mt-6">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Game / Olahraga
            </h2>

            <div class="relative dropdown">
              <button
                type="button"
                class="dropdown-toggle flex h-11 w-full items-center rounded-md border border-border bg-card-alt px-3 text-[13px] font-medium text-white transition-colors hover:border-muted"
                aria-haspopup="listbox"
                aria-expanded="false"
              >
                <div
                  class="flex h-6 w-6 items-center justify-center rounded text-red mr-3"
                >
                  <img src="/asset/icon/Valoran1.svg" alt="" />
                </div>

                <span class="dropdown-value">Valorant</span>
                <i class="ph ph-caret-down ml-auto text-muted"></i>
              </button>

              <div
                class="dropdown-menu absolute left-0 right-0 z-50 mt-1 hidden overflow-hidden rounded-md border border-border bg-card-alt py-1 shadow-lg"
                role="listbox"
              >
                <button
                  type="button"
                  class="dropdown-option w-full px-3 py-2 text-left text-[13px] text-white hover:bg-border"
                  role="option"
                >
                  Valorant
                </button>
                <button
                  type="button"
                  class="dropdown-option w-full px-3 py-2 text-left text-[13px] text-muted hover:bg-border hover:text-white"
                  role="option"
                >
                  Mobile Legends
                </button>
                <button
                  type="button"
                  class="dropdown-option w-full px-3 py-2 text-left text-[13px] text-muted hover:bg-border hover:text-white"
                  role="option"
                >
                  PUBG Mobile
                </button>
              </div>
            </div>
          </div>

          <!-- City Select -->
          <div class=" flex flex-col gap-3 mt-6">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Kota
            </h2>
            <div class="relative dropdown">
              <button
                type="button"
                class="dropdown-toggle flex h-11 w-full items-center rounded-md border border-border bg-card-alt px-3 text-[13px] font-medium text-white transition-colors hover:border-muted"
                aria-haspopup="listbox"
                aria-expanded="false"
              >
                <i class="ph-fill ph-map-pin mr-3 text-lg text-muted"></i>
                <span class="dropdown-value">Malang</span>
                <i class="ph ph-caret-down ml-auto text-muted"></i>
              </button>
              <div
                class="dropdown-menu absolute left-0 right-0 z-20 mt-1 hidden overflow-hidden rounded-md border border-border bg-card-alt py-1 shadow-lg"
                role="listbox"
              >
                <button
                  type="button"
                  class="dropdown-option w-full px-3 py-2 text-left text-[13px] text-white transition-colors hover:bg-border"
                  role="option"
                >
                  Malang
                </button>
                <button
                  type="button"
                  class="dropdown-option w-full px-3 py-2 text-left text-[13px] text-muted transition-colors hover:bg-border hover:text-white"
                  role="option"
                >
                  Surabaya
                </button>
                <button
                  type="button"
                  class="dropdown-option w-full px-3 py-2 text-left text-[13px] text-muted transition-colors hover:bg-border hover:text-white"
                  role="option"
                >
                  Jakarta
                </button>
              </div>
            </div>
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
        <div class="border-2 p-3 rounded-md" style="border-color: #232d39">
          <div class="flex flex-col gap-3 mt-1">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Kategori
            </h2>
            <div class="grid grid-cols-2 gap-2">
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-red bg-card-alt text-[12px] font-semibold text-white transition-all"
                data-group="category"
              >
                <i class="ph-fill ph-game-controller text-lg"></i>
                Esports
              </button>
              <button
                class="filter-tab flex h-11 items-center justify-center gap-2 rounded-md border border-border text-[12px] font-semibold text-muted transition-colors hover:border-muted hover:text-white"
                data-group="category"
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
              placeholder="Nama Turnamen"
              class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
            />
          </div>

          <div class="flex flex-col gap-3 mt-6">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              Kota
            </h2>
            <input
              type="text"
              placeholder="Masukkan Kota"
              class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
            />
          </div>

          <div class="flex flex-col gap-3 mt-6">
            <h2
              class="text-[11px] font-bold tracking-[0.15em] text-white uppercase"
            >
              GAME/OLARAGA
            </h2>
            <input
              type="text"
              placeholder="Nama Per mainan"
              class="h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
            />
          </div>

          <button
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-md border border-red bg-transparent py-3 text-[13px] font-semibold text-red transition-all hover:bg-red/10"
          >
            <i class="ph ph-plus text-lg font-bold"></i> Buat Sesi
          </button>
        </div>
      </aside>