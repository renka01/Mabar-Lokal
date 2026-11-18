<!-- MAIN CONTENT -->
      <main
        class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6"
      >
        <!-- Header Judul & Subtitle -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
          <div>
            <h1 class="text-[28px] font-bold text-white uppercase tracking-tight leading-none mb-1.5">
              Cari Mabar
            </h1>
            <p class="text-[13px] text-muted leading-relaxed">
              Temukan teman mabar selevel untuk push rank Esports &amp; main bareng Olahraga lokal di Jawa Timur.
            </p>
          </div>
        </div>

        <article
          id="featured-match"
          class="hidden relative shrink-0 min-h-[170px] flex overflow-hidden rounded-lg border border-red bg-card p-6 shadow-lg shadow-black/40 transition-colors duration-300 cursor-pointer group"
          data-detail-url=""
          title="Klik untuk melihat info sesi & lobby"
        >
          <!-- Background Watermark/Logo -->
          <div
            id="featured-watermark"
            class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 select-none opacity-20 transform scale-150 mr-10"
          >
            <i class="ph-fill ph-triangle text-[240px] text-red-dark"></i>
          </div>

          <div class="relative flex flex-col justify-between sm:flex-row gap-6 w-full">
            <!-- Game Icon Box -->
            <div
              id="featured-icon-box"
              class="grid h-24 w-24 shrink-0 place-items-center rounded-lg border border-red bg-bg text-[48px] text-red p-3 transition-colors"
            >
              <img id="featured-icon-img" src="<?= $basePath ?>asset/icon/Valoran1.svg" alt="Game" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
            </div>

            <div class="min-w-0 flex-1 flex flex-col justify-center">
              <div class="mb-3 flex flex-wrap gap-2">
                <span
                  id="featured-tag-1"
                  class="inline-flex items-center gap-1.5 rounded-full bg-red/15 text-red border border-red/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-red"></span>
                  SEGERA MULAI
                </span>
                <span
                  id="featured-tag-2"
                  class="inline-flex items-center rounded-full bg-card-alt border border-border px-3 py-1 text-[10px] font-bold tracking-wider text-muted uppercase"
                >
                  VALORANT
                </span>
              </div>
              <h1
                id="featured-title"
                class="text-[32px] font-bold tracking-tight text-white mb-2 leading-none transition-colors group-hover:text-red card-title-esport"
              >
                Memuat Sesi...
              </h1>
              <span
                class="text-[10px] text-muted mb-1 font-medium tracking-wide"
                >Deskripsi</span
              >
              <p id="featured-desc" class="max-w-[400px] text-[12px] leading-relaxed text-muted"></p>
            </div>

            <div class="flex flex-col items-center shrink-0 max-w-full p-5">
              <!-- Slot -->
              <div class="flex flex-col items-center">
                <div id="featured-slot-count" class="text-[36px] font-bold leading-none text-white mb-2">
                  0<span class="text-[28px] text-muted font-normal">/5</span>
                </div>
                <div id="featured-avatars" class="flex"></div>
              </div>

              <!-- Action Button -->
              <div class="mt-6">
                <button
                  type="button"
                  id="featured-join-btn"
                  data-session-slug=""
                  class="inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center cursor-pointer"
                >
                  Gabung Slot
                </button>
              </div>
            </div>
          </div>
        </article>

        <!-- Matches Grid -->
        <div id="sessions" class="grid gap-4 sm:grid-cols-2"></div>

        <!-- Load More -->
        <div id="load-more-wrapper" class="hidden mt-4 flex justify-center pb-4">
          <button
            id="load-more"
            type="button"
            class="flex items-center gap-2 text-[13px] font-medium text-white transition-colors hover:text-red cursor-pointer"
          >
            Muat lebih banyak sesi <i class="ph-bold ph-caret-down"></i>
          </button>
        </div>
      </main>
