<!-- RIGHT CHAT SIDEBAR -->
      <aside id="chat-sidebar" class="py-6">
        <div
          class="hidden lg:flex w-[320px] shrink-0 flex-col overflow-hidden rounded-lg border border-border bg-card h-full"
        >
          <!-- Header -->
          <div
            class="flex justify-between items-center border-b border-border p-4 bg-bg"
          >
            <div class="flex items-center gap-3">
              <span
                class="text-[13px] font-bold tracking-widest text-white uppercase"
                >CHAT</span
              >
              <div class="flex items-center gap-1.5 text-[11px] text-muted">
                <i class="h-1.5 w-1.5 rounded-full bg-green"></i>
                3 online
              </div>
            </div>
            <!-- Close Chat Button -->
            <button
              id="btn-close-chat"
              class="text-muted hover:text-red transition-colors"
              title="Sembunyikan Chat"
            >
              <i class="ph-bold ph-caret-right text-lg"></i>
            </button>
          </div>

          <!-- Tabs -->
          <div
            class="flex border-b border-border text-[12px] font-medium bg-bg"
          >
            <button
              class="chat-tab flex-1 border-b-[2px] border-red py-3 text-red transition-colors bg-card"
              data-chat="all"
            >
              Semua
            </button>
            <button
              class="chat-tab flex-1 border-b-[2px] border-transparent py-3 text-muted transition-colors hover:text-white"
              data-chat="unread"
            >
              Belum dibaca
            </button>
          </div>

          <!-- Chat List -->
          <div
            class="flex flex-col overflow-y-auto hide-scrollbar max-h-[220px] border-b border-border bg-bg"
          >
            <!-- Conv 1 -->
            <button
              class="flex items-start gap-3 p-4 text-left transition-colors hover:bg-card-alt group relative"
            >
              <div
                class="absolute left-0 top-0 bottom-0 w-0 group-hover:w-[2px] bg-red transition-all"
              ></div>
              <div
                class="relative h-10 w-10 shrink-0 rounded-full bg-[#f4f5f7]"
              >
                <div
                  class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-bg bg-green"
                ></div>
              </div>
              <div class="flex-1 min-w-0 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                  <span class="text-[13px] font-semibold text-white">Amba</span>
                  <span class="text-[11px] text-muted">20.00</span>
                </div>
                <div class="flex items-center justify-between mt-1">
                  <span class="truncate text-[12px] text-muted"
                    >Jam 8 Gas?</span
                  >
                  <span
                    class="grid h-4 w-4 place-items-center rounded-full bg-red text-[9px] font-bold text-white"
                    >1</span
                  >
                </div>
              </div>
            </button>
            <!-- Conv 2 -->
            <!-- <button class="flex items-start gap-3 p-4 text-left transition-colors hover:bg-card-alt group relative">
              <div class="absolute left-0 top-0 bottom-0 w-0 group-hover:w-[2px] bg-red transition-all"></div>
              <div class="relative h-10 w-10 shrink-0 rounded-full bg-[#9ba5b4]"></div>
              <div class="flex-1 min-w-0 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                  <span class="text-[13px] font-semibold text-white">Fuad</span>
                  <span class="text-[11px] text-muted">21.00</span>
                </div>
                <div class="flex items-center justify-between mt-1">
                  <span class="truncate text-[12px] text-muted">Bukankah ini My Kisah Gwe</span>
                  <span class="grid h-4 w-4 place-items-center rounded-full bg-red text-[9px] font-bold text-white">1</span>
                </div>
              </div>
            </button> -->
            <!-- Conv 3 -->
            <button
              class="flex items-start gap-3 p-4 text-left transition-colors hover:bg-card-alt group relative"
            >
              <div
                class="absolute left-0 top-0 bottom-0 w-0 group-hover:w-[2px] bg-red transition-all"
              ></div>
              <div
                class="relative h-10 w-10 shrink-0 rounded-full bg-[#f4f5f7]"
              ></div>
              <div class="flex-1 min-w-0 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                  <span class="text-[13px] font-semibold text-white"
                    >Faren</span
                  >
                  <span class="text-[11px] text-muted">19.44</span>
                </div>
                <div class="flex items-center justify-between mt-1">
                  <span class="truncate text-[12px] text-muted"
                    >Ok, ak makan dulu</span
                  >
                </div>
              </div>
            </button>
            <div
              class="p-3 text-[11px] text-center font-medium text-red hover:text-red-bright cursor-pointer bg-bg"
            >
              Lihat semua chat <i class=""></i>
            </div>
          </div>

          <!-- Active Chat Area -->
          <div
            id="chat-content"
            class="flex-1 flex flex-col overflow-y-auto hide-scrollbar bg-[#0a1017]"
          >
            <!-- Active Header inside chat area -->
            <div
              class="sticky top-0 flex items-center gap-3 p-4 bg-[#0a1017] z-10 border-b border-border/30"
            >
              <div class="relative h-9 w-9 shrink-0 rounded-full bg-[#f4f5f7]">
                <div
                  class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-[#0a1017] bg-green"
                ></div>
              </div>
              <div class="flex flex-col">
                <span class="text-[13px] font-semibold text-white">Faren</span>
                <div class="flex items-center gap-1 text-[10px] text-muted">
                  <i class="h-1.5 w-1.5 rounded-full bg-green"></i>
                  Online
                </div>
              </div>
            </div>

            <!-- Messages container -->
            <div class="flex-1 flex flex-col justify-end p-4 gap-4">
              <!-- Date Separator -->
              <div
                class="relative text-center text-[10px] text-muted mt-2 mb-2"
              >
                <span
                  class="relative z-10 bg-[#0a1017] px-3 py-1 rounded-full border border-border/50"
                  >Hari ini</span
                >
                <div
                  class="absolute left-0 top-1/2 h-px w-full -translate-y-1/2 bg-border/50"
                ></div>
              </div>

              <!-- Incoming -->
              <div class="flex flex-col items-start max-w-[85%]">
                <div class="flex items-center gap-2 mb-1">
                  <div class="h-6 w-6 rounded-full bg-[#f4f5f7]"></div>
                  <div
                    class="rounded-2xl rounded-tl-sm border border-border bg-card-alt px-3.5 py-2 text-[12px] leading-relaxed text-white"
                  >
                    Jam 8 gas?
                  </div>
                </div>
                <span class="ml-9 text-[9px] text-muted">19.44</span>
              </div>

              <!-- Outgoing -->
              <div class="flex flex-col items-end self-end max-w-[85%] mt-2">
                <div
                  class="rounded-2xl rounded-br-sm bg-red px-3.5 py-2 text-[12px] leading-relaxed text-white shadow-sm shadow-red/10"
                >
                  Ok, ak makan dlu
                </div>
                <span class="mt-1 mr-1 text-[9px] text-muted text-right"
                  >19.44</span
                >
              </div>
            </div>
          </div>

          <!-- Chat Input -->
          <form
            id="chat-form"
            class="flex items-center gap-2 border-t border-border bg-bg p-3 shrink-0"
          >
            <input
              id="chat-input"
              class="flex-1 rounded-md border border-border bg-[#080f18] px-3.5 py-2.5 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
              placeholder="Tulis pesan..."
              autocomplete="off"
            />
            <button
              class="grid h-10 w-10 shrink-0 place-items-center rounded-md bg-red text-white transition-transform active:scale-[0.95] hover:bg-red-bright"
              aria-label="Kirim pesan"
            >
              <i class="ph-fill ph-paper-plane-right text-[16px]"></i>
            </button>
          </form>
        </div>
      </aside>