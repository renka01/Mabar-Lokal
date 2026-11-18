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

function resolveAssetUrl(src) {
  if (!src) return "";
  if (typeof window.mabarResolveAsset === "function") {
    return window.mabarResolveAsset(src);
  }
  if (/^(?:data:|blob:|https?:\/\/|\/)/i.test(src)) return src;
  return `../../${src}`;
}

// Helper set gambar avatar profil halaman & header
function setAvatarImage(src) {
  const img = document.getElementById("profile-avatar-img");
  const icon = document.getElementById("profile-avatar-icon");
  const resolved = resolveAssetUrl(src);
  if (img && icon) {
    if (resolved) {
      img.src = resolved;
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

function setBannerImage(src) {
  const bannerImg = document.getElementById("profile-banner-img");
  const bannerDefaultBg = document.getElementById("profile-banner-default-bg");
  const resolved = resolveAssetUrl(src);
  if (resolved && bannerImg) {
    bannerImg.src = resolved;
    bannerImg.classList.remove("hidden");
    if (bannerDefaultBg) bannerDefaultBg.classList.add("hidden");
  } else {
    if (bannerImg) {
      bannerImg.src = "";
      bannerImg.classList.add("hidden");
    }
    if (bannerDefaultBg) bannerDefaultBg.classList.remove("hidden");
  }
}

window.handleSaveProfile = async function() {
  const nama = document.getElementById("input-nama")?.value.trim() || "";
  const lokasi = document.getElementById("input-lokasi")?.value.trim() || "";
  const email = document.getElementById("input-email")?.value.trim() || "";
  const nohp = document.getElementById("input-nohp")?.value.trim() || "";
  const bio = document.getElementById("input-bio")?.value.trim() || "";
  const saveBtn = document.getElementById("btn-save-profile");

  const auth = window.MABAR_AUTH || {};
  if (!auth.authenticated) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Silakan masuk akun terlebih dahulu untuk menyimpan perubahan ke database.", "error");
    }
    return;
  }

  const prevHtml = saveBtn ? saveBtn.innerHTML : "Simpan Perubahan";
  if (saveBtn) {
    saveBtn.disabled = true;
    saveBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin mr-1.5"></i> Menyimpan...`;
  }

  try {
    const res = await fetch(`${window.MABAR_BASE_PATH || "../../"}api/profile/update.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      credentials: "same-origin",
      body: JSON.stringify({
        name: nama,
        email,
        city: lokasi,
        phone: nohp,
        bio
      })
    });
    const data = await res.json();
    if (!res.ok || !data || data.ok === false) {
      throw new Error((data && data.message) || "Gagal menyimpan profil.");
    }

    const u = data.user || {};
    window.MABAR_AUTH.user = u;

    localStorage.setItem("mabar_profile_name", u.name || nama);
    localStorage.setItem("mabar_profile_tag", u.username_tag || "");
    localStorage.setItem("mabar_profile_location", u.city || lokasi);
    localStorage.setItem("mabar_profile_email", u.email || email);
    localStorage.setItem("mabar_profile_nohp", u.phone || nohp);
    localStorage.setItem("mabar_profile_bio", u.bio || bio);

    const displayName = document.getElementById("profile-display-name");
    const displayLoc = document.getElementById("profile-display-location");
    if (displayName) displayName.textContent = u.name || nama;
    if (displayLoc) displayLoc.textContent = u.city || lokasi;

    if (typeof window.syncHeaderProfile === "function") {
      window.syncHeaderProfile();
    }

    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(data.message || "Perubahan profil berhasil disimpan!", "sukses");
    }
  } catch (err) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi(err.message || "Gagal menyimpan perubahan profil.", "error");
    }
  } finally {
    if (saveBtn) {
      saveBtn.disabled = false;
      saveBtn.innerHTML = prevHtml;
    }
  }
};

async function uploadProfileMedia(type, file) {
  const auth = window.MABAR_AUTH || {};
  if (!auth.authenticated) {
    if (typeof window.tampilkanNotifikasi === "function") {
      window.tampilkanNotifikasi("Masuk akun terlebih dahulu untuk mengunggah foto profil atau banner.", "error");
    }
    return null;
  }

  const formData = new FormData();
  formData.append("type", type);
  formData.append("file", file);

  const res = await fetch(`${window.MABAR_BASE_PATH || "../../"}api/profile/upload_media.php`, {
    method: "POST",
    credentials: "same-origin",
    body: formData
  });

  const data = await res.json();
  if (!res.ok || !data || data.ok === false) {
    throw new Error((data && data.message) || "Gagal mengunggah gambar.");
  }

  if (data.user) {
    window.MABAR_AUTH.user = data.user;
  }
  return data;
}

document.addEventListener("DOMContentLoaded", () => {
  const auth = window.MABAR_AUTH || {};
  const dbUser = auth.authenticated ? auth.user : null;

  const savedBanner   = dbUser ? (dbUser.banner_path || "") : "";
  const savedAvatar   = dbUser ? (dbUser.avatar_path || "") : "";
  const savedName     = dbUser ? (dbUser.name || "") : (localStorage.getItem("mabar_profile_name") || "Tamu Mabar");
  const savedLocation = dbUser ? (dbUser.city || "Kota Malang") : (localStorage.getItem("mabar_profile_location") || "Jawa Timur");
  const savedEmail    = dbUser ? (dbUser.email || "") : "";
  const savedNoHp     = dbUser ? (dbUser.phone || "") : "";
  const savedBio      = dbUser ? (dbUser.bio || "") : "";

  setBannerImage(savedBanner);
  setAvatarImage(savedAvatar);

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

  const inputEmail = document.getElementById("input-email");
  if (inputEmail) inputEmail.value = savedEmail;

  const inputNoHp = document.getElementById("input-nohp");
  if (inputNoHp) inputNoHp.value = savedNoHp;

  const inputBio = document.getElementById("input-bio");
  if (inputBio) {
    inputBio.value = savedBio;
  }

  // Render icon minat dari kuesioner
  renderProfileInterestBadges();

  // Live sync nama & lokasi saat user mengetik
  document.getElementById("input-nama")?.addEventListener("input", (e) => {
    const val = e.target.value.trim() || "Pemain";
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

  document.getElementById("banner-upload")?.addEventListener("change", async function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Ukuran foto banner terlalu besar. Maksimal 5MB.", "error");
      }
      this.value = "";
      return;
    }

    const previewUrl = URL.createObjectURL(file);
    setBannerImage(previewUrl);

    try {
      const data = await uploadProfileMedia("banner", file);
      if (!data) {
        setBannerImage(savedBanner);
        return;
      }
      setBannerImage(data.path);
      localStorage.setItem("mabar_profile_banner", data.path);
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Foto banner berhasil disimpan!", "sukses");
      }
    } catch (err) {
      setBannerImage(savedBanner);
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal mengunggah foto banner.", "error");
      }
    } finally {
      URL.revokeObjectURL(previewUrl);
      this.value = "";
    }
  });

  document.getElementById("avatar-upload")?.addEventListener("change", async function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi("Ukuran foto profil terlalu besar. Maksimal 5MB.", "error");
      }
      this.value = "";
      return;
    }

    const previewUrl = URL.createObjectURL(file);
    setAvatarImage(previewUrl);

    try {
      const data = await uploadProfileMedia("avatar", file);
      if (!data) {
        setAvatarImage(savedAvatar);
        return;
      }
      localStorage.setItem("mabar_profile_avatar", data.path);
      setAvatarImage(data.path);
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(data.message || "Foto profil berhasil diperbarui & terhubung ke header!", "sukses");
      }
    } catch (err) {
      setAvatarImage(savedAvatar);
      if (typeof window.tampilkanNotifikasi === "function") {
        window.tampilkanNotifikasi(err.message || "Gagal mengunggah foto profil.", "error");
      }
    } finally {
      URL.revokeObjectURL(previewUrl);
      this.value = "";
    }
  });
});
