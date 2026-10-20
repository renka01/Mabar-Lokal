 <!-- MAIN CONTENT -->
      <main
        class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6"
      >
        <!-- Featured Match -->
        <article
          id="featured-match"
          class="relative shrink-0 min-h-[170px] flex overflow-hidden rounded-lg border border-red bg-card p-6 shadow-lg shadow-black/40 transition-colors duration-300 cursor-pointer group"
          onclick="if(!event.target.closest('#featured-join-btn')){ window.location.href=this.dataset.detailUrl || '<?= $basePath ?>pages/sesi/index.php?id=featured&nama=Ranked+Duelist&game=Valorant&kategori=esports'; }"
          data-detail-url="<?= $basePath ?>pages/sesi/index.php?id=featured&nama=Ranked+Duelist&game=Valorant&kategori=esports"
          title="Klik untuk melihat info sesi & lobby"
        >
          <!-- Background Watermark/Logo -->
          <div
            id="featured-watermark"
            class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 select-none opacity-20 transform scale-150 mr-10"
          >
            <i class="ph-fill ph-triangle text-[240px] text-red-dark"></i>
          </div>

          <div class="relative flex flex-col justify-between sm:flex-row gap-6">
            <!-- Game Icon Box -->
            <div
              id="featured-icon-box"
              class="grid h-24 w-24 shrink-0 place-items-center rounded-lg border border-red bg-bg text-[48px] text-red p-3 transition-colors"
            >
              <img id="featured-icon-img" src="<?= $basePath ?>asset/icon/Valoran1.svg" alt="Valorant" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
            </div>

            <div class="min-w-0 flex-1 flex flex-col justify-center">
              <div class="mb-3 flex flex-wrap gap-2">
                <span
                  id="featured-tag-1"
                  class="inline-flex items-center gap-1.5 rounded-full bg-red/15 text-red border border-red/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-red animate-pulse"></span>
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
                Ranked Duelist
              </h1>
              <span
                class="text-[10px] text-muted mb-1 font-medium tracking-wide"
                >Deskripsi</span
              >
              <p id="featured-desc" class="max-w-[400px] text-[12px] leading-relaxed text-muted">
                -2 rank Plat - Diamond, main duelist. Paling engga paham entry
                site. Playstyle menyesuaikan in-game. Kalo cocok gas push sampe
                immortal.
              </p>
            </div>

            <div class="flex flex-col items-center shrink-0 max-w-full p-5">
              <!-- Slot -->
              <div class="flex flex-col items-center">
                <div id="featured-slot-count" class="text-[36px] font-bold leading-none text-white mb-2">
                  3<span class="text-[28px] text-muted font-normal">/5</span>
                </div>
                <div class="flex">
                  <div
                    class="relative -mr-2 h-7 w-7 rounded-full border border-border flex items-center justify-center overflow-hidden"
                    style="background-color: #1a2636; color: #9ba5b4;"
                  >
                    <i class="ph-fill ph-user text-sm"></i>
                  </div>
                  <div
                    class="relative -mr-2 h-7 w-7 rounded-full border border-border flex items-center justify-center overflow-hidden"
                    style="background-color: #1a2636; color: #9ba5b4;"
                  >
                    <i class="ph-fill ph-user text-sm"></i>
                  </div>
                  <div
                    class="relative h-7 w-7 rounded-full border border-border flex items-center justify-center overflow-hidden"
                    style="background-color: #1a2636; color: #9ba5b4;"
                  >
                    <i class="ph-fill ph-user text-sm"></i>
                  </div>
                </div>
              </div>

              <!-- Action Button -->
              <div class="mt-6">
                <a
                  id="featured-join-btn"
                  href="<?= $basePath ?>pages/sesi/index.php?id=featured&nama=Ranked+Duelist&game=Valorant&kategori=esports&joined=1&tab=lobby"
                  onclick="event.stopPropagation();"
                  class="inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center"
                >
                  Gabung Slot
                </a>
              </div>
            </div>
          </div>
        </article>

        <!-- Matches Grid -->
        <div id="sessions" class="grid gap-4 sm:grid-cols-2"></div>

        <!-- Load More -->
        <div class="mt-4 flex justify-center pb-4">
          <button
            id="load-more"
            class="flex items-center gap-2 text-[13px] font-medium text-white transition-colors hover:text-red"
          >
            Muat lebih banyak sesi <i class="ph-bold ph-caret-down"></i>
          </button>
        </div>
      </main>
