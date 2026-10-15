 <!-- MAIN CONTENT -->
      <main
        class="flex-1 min-w-0 overflow-y-auto hide-scrollbar flex flex-col gap-6 py-6"
      >
        <!-- Featured Match -->
        <article
          class="relative shrink-0 min-h-[170px] flex overflow-hidden rounded-lg border border-red bg-card p-6 shadow-lg shadow-black/40"
        >
          <!-- Background Watermark/Logo -->
          <div
            class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 select-none opacity-20 transform scale-150 mr-10"
          >
            <i class="ph-fill ph-triangle text-[240px] text-red-dark"></i>
            <!-- <img src="/asset/icon/Valoran1.svg" alt="" class=" text-full"> -->
          </div>

          <div class="relative flex flex-col justify-between sm:flex-row gap-6">
            <!-- Game Icon Box -->
            <div
              class="grid h-24 w-24 shrink-0 place-items-center rounded-lg border border-red bg-bg text-[48px] text-red"
            >
              <!-- <i class="ph-fill ph-triangle"></i> -->
              <img src="/asset/icon/Valoran1.svg" alt="" />
            </div>

            <div class="min-w-0 flex-1 flex flex-col justify-center">
              <div class="mb-3 flex flex-wrap gap-2">
                <span
                  class="rounded bg-red px-3 py-1 text-[10px] font-bold tracking-widest text-white uppercase"
                  >FEATURED</span
                >
                <span
                  class="rounded bg-red-dark px-3 py-1 text-[10px] font-bold tracking-widest text-white uppercase"
                  >VALORANT</span
                >
              </div>
              <h1
                class="text-[32px] font-bold tracking-tight text-white mb-2 leading-none"
              >
                Ranked Duelist
              </h1>
              <span
                class="text-[10px] text-muted mb-1 font-medium tracking-wide"
                >Deskripsi</span
              >
              <p class="max-w-[400px] text-[12px] leading-relaxed text-muted">
                -2 rank Plat - Diamond, main duelist. Paling engga paham entry
                site. Playstyle menyesuaikan in-game. Kalo cocok gas push sampe
                immortal.
              </p>
            </div>

            <div class="flex flex-col items-center shrink-0 max-w-full p-5">
              <!-- Slot -->
              <div class="flex flex-col items-center">
                <div class="text-[36px] font-bold leading-none text-white mb-2">
                  3<span class="text-[28px] text-muted font-normal">/5</span>
                </div>
                <div class="flex">
                  <div
                    class="relative -mr-2 h-7 w-7 rounded-full bg-white border border-border"
                  ></div>
                  <div
                    class="relative -mr-2 h-7 w-7 rounded-full bg-[#e2e8f0] border border-border"
                  ></div>
                  <div
                    class="relative h-7 w-7 rounded-full bg-[#cbd5e1] border border-border"
                  ></div>
                </div>
              </div>

              <!-- Buttons -->
              <div class="flex gap-2 mt-6">
                <button
                  class="rounded-md bg-red px-6 py-2.5 text-[13px] font-bold text-white transition-transform active:scale-[0.98] hover:bg-red-bright"
                >
                  Gabung Slot
                </button>
                <button
                  class="rounded-md border border-border bg-card-alt p-2.5 text-muted transition-colors hover:text-white"
                >
                  <i class="ph-bold ph-chat-circle-dots text-lg"></i>
                </button>
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