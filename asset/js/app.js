requestAnimationFrame(() => {
  document.documentElement.classList.add("loaded");
});

const profileDropdownBtn = document.getElementById("profile-dropdown-btn");
const profileDropdownMenu = document.getElementById("profile-dropdown-menu");
const profileCaret = document.getElementById("profile-caret");

if (profileDropdownBtn && profileDropdownMenu) {
  const toggleProfileDropdown = (forceState) => {
    const isOpen = !profileDropdownMenu.classList.contains("hidden");
    const shouldOpen = typeof forceState === "boolean" ? forceState : !isOpen;

    if (shouldOpen) {
      if (typeof window.closeNotificationPopover === "function") {
        window.closeNotificationPopover();
      }
      profileDropdownMenu.classList.remove("hidden");
      profileDropdownBtn.setAttribute("aria-expanded", "true");
      if (profileCaret) profileCaret.classList.add("rotate-180");
    } else {
      profileDropdownMenu.classList.add("hidden");
      profileDropdownBtn.setAttribute("aria-expanded", "false");
      if (profileCaret) profileCaret.classList.remove("rotate-180");
    }
  };

  window.closeProfileDropdown = () => toggleProfileDropdown(false);

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

function syncHeaderProfile() {
  const resolveAsset = typeof window.mabarResolveAsset === "function"
    ? window.mabarResolveAsset
    : (src) => src || "";

  const savedAvatarRaw = localStorage.getItem("mabar_profile_avatar");
  const savedAvatar = savedAvatarRaw ? resolveAsset(savedAvatarRaw) : "";
  const savedName = localStorage.getItem("mabar_profile_name");
  const savedTag = localStorage.getItem("mabar_profile_tag") || "";
  const savedEmail = localStorage.getItem("mabar_profile_email") || "";
  const isGuest = localStorage.getItem("mabar_is_guest") === "true";

  const headerAvatarImg = document.getElementById("header-avatar-img");
  const headerAvatarIcon = document.getElementById("header-avatar-icon");
  const headerUserName = document.getElementById("header-user-name");
  const headerUserTag = document.getElementById("header-user-tag");
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
      const img = document.createElement("img");
      img.src = savedAvatar;
      img.alt = savedName || "Profil Saya";
      img.className = "w-full h-full object-cover";
      container.replaceChildren(img);
    });
  } else {
    if (headerAvatarImg) {
      headerAvatarImg.src = "";
      headerAvatarImg.classList.add("hidden");
    }
    if (headerAvatarIcon) {
      headerAvatarIcon.classList.remove("hidden");
    }
    document.querySelectorAll(".lobby-self-avatar").forEach((container) => {
      if (container.querySelector("img")) {
        const icon = document.createElement("i");
        icon.className = "ph-fill ph-user text-xl";
        icon.style.color = "#9ba5b4";
        container.replaceChildren(icon);
      }
    });
  }

  if (savedName) {
    if (headerUserName) headerUserName.textContent = savedName;
    if (dropdownUserFullname) dropdownUserFullname.textContent = savedName;
    document.querySelectorAll(".lobby-self-name").forEach((el) => {
      el.textContent = savedName;
    });
  }

  if (headerUserTag) {
    if (isGuest) {
      headerUserTag.textContent = "Mode Tamu";
    } else if (savedTag) {
      headerUserTag.textContent = savedTag === "wnwnwnrwn" ? "wnwnwnrwn" : `@${savedTag.replace(/^@/, "")}`;
    } else if (savedName && (savedName.toLowerCase() !== "wawan" || (savedEmail && savedEmail !== "wawanwrwn@gmail.com"))) {
      const slug = savedName.toLowerCase().replace(/[^a-z0-9]+/g, "");
      headerUserTag.textContent = `@${slug || "pemain"}`;
    } else {
      headerUserTag.textContent = "wnwnwnrwn";
    }
  }

  window.dispatchEvent(new CustomEvent("mabar:profile-updated"));
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", syncHeaderProfile);
} else {
  syncHeaderProfile();
}
window.addEventListener("storage", syncHeaderProfile);
window.syncHeaderProfile = syncHeaderProfile;

if (typeof window.openTermsModal !== "function") {
  window.openTermsModal = function () {
    const modal = document.getElementById("modal-syarat-ketentuan");
    if (!modal) return;

    const hasRegisterCheckbox = Boolean(document.getElementById("reg-agree"));
    const agreeBtn = document.getElementById("btn-agree-terms-modal");
    const footerNote = modal.querySelector(".terms-footer-note");

    if (agreeBtn) {
      agreeBtn.classList.toggle("hidden", !hasRegisterCheckbox);
    }
    if (footerNote) {
      footerNote.textContent = hasRegisterCheckbox
        ? "Wajib dipahami sebelum mendaftar"
        : "Berlaku untuk seluruh pengguna MabarLokal";
    }

    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  };
}

if (typeof window.closeTermsModal !== "function") {
  window.closeTermsModal = function () {
    const modal = document.getElementById("modal-syarat-ketentuan");
    if (!modal) return;
    modal.classList.add("hidden");
    document.body.style.overflow = "";
  };
}

if (typeof window.acceptTermsAndClose !== "function") {
  window.acceptTermsAndClose = function () {
    const agreeCheckbox = document.getElementById("reg-agree");
    if (agreeCheckbox) {
      agreeCheckbox.checked = true;
    }
    window.closeTermsModal();
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Syarat & ketentuan komunitas telah Anda setujui.", "sukses");
    }
  };
}

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    const termsModal = document.getElementById("modal-syarat-ketentuan");
    if (termsModal && !termsModal.classList.contains("hidden")) {
      window.closeTermsModal();
    }
  }
});