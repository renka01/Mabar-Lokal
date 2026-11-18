(function () {
  const ACTIVE_KEY = "mabar_chat_active_v1";
  const CLOSED_KEY = "mabar_chat_closed";
  const POLL_INTERVAL_MS = 12000;

  const htmlEl = document.documentElement;
  const chatSidebar = document.getElementById("chat-sidebar");
  const btnCloseChat = document.getElementById("btn-close-chat");
  const btnOpenChat = document.getElementById("btn-open-chat");
  const openChatDot = document.getElementById("btn-open-chat-dot");
  const summaryEl = document.getElementById("chat-status-summary");
  const threadListEl = document.getElementById("chat-thread-list");
  const activeHeaderEl = document.getElementById("chat-active-header");
  const activeAvatarEl = document.getElementById("chat-active-avatar");
  const activeNameEl = document.getElementById("chat-active-name");
  const activeSubEl = document.getElementById("chat-active-sub");
  const messagesEl = document.getElementById("chat-content");
  const chatForm = document.getElementById("chat-form");
  const chatInput = document.getElementById("chat-input");
  const chatSendBtn = chatForm ? chatForm.querySelector('button[type="submit"]') : null;

  if (!chatSidebar) return;
  htmlEl.classList.add("has-chatbar");

  const basePath = window.MABAR_BASE_PATH || "";

  let activeTab = "all";
  let threads = [];
  let activeThreadId = "";
  let isLoading = true;
  let isSending = false;
  let isFetching = false;

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

  function resolveAsset(src) {
    if (!src) return "";
    if (typeof window.mabarResolveAsset === "function") {
      return window.mabarResolveAsset(src);
    }
    return src;
  }

  function makeInitials(name) {
    const parts = String(name || "P").trim().split(/\s+/).filter(Boolean);
    if (parts.length >= 2) {
      return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return (parts[0] || "P").slice(0, 2).toUpperCase();
  }

  function pickAccentColor(name) {
    const palette = ["#ef233c", "#19d66b", "#f9a826", "#38bdf8", "#a855f7"];
    let sum = 0;
    const text = String(name || "");
    for (let i = 0; i < text.length; i++) {
      sum += text.charCodeAt(i);
    }
    return palette[sum % palette.length];
  }

  function getSelfProfile() {
    const user = (window.MABAR_AUTH && window.MABAR_AUTH.user) || null;
    let rawAvatar = user && user.avatar_path ? user.avatar_path : "";
    let name = user && user.name ? user.name : "";

    try {
      if (!rawAvatar) rawAvatar = localStorage.getItem("mabar_profile_avatar") || "";
      if (!name) name = localStorage.getItem("mabar_profile_name") || "Pemain";
    } catch (e) {}

    name = (name || "Pemain").trim() || "Pemain";
    return {
      avatar: resolveAsset(rawAvatar),
      name,
      initials: makeInitials(name)
    };
  }

  function normalizeThread(raw) {
    const name = String(raw.name || "Pemain");
    return {
      id: String(raw.id),
      numericId: Number(raw.id) || 0,
      thread_key: String(raw.thread_key || ""),
      peer_id: Number(raw.peer_id) || 0,
      name,
      tag: String(raw.tag || ""),
      avatar: resolveAsset(raw.avatar || ""),
      initials: makeInitials(name),
      color: pickAccentColor(name),
      subtitle: String(raw.subtitle || "Pemain Mabar Lokal"),
      unread: Number(raw.unread) || 0,
      online: Boolean(raw.online ?? true),
      messages: Array.isArray(raw.messages) ? raw.messages : []
    };
  }

  function getSavedActiveId() {
    try {
      return localStorage.getItem(ACTIVE_KEY) || "";
    } catch (e) {
      return "";
    }
  }

  function setSavedActiveId(id) {
    activeThreadId = String(id || "");
    try {
      if (activeThreadId) {
        localStorage.setItem(ACTIVE_KEY, activeThreadId);
      } else {
        localStorage.removeItem(ACTIVE_KEY);
      }
    } catch (e) {}
  }

  function resolveActiveThread() {
    if (threads.length === 0) return null;
    if (activeThreadId && threads.some((t) => t.id === activeThreadId)) {
      return threads.find((t) => t.id === activeThreadId);
    }
    const saved = getSavedActiveId();
    if (saved && threads.some((t) => t.id === saved)) {
      activeThreadId = saved;
      return threads.find((t) => t.id === saved);
    }
    activeThreadId = threads[0].id;
    return threads[0];
  }

  function isMobileViewport() {
    return window.innerWidth < 1024;
  }

  function openChat() {
    if (isMobileViewport()) {
      htmlEl.classList.add("chat-mobile-open");
    } else {
      if (chatSidebar) chatSidebar.classList.remove("chat-hidden");
      htmlEl.classList.remove("chat-is-closed");
      if (btnOpenChat) btnOpenChat.classList.remove("animate-in");
      try {
        localStorage.setItem(CLOSED_KEY, "false");
      } catch (e) {}
    }
    scrollToBottom();
  }

  function closeChat() {
    if (isMobileViewport()) {
      htmlEl.classList.remove("chat-mobile-open");
    } else {
      if (chatSidebar) chatSidebar.classList.add("chat-hidden");
      htmlEl.classList.add("chat-is-closed");
      if (btnOpenChat) btnOpenChat.classList.add("animate-in");
      try {
        localStorage.setItem(CLOSED_KEY, "true");
      } catch (e) {}
    }
  }

  function scrollToBottom() {
    if (messagesEl) {
      messagesEl.scrollTop = messagesEl.scrollHeight;
    }
  }

  function renderAvatarInner(avatarUrl, initials, altName) {
    if (avatarUrl) {
      return `<img src="${escapeHtml(avatarUrl)}" alt="${escapeHtml(altName || "Avatar")}" class="chat-avatar-img" />`;
    }
    return `<span>${escapeHtml(initials)}</span>`;
  }

  function setInputEnabled(enabled, placeholderText) {
    if (chatInput) {
      chatInput.disabled = !enabled;
      chatInput.placeholder = placeholderText || "Tulis pesan...";
    }
    if (chatSendBtn) {
      chatSendBtn.disabled = !enabled;
    }
  }

  function renderGuestState() {
    if (openChatDot) openChatDot.classList.add("hidden");
    if (summaryEl) summaryEl.textContent = "Mode Tamu";
    if (activeHeaderEl) activeHeaderEl.classList.add("hidden");
    if (threadListEl) {
      threadListEl.innerHTML = `
        <div class="chat-thread-empty">
          <i class="ph ph-lock-simple"></i>
          <span>Masuk akun untuk akses Direct Message</span>
        </div>
      `;
    }
    if (messagesEl) {
      messagesEl.innerHTML = `
        <div class="chat-messages-empty">
          <div class="chat-messages-empty-icon">
            <i class="ph ph-chats-circle"></i>
          </div>
          <p class="chat-messages-empty-title">Obrolan Langsung (DM)</p>
          <p class="chat-messages-empty-desc">Masuk akun untuk mulai mengobrol dengan host sesi mabar, kapten scrim, atau teman bermainmu.</p>
          <a href="${escapeHtml(basePath)}pages/auth.php" class="mt-3 inline-flex items-center gap-1.5 rounded-md bg-red px-3.5 py-2 text-xs font-semibold text-white hover:bg-red-hover transition-colors">
            <i class="ph-bold ph-sign-in"></i>
            <span>Masuk / Daftar</span>
          </a>
        </div>
      `;
    }
    setInputEnabled(false, "Masuk akun untuk mengirim pesan...");
  }

  function renderAll() {
    if (!chatSidebar) return;

    if (!isAuthenticated()) {
      renderGuestState();
      return;
    }

    const activeThread = resolveActiveThread();
    const totalUnread = threads.reduce((acc, t) => acc + (Number(t.unread) || 0), 0);

    if (openChatDot) {
      openChatDot.classList.toggle("hidden", totalUnread === 0);
    }

    if (summaryEl) {
      if (isLoading && threads.length === 0) {
        summaryEl.textContent = "Memuat...";
      } else {
        summaryEl.textContent = totalUnread > 0
          ? `${totalUnread} belum dibaca`
          : `${threads.length} obrolan`;
      }
    }

    const visibleThreads = activeTab === "unread"
      ? threads.filter((t) => (Number(t.unread) || 0) > 0)
      : threads;

    if (threadListEl) {
      if (isLoading && threads.length === 0) {
        threadListEl.innerHTML = `
          <div class="chat-thread-empty">
            <i class="ph ph-spinner"></i>
            <span>Memuat percakapan...</span>
          </div>
        `;
      } else if (visibleThreads.length === 0) {
        threadListEl.innerHTML = `
          <div class="chat-thread-empty">
            <i class="ph ${activeTab === "unread" ? "ph-check-circle" : "ph-chats-circle"}"></i>
            <span>${activeTab === "unread" ? "Semua pesan sudah dibaca" : "Belum ada percakapan"}</span>
          </div>
        `;
      } else {
        threadListEl.innerHTML = visibleThreads
          .map((t) => {
            const lastMsg = t.messages && t.messages.length > 0
              ? t.messages[t.messages.length - 1]
              : null;
            const previewText = lastMsg
              ? `${lastMsg.from === "me" ? "Anda: " : ""}${lastMsg.text}`
              : (t.subtitle || "Belum ada pesan");
            const lastTime = lastMsg ? lastMsg.time : "";
            const isActive = activeThread && t.id === activeThread.id;
            const unreadBadge = (Number(t.unread) || 0) > 0
              ? `<span class="chat-thread-unread">${t.unread}</span>`
              : "";

            return `
              <button
                type="button"
                class="chat-thread-item ${isActive ? "is-active" : ""}"
                data-thread-id="${escapeHtml(t.id)}"
              >
                <div class="chat-avatar chat-avatar-sm" style="border-color: ${escapeHtml(t.color || "#253142")};">
                  ${renderAvatarInner(t.avatar, t.initials, t.name)}
                  ${t.online ? '<span class="chat-online-dot"></span>' : ""}
                </div>
                <div class="chat-thread-body">
                  <div class="chat-thread-top">
                    <span class="chat-thread-name">${escapeHtml(t.name)}</span>
                    <span class="chat-thread-time">${escapeHtml(lastTime)}</span>
                  </div>
                  <div class="chat-thread-bottom">
                    <span class="chat-thread-preview">${escapeHtml(previewText)}</span>
                    ${unreadBadge}
                  </div>
                </div>
              </button>
            `;
          })
          .join("");
      }
    }

    if (!activeThread) {
      if (activeHeaderEl) activeHeaderEl.classList.add("hidden");
      if (messagesEl) {
        messagesEl.innerHTML = `
          <div class="chat-messages-empty">
            <div class="chat-messages-empty-icon">
              <i class="ph ph-chat-teardrop-text"></i>
            </div>
            <p class="chat-messages-empty-title">Belum Ada Obrolan Aktif</p>
            <p class="chat-messages-empty-desc">Klik tombol "Kirim Pesan" di profil pemain, lobby sesi mabar, atau ruang tanding scrim untuk mulai mengobrol.</p>
          </div>
        `;
      }
      setInputEnabled(false, "Pilih atau mulai percakapan terlebih dahulu...");
      return;
    }

    if (activeHeaderEl) activeHeaderEl.classList.remove("hidden");
    setInputEnabled(!isSending, "Tulis pesan...");

    if (activeNameEl) activeNameEl.textContent = activeThread.name;
    if (activeSubEl) activeSubEl.textContent = activeThread.subtitle || "Pemain Mabar Lokal";
    if (activeAvatarEl) {
      activeAvatarEl.style.borderColor = activeThread.color || "#253142";
      activeAvatarEl.innerHTML = `
        ${renderAvatarInner(activeThread.avatar, activeThread.initials, activeThread.name)}
        ${activeThread.online ? '<span class="chat-online-dot"></span>' : ""}
      `;
    }

    if (messagesEl) {
      const msgs = Array.isArray(activeThread.messages) ? activeThread.messages : [];
      if (msgs.length === 0) {
        messagesEl.innerHTML = `
          <div class="chat-messages-empty">
            <div class="chat-messages-empty-icon">
              <i class="ph ph-chat-teardrop-text"></i>
            </div>
            <p class="chat-messages-empty-title">Mulai obrolan dengan ${escapeHtml(activeThread.name)}</p>
            <p class="chat-messages-empty-desc">Kirim pesan untuk koordinasi jam main, room, atau jadwal sparring.</p>
          </div>
        `;
      } else {
        const oppColor = escapeHtml(activeThread.color || "#253142");
        const oppAvatarInner = renderAvatarInner(activeThread.avatar, activeThread.initials, activeThread.name);
        const selfProfile = getSelfProfile();
        const selfAvatarInner = renderAvatarInner(selfProfile.avatar, selfProfile.initials, selfProfile.name);
        let lastDate = "";

        const bubblesHtml = msgs
          .map((m) => {
            let dateSepHtml = "";
            const label = m.dateLabel || "Hari ini";
            if (label !== lastDate) {
              lastDate = label;
              dateSepHtml = `
                <div class="chat-date-separator">
                  <span>${escapeHtml(label)}</span>
                </div>
              `;
            }

            if (m.from === "me") {
              return `
                ${dateSepHtml}
                <div class="chat-msg-row is-me">
                  <div class="chat-msg-me-wrap">
                    <div class="chat-bubble is-me">${escapeHtml(m.text)}</div>
                    <div class="chat-avatar chat-avatar-xs chat-avatar-self" title="${escapeHtml(selfProfile.name)}">${selfAvatarInner}</div>
                  </div>
                  <span class="chat-msg-time is-me">${escapeHtml(m.time || "")}</span>
                </div>
              `;
            }

            return `
              ${dateSepHtml}
              <div class="chat-msg-row is-them">
                <div class="chat-msg-them-wrap">
                  <div class="chat-avatar chat-avatar-xs" style="border-color: ${oppColor};">${oppAvatarInner}</div>
                  <div class="chat-bubble is-them">${escapeHtml(m.text)}</div>
                </div>
                <span class="chat-msg-time is-them">${escapeHtml(m.time || "")}</span>
              </div>
            `;
          })
          .join("");

        messagesEl.innerHTML = `<div class="chat-messages-stack">${bubblesHtml}</div>`;
        scrollToBottom();
      }
    }
  }

  async function fetchThreads(opts = {}) {
    if (!isAuthenticated()) {
      threads = [];
      isLoading = false;
      renderAll();
      return [];
    }
    if (isFetching) return threads;

    isFetching = true;
    if (!opts.silent && threads.length === 0) {
      isLoading = true;
      renderAll();
    }

    try {
      const res = await fetch(`${basePath}api/chat/threads.php`, {
        method: "GET",
        credentials: "same-origin",
        headers: { Accept: "application/json" }
      });
      const json = await res.json();
      if (res.ok && json && json.ok) {
        const list = Array.isArray(json.threads) ? json.threads : [];
        threads = list.map(normalizeThread);
      }
    } catch (e) {
      // Biarkan daftar terakhir jika koneksi sempat terputus
    } finally {
      isLoading = false;
      isFetching = false;
      renderAll();
    }

    return threads;
  }

  async function markThreadReadServer(numericThreadId) {
    if (!isAuthenticated() || !numericThreadId) return;
    try {
      await fetch(`${basePath}api/chat/read.php`, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify({ thread_id: numericThreadId })
      });
    } catch (e) {}
  }

  function selectThread(threadId) {
    const idStr = String(threadId || "");
    const target = threads.find((t) => t.id === idStr);
    if (!target) return;

    const hadUnread = target.unread > 0;
    if (hadUnread) {
      target.unread = 0;
    }

    setSavedActiveId(target.id);
    renderAll();

    if (hadUnread) {
      markThreadReadServer(target.numericId);
    }
  }

  async function openWith(name, subtitle, targetUserId) {
    const cleanName = String(name || "").trim();
    if (!isAuthenticated()) {
      openChat();
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Masuk akun terlebih dahulu untuk mengirim pesan langsung.", "error");
      }
      return;
    }

    if (!cleanName && !targetUserId) {
      openChat();
      return;
    }

    try {
      const payload = {
        subtitle: subtitle || "Obrolan Langsung (DM)"
      };
      if (targetUserId && Number(targetUserId) > 0) {
        payload.target_user_id = Number(targetUserId);
      } else {
        payload.target_name = cleanName;
      }

      const res = await fetch(`${basePath}api/chat/open.php`, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify(payload)
      });
      const json = await res.json();

      if (!res.ok || !json || !json.ok) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi((json && json.message) || "Gagal membuka percakapan.", "error");
        }
        return;
      }

      const openedId = String(json.thread_id || "");
      if (openedId) {
        setSavedActiveId(openedId);
      }

      activeTab = "all";
      document.querySelectorAll(".chat-tab").forEach((btn) => {
        btn.classList.toggle("active", btn.dataset.chat === "all");
      });

      openChat();
      await fetchThreads({ silent: true });

      if (openedId) {
        setSavedActiveId(openedId);
        renderAll();
      }

      setTimeout(() => chatInput?.focus(), 120);
    } catch (e) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Gagal terhubung ke server chat.", "error");
      }
    }
  }

  if (chatSidebar && btnCloseChat && btnOpenChat) {
    if (!isMobileViewport()) {
      if (htmlEl.classList.contains("chat-is-closed")) {
        chatSidebar.classList.add("chat-hidden");
      } else {
        chatSidebar.classList.remove("chat-hidden");
      }
    }

    btnCloseChat.addEventListener("click", closeChat);
    btnOpenChat.addEventListener("click", () => {
      openChat();
      if (isAuthenticated()) {
        fetchThreads({ silent: true });
      }
    });

    chatSidebar.addEventListener("click", (e) => {
      if (isMobileViewport() && e.target === chatSidebar) {
        closeChat();
      }
    });
  }

  document.querySelectorAll(".chat-tab").forEach((tab) => {
    tab.addEventListener("click", () => {
      activeTab = tab.dataset.chat === "unread" ? "unread" : "all";
      document.querySelectorAll(".chat-tab").forEach((item) => {
        item.classList.toggle("active", item === tab);
      });
      renderAll();
    });
  });

  if (threadListEl) {
    threadListEl.addEventListener("click", (e) => {
      const btn = e.target.closest("[data-thread-id]");
      if (!btn) return;
      selectThread(btn.dataset.threadId);
    });
  }

  if (chatForm && chatInput) {
    chatForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (!isAuthenticated() || isSending) return;

      const text = chatInput.value.trim();
      if (!text) return;

      const activeThread = resolveActiveThread();
      if (!activeThread || !activeThread.numericId) return;

      isSending = true;
      setInputEnabled(false, "Mengirim...");

      try {
        const res = await fetch(`${basePath}api/chat/send.php`, {
          method: "POST",
          credentials: "same-origin",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json"
          },
          body: JSON.stringify({
            thread_id: activeThread.numericId,
            body: text
          })
        });
        const json = await res.json();

        if (!res.ok || !json || !json.ok) {
          if (typeof window.tampilkanNotifikasi === "function") {
            window.tampilkanNotifikasi((json && json.message) || "Gagal mengirim pesan.", "error");
          }
          return;
        }

        const item = json.message_item || null;
        activeThread.messages = Array.isArray(activeThread.messages) ? activeThread.messages : [];
        if (item) {
          activeThread.messages.push(item);
        }
        activeThread.unread = 0;

        // Pindahkan thread aktif ke urutan paling atas daftar percakapan
        threads = [activeThread, ...threads.filter((t) => t.id !== activeThread.id)];
        chatInput.value = "";
        renderAll();
      } catch (err) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Gagal terhubung ke server saat mengirim pesan.", "error");
        }
      } finally {
        isSending = false;
        setInputEnabled(Boolean(resolveActiveThread()), "Tulis pesan...");
        chatInput.focus();
      }
    });
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && isMobileViewport() && htmlEl.classList.contains("chat-mobile-open")) {
      closeChat();
    }
  });

  window.addEventListener("storage", (e) => {
    if (e.key === ACTIVE_KEY || e.key === "mabar_profile_avatar" || e.key === "mabar_profile_name") {
      renderAll();
    }
  });

  window.addEventListener("mabar:profile-updated", () => renderAll());

  document.addEventListener("visibilitychange", () => {
    if (!document.hidden && isAuthenticated()) {
      fetchThreads({ silent: true });
    }
  });

  window.openChat = openChat;
  window.closeChat = closeChat;
  window.MabarChat = {
    open: openChat,
    close: closeChat,
    openWith,
    selectThread,
    refresh: () => fetchThreads({ silent: true })
  };

  fetchThreads().then(() => {
    try {
      const pendingRaw = sessionStorage.getItem("mabar_pending_open_chat");
      if (pendingRaw) {
        sessionStorage.removeItem("mabar_pending_open_chat");
        const pending = JSON.parse(pendingRaw);
        if (pending && (pending.name || pending.userId)) {
          openWith(pending.name || "", pending.subtitle || "", pending.userId || 0);
        } else {
          openChat();
        }
      }
    } catch (e) {}
  });

  // Polling ringan saat tab aktif & user login
  setInterval(() => {
    if (!document.hidden && isAuthenticated() && !isSending) {
      fetchThreads({ silent: true });
    }
  }, POLL_INTERVAL_MS);
})();
