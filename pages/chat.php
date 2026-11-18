<!-- RIGHT CHAT SIDEBAR -->
<aside id="chat-sidebar" class="py-6 chat-hidden">
  <script>
    document.documentElement.classList.add("has-chatbar");
    if (localStorage.getItem("mabar_chat_closed") !== "true") {
      document.currentScript.parentElement.classList.remove("chat-hidden");
    }
  </script>
  <div class="chat-shell">
    <!-- Header -->
    <div class="chat-shell-header">
      <div class="chat-shell-title-group">
        <span class="chat-shell-title">CHAT</span>
        <span id="chat-status-summary" class="chat-status-summary">Memuat...</span>
      </div>
      <button
        id="btn-close-chat"
        type="button"
        class="chat-close-btn"
        title="Sembunyikan Chat"
        aria-label="Tutup Chat"
      >
        <i class="ph-bold ph-caret-right text-lg"></i>
      </button>
    </div>

    <!-- Tabs -->
    <div class="chat-tabs-bar" role="tablist" aria-label="Filter Obrolan">
      <button type="button" class="chat-tab active" data-chat="all">Semua</button>
      <button type="button" class="chat-tab" data-chat="unread">Belum dibaca</button>
    </div>

    <!-- Chat List -->
    <div id="chat-thread-list" class="chat-thread-list hide-scrollbar"></div>

    <!-- Active Chat Header -->
    <div id="chat-active-header" class="chat-active-header">
      <div id="chat-active-avatar" class="chat-avatar">
        <span id="chat-active-initial">DM</span>
        <span class="chat-online-dot"></span>
      </div>
      <div class="chat-active-meta">
        <span id="chat-active-name" class="chat-active-name">Direct Message</span>
        <span id="chat-active-sub" class="chat-active-sub">Obrolan pemain Mabar Lokal</span>
      </div>
    </div>

    <!-- Active Messages Container -->
    <div id="chat-content" class="chat-messages-area modal-terms-scroll"></div>

    <!-- Chat Input -->
    <form id="chat-form" class="chat-input-bar">
      <input
        id="chat-input"
        type="text"
        class="chat-input-field"
        placeholder="Tulis pesan..."
        autocomplete="off"
      />
      <button
        type="submit"
        class="chat-send-btn"
        aria-label="Kirim pesan"
        title="Kirim pesan"
      >
        <i class="ph-fill ph-paper-plane-right"></i>
      </button>
    </form>
  </div>
</aside>
