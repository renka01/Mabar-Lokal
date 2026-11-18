document.addEventListener("DOMContentLoaded", () => {
  const sessionsContainer = document.querySelector("#sessions");
  if (!sessionsContainer) return;

  const isInsidePages = window.location.pathname.includes("/pages/");
  const basePath = typeof window.MABAR_BASE_PATH === "string"
    ? window.MABAR_BASE_PATH
    : (isInsidePages ? "../../" : "");

  const resolveAsset = typeof window.mabarResolveAsset === "function"
    ? window.mabarResolveAsset
    : (src) => {
        if (!src) return "";
        if (/^(?:data:|blob:|https?:\/\/|\/)/i.test(src)) return src;
        return basePath + src;
      };

  function escapeHtml(str) {
    return String(str ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  let currentOffset = 0;
  const PAGE_LIMIT = 8;
  let loadedSessions = [];
  let currentFeatured = null;
  let activeFetchSeq = 0;

  async function joinAndGoToSession(slug, triggerBtn = null) {
    if (!slug) return;
    const auth = window.MABAR_AUTH || {};
    if (!auth.authenticated) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Silakan masuk akun terlebih dahulu untuk bergabung ke sesi mabar!", "error");
      }
      setTimeout(() => {
        window.location.href = `${basePath}pages/auth.php`;
      }, 750);
      return;
    }

    const prevText = triggerBtn ? triggerBtn.innerHTML : "";
    if (triggerBtn) {
      triggerBtn.disabled = true;
      triggerBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin"></i>`;
    }

    try {
      const res = await fetch(`${basePath}api/sessions/join.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        credentials: "same-origin",
        body: JSON.stringify({ session_id: slug })
      });
      const data = await res.json();
      if (!res.ok || !data || data.ok === false) {
        throw new Error((data && data.message) || "Gagal bergabung ke sesi.");
      }

      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Berhasil bergabung ke lobby sesi!", "sukses");
      }
      window.location.href = `${basePath}pages/sesi/index.php?id=${encodeURIComponent(data.slug || slug)}`;
    } catch (err) {
      if (triggerBtn) {
        triggerBtn.disabled = false;
        triggerBtn.innerHTML = prevText;
      }
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal bergabung ke sesi.", "error");
      }
    }
  }

  const templateKartuSesi = (item) => {
    const isOlahraga = item.category === "olahraga";
    const isFull = Number(item.slot) >= Number(item.maxSlot);
    const boxBg = isOlahraga ? "bg-[#0c1a17] border-[#19d66b]/30" : "bg-[#260f14] border-red/30";

    const tagBadge = isOlahraga
      ? `<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wider bg-[#19d66b]/15 text-[#19d66b] uppercase">Olahraga</span>`
      : `<span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold tracking-wider bg-red/15 text-red uppercase">Esports</span>`;

    const joinBtnClass = isFull
      ? "border border-border bg-card-alt text-muted cursor-not-allowed opacity-70"
      : (isOlahraga
          ? "border border-[#19d66b]/50 bg-[#19d66b]/5 text-[#19d66b] hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_12px_rgba(25,214,107,0.3)] cursor-pointer"
          : "border border-red/50 bg-red/5 text-red hover:bg-red hover:text-white hover:shadow-[0_0_12px_rgba(239,35,60,0.3)] cursor-pointer");

    const hoverTitleColor = isOlahraga ? "group-hover:text-[#19d66b] card-title-sport" : "group-hover:text-red card-title-esport";
    const sessionUrl = `${basePath}pages/sesi/index.php?id=${encodeURIComponent(item.slug)}`;
    const iconSrc = resolveAsset(item.icon);

    return `
      <article
        class="session-card group relative flex flex-col justify-between rounded-lg border border-border bg-card p-5 transition-all duration-200 hover:-translate-y-1 hover:border-muted hover:shadow-lg hover:shadow-black/40 cursor-pointer"
        data-session-url="${escapeHtml(sessionUrl)}"
        title="Klik kartu untuk melihat info sesi & lobby"
      >
        <div class="flex items-start gap-4">
          <div class="grid h-12 w-12 shrink-0 place-items-center rounded-lg border ${boxBg} p-2 text-2xl">
            <img src="${escapeHtml(iconSrc)}" alt="${escapeHtml(item.game)}" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-1">
              ${tagBadge}
              <span class="text-[11px] font-medium text-muted truncate">${escapeHtml(item.game)}</span>
            </div>
            <h3 class="text-[16px] font-bold text-white truncate tracking-tight transition-colors ${hoverTitleColor}" title="${escapeHtml(item.title)}">${escapeHtml(item.title)}</h3>
          </div>
          <div class="text-right shrink-0">
            <div class="text-[20px] font-bold leading-none text-white">${Number(item.slot)} <span class="text-[14px] text-muted font-normal">/ ${Number(item.maxSlot)}</span></div>
            <div class="text-[10px] text-muted mt-1 uppercase font-semibold tracking-wider">slot</div>
          </div>
        </div>

        <div class="mt-5 flex items-center justify-between border-t border-border/50 pt-4">
          <div class="flex flex-col gap-1 min-w-0 pr-3">
            <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
              <i class="ph-bold ph-map-pin text-sm text-muted shrink-0"></i>
              <span class="truncate max-w-[220px]" title="${escapeHtml(item.place)}">${escapeHtml(item.place)}</span>
            </div>
            <div class="flex items-center text-[12px] text-muted gap-2 font-medium">
              <i class="ph-bold ph-calendar-blank text-sm text-muted shrink-0"></i>
              <span class="truncate">${escapeHtml(item.date)}</span>
            </div>
          </div>
          <div class="shrink-0">
            <button
              type="button"
              class="join-session inline-flex items-center justify-center h-8 px-5 rounded-md text-[12px] font-semibold tracking-wide transition-all duration-200 active:scale-95 ${joinBtnClass}"
              data-session-slug="${escapeHtml(item.slug)}"
              ${isFull ? "disabled" : ""}
              title="${isFull ? "Slot sesi sudah penuh" : "Gabung langsung ke lobby sesi"}"
            >
              ${isFull ? "Penuh" : "Gabung"}
            </button>
          </div>
        </div>
      </article>
    `;
  };

  const renderFeaturedMatch = (item) => {
    const featuredMatch = document.getElementById("featured-match");
    if (!featuredMatch) return;

    if (!item) {
      featuredMatch.classList.add("hidden");
      return;
    }

    featuredMatch.classList.remove("hidden");
    const isOlahraga = item.category === "olahraga";
    const isFull = Number(item.slot) >= Number(item.maxSlot);
    const detailUrl = `${basePath}pages/sesi/index.php?id=${encodeURIComponent(item.slug)}`;
    featuredMatch.dataset.detailUrl = detailUrl;

    const iconImg = document.getElementById("featured-icon-img");
    const iconBox = document.getElementById("featured-icon-box");
    const tag1 = document.getElementById("featured-tag-1");
    const tag2 = document.getElementById("featured-tag-2");
    const title = document.getElementById("featured-title");
    const desc = document.getElementById("featured-desc");
    const slotCount = document.getElementById("featured-slot-count");
    const avatarsWrap = document.getElementById("featured-avatars");
    const joinBtn = document.getElementById("featured-join-btn");
    const watermark = document.getElementById("featured-watermark");

    if (iconImg) {
      iconImg.src = resolveAsset(item.icon);
      iconImg.alt = item.game;
    }

    if (tag2) {
      tag2.textContent = String(item.game || "").toUpperCase();
      tag2.className = "inline-flex items-center rounded-full bg-card-alt border border-border px-3 py-1 text-[10px] font-bold tracking-wider text-muted uppercase";
    }

    if (title) {
      title.textContent = item.title;
      title.className = isOlahraga
        ? "text-[32px] font-bold tracking-tight text-white mb-2 leading-none transition-colors group-hover:text-[#19d66b] card-title-sport"
        : "text-[32px] font-bold tracking-tight text-white mb-2 leading-none transition-colors group-hover:text-red card-title-esport";
    }

    if (desc) {
      desc.textContent = item.description || `${item.place} • ${item.date}`;
    }

    if (slotCount) {
      slotCount.innerHTML = `${Number(item.slot)}<span class="text-[28px] text-muted font-normal">/${Number(item.maxSlot)}</span>`;
    }

    if (avatarsWrap) {
      const avList = Array.isArray(item.avatars) && item.avatars.length > 0
        ? item.avatars.slice(0, 4)
        : Array.from({ length: Math.min(3, Math.max(1, Number(item.slot))) }, () => ({ name: "Pemain", avatar: "" }));

      avatarsWrap.innerHTML = avList
        .map((p, idx) => {
          const marginCls = idx < avList.length - 1 ? "-mr-2" : "";
          const avUrl = p.avatar ? resolveAsset(p.avatar) : "";
          const inner = avUrl
            ? `<img src="${escapeHtml(avUrl)}" alt="${escapeHtml(p.name)}" class="w-full h-full object-cover" />`
            : `<i class="ph-fill ph-user text-sm"></i>`;
          return `
            <div class="relative ${marginCls} h-7 w-7 rounded-full border border-border flex items-center justify-center overflow-hidden" style="background-color: #1a2636; color: #9ba5b4;" title="${escapeHtml(p.name)}">
              ${inner}
            </div>
          `;
        })
        .join("");
    }

    if (isOlahraga) {
      featuredMatch.classList.remove("border-red");
      featuredMatch.style.borderColor = "#19d66b";
      if (iconBox) {
        iconBox.classList.remove("border-red", "text-red");
        iconBox.style.borderColor = "#19d66b";
        iconBox.style.color = "#19d66b";
      }
      if (tag1) {
        tag1.className = "inline-flex items-center gap-1.5 rounded-full bg-[#19d66b]/15 text-[#19d66b] border border-[#19d66b]/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase";
        tag1.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-[#19d66b]"></span> SEGERA MULAI`;
      }
      if (watermark) {
        watermark.innerHTML = `<i class="ph-fill ph-trophy text-[240px]" style="color: #0c2518;"></i>`;
      }
    } else {
      featuredMatch.style.borderColor = "";
      featuredMatch.classList.add("border-red");
      if (iconBox) {
        iconBox.style.borderColor = "";
        iconBox.style.color = "";
        iconBox.classList.add("border-red", "text-red");
      }
      if (tag1) {
        tag1.className = "inline-flex items-center gap-1.5 rounded-full bg-red/15 text-red border border-red/30 px-3 py-1 text-[10px] font-bold tracking-wider uppercase";
        tag1.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-red"></span> SEGERA MULAI`;
      }
      if (watermark) {
        watermark.innerHTML = `<i class="ph-fill ph-triangle text-[240px] text-red-dark"></i>`;
      }
    }

    if (joinBtn) {
      joinBtn.dataset.sessionSlug = item.slug;
      joinBtn.disabled = isFull;
      joinBtn.textContent = isFull ? "Slot Penuh" : "Gabung Slot";
      joinBtn.className = isFull
        ? "inline-flex items-center justify-center h-10 px-6 rounded-md border border-border text-muted bg-card-alt text-[13px] font-bold tracking-wide opacity-70 cursor-not-allowed text-center"
        : (isOlahraga
            ? "inline-flex items-center justify-center h-10 px-6 rounded-md border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)] text-center cursor-pointer"
            : "inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all active:scale-95 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] text-center cursor-pointer");
    }
  };

  const renderDaftarSesi = (items) => {
    if (!items || items.length === 0) {
      if (currentFeatured) {
        sessionsContainer.innerHTML = "";
        return;
      }
      sessionsContainer.innerHTML = `
        <div class="mabar-empty-state rounded-xl border border-dashed border-border bg-card/60">
          <div class="mabar-empty-icon">
            <i class="ph ph-magnifying-glass"></i>
          </div>
          <h3 class="text-[17px] font-bold text-white mb-1.5">Tidak Ada Sesi Ditemukan</h3>
          <p class="mabar-empty-desc">
            Belum ada pertandingan yang cocok dengan filter yang Anda pilih. Coba ganti kata kunci atau buat sesi baru!
          </p>
          <button
            id="btn-reset-filter-sesi"
            type="button"
            class="px-4 py-2 rounded-md border border-border bg-card-alt text-[12px] font-semibold text-white hover:border-muted transition-colors cursor-pointer"
          >
            Reset Filter
          </button>
        </div>
      `;

      document.getElementById("btn-reset-filter-sesi")?.addEventListener("click", () => {
        resetSemuaFilter();
      });
      return;
    }

    sessionsContainer.innerHTML = items.map(templateKartuSesi).join("");
  };

  const readFilters = () => {
    const sidebarCari = document.getElementById("sidebar-cari-sesi") || document.querySelector("aside .border-2");

    let category = "esports";
    if (sidebarCari) {
      const activeCatBtn = sidebarCari.querySelector(
        "[data-group='category'].text-white, [data-group='category'].border-red, [data-group='category'].border-\\[\\#19d66b\\]"
      );
      if (activeCatBtn && activeCatBtn.dataset.category) {
        category = activeCatBtn.dataset.category.toLowerCase();
      }
    }

    let game = "";
    if (sidebarCari) {
      const activeWrapper = category === "olahraga"
        ? sidebarCari.querySelector(".sport-dropdown-wrapper")
        : sidebarCari.querySelector(".game-dropdown-wrapper");
      const valEl = activeWrapper?.querySelector(".dropdown-value");
      if (valEl && !valEl.textContent.includes("Pilih") && !valEl.textContent.includes("--")) {
        game = valEl.textContent.trim();
      }
    }

    let city = "";
    if (sidebarCari) {
      const cityInput = sidebarCari.querySelector(".city-combobox-input");
      if (cityInput && cityInput.value && !cityInput.value.toLowerCase().includes("cari kota")) {
        city = cityInput.value.trim();
      }
    }

    const searchInput = document.getElementById("search-input");
    const q = searchInput ? searchInput.value.trim() : "";

    const slotCheckbox = document.getElementById("filter-slot-tersedia");
    const availableOnly = Boolean(slotCheckbox && slotCheckbox.checked);

    return { category, game, city, q, availableOnly };
  };

  const fetchAndRenderSessions = async (append = false) => {
    const seq = ++activeFetchSeq;
    const filters = readFilters();
    const loadMoreWrapper = document.getElementById("load-more-wrapper");

    if (!append) {
      currentOffset = 0;
      loadedSessions = [];
    }

    const params = new URLSearchParams();
    params.set("category", filters.category);
    if (filters.game) params.set("game", filters.game);
    if (filters.city) params.set("city", filters.city);
    if (filters.q) params.set("q", filters.q);
    if (filters.availableOnly) params.set("available_only", "1");
    params.set("limit", String(PAGE_LIMIT));
    params.set("offset", String(currentOffset));

    try {
      const res = await fetch(`${basePath}api/sessions/list.php?${params.toString()}`, {
        headers: { Accept: "application/json" },
        credentials: "same-origin"
      });
      const data = await res.json();
      if (seq !== activeFetchSeq) return;

      if (!res.ok || !data || data.ok === false) {
        throw new Error((data && data.message) || "Gagal memuat daftar sesi.");
      }

      if (!append) {
        currentFeatured = data.featured || null;
        renderFeaturedMatch(currentFeatured);
        loadedSessions = Array.isArray(data.sessions) ? data.sessions : [];
      } else {
        const nextItems = Array.isArray(data.sessions) ? data.sessions : [];
        const existingIds = new Set(loadedSessions.map((s) => s.id));
        if (currentFeatured) existingIds.add(currentFeatured.id);
        nextItems.forEach((item) => {
          if (!existingIds.has(item.id)) {
            loadedSessions.push(item);
          }
        });
      }

      currentOffset += PAGE_LIMIT;
      renderDaftarSesi(loadedSessions);

      if (loadMoreWrapper) {
        loadMoreWrapper.classList.toggle("hidden", !data.has_more);
      }
    } catch (err) {
      if (seq !== activeFetchSeq) return;
      renderFeaturedMatch(null);
      sessionsContainer.innerHTML = `
        <div class="col-span-full flex flex-col items-center justify-center p-10 text-center rounded-lg border border-red/30 bg-card/60">
          <i class="ph-bold ph-warning-circle text-3xl text-red mb-2"></i>
          <h3 class="text-[15px] font-bold text-white mb-1">Gagal Memuat Sesi Mabar</h3>
          <p class="text-[12.5px] text-muted">${escapeHtml(err.message || "Periksa koneksi database PostgreSQL.")}</p>
        </div>
      `;
      if (loadMoreWrapper) loadMoreWrapper.classList.add("hidden");
    }
  };

  const resetSemuaFilter = () => {
    const searchInput = document.getElementById("search-input");
    if (searchInput) searchInput.value = "";

    const sidebarCari = document.getElementById("sidebar-cari-sesi") || document.querySelector("aside .border-2");
    if (sidebarCari) {
      const cityInput = sidebarCari.querySelector(".city-combobox-input");
      if (cityInput) {
        cityInput.value = "";
        cityInput.classList.remove("text-white");
        cityInput.classList.add("text-muted");
      }

      sidebarCari.querySelectorAll(".dropdown-value").forEach((el) => {
        el.textContent = el.closest(".sport-dropdown-wrapper") ? "Pilih Olahraga..." : "Pilih Game...";
        el.classList.add("text-muted");
      });
      sidebarCari.querySelectorAll(".dropdown-icon-wrapper").forEach((el) => el.classList.add("hidden"));
      sidebarCari.querySelectorAll(".dropdown-placeholder-icon").forEach((el) => el.classList.remove("hidden"));

      const slotCheckbox = document.getElementById("filter-slot-tersedia");
      if (slotCheckbox) slotCheckbox.checked = false;
    }

    fetchAndRenderSessions(false);
  };

  // Delegasi klik kartu & tombol gabung
  sessionsContainer.addEventListener("click", (e) => {
    const joinBtn = e.target.closest(".join-session");
    if (joinBtn) {
      e.stopPropagation();
      const slug = joinBtn.dataset.sessionSlug;
      if (slug && !joinBtn.disabled) {
        joinAndGoToSession(slug, joinBtn);
      }
      return;
    }
    const card = e.target.closest(".session-card");
    if (card && card.dataset.sessionUrl) {
      window.location.href = card.dataset.sessionUrl;
    }
  });

  const featuredEl = document.getElementById("featured-match");
  featuredEl?.addEventListener("click", (e) => {
    const joinBtn = e.target.closest("#featured-join-btn");
    if (joinBtn) {
      e.stopPropagation();
      const slug = joinBtn.dataset.sessionSlug;
      if (slug && !joinBtn.disabled) {
        joinAndGoToSession(slug, joinBtn);
      }
      return;
    }
    if (featuredEl.dataset.detailUrl) {
      window.location.href = featuredEl.dataset.detailUrl;
    }
  });

  const sidebarCari = document.getElementById("sidebar-cari-sesi") || document.querySelector("aside .border-2");
  if (sidebarCari) {
    sidebarCari.querySelectorAll(".filter-tab").forEach((btn) => {
      btn.addEventListener("click", () => setTimeout(() => fetchAndRenderSessions(false), 40));
    });

    sidebarCari.querySelectorAll(".dropdown-option").forEach((opt) => {
      opt.addEventListener("click", () => setTimeout(() => fetchAndRenderSessions(false), 40));
    });

    let cityDebounce = null;
    const cityInput = sidebarCari.querySelector(".city-combobox-input");
    cityInput?.addEventListener("input", () => {
      clearTimeout(cityDebounce);
      cityDebounce = setTimeout(() => fetchAndRenderSessions(false), 220);
    });

    sidebarCari.querySelectorAll(".city-combobox-item").forEach((item) => {
      item.addEventListener("click", () => setTimeout(() => fetchAndRenderSessions(false), 40));
    });

    document.getElementById("find-session")?.addEventListener("click", () => {
      fetchAndRenderSessions(false);
      sessionsContainer.scrollIntoView({ behavior: "smooth", block: "start" });
    });

    document.getElementById("filter-slot-tersedia")?.addEventListener("change", () => {
      fetchAndRenderSessions(false);
    });
  }

  let searchDebounce = null;
  document.getElementById("search-input")?.addEventListener("input", () => {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => fetchAndRenderSessions(false), 220);
  });

  document.getElementById("load-more")?.addEventListener("click", () => {
    fetchAndRenderSessions(true);
  });

  fetchAndRenderSessions(false);
});
