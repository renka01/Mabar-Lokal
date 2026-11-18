(function () {
  const POLL_INTERVAL_MS = 20000;

  const wrapper = document.getElementById("notification-wrapper");
  const triggerBtn = document.getElementById("notification-btn");
  const badgeEl = document.getElementById("notification-badge");
  const popover = document.getElementById("notification-popover");
  const listEl = document.getElementById("notification-list");
  const unreadPillEl = document.getElementById("notification-unread-pill");
  const markAllBtn = document.getElementById("btn-notif-mark-all");
  const clearReadBtn = document.getElementById("btn-notif-clear-read");
  const totalInfoEl = document.getElementById("notification-total-info");

  if (!wrapper || !triggerBtn || !popover || !listEl) return;

  const basePath = wrapper.dataset.basePath || window.MABAR_BASE_PATH || "";
  let activeFilter = "all";
  let notifications = [];
  let isLoading = true;
  let isFetching = false;

  const CATEGORY_META = {
    mabar: { label: "Cari Mabar", icon: "ph-users-three", cls: "notif-cat-mabar" },
    scrim: { label: "Scrim", icon: "ph-sword", cls: "notif-cat-scrim" },
    turnamen: { label: "Turnamen", icon: "ph-trophy", cls: "notif-cat-turnamen" },
    chat: { label: "Chat", icon: "ph-chat-circle-dots", cls: "notif-cat-chat" },
    sistem: { label: "Akun", icon: "ph-shield-check", cls: "notif-cat-sistem" }
  };

  function isAuthenticated() {
    return Boolean(window.MABAR_AUTH && window.MABAR_AUTH.authenticated);
  }

  function escapeHtml(str) {
    return String(str ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function formatRelativeTime(ts) {
    if (!ts || Number.isNaN(Number(ts))) return "Baru saja";
    const diffSec = Math.max(0, Math.floor((Date.now() - Number(ts)) / 1000));
    if (diffSec < 60) return "Baru saja";
    const diffMin = Math.floor(diffSec / 60);
    if (diffMin < 60) return `${diffMin} menit lalu`;
    const diffHour = Math.floor(diffMin / 60);
    if (diffHour < 24) return `${diffHour} jam lalu`;
    const diffDay = Math.floor(diffHour / 24);
    if (diffDay === 1) return "Kemarin";
    return `${diffDay} hari lalu`;
  }

  function resolveHref(relHref) {
    if (!relHref) return "";
    if (/^(https?:|#|\/)/i.test(relHref)) return relHref;
    return basePath + relHref;
  }

  function getFilteredItems(list) {
    if (activeFilter === "all") return list;
    if (activeFilter === "unread") return list.filter((n) => !n.read);
    return list.filter((n) => n.category === activeFilter);
  }

  function updateBadgeUI(unreadCount) {
    if (badgeEl) {
      if (unreadCount > 0) {
        badgeEl.textContent = unreadCount > 99 ? "99+" : String(unreadCount);
        badgeEl.classList.remove("hidden");
      } else {
        badgeEl.textContent = "0";
        badgeEl.classList.add("hidden");
      }
    }

    if (unreadPillEl) {
      if (unreadCount > 0) {
        unreadPillEl.textContent = `${unreadCount} Baru`;
        unreadPillEl.classList.remove("hidden");
      } else {
        unreadPillEl.classList.add("hidden");
      }
    }
  }

  function renderNotifications() {
    if (!isAuthenticated()) {
      updateBadgeUI(0);
      if (markAllBtn) markAllBtn.disabled = true;
      if (clearReadBtn) clearReadBtn.disabled = true;
      if (totalInfoEl) totalInfoEl.textContent = "Mode Tamu";

      listEl.innerHTML = `
        <div class="notif-empty-state">
          <div class="notif-empty-icon">
            <i class="ph ph-lock-simple"></i>
          </div>
          <p class="notif-empty-title">Masuk untuk melihat notifikasi</p>
          <p class="notif-empty-desc">Pantau undangan mabar, status scrim, verifikasi turnamen, dan pesan baru di sini.</p>
          <a href="${escapeHtml(basePath)}pages/auth.php" class="mt-3 inline-flex items-center gap-1.5 rounded-md bg-red px-3.5 py-2 text-xs font-semibold text-white hover:bg-red-hover transition-colors">
            <i class="ph-bold ph-sign-in"></i>
            <span>Masuk / Daftar</span>
          </a>
        </div>
      `;
      return;
    }

    const unreadCount = notifications.filter((n) => !n.read).length;
    const readCount = notifications.length - unreadCount;
    const filtered = getFilteredItems(notifications);

    updateBadgeUI(unreadCount);

    if (markAllBtn) {
      markAllBtn.disabled = unreadCount === 0;
    }

    if (clearReadBtn) {
      clearReadBtn.disabled = readCount === 0;
    }

    if (totalInfoEl) {
      totalInfoEl.textContent = `${notifications.length} notifikasi`;
    }

    if (isLoading && notifications.length === 0) {
      listEl.innerHTML = `
        <div class="notif-empty-state">
          <div class="notif-empty-icon">
            <i class="ph ph-spinner"></i>
          </div>
          <p class="notif-empty-title">Memuat notifikasi...</p>
          <p class="notif-empty-desc">Mengambil aktivitas terbaru akunmu dari server.</p>
        </div>
      `;
      return;
    }

    if (filtered.length === 0) {
      const isAllEmpty = notifications.length === 0;
      const emptyTitle = activeFilter === "unread"
        ? "Semua notifikasi sudah dibaca"
        : isAllEmpty
          ? "Belum ada notifikasi"
          : "Tidak ada notifikasi di kategori ini";
      const emptyDesc = activeFilter === "unread"
        ? "Semua aktivitas mabar, scrim, dan turnamenmu sudah terpantau."
        : "Aktivitas baru dari sesi mabar, scrim, atau turnamen akan muncul di sini.";

      listEl.innerHTML = `
        <div class="notif-empty-state">
          <div class="notif-empty-icon">
            <i class="ph ${activeFilter === "unread" ? "ph-check-circle" : "ph-bell-slash"}"></i>
          </div>
          <p class="notif-empty-title">${emptyTitle}</p>
          <p class="notif-empty-desc">${emptyDesc}</p>
        </div>
      `;
      return;
    }

    listEl.innerHTML = filtered
      .map((item) => {
        const meta = CATEGORY_META[item.category] || CATEGORY_META.sistem;
        const timeStr = formatRelativeTime(item.createdAt);
        const actionHtml = item.actionLabel
          ? `<span class="notif-item-action">${escapeHtml(item.actionLabel)} <i class="ph-bold ph-arrow-right"></i></span>`
          : "";

        return `
          <div
            class="notif-item ${item.read ? "" : "unread"}"
            role="button"
            tabindex="0"
            data-notif-id="${escapeHtml(item.id)}"
          >
            <div class="notif-icon-box ${meta.cls}">
              <i class="ph-bold ${meta.icon}"></i>
            </div>
            <div class="notif-item-content">
              <div class="notif-item-meta-row">
                <span class="notif-item-cat">${escapeHtml(meta.label)}</span>
                <span class="notif-meta-sep">•</span>
                <span class="notif-item-time">${escapeHtml(timeStr)}</span>
                ${item.read ? "" : '<span class="notif-unread-dot" title="Belum dibaca"></span>'}
              </div>
              <p class="notif-item-title">${escapeHtml(item.title)}</p>
              <p class="notif-item-msg">${escapeHtml(item.message)}</p>
              ${actionHtml}
            </div>
            <button
              type="button"
              class="notif-dismiss-btn"
              data-notif-dismiss="${escapeHtml(item.id)}"
              aria-label="Hapus notifikasi"
              title="Hapus notifikasi"
            >
              <i class="ph-bold ph-x"></i>
            </button>
          </div>
        `;
      })
      .join("");
  }

  async function fetchNotifications(opts = {}) {
    if (!isAuthenticated()) {
      notifications = [];
      isLoading = false;
      renderNotifications();
      return [];
    }
    if (isFetching) return notifications;

    isFetching = true;
    if (!opts.silent && notifications.length === 0) {
      isLoading = true;
      renderNotifications();
    }

    try {
      const res = await fetch(`${basePath}api/notifications/list.php`, {
        method: "GET",
        credentials: "same-origin",
        headers: { Accept: "application/json" }
      });
      const json = await res.json();
      if (res.ok && json && json.ok) {
        const list = Array.isArray(json.notifications) ? json.notifications : [];
        notifications = list.map((n) => ({
          id: String(n.id),
          numericId: Number(n.id) || 0,
          category: String(n.category || "sistem"),
          title: String(n.title || ""),
          message: String(n.message || ""),
          actionLabel: String(n.actionLabel || ""),
          href: String(n.href || ""),
          action: String(n.action || ""),
          chatTarget: String(n.chatTarget || ""),
          chatSubtitle: String(n.chatSubtitle || ""),
          read: Boolean(n.read),
          createdAt: Number(n.createdAt) || Date.now()
        }));
      }
    } catch (e) {
      // Pertahankan state terakhir bila jaringan sementara terganggu
    } finally {
      isLoading = false;
      isFetching = false;
      renderNotifications();
    }

    return notifications;
  }

  async function postAction(payload, opts = {}) {
    if (!isAuthenticated()) return null;
    try {
      const res = await fetch(`${basePath}api/notifications/action.php`, {
        method: "POST",
        credentials: "same-origin",
        keepalive: Boolean(opts.keepalive),
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify(payload)
      });
      const json = await res.json();
      if (res.ok && json && json.ok) {
        return json;
      }
    } catch (e) {}
    return null;
  }

  function setPopoverState(shouldOpen) {
    if (shouldOpen) {
      if (typeof window.closeProfileDropdown === "function") {
        window.closeProfileDropdown();
      }
      renderNotifications();
      popover.classList.remove("hidden");
      triggerBtn.classList.add("is-open");
      triggerBtn.setAttribute("aria-expanded", "true");
      if (isAuthenticated()) {
        fetchNotifications({ silent: true });
      }
    } else {
      popover.classList.add("hidden");
      triggerBtn.classList.remove("is-open");
      triggerBtn.setAttribute("aria-expanded", "false");
    }
  }

  window.closeNotificationPopover = function () {
    setPopoverState(false);
  };

  triggerBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    const isCurrentlyOpen = !popover.classList.contains("hidden");
    setPopoverState(!isCurrentlyOpen);
  });

  popover.addEventListener("click", async (e) => {
    const tabBtn = e.target.closest("[data-notif-filter]");
    if (tabBtn) {
      activeFilter = tabBtn.dataset.notifFilter || "all";
      popover.querySelectorAll("[data-notif-filter]").forEach((btn) => {
        btn.classList.toggle("active", btn === tabBtn);
      });
      renderNotifications();
      return;
    }

    const dismissBtn = e.target.closest("[data-notif-dismiss]");
    if (dismissBtn) {
      e.stopPropagation();
      const targetId = String(dismissBtn.dataset.notifDismiss || "");
      const target = notifications.find((n) => n.id === targetId);
      notifications = notifications.filter((n) => n.id !== targetId);
      renderNotifications();
      if (target && target.numericId > 0) {
        await postAction({ action: "dismiss", id: target.numericId });
      }
      return;
    }

    const itemEl = e.target.closest("[data-notif-id]");
    if (itemEl) {
      const targetId = String(itemEl.dataset.notifId || "");
      const clicked = notifications.find((n) => n.id === targetId);
      if (!clicked) return;

      if (!clicked.read) {
        clicked.read = true;
        renderNotifications();
        if (clicked.numericId > 0) {
          postAction({ action: "mark_read", id: clicked.numericId }, { keepalive: true });
        }
      }

      if (clicked.action === "openChat") {
        setPopoverState(false);
        const targetChat = clicked.chatTarget || "";
        const targetSub = clicked.chatSubtitle || "";
        if (document.getElementById("chat-sidebar") && window.MabarChat && typeof window.MabarChat.openWith === "function") {
          window.MabarChat.openWith(targetChat, targetSub);
        } else if (document.getElementById("chat-sidebar") && typeof window.openChat === "function") {
          window.openChat();
        } else {
          try {
            sessionStorage.setItem("mabar_pending_open_chat", JSON.stringify({ name: targetChat, subtitle: targetSub }));
          } catch (err) {}
          window.location.href = resolveHref("index.php");
        }
      } else if (clicked.href) {
        setPopoverState(false);
        window.location.href = resolveHref(clicked.href);
      }
    }
  });

  listEl.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      const itemEl = e.target.closest("[data-notif-id]");
      if (itemEl && !e.target.closest("[data-notif-dismiss]")) {
        e.preventDefault();
        itemEl.click();
      }
    }
  });

  if (markAllBtn) {
    markAllBtn.addEventListener("click", async () => {
      if (!isAuthenticated()) return;
      notifications = notifications.map((n) => ({ ...n, read: true }));
      renderNotifications();
      await postAction({ action: "mark_all_read" });
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Semua notifikasi ditandai sudah dibaca.", "sukses");
      }
    });
  }

  if (clearReadBtn) {
    clearReadBtn.addEventListener("click", async () => {
      if (!isAuthenticated()) return;
      notifications = notifications.filter((n) => !n.read);
      renderNotifications();
      await postAction({ action: "clear_read" });
    });
  }

  document.addEventListener("click", (e) => {
    if (!wrapper.contains(e.target)) {
      setPopoverState(false);
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      setPopoverState(false);
    }
  });

  document.addEventListener("visibilitychange", () => {
    if (!document.hidden && isAuthenticated()) {
      fetchNotifications({ silent: true });
    }
  });

  window.MabarNotifCenter = {
    refresh() {
      return fetchNotifications({ silent: true });
    },
    markAllRead() {
      if (!isAuthenticated()) return;
      notifications = notifications.map((n) => ({ ...n, read: true }));
      renderNotifications();
      postAction({ action: "mark_all_read" });
    },
    markReadById(id) {
      const idStr = String(id || "");
      const target = notifications.find((n) => n.id === idStr);
      if (target && !target.read) {
        target.read = true;
        renderNotifications();
        if (target.numericId > 0) {
          postAction({ action: "mark_read", id: target.numericId });
        }
      }
    },
    open() {
      setPopoverState(true);
    },
    close() {
      setPopoverState(false);
    }
  };

  fetchNotifications({ silent: true });

  // Polling ringan saat tab aktif & user login
  setInterval(() => {
    if (!document.hidden && isAuthenticated()) {
      fetchNotifications({ silent: true });
    }
  }, POLL_INTERVAL_MS);
})();
