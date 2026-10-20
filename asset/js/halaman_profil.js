/**
 * asset/js/halaman_profil.js — Logika Interaktif Halaman Profil Pengguna
 * Menangani preview banner, upload avatar kustom, live text sync, dan sinkronisasi LocalStorage dengan header global.
 */

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

  localStorage.setItem("mabar_profile_name", nama);
  localStorage.setItem("mabar_profile_location", lokasi);
  localStorage.setItem("mabar_profile_email", email);
  localStorage.setItem("mabar_profile_nohp", nohp);

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
