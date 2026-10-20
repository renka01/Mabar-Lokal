/**
 * asset/js/app.js — Skrip Utama Navigasi Global
 * Menangani sidebar chat, dropdown profil user, kartu sesi beranda, dan form obrolan.
 */

// ── 1. TOGGLE CHAT SIDEBAR ──
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
  if (htmlEl.classList.contains("chat-is-closed")) {
    chatSidebar.classList.add("chat-hidden");
  } else {
    chatSidebar.classList.remove("chat-hidden");
  }

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


// ── 2. DROPDOWN PROFIL PENGGUNA ──
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

  document.addEventListener("click", (e) => {
    if (!profileDropdownBtn.contains(e.target) && !profileDropdownMenu.contains(e.target)) {
      toggleProfileDropdown(false);
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      toggleProfileDropdown(false);
    }
  });
}

window.openChat = openChat;
window.closeChat = closeChat;

// ── 3. MENU NAVIGASI MOBILE ──


// ── 4. MENU NAVIGASI MOBILE ──
document.querySelector("#menu-btn")?.addEventListener("click", () => {
  const nav = document.querySelector("#desktop-nav");
  if (!nav) return;
  nav.classList.toggle("hidden");
  nav.classList.toggle("flex");
  nav.classList.toggle("flex-col");
  nav.classList.toggle("absolute");
  nav.classList.toggle("top-[76px]");
  nav.classList.toggle("left-0");
  nav.classList.toggle("w-full");
  nav.classList.toggle("bg-[#080f18]");
  nav.classList.toggle("p-4");
  nav.classList.toggle("z-40");
});


// ── 5. FORM CHATBAR & TAB PESAN ──
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

document.querySelector("#chat-form")?.addEventListener("submit", (event) => {
  event.preventDefault();
  const input = document.querySelector("#chat-input");
  const val = input.value.trim();
  if (!val) return;

  const bubble = document.createElement("div");
  bubble.className = "flex flex-col items-end self-end max-w-[85%] mt-2";
  bubble.innerHTML = `
    <div class="rounded-2xl rounded-br-sm bg-red px-3.5 py-2 text-[12px] leading-relaxed text-white shadow-sm shadow-red/10">
      ${val}
    </div>
    <span class="mt-1 mr-1 text-[9px] text-muted text-right">Barusan</span>
  `;

  const chatContainer = document.querySelector(
    "#chat-content .flex-1.flex.flex-col.justify-end"
  );
  if (chatContainer) chatContainer.appendChild(bubble);

  const wrapper = document.querySelector("#chat-content");
  if (wrapper) wrapper.scrollTop = wrapper.scrollHeight;

  input.value = "";
});


// ── 6. PREVIEW UPLOAD POSTER TURNAMEN ──
const posterInput = document.querySelector("#poster-upload");
const posterPreview = document.querySelector("#poster-preview");
const posterPlaceholder = document.querySelector("#poster-placeholder");
const posterRemove = document.querySelector("#poster-remove");

if (posterInput) {
  posterInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;

    // Validasi ukuran maks 5MB dengan notifikasi custom
    if (file.size > 5 * 1024 * 1024) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Ukuran file terlalu besar. Maksimal 5MB.", "error");
      } else {
        alert("Ukuran file terlalu besar. Maksimal 5MB.");
      }
      posterInput.value = "";
      return;
    }

    const reader = new FileReader();
    reader.onload = (ev) => {
      if (posterPreview) {
        posterPreview.src = ev.target.result;
        posterPreview.classList.remove("hidden");
      }
      posterPlaceholder?.classList.add("hidden");
      posterRemove?.classList.remove("hidden");
    };
    reader.readAsDataURL(file);
  });
}

if (posterRemove) {
  posterRemove.addEventListener("click", () => {
    if (posterInput) posterInput.value = "";
    if (posterPreview) {
      posterPreview.src = "";
      posterPreview.classList.add("hidden");
    }
    posterPlaceholder?.classList.remove("hidden");
    posterRemove.classList.add("hidden");
  });
}


// ── 7. INISIALISASI FLATPICKR KALENDER ──
if (typeof flatpickr !== "undefined") {
  const flatpickrConfig = {
    dateFormat: "d/m/y",
    disableMobile: true,
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
      const icon = fp.input.closest(".date-input-wrapper")?.querySelector(".date-icon");
      if (icon) icon.addEventListener("click", () => fp.open());
    },
    onChange(selectedDates, _, fp) {
      fp.input.classList.toggle("has-value", selectedDates.length > 0);
      if (selectedDates.length > 0) {
        fp.input.classList.replace("text-muted", "text-white") || fp.input.classList.add("text-white");
      }
    }
  };

  const mulai = document.getElementById("tanggal-mulai");
  const selesai = document.getElementById("tanggal-selesai");
  if (mulai) flatpickr(mulai, flatpickrConfig);
  if (selesai) flatpickr(selesai, flatpickrConfig);
}

// ── 8. SYNC PROFIL HEADER DARI LOCALSTORAGE ──
function syncHeaderProfile() {
  const savedAvatar = localStorage.getItem("mabar_profile_avatar");
  const savedName = localStorage.getItem("mabar_profile_name");

  const headerAvatarImg = document.getElementById("header-avatar-img");
  const headerAvatarIcon = document.getElementById("header-avatar-icon");
  const headerUserName = document.getElementById("header-user-name");
  const dropdownUserFullname = document.getElementById("dropdown-user-fullname");

  if (savedAvatar) {
    if (headerAvatarImg) {
      headerAvatarImg.src = savedAvatar;
      headerAvatarImg.classList.remove("hidden");
    }
    if (headerAvatarIcon) {
      headerAvatarIcon.classList.add("hidden");
    }
    document.querySelectorAll(".lobby-self-avatar").forEach((container) => {
      container.innerHTML = `<img src="${savedAvatar}" alt="Profil Saya" class="w-full h-full object-cover" />`;
    });
  } else {
    if (headerAvatarImg) {
      headerAvatarImg.src = "";
      headerAvatarImg.classList.add("hidden");
    }
    if (headerAvatarIcon) {
      headerAvatarIcon.classList.remove("hidden");
    }
  }

  if (savedName) {
    if (headerUserName) headerUserName.textContent = savedName;
    if (dropdownUserFullname) dropdownUserFullname.textContent = savedName;
    document.querySelectorAll(".lobby-self-name").forEach((el) => {
      el.textContent = savedName;
    });
  }
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", syncHeaderProfile);
} else {
  syncHeaderProfile();
}
window.addEventListener("storage", syncHeaderProfile);
window.syncHeaderProfile = syncHeaderProfile;