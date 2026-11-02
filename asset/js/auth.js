/**
 * MabarLokal - Authentication Logic (asset/js/auth.js)
 * Modul logika interaktif untuk halaman autentikasi (pages/auth.php)
 */

document.addEventListener("DOMContentLoaded", () => {
  // 1. Tab Switcher Logic (Tanpa Page Reload, Smooth Transition & Nol Jumping)
  window.switchAuthMode = function(mode) {
    const btnLogin = document.getElementById("tab-btn-login");
    const btnReg = document.getElementById("tab-btn-register");
    const panelLogin = document.getElementById("panel-login");
    const panelReg = document.getElementById("panel-register");

    if (!btnLogin || !btnReg || !panelLogin || !panelReg) return;

    if (mode === "register") {
      btnLogin.classList.remove("active");
      btnReg.classList.add("active");
      panelLogin.classList.remove("active");
      panelReg.classList.add("active");
      window.history.replaceState(null, "", "auth.php?mode=register");
      document.title = "MabarLokal - Daftar Akun Baru";
    } else {
      btnReg.classList.remove("active");
      btnLogin.classList.add("active");
      panelReg.classList.remove("active");
      panelLogin.classList.add("active");
      window.history.replaceState(null, "", "auth.php?mode=login");
      document.title = "MabarLokal - Masuk Akun";
    }
  };

  // 2. Eye Toggle Show / Hide Password Helper
  function setupEyeToggle(buttonId, inputId, iconId) {
    const btn = document.getElementById(buttonId);
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!btn || !input || !icon) return;

    btn.addEventListener("click", () => {
      const isPass = input.getAttribute("type") === "password";
      if (isPass) {
        input.setAttribute("type", "text");
        icon.className = "ph ph-eye-slash text-base text-white";
      } else {
        input.setAttribute("type", "password");
        icon.className = "ph ph-eye text-base text-muted";
      }
    });
  }

  setupEyeToggle("btn-toggle-password", "input-password", "icon-eye-password");
  setupEyeToggle("btn-toggle-reg-pass", "reg-pass", "icon-reg-pass");
  setupEyeToggle("btn-toggle-reg-confirm", "reg-confirm-pass", "icon-reg-confirm");

  // 3. Realtime Password Strength Meter
  window.checkPasswordStrength = function(val) {
    const bar = document.getElementById("strength-meter-bar");
    const txt = document.getElementById("strength-meter-text");
    if (!bar || !txt) return;

    if (val.length === 0) {
      bar.style.width = "0%";
      bar.style.backgroundColor = "transparent";
      txt.textContent = "-";
      txt.className = "text-[10px] text-muted font-medium";
      return;
    }

    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val) || /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    if (score <= 1) {
      bar.style.width = "33%";
      bar.style.backgroundColor = "#ef233c";
      txt.textContent = "Lemah";
      txt.className = "text-[10px] text-red font-semibold";
    } else if (score === 2) {
      bar.style.width = "66%";
      bar.style.backgroundColor = "#f59e0b";
      txt.textContent = "Cukup Baik";
      txt.className = "text-[10px] text-amber-400 font-semibold";
    } else {
      bar.style.width = "100%";
      bar.style.backgroundColor = "#19d66b";
      txt.textContent = "Kuat & Aman";
      txt.className = "text-[10px] text-green font-semibold";
    }
  };

  // 4. Validasi Nomor WhatsApp / Seluler Indonesia (Format & Operator Resmi RI)
  window.validateIndonesianPhone = function(val) {
    const input = document.getElementById("reg-nohp");
    const indicator = document.getElementById("phone-valid-indicator");
    if (!input || !indicator) return false;

    const clean = val.replace(/[\s\-\(\)]/g, "");
    // Prefix operator resmi RI: Telkomsel (0811-13, 0821-23, 0851-53), Indosat (0814-16, 0855-58), XL/Axis (0817-19, 0831-38, 0859, 0877-78), Smartfren (0881-89), Tri (0895-99)
    const idPhoneRegex = /^(?:\+62|62|0)8(1[1-9]|2[1-3]|3[1-38]|5[1-35-9]|7[78]|8[1-9]|9[5-9])[0-9]{6,9}$/;
    const isValid = idPhoneRegex.test(clean);

    if (isValid) {
      indicator.classList.add("active");
      input.classList.add("is-valid-phone");
      return true;
    } else {
      indicator.classList.remove("active");
      input.classList.remove("is-valid-phone");
      return false;
    }
  };

  // Listener otomatis untuk input nomor WhatsApp (hanya angka dan +)
  document.getElementById("reg-nohp")?.addEventListener("input", function() {
    let val = this.value.replace(/[^\d+]/g, "");
    if (val.indexOf("+") > 0) {
      val = val.charAt(0) === "+" ? "+" + val.substring(1).replace(/\+/g, "") : val.replace(/\+/g, "");
    }
    if (this.value !== val) {
      this.value = val;
    }
    validateIndonesianPhone(this.value);
  });

  // 5. Searchable City Combobox Logic (38 Kota/Kab Jawa Timur - Sama Seperti Sidebar Buatsesi)
  const KOTA_JATIM_AUTH = [
    "Kota Malang", "Kab. Malang", "Kota Batu",
    "Kota Surabaya", "Kab. Sidoarjo", "Kab. Gresik",
    "Kota Kediri", "Kab. Kediri",
    "Kota Blitar", "Kab. Blitar",
    "Kota Pasuruan", "Kab. Pasuruan",
    "Kota Probolinggo", "Kab. Probolinggo",
    "Kota Mojokerto", "Kab. Mojokerto",
    "Kota Madiun", "Kab. Madiun",
    "Kab. Jember", "Kab. Banyuwangi",
    "Kab. Tuban", "Kab. Lamongan", "Kab. Bojonegoro",
    "Kab. Lumajang", "Kab. Tulungagung", "Kab. Trenggalek",
    "Kab. Nganjuk", "Kab. Jombang", "Kab. Magetan", "Kab. Ngawi",
    "Kab. Ponorogo", "Kab. Pacitan", "Kab. Bondowoso", "Kab. Situbondo",
    "Kab. Bangkalan", "Kab. Sampang", "Kab. Pamekasan", "Kab. Sumenep"
  ];

  (function initCityCombobox() {
    const input = document.getElementById("reg-kota");
    const list = document.getElementById("reg-kota-list");
    const caret = document.getElementById("reg-kota-caret");
    const wrapper = document.getElementById("wrapper-reg-kota");
    if (!input || !list) return;

    function renderList(query = "", forceAll = false) {
      const q = forceAll ? "" : query.toLowerCase().trim();
      const filtered = q ? KOTA_JATIM_AUTH.filter(k => k.toLowerCase().includes(q)) : KOTA_JATIM_AUTH;

      if (!filtered.length) {
        list.innerHTML = `<li class="px-3 py-2 text-[12px] text-muted/60 italic text-center">Kota tidak ditemukan</li>`;
        return;
      }

      list.innerHTML = filtered.map(k => {
        const isSelected = k.toLowerCase() === input.value.toLowerCase().trim();
        return `<li class="city-auth-option ${isSelected ? 'selected' : ''}" data-value="${k}">
          <span>${k}</span>
          ${isSelected ? '<i class="ph-bold ph-check text-red text-xs"></i>' : ''}
        </li>`;
      }).join("");
    }

    function openList(forceAll = false) {
      renderList(input.value, forceAll);
      list.classList.remove("hidden");
      if (caret) caret.style.transform = "rotate(180deg)";
    }

    function closeList() {
      list.classList.add("hidden");
      if (caret) caret.style.transform = "";
    }

    input.addEventListener("focus", function() {
      this.select();
      openList(true);
    });

    input.addEventListener("input", function() {
      openList(false);
    });

    input.addEventListener("click", function(e) {
      e.stopPropagation();
      if (list.classList.contains("hidden")) {
        openList(true);
      }
    });

    list.addEventListener("click", (e) => {
      const opt = e.target.closest(".city-auth-option");
      if (!opt) return;
      const val = opt.getAttribute("data-value");
      if (val) {
        input.value = val;
        closeList();
      }
    });

    document.addEventListener("click", (e) => {
      if (wrapper && !wrapper.contains(e.target)) {
        closeList();
      }
    });
  })();

  // 6. Submit Login Handler
  window.handleLogin = function(customEmail = null) {
    const emailInput = document.getElementById("input-email");
    const passwordInput = document.getElementById("input-password");
    const submitBtn = document.getElementById("btn-submit-login");

    const email = customEmail || emailInput.value.trim();
    const password = passwordInput.value;

    if (!email) {
      window.tampilkanNotifikasi("Silakan masukkan alamat email Anda!", "error");
      emailInput.focus();
      return;
    }

    if (!password && !customEmail) {
      window.tampilkanNotifikasi("Silakan masukkan kata sandi Anda!", "error");
      passwordInput.focus();
      return;
    }

    submitBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin text-base mr-2"></i> Memproses Masuk...`;
    submitBtn.style.pointerEvents = "none";

    // Simpan default profile data jika belum ada di localStorage
    if (!localStorage.getItem("mabar_profile_name") || localStorage.getItem("mabar_is_guest") === "true") {
      localStorage.setItem("mabar_profile_name", "Wawan");
    }
    localStorage.removeItem("mabar_is_guest");
    localStorage.setItem("mabar_profile_email", email);

    window.tampilkanNotifikasi("Berhasil masuk! Mengalihkan ke Beranda...", "sukses");

    setTimeout(() => {
      window.location.href = "../index.php";
    }, 650);
  };

  // 7. Quick Demo Login Button Handler
  document.getElementById("btn-demo-login")?.addEventListener("click", () => {
    document.getElementById("input-email").value = "wawanwrwn@gmail.com";
    document.getElementById("input-password").value = "demopbl123";
    window.handleLogin("wawanwrwn@gmail.com");
  });

  // 8. Guest Mode Handler (Jelajahi sebagai Tamu)
  window.handleGuestMode = function() {
    localStorage.setItem("mabar_is_guest", "true");
    localStorage.setItem("mabar_profile_name", "Tamu Mabar");
    localStorage.setItem("mabar_profile_email", "guest@mabarlokal.com");
    localStorage.setItem("mabar_profile_location", "Jawa Timur");

    window.tampilkanNotifikasi("Masuk sebagai Tamu. Selamat menjelajah!", "sukses");

    setTimeout(() => {
      window.location.href = "../index.php";
    }, 650);
  };

  // 9. Submit Register Handler
  window.handleRegister = function() {
    const nama = document.getElementById("reg-nama").value.trim();
    const email = document.getElementById("reg-email").value.trim();
    const nohp = document.getElementById("reg-nohp").value.trim();
    const kota = document.getElementById("reg-kota").value.trim();
    const pass = document.getElementById("reg-pass").value;
    const confirmPass = document.getElementById("reg-confirm-pass").value;
    const agree = document.getElementById("reg-agree").checked;
    const submitBtn = document.getElementById("btn-submit-reg");

    if (!nama || !email || !nohp || !pass || !confirmPass) {
      window.tampilkanNotifikasi("Harap lengkapi semua data formulir!", "error");
      return;
    }

    if (!kota) {
      window.tampilkanNotifikasi("Silakan pilih kota domisili Anda!", "error");
      document.getElementById("reg-kota").focus();
      return;
    }

    if (!validateIndonesianPhone(nohp)) {
      window.tampilkanNotifikasi("Nomor WhatsApp tidak valid! Gunakan nomor HP seluler Indonesia aktif (contoh: 08123456789).", "error");
      document.getElementById("reg-nohp").focus();
      return;
    }

    if (pass.length < 6) {
      window.tampilkanNotifikasi("Kata sandi minimal harus 6 karakter!", "error");
      document.getElementById("reg-pass").focus();
      return;
    }

    if (pass !== confirmPass) {
      window.tampilkanNotifikasi("Kata sandi dan konfirmasi kata sandi tidak cocok!", "error");
      document.getElementById("reg-confirm-pass").focus();
      return;
    }

    if (!agree) {
      window.tampilkanNotifikasi("Harap baca dan setujui Syarat & Ketentuan komunitas terlebih dahulu!", "error");
      window.openTermsModal();
      return;
    }

    submitBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin text-base mr-2"></i> Mendaftarkan Akun...`;
    submitBtn.style.pointerEvents = "none";

    // Simpan data pendaftar ke localStorage
    localStorage.removeItem("mabar_is_guest");
    localStorage.setItem("mabar_profile_name", nama);
    localStorage.setItem("mabar_profile_email", email);
    localStorage.setItem("mabar_profile_nohp", nohp);
    localStorage.setItem("mabar_profile_location", kota);

    window.tampilkanNotifikasi("Pendaftaran berhasil! Mengatur preferensi game & olahraga...", "sukses");

    setTimeout(() => {
      window.location.href = "kuesioner.php";
    }, 750);
  };

  // 10. Modal Syarat & Ketentuan Handlers (Smart Fair Play Flow)
  let hasReadTerms = false;

  window.openTermsModal = function() {
    const modal = document.getElementById("modal-syarat-ketentuan");
    if (!modal) return;
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  };

  window.closeTermsModal = function() {
    const modal = document.getElementById("modal-syarat-ketentuan");
    if (!modal) return;
    modal.classList.add("hidden");
    document.body.style.overflow = "";
  };

  window.acceptTermsAndClose = function() {
    const agreeCheckbox = document.getElementById("reg-agree");
    if (agreeCheckbox) {
      agreeCheckbox.checked = true;
    }
    hasReadTerms = true;
    window.closeTermsModal();
    window.tampilkanNotifikasi("Syarat & ketentuan komunitas telah Anda setujui.", "sukses");
  };

  // Tutup modal via tombol keyboard Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      window.closeTermsModal();
    }
  });

  // Intercept klik langsung checkbox jika belum pernah membuka modal
  const agreeCheckbox = document.getElementById("reg-agree");
  if (agreeCheckbox) {
    agreeCheckbox.addEventListener("click", (e) => {
      if (!hasReadTerms && agreeCheckbox.checked) {
        e.preventDefault();
        agreeCheckbox.checked = false;
        window.openTermsModal();
      }
    });
  }
});

