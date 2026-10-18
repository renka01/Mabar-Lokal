  // Toggle Chat Logic
      const chatSidebar = document.getElementById("chat-sidebar");
      const btnCloseChat = document.getElementById("btn-close-chat");
      const btnOpenChat = document.getElementById("btn-open-chat");
      const htmlEl = document.documentElement;

      const openChat = () => {
        if (chatSidebar) chatSidebar.classList.remove("chat-hidden");
        htmlEl.classList.remove("chat-is-closed");
        if (btnOpenChat) btnOpenChat.classList.remove("animate-in");
        localStorage.setItem("mabar_chat_closed", "false");
      };

      const closeChat = () => {
        if (chatSidebar) chatSidebar.classList.add("chat-hidden");
        htmlEl.classList.add("chat-is-closed");
        if (btnOpenChat) btnOpenChat.classList.add("animate-in");
        localStorage.setItem("mabar_chat_closed", "true");
      };

      if (chatSidebar && btnCloseChat && btnOpenChat) {
        // html.chat-is-closed sudah di-set oleh inline script di <head> sebelum render
        // Tinggal sync chat-sidebar sesuai state
        if (htmlEl.classList.contains("chat-is-closed")) {
          chatSidebar.classList.add("chat-hidden");
        } else {
          chatSidebar.classList.remove("chat-hidden");
        }

        // Aktifkan transisi setelah render pertama
        requestAnimationFrame(() => {
          document.documentElement.classList.add("loaded");
        });

        btnCloseChat.addEventListener("click", closeChat);
        btnOpenChat.addEventListener("click", openChat);
      } else {
        requestAnimationFrame(() => {
          document.documentElement.classList.add("loaded");
        });
      }

      // Profile Dropdown Toggle
      const profileDropdownBtn = document.getElementById("profile-dropdown-btn");
      const profileDropdownMenu = document.getElementById("profile-dropdown-menu");
      const profileCaret = document.getElementById("profile-caret");

      if (profileDropdownBtn && profileDropdownMenu) {
        const toggleProfileDropdown = (forceState) => {
          const isOpen = !profileDropdownMenu.classList.contains("hidden");
          const shouldOpen = typeof forceState === "boolean" ? forceState : !isOpen;

          if (shouldOpen) {
            profileDropdownMenu.classList.remove("hidden");
            profileDropdownBtn.setAttribute("aria-expanded", "true");
            if (profileCaret) profileCaret.classList.add("rotate-180");
          } else {
            profileDropdownMenu.classList.add("hidden");
            profileDropdownBtn.setAttribute("aria-expanded", "false");
            if (profileCaret) profileCaret.classList.remove("rotate-180");
          }
        };

        profileDropdownBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          toggleProfileDropdown();
        });

        // Klik di luar dropdown untuk menutup
        document.addEventListener("click", (e) => {
          if (!profileDropdownBtn.contains(e.target) && !profileDropdownMenu.contains(e.target)) {
            toggleProfileDropdown(false);
          }
        });

        // Tutup dengan tombol Escape
        document.addEventListener("keydown", (e) => {
          if (e.key === "Escape") {
            toggleProfileDropdown(false);
          }
        });
      }

      // Card Generator
      const sessionData = Array.from({ length: 4 }, (_, i) => ({
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
        if (!sessionsContainer) return;
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
          const parentCard = btn.closest(".border-2") || document;

          parentCard
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

          // Toggle dropdown game vs sport berdasarkan kategori yang dipilih
          const category = btn.dataset.category;
          if (category) {
            const card = btn.closest(".border-2");
            if (card) {
              const gameWrapper = card.querySelector(".game-dropdown-wrapper");
              const sportWrapper = card.querySelector(".sport-dropdown-wrapper");

              if (category === "esports") {
                gameWrapper?.classList.remove("hidden");
                sportWrapper?.classList.add("hidden");
              } else if (category === "olahraga") {
                gameWrapper?.classList.add("hidden");
                sportWrapper?.classList.remove("hidden");
              }
            }
          }
        });
      });

      document.querySelectorAll(".turnamen-filter").forEach((btn) => {
        btn.addEventListener("click", () => {
          const group = btn.dataset.filterGroup;
          document
            .querySelectorAll(`.turnamen-filter[data-filter-group="${group}"]`)
            .forEach((item) => {
              item.classList.remove("active", "border-red", "bg-card-alt", "text-white");
              item.classList.add("border-border", "text-muted");
              item.setAttribute("aria-pressed", "false");
            });

          btn.classList.remove("border-border", "text-muted");
          btn.classList.add("active", "border-red", "bg-card-alt", "text-white");
          btn.setAttribute("aria-pressed", "true");

          // Toggle dropdown game vs sport berdasarkan kategori yang dipilih di turnamen sidebar
          const category = btn.dataset.category;
          if (category) {
            const card = btn.closest(".border-2");
            if (card) {
              const gameWrapper = card.querySelector(".game-dropdown-wrapper");
              const sportWrapper = card.querySelector(".sport-dropdown-wrapper");

              if (category === "esports") {
                gameWrapper?.classList.remove("hidden");
                sportWrapper?.classList.add("hidden");
              } else if (category === "olahraga") {
                gameWrapper?.classList.add("hidden");
                sportWrapper?.classList.remove("hidden");
              }
            }
          }
        });
      });

      document.querySelectorAll(".tournament-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
          document.querySelectorAll(".tournament-tab").forEach((item) => {
            item.classList.remove("active", "border-red", "text-white");
            item.classList.add("border-transparent", "text-muted");
            item.setAttribute("aria-selected", "false");
          });

          tab.classList.remove("border-transparent", "text-muted");
          tab.classList.add("active", "border-red", "text-white");
          tab.setAttribute("aria-selected", "true");
        });
      });

      // Search Filter
      document
        .querySelector("#search-input")
        ?.addEventListener("input", (event) => {
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
        ?.addEventListener("click", (event) => {
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
            {
              title: "Futsal Weekend",
              place: "Lapangan Rampal, Malang",
              date: "Minggu, 27 September 2026",
              slot: "6",
              maxSlot: "10",
            },
            {
              title: "Padel Santuy",
              place: "Platinum, Malang",
              date: "Senin, 28 September 2026",
              slot: "2",
              maxSlot: "4",
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
        if (!toggle || !menu || !value) return;

        // Ambil input "lainnya" yang berelasi dengan dropdown ini (letaknya diluar .dropdown)
        const dropdownId = dropdown.dataset.dropdownId;
        const otherInput = dropdownId
          ? document.querySelector(`.dropdown-other-input[data-for="${dropdownId}"]`)
          : null;

        toggle.addEventListener("click", () => {
          const isOpen = !menu.classList.contains("hidden");
          closeDropdowns();
          menu.classList.toggle("hidden", isOpen);
          toggle.setAttribute("aria-expanded", String(!isOpen));
        });

        dropdown.querySelectorAll(".dropdown-option").forEach((option) => {
          option.addEventListener("click", () => {
            const isOther = option.dataset.name === "__other__";

            // Reset semua opsi
            dropdown.querySelectorAll(".dropdown-option").forEach((item) => {
              item.classList.remove("active", "bg-border", "text-white");
              item.classList.add("text-muted");
              item.setAttribute("aria-selected", "false");
            });

            if (isOther) {
              // Opsi "Game Lainnya" dipilih
              value.textContent = "Game lainnya...";
              value.classList.remove("text-muted");
              value.classList.add("text-white");

              // Sembunyikan img icon, tampilkan icon placeholder (pensil)
              const iconWrapper = toggle.querySelector(".dropdown-icon-wrapper");
              const placeholderIcon = toggle.querySelector(".dropdown-placeholder-icon");
              if (iconWrapper) iconWrapper.classList.add("hidden");
              if (placeholderIcon) {
                placeholderIcon.classList.remove("hidden");
                placeholderIcon.className = placeholderIcon.className.replace("ph-game-controller", "ph-pencil-simple");
              }

              // Tampilkan input custom
              if (otherInput) {
                otherInput.classList.remove("hidden");
                setTimeout(() => otherInput.focus(), 50);
              }
            } else {
              // Opsi game terdaftar dipilih
              const gameName = option.dataset.name || option.textContent.trim();
              const gameIcon = option.dataset.icon;

              value.textContent = gameName;
              value.classList.remove("text-muted");
              value.classList.add("text-white");

              // Tampilkan img icon, sembunyikan placeholder icon
              const iconWrapper = toggle.querySelector(".dropdown-icon-wrapper");
              const placeholderIcon = toggle.querySelector(".dropdown-placeholder-icon");
              const currentIcon = toggle.querySelector(".dropdown-current-icon");

              if (iconWrapper) iconWrapper.classList.remove("hidden");
              if (placeholderIcon) placeholderIcon.classList.add("hidden");
              if (currentIcon && gameIcon) {
                currentIcon.src = gameIcon;
                currentIcon.alt = gameName;
              }

              // Sembunyikan input custom
              if (otherInput) {
                otherInput.classList.add("hidden");
                otherInput.value = "";
              }
            }

            option.classList.remove("text-muted");
            option.classList.add("active", "bg-border", "text-white");
            option.setAttribute("aria-selected", "true");
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
      document.querySelector("#find-session")?.addEventListener("click", () => {
        document.querySelector("#search-input")?.focus();
      });

      // Mobile Menu
      document.querySelector("#menu-btn")?.addEventListener("click", () => {
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
        ?.addEventListener("submit", (event) => {
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

      // Poster Upload Preview
      const posterInput = document.querySelector("#poster-upload");
      const posterPreview = document.querySelector("#poster-preview");
      const posterPlaceholder = document.querySelector("#poster-placeholder");
      const posterRemove = document.querySelector("#poster-remove");

      if (posterInput) {
        posterInput.addEventListener("change", (e) => {
          const file = e.target.files[0];
          if (!file) return;

          // Validasi ukuran maks 5MB
          if (file.size > 5 * 1024 * 1024) {
            alert("Ukuran file terlalu besar. Maksimal 5MB.");
            posterInput.value = "";
            return;
          }

          const reader = new FileReader();
          reader.onload = (ev) => {
            posterPreview.src = ev.target.result;
            posterPreview.classList.remove("hidden");
            posterPlaceholder.classList.add("hidden");
            posterRemove.classList.remove("hidden");
          };
          reader.readAsDataURL(file);
        });
      }

      if (posterRemove) {
        posterRemove.addEventListener("click", () => {
          posterInput.value = "";
          posterPreview.src = "";
          posterPreview.classList.add("hidden");
          posterPlaceholder.classList.remove("hidden");
          posterRemove.classList.add("hidden");
        });
      }

      // ── Flatpickr Date Picker ──
      if (typeof flatpickr !== "undefined") {
        const flatpickrConfig = {
          dateFormat: "d/m/y",       // dd/mm/yy
          disableMobile: true,       // selalu pakai Flatpickr, bukan native mobile
          locale: {
            months: {
              shorthand: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"],
              longhand: ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"]
            },
            weekdays: {
              shorthand: ["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
              longhand: ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"]
            },
            firstDayOfWeek: 1
          },
          onReady(_, __, fp) {
            // Klik icon kalender juga buka picker
            const icon = fp.input.closest(".date-input-wrapper")?.querySelector(".date-icon");
            if (icon) icon.addEventListener("click", () => fp.open());
          },
          onChange(selectedDates, _, fp) {
            fp.input.classList.toggle("has-value", selectedDates.length > 0);
            if (selectedDates.length > 0) fp.input.classList.replace("text-muted", "text-white") || fp.input.classList.add("text-white");
          }
        };

        const mulai = document.getElementById("tanggal-mulai");
        const selesai = document.getElementById("tanggal-selesai");
        if (mulai) flatpickr(mulai, flatpickrConfig);
        if (selesai) flatpickr(selesai, flatpickrConfig);
      }


      // -- City Combobox --
      const KOTA_JATIM = [
        "Kota Surabaya", "Kota Malang", "Kota Kediri", "Kota Blitar",
        "Kota Madiun", "Kota Mojokerto", "Kota Probolinggo", "Kota Pasuruan", "Kota Batu",
        "Kab. Gresik", "Kab. Sidoarjo", "Kab. Lamongan", "Kab. Tuban", "Kab. Bojonegoro",
        "Kab. Ngawi", "Kab. Madiun", "Kab. Magetan", "Kab. Nganjuk", "Kab. Jombang",
        "Kab. Mojokerto", "Kab. Kediri", "Kab. Tulungagung", "Kab. Blitar", "Kab. Malang",
        "Kab. Lumajang", "Kab. Jember", "Kab. Bondowoso", "Kab. Situbondo",
        "Kab. Banyuwangi", "Kab. Probolinggo", "Kab. Pasuruan", "Kab. Trenggalek",
        "Kab. Ponorogo", "Kab. Pacitan", "Kab. Sampang", "Kab. Pamekasan",
        "Kab. Sumenep", "Kab. Bangkalan"
      ];

      document.querySelectorAll(".city-combobox").forEach((combobox) => {
        const input  = combobox.querySelector(".city-combobox-input");
        const list   = combobox.querySelector(".city-combobox-list");
        const caret  = combobox.querySelector(".city-combobox-caret");
        let selected = "";

        const renderList = (filter = "") => {
          const q = filter.toLowerCase();
          const matches = KOTA_JATIM.filter(k => k.toLowerCase().includes(q));
          list.innerHTML = matches.length
            ? matches.map(k => `<li class="city-option px-3 py-2 text-[13px] cursor-pointer transition-colors hover:bg-border ${k === selected ? "text-white font-medium" : "text-muted hover:text-white"}" role="option" data-value="${k}">${k}</li>`).join("")
            : `<li class="px-3 py-2 text-[13px] text-muted italic">Kota tidak ditemukan</li>`;
        };

        const openList = () => {
          renderList(input.value);
          list.classList.remove("hidden");
          caret.style.transform = "rotate(180deg)";
        };

        const closeList = () => {
          list.classList.add("hidden");
          caret.style.transform = "";
        };

        input.addEventListener("focus", () => openList());
        input.addEventListener("input", () => openList());

        list.addEventListener("click", (e) => {
          const opt = e.target.closest(".city-option");
          if (!opt) return;
          selected = opt.dataset.value;
          input.value = selected;
          input.classList.remove("text-muted");
          input.classList.add("text-white");
          closeList();
        });

        document.addEventListener("click", (e) => {
          if (!combobox.contains(e.target)) closeList();
        });
      });