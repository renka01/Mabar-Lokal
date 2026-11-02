/**
 * asset/js/halaman_profil.js — Logika Interaktif Halaman Profil Pengguna
 * Menangani preview banner, upload avatar kustom, live text sync, dan sinkronisasi LocalStorage dengan header global.
 */

// Map nama game & cabor ke file icon aset lokal
const GAME_ICON_MAP = {
  "Valorant": "asset/icon/Valoran1.svg",
  "Mobile Legends": "asset/icon/ml-logo.jpeg",
  "CS2": "asset/icon/cs2-logo.jpeg",
  "PUBG Mobile": "asset/icon/pubgm-logo.jpeg",
  "Dota 2": "asset/icon/dota2-logo.jpeg",
  "Honor of Kings": "asset/icon/hok-logo.png",
  "League of Legends": "asset/icon/lol-logo.jpeg",
  "PUBG PC": "asset/icon/pubg-pc-logo.jpeg",
  "Futsal": "asset/icon/Futsal.png",
  "Bulu Tangkis": "asset/icon/Bulu Tangkis.png",
  "Basket": "asset/icon/Basket.png",
  "Billiard": "asset/icon/Billiard.png",
  "Padel": "asset/icon/padel.png",
  "Lari": "asset/icon/lARI.png",
  "Sepak Bola": "asset/icon/sepak-bola.svg",
  "Tenis": "asset/icon/tenis.svg",
  "Voli": "asset/icon/voli.svg"
};

// Render chip icon minat berdasarkan hasil kuesioner dari localStorage
function renderProfileInterestBadges() {
  const container = document.getElementById("profile-interest-badges");
  if (!container) return;

  const basePath = "../../";
  let savedGames = [];
  try {
    savedGames = JSON.parse(localStorage.getItem("mabar_pref_games") || "[]");
  } catch (e) {
    savedGames = [];
  }

  // Jika belum ada game yang tersimpan, berikan game populer default
  if (!savedGames || savedGames.length === 0) {
    savedGames = ["Valorant", "Mobile Legends", "Futsal"];
  }

  const sportsList = ["Futsal", "Bulu Tangkis", "Basket", "Billiard", "Padel", "Lari", "Sepak Bola", "Tenis", "Voli"];

  container.innerHTML = savedGames.map(game => {
    const iconFile = GAME_ICON_MAP[game] || "asset/icon/Logo1.svg";
    const isSport = sportsList.includes(game);
    const tagBg = isSport ? "rgba(25, 214, 107, 0.12)" : "rgba(239, 35, 60, 0.12)";
    const tagBorder = isSport ? "rgba(25, 214, 107, 0.35)" : "rgba(239, 35, 60, 0.35)";
    const tagColor = isSport ? "#19d66b" : "#ef233c";
    const tagText = isSport ? "Fisik" : "Esports";

    return `
      <div
        class="inline-flex items-center rounded-lg bg-card-alt border border-border shadow-sm transition-all hover:border-muted/80 cursor-default"
        style="padding: 6px 14px 6px 10px; gap: 10px;"
      >
        <img
          src="${basePath}${iconFile}"
          alt="${game}"
          class="interest-badge-img"
          style="width: 22px !important; height: 22px !important; min-width: 22px !important; min-height: 22px !important; max-width: 22px !important; max-height: 22px !important; object-fit: cover !important; border-radius: 4px !important; flex-shrink: 0 !important; display: block !important;"
          onerror="this.onerror=null; this.src='${basePath}asset/icon/Logo1.svg';"
        />
        <span class="font-semibold text-[12.5px] text-white whitespace-nowrap">${game}</span>
        <span
          style="margin-left: 2px; padding: 2px 7px; border-radius: 4px; font-size: 9.5px; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; background-color: ${tagBg}; border: 1px solid ${tagBorder}; color: ${tagColor}; white-space: nowrap;"
        >
          ${tagText}
        </span>
      </div>
    `;
  }).join("");
}

// Helper set gambar avatar profil halaman & header
function setAvatarImage(src) {
  const img = document.getElementById("profile-avatar-img");
  const icon = document.getElementById("profile-avatar-icon");
  if (img && icon) {
    if (src) {
      img.src = src;
      img.classList.remove("hidden");
      icon.classList.add("hidden");
    } else {
      img.src = "";
      img.classList.add("hidden");
      icon.classList.remove("hidden");
    }
  }

  // Sinkronisasi otomatis ke header
  if (typeof window.syncHeaderProfile === "function") {
    window.syncHeaderProfile();
  }
}

// ── SIMPAN PERUBAHAN PROFIL ──
window.handleSaveProfile = function() {
  const nama = document.getElementById("input-nama")?.value.trim() || "";
  const lokasi = document.getElementById("input-lokasi")?.value.trim() || "";
  const email = document.getElementById("input-email")?.value.trim() || "";
  const nohp = document.getElementById("input-nohp")?.value.trim() || "";
  const bio = document.getElementById("input-bio")?.value.trim() || "";

  localStorage.setItem("mabar_profile_name", nama);
  localStorage.setItem("mabar_profile_location", lokasi);
  localStorage.setItem("mabar_profile_email", email);
  localStorage.setItem("mabar_profile_nohp", nohp);
  localStorage.setItem("mabar_profile_bio", bio);

  // Sync nama ke header
  if (typeof window.syncHeaderProfile === "function") {
    window.syncHeaderProfile();
  }

  if (typeof window.tampilkanNotifikasi === "function") {
    window.tampilkanNotifikasi("Perubahan profil berhasil disimpan!", "sukses");
  }
};

