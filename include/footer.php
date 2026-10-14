    <!-- Footer -->
    <footer class="mt-auto border-t border-border bg-[#080f18] shrink-0">
      <div
        class="mx-auto flex h-16 max-w-[1600px] items-center gap-4 px-4 lg:px-8"
      >
        <div class="p-2">
          <!-- <i class="ph-fill ph-shield"></i> -->
          <img src="/asset/icon/LogoFull.svg" alt="" style="height: 30px" />
        </div>
        <!-- <span class="text-[13px] font-bold text-white tracking-wide">Mabar Lokal</span> -->
        <span class="h-1 w-1 rounded-full bg-muted"></span>
        <span class="text-[12px] text-muted"
          >Temukan kawan, lawan, dan komunitas bermainmu</span
        >
      </div>
    </footer>

    <script>
      // Toggle Chat Logic
      const chatSidebar = document.getElementById("chat-sidebar");
      const btnCloseChat = document.getElementById("btn-close-chat");
      const btnOpenChat = document.getElementById("btn-open-chat");

      btnCloseChat.addEventListener("click", () => {
        chatSidebar.classList.add("chat-hidden");
        btnOpenChat.classList.remove("hidden");
      });

      btnOpenChat.addEventListener("click", () => {
        chatSidebar.classList.remove("chat-hidden");
        btnOpenChat.classList.add("hidden");
      });

      // Card Generator
      const sessionData = Array.from({ length: 6 }, (_, i) => ({
        title: "Tennis Pagi Pagi",
        place: "Lapangan Rampal, Malang",
        date: "Minggu, 27 September 2026",
        slot: "6",
        maxSlot: "8",
      }));

      const card = ({ title, place, date, slot, maxSlot }) => `
        <article class="group flex flex-col justify-between rounded-lg border border-border bg-card p-5 transition-all hover:-translate-y-1 hover:border-muted hover:shadow-lg hover:shadow-black/20">
          <div class="flex items-start gap-4">
            
            <div class="grid h-14 w-14 shrink-0 place-items-center rounded-lg border border-green/30 bg-[#0c1a17] text-3xl text-green">
              <i class="ph-fill ph-tennis-ball"></i>
            </div>
            
            <div class="min-w-0 flex-1">
              <span class="inline-block rounded bg-green px-2 py-0.5 text-[9px] font-bold tracking-widest text-bg uppercase mb-1">OLAHRAGA</span>
              <h3 class="text-[18px] font-bold text-white truncate tracking-tight">${title}</h3>
            </div>
            
            <div class="text-right shrink-0">
              <div class="text-[24px] font-bold leading-none text-white">${slot} <span class="text-[16px] text-muted font-normal">/ ${maxSlot}</span></div>
              <div class="text-[11px] text-muted mt-1">slot</div>
            </div>
          </div>
          
          <div class="mt-5 flex items-end justify-between border-t border-border/50 pt-5">
            <div class="flex flex-col gap-2.5">
              <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
                <i class="ph-bold ph-map-pin text-sm text-muted"></i> ${place}
              </div>
              <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
                <i class="ph-bold ph-calendar-blank text-sm text-muted"></i> ${date}
              </div>
            </div>
            <div class="flex gap-2 shrink-0">
               <button class="join-session rounded-md bg-red px-6 py-2.5 text-[13px] font-bold text-white transition-colors hover:bg-red-bright active:scale-[0.98]">
                Gabung
              </button>
              <button class="rounded-md border border-border bg-card-alt p-2.5 text-muted transition-colors hover:text-white hover:border-muted active:scale-[0.98]">
                <i class="ph-bold ph-chat-circle-dots text-lg"></i>
              </button>
            </div>
          </div>
        </article>`;

      const sessionsContainer = document.querySelector("#sessions");

      const render = (items) => {
        sessionsContainer.innerHTML = items.map(card).join("");

        document.querySelectorAll(".join-session").forEach((btn) => {
          btn.addEventListener("click", () => {
            btn.textContent = "Bergabung";
            btn.classList.remove("bg-red", "hover:bg-red-bright");
            btn.classList.add("bg-green", "hover:bg-green/80", "text-bg");
          });
        });
      };

      render(sessionData);

      // Tab Filters
      document.querySelectorAll(".filter-tab").forEach((btn) => {
        btn.addEventListener("click", () => {
          const isMode = btn.dataset.group === "mode";

          document
            .querySelectorAll(`[data-group="${btn.dataset.group}"]`)
            .forEach((item) => {
              item.classList.remove("border-red", "text-white");
              if (isMode) item.classList.remove("bg-red-dark");
              else item.classList.remove("bg-card-alt");

              item.classList.add("text-muted");
              if (!isMode) item.classList.add("border-border");
            });

          btn.classList.remove("text-muted", "border-border");
          btn.classList.add("border-red", "text-white");
          if (isMode) btn.classList.add("bg-red-dark");
          else btn.classList.add("bg-card-alt");
        });
      });

      // Search Filter
      document
        .querySelector("#search-input")
        .addEventListener("input", (event) => {
          const query = event.target.value.toLowerCase();
          render(
            sessionData.filter((item) =>
              `${item.title} ${item.place}`.toLowerCase().includes(query),
            ),
          );
        });

      // Load More
      document
        .querySelector("#load-more")
        .addEventListener("click", (event) => {
          sessionData.push(
            {
              title: "Badminton Sore",
              place: "GOR Ken Arok, Malang",
              date: "Sabtu, 26 September 2026",
              slot: "2",
              maxSlot: "4",
            },
            {
              title: "Basket Weekend",
              place: "Lapangan Rampal, Malang",
              date: "Sabtu, 26 September 2026",
              slot: "8",
              maxSlot: "10",
            },
          );
          render(sessionData);
          event.currentTarget.innerHTML =
            'Semua sesi telah ditampilkan <i class="ph-bold ph-check"></i>';
        });

      // Dropdown Filters
      const closeDropdowns = (except = null) => {
        document.querySelectorAll(".dropdown").forEach((dropdown) => {
          if (dropdown === except) return;
          dropdown.querySelector(".dropdown-menu").classList.add("hidden");
          dropdown
            .querySelector(".dropdown-toggle")
            .setAttribute("aria-expanded", "false");
        });
      };

      document.querySelectorAll(".dropdown").forEach((dropdown) => {
        const toggle = dropdown.querySelector(".dropdown-toggle");
        const menu = dropdown.querySelector(".dropdown-menu");
        const value = dropdown.querySelector(".dropdown-value");

        toggle.addEventListener("click", () => {
          const isOpen = !menu.classList.contains("hidden");
          closeDropdowns();
          menu.classList.toggle("hidden", isOpen);
          toggle.setAttribute("aria-expanded", String(!isOpen));
        });

        dropdown.querySelectorAll(".dropdown-option").forEach((option) => {
          option.addEventListener("click", () => {
            value.textContent = option.textContent;
            menu.classList.add("hidden");
            toggle.setAttribute("aria-expanded", "false");
          });
        });
      });

      document.addEventListener("click", (event) => {
        if (!event.target.closest(".dropdown")) closeDropdowns();
      });

      document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") closeDropdowns();
      });

      // Find Session Focus
      document.querySelector("#find-session").addEventListener("click", () => {
        document.querySelector("#search-input").focus();
      });

      // Mobile Menu
      document.querySelector("#menu-btn").addEventListener("click", () => {
        document.querySelector("#desktop-nav").classList.toggle("hidden");
        document.querySelector("#desktop-nav").classList.toggle("flex");
        document.querySelector("#desktop-nav").classList.toggle("flex-col");
        document.querySelector("#desktop-nav").classList.toggle("absolute");
        document.querySelector("#desktop-nav").classList.toggle("top-[76px]");
        document.querySelector("#desktop-nav").classList.toggle("left-0");
        document.querySelector("#desktop-nav").classList.toggle("w-full");
        document.querySelector("#desktop-nav").classList.toggle("bg-[#080f18]");
        document.querySelector("#desktop-nav").classList.toggle("p-4");
        document.querySelector("#desktop-nav").classList.toggle("z-40");
      });

      // Chat Tabs
      document.querySelectorAll(".chat-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
          document.querySelectorAll(".chat-tab").forEach((item) => {
            item.classList.remove("border-red", "text-red", "bg-card");
            item.classList.add("border-transparent", "text-muted");
          });
          tab.classList.remove("border-transparent", "text-muted");
          tab.classList.add("border-red", "text-red", "bg-card");
        });
      });

      // Chat Form Submit
      document
        .querySelector("#chat-form")
        .addEventListener("submit", (event) => {
          event.preventDefault();
          const input = document.querySelector("#chat-input");
          const val = input.value.trim();
          if (!val) return;

          const bubble = document.createElement("div");
          bubble.className =
            "flex flex-col items-end self-end max-w-[85%] mt-2";
          bubble.innerHTML = `
          <div class="rounded-2xl rounded-br-sm bg-red px-3.5 py-2 text-[12px] leading-relaxed text-white shadow-sm shadow-red/10">
            ${val}
          </div>
          <span class="mt-1 mr-1 text-[9px] text-muted text-right">Barusan</span>
        `;

          const chatContainer = document.querySelector(
            "#chat-content .flex-1.flex.flex-col.justify-end",
          );
          chatContainer.appendChild(bubble);

          const wrapper = document.querySelector("#chat-content");
          wrapper.scrollTop = wrapper.scrollHeight;

          input.value = "";
        });
    </script>
  </body>
</html>