document.addEventListener("DOMContentLoaded", () => {
  // ── 1. LOAD DATA DARI LOCALSTORAGE SAAT AWAL ──
  const savedBanner   = localStorage.getItem("mabar_profile_banner");
  const savedAvatar   = localStorage.getItem("mabar_profile_avatar");
  const savedName     = localStorage.getItem("mabar_profile_name");
  const savedLocation = localStorage.getItem("mabar_profile_location");
  const savedEmail    = localStorage.getItem("mabar_profile_email");
  const savedNoHp     = localStorage.getItem("mabar_profile_nohp");

  const bannerImg = document.getElementById("profile-banner-img");
  const bannerDefaultBg = document.getElementById("profile-banner-default-bg");

  // Validasi banner: jika dulunya string path asset lokal yang sudah dihapus, hapus dari localStorage
  if (savedBanner) {
    if (savedBanner.includes("profile-banner") || savedBanner.includes("asset/")) {
      localStorage.removeItem("mabar_profile_banner");
      if (bannerImg) {
        bannerImg.src = "";
        bannerImg.classList.add("hidden");
      }
      if (bannerDefaultBg) bannerDefaultBg.classList.remove("hidden");
    } else {
      // Banner base64 kustom dari user
      if (bannerImg) {
        bannerImg.src = savedBanner;
        bannerImg.classList.remove("hidden");
      }
      if (bannerDefaultBg) bannerDefaultBg.classList.add("hidden");
    }
  } else {
    if (bannerImg) {
      bannerImg.src = "";
      bannerImg.classList.add("hidden");
    }
    if (bannerDefaultBg) bannerDefaultBg.classList.remove("hidden");
  }

  // Load avatar
  if (savedAvatar) {
    setAvatarImage(savedAvatar);
  }

  // Load teks form & display
  if (savedName) {
    const inputNama = document.getElementById("input-nama");
    const displayName = document.getElementById("profile-display-name");
    if (inputNama) inputNama.value = savedName;
    if (displayName) displayName.textContent = savedName;
  }

  if (savedLocation) {
    const inputLokasi = document.getElementById("input-lokasi");
    const displayLoc = document.getElementById("profile-display-location");
    if (inputLokasi) inputLokasi.value = savedLocation;
    if (displayLoc) displayLoc.textContent = savedLocation;
  }

  if (savedEmail) {
    const inputEmail = document.getElementById("input-email");
    if (inputEmail) inputEmail.value = savedEmail;
  }

  if (savedNoHp) {
    const inputNoHp = document.getElementById("input-nohp");
    if (inputNoHp) inputNoHp.value = savedNoHp;
  }

  // Load Deskripsi / Bio
  const savedBio = localStorage.getItem("mabar_profile_bio");
  const inputBio = document.getElementById("input-bio");
  if (inputBio) {
    const defaultBio = "Main santai, komunikasi aktif, wajib no toxic & siap koordinasi tim!";
    inputBio.value = savedBio !== null ? savedBio : defaultBio;
  }

  // Render icon minat dari kuesioner
  renderProfileInterestBadges();

  // Live sync nama & lokasi saat user mengetik
  document.getElementById("input-nama")?.addEventListener("input", (e) => {
    const val = e.target.value.trim() || "Wawan";
    const displayName = document.getElementById("profile-display-name");
    if (displayName) displayName.textContent = val;

    const headerUserName = document.getElementById("header-user-name");
    if (headerUserName) headerUserName.textContent = val;

    const dropdownUserFullname = document.getElementById("dropdown-user-fullname");
    if (dropdownUserFullname) dropdownUserFullname.textContent = val;
  });

  document.getElementById("input-lokasi")?.addEventListener("input", (e) => {
    const displayLoc = document.getElementById("profile-display-location");
    if (displayLoc) displayLoc.textContent = e.target.value.trim() || "Malang";
  });

  // Listener form submit
  document.getElementById("profile-form")?.addEventListener("submit", (e) => {
    e.preventDefault();
    window.handleSaveProfile();
  });

  // ── 2. UPLOAD FOTO BANNER (FILE READER & BASE64 KE LOCALSTORAGE) ──
  document.getElementById("banner-upload")?.addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Ukuran foto banner terlalu besar. Maksimal 5MB.", "error");
      }
      return;
    }

    const reader = new FileReader();
    reader.onload = function(evt) {
      const base64 = evt.target.result;
      if (bannerImg) {
        bannerImg.src = base64;
        bannerImg.classList.remove("hidden");
      }
      if (bannerDefaultBg) bannerDefaultBg.classList.add("hidden");

      try {
        localStorage.setItem("mabar_profile_banner", base64);
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Foto banner berhasil disimpan!", "sukses");
        }
      } catch (err) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Ukuran gambar terlalu besar untuk penyimpanan browser.", "error");
        }
      }
    };
    reader.readAsDataURL(file);
  });

  // ── 3. UPLOAD FOTO AVATAR (FILE READER & BASE64 KE LOCALSTORAGE & HEADER) ──
  document.getElementById("avatar-upload")?.addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Ukuran foto profil terlalu besar. Maksimal 5MB.", "error");
      }
      return;
    }

    const reader = new FileReader();
    reader.onload = function(evt) {
      const base64 = evt.target.result;
      setAvatarImage(base64);

      try {
        localStorage.setItem("mabar_profile_avatar", base64);
        // Pastikan sync header terpicu
        if (typeof window.syncHeaderProfile === "function") {
          window.syncHeaderProfile();
        }
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Foto profil berhasil diperbarui & terhubung ke header!", "sukses");
        }
      } catch (err) {
        if (typeof window.tampilkanNotifikasi === "function") {
          window.tampilkanNotifikasi("Ukuran foto profil terlalu besar untuk penyimpanan browser.", "error");
        }
      }
    };
    reader.readAsDataURL(file);
  });
});
