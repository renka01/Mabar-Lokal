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
  setupEyeToggle("btn-toggle-forgot-pass", "forgot-new-pass", "icon-forgot-pass");
  setupEyeToggle("btn-toggle-forgot-confirm", "forgot-confirm-pass", "icon-forgot-confirm");

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

  function syncUserToLocalStorage(user) {
    if (!user) return;
    localStorage.removeItem("mabar_is_guest");
    localStorage.setItem("mabar_profile_name", user.name || "Pemain");
    localStorage.setItem("mabar_profile_tag", user.username_tag || "");
    localStorage.setItem("mabar_profile_email", user.email || "");
    localStorage.setItem("mabar_profile_location", user.city || "Kota Malang");
    localStorage.setItem("mabar_profile_nohp", user.phone || "");
    localStorage.setItem("mabar_profile_bio", user.bio || "");
    if (user.avatar_path) localStorage.setItem("mabar_profile_avatar", user.avatar_path);
    else localStorage.removeItem("mabar_profile_avatar");
    if (user.banner_path) localStorage.setItem("mabar_profile_banner", user.banner_path);
    else localStorage.removeItem("mabar_profile_banner");
    if (user.pref_category) localStorage.setItem("mabar_pref_category", user.pref_category);
    else localStorage.removeItem("mabar_pref_category");
    if (Array.isArray(user.pref_games)) {
      localStorage.setItem("mabar_pref_games", JSON.stringify(user.pref_games));
    } else {
      localStorage.removeItem("mabar_pref_games");
    }
    if (user.questionnaire_completed) {
      localStorage.setItem("mabar_questionnaire_completed", "true");
    } else {
      localStorage.removeItem("mabar_questionnaire_completed");
    }
  }

  async function postJson(url, payload) {
    const res = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      credentials: "same-origin",
      body: JSON.stringify(payload || {})
    });
    let data = null;
    try {
      data = await res.json();
    } catch (e) {
      data = null;
    }
    if (!res.ok || !data || data.ok === false) {
      const msg = (data && data.message) ? data.message : "Terjadi kesalahan pada server.";
      const err = new Error(msg);
      err.payload = data;
      throw err;
    }
    return data;
  }

  window.handleLogin = async function(customEmail = null, customPassword = null) {
    const emailInput = document.getElementById("input-email");
    const passwordInput = document.getElementById("input-password");
    const rememberInput = document.getElementById("remember");
    const submitBtn = document.getElementById("btn-submit-login");

    const email = (customEmail || emailInput.value).trim();
    const password = customPassword !== null ? customPassword : passwordInput.value;
    const remember = Boolean(rememberInput?.checked);

    if (!email) {
      window.tampilkanNotifikasi("Silakan masukkan alamat email Anda!", "error");
      emailInput.focus();
      return;
    }

    if (!password) {
      window.tampilkanNotifikasi("Silakan masukkan kata sandi Anda!", "error");
      passwordInput.focus();
      return;
    }

    const defaultBtnHtml = "Masuk ke MabarLokal";
    submitBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin text-base mr-2"></i> Memproses Masuk...`;
    submitBtn.style.pointerEvents = "none";

    try {
      const data = await postJson("../api/auth/login.php", { email, password, remember });
      syncUserToLocalStorage(data.user);
      window.tampilkanNotifikasi(data.message || "Berhasil masuk! Mengalihkan ke Beranda...", "sukses");
      setTimeout(() => {
        window.location.href = data.redirect || "../index.php";
      }, 550);
    } catch (err) {
      submitBtn.innerHTML = defaultBtnHtml;
      submitBtn.style.pointerEvents = "";
      window.tampilkanNotifikasi(err.message || "Email atau kata sandi salah.", "error");
    }
  };

  // 7. Guest Mode Handler (Jelajahi sebagai Tamu)
  window.handleGuestMode = async function() {
    localStorage.setItem("mabar_is_guest", "true");
    localStorage.setItem("mabar_profile_name", "Tamu Mabar");
    localStorage.setItem("mabar_profile_tag", "Mode Tamu");
    localStorage.removeItem("mabar_profile_avatar");
    localStorage.removeItem("mabar_profile_banner");
    localStorage.removeItem("mabar_profile_email");
    localStorage.removeItem("mabar_profile_nohp");
    localStorage.removeItem("mabar_profile_bio");

    try {
      await postJson("../api/auth/guest.php", {});
    } catch (e) {}

    window.tampilkanNotifikasi("Masuk sebagai Tamu. Selamat menjelajah!", "sukses");

    setTimeout(() => {
      window.location.href = "../index.php";
    }, 550);
  };

  window.handleRegister = async function() {
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

    const defaultRegHtml = "Daftar Akun Sekarang";
    submitBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin text-base mr-2"></i> Mendaftarkan Akun...`;
    submitBtn.style.pointerEvents = "none";

    try {
      const data = await postJson("../api/auth/register.php", {
        name: nama,
        email,
        phone: nohp,
        city: kota,
        password: pass,
        confirm_password: confirmPass,
        agree
      });
      syncUserToLocalStorage(data.user);
      window.tampilkanNotifikasi(data.message || "Pendaftaran berhasil! Mengatur preferensi game & olahraga...", "sukses");
      setTimeout(() => {
        window.location.href = data.redirect || "kuesioner.php";
      }, 650);
    } catch (err) {
      submitBtn.innerHTML = defaultRegHtml;
      submitBtn.style.pointerEvents = "";
      window.tampilkanNotifikasi(err.message || "Gagal mendaftarkan akun.", "error");
    }
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
      window.closeForgotPasswordModal();
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

  const forgotState = {
    email: "",
    resetToken: "",
    expiresAt: 0,
    resendLeft: 0,
    timerId: null
  };

  function clearForgotTimer() {
    if (forgotState.timerId) {
      clearInterval(forgotState.timerId);
      forgotState.timerId = null;
    }
  }

  function updateForgotCountdownUI() {
    const expiryEl = document.getElementById("forgot-otp-expiry");
    const resendBtn = document.getElementById("btn-forgot-resend");
    const now = Date.now();
    const remainSec = Math.max(0, Math.ceil((forgotState.expiresAt - now) / 1000));

    if (expiryEl) {
      if (remainSec <= 0) {
        expiryEl.textContent = "Kode kedaluwarsa";
        expiryEl.style.color = "#ef233c";
      } else {
        const mm = String(Math.floor(remainSec / 60)).padStart(2, "0");
        const ss = String(remainSec % 60).padStart(2, "0");
        expiryEl.textContent = `Berlaku ${mm}:${ss}`;
        expiryEl.style.color = "";
      }
    }

    if (resendBtn) {
      if (forgotState.resendLeft > 0) {
        resendBtn.disabled = true;
        resendBtn.textContent = `Kirim Ulang (${forgotState.resendLeft}d)`;
      } else {
        resendBtn.disabled = false;
        resendBtn.textContent = "Kirim Ulang Kode";
      }
    }

    if (remainSec <= 0 && forgotState.resendLeft <= 0) {
      clearForgotTimer();
    }
  }

  function startForgotTimers() {
    clearForgotTimer();
    updateForgotCountdownUI();
    forgotState.timerId = setInterval(() => {
      if (forgotState.resendLeft > 0) {
        forgotState.resendLeft -= 1;
      }
      updateForgotCountdownUI();
    }, 1000);
  }

  window.goToForgotStep = function(step) {
    const subtitleEl = document.getElementById("forgot-modal-subtitle");
    const subtitles = {
      1: "Langkah 1 dari 3 • Masukkan alamat email terdaftar",
      2: "Langkah 2 dari 3 • Verifikasi kode OTP email",
      3: "Langkah 3 dari 3 • Buat kata sandi baru"
    };
    if (subtitleEl) subtitleEl.textContent = subtitles[step] || subtitles[1];

    [1, 2, 3].forEach((n) => {
      const panel = document.getElementById(`forgot-step-${n}`);
      const pill = document.querySelector(`[data-forgot-pill="${n}"]`);
      if (panel) panel.classList.toggle("hidden", n !== step);
      if (pill) {
        pill.classList.toggle("is-active", n === step);
        pill.classList.toggle("is-done", n < step);
      }
    });

    if (step === 1) {
      setTimeout(() => document.getElementById("forgot-email")?.focus(), 80);
    } else if (step === 2) {
      setTimeout(() => document.getElementById("forgot-otp")?.focus(), 80);
    } else if (step === 3) {
      setTimeout(() => document.getElementById("forgot-new-pass")?.focus(), 80);
    }
  };

  window.openForgotPasswordModal = function() {
    const modal = document.getElementById("modal-lupa-password");
    if (!modal) return;

    clearForgotTimer();
    forgotState.email = "";
    forgotState.resetToken = "";
    forgotState.expiresAt = 0;
    forgotState.resendLeft = 0;

    const loginEmail = document.getElementById("input-email")?.value.trim() || "";
    const forgotEmailInput = document.getElementById("forgot-email");
    const otpInput = document.getElementById("forgot-otp");
    const newPassInput = document.getElementById("forgot-new-pass");
    const confirmPassInput = document.getElementById("forgot-confirm-pass");
    const demoBanner = document.getElementById("forgot-demo-banner");
    const demoOtpEl = document.getElementById("forgot-demo-otp");

    if (forgotEmailInput) forgotEmailInput.value = loginEmail;
    if (otpInput) otpInput.value = "";
    if (newPassInput) newPassInput.value = "";
    if (confirmPassInput) confirmPassInput.value = "";
    if (demoBanner) demoBanner.classList.add("hidden");
    if (demoOtpEl) demoOtpEl.textContent = "------";
    window.checkForgotPasswordStrength("");

    window.goToForgotStep(1);
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  };

  window.closeForgotPasswordModal = function() {
    const modal = document.getElementById("modal-lupa-password");
    if (!modal) return;
    clearForgotTimer();
    modal.classList.add("hidden");
    document.body.style.overflow = "";
  };

  async function requestBackendOtp(email, isResend = false) {
    const sendBtn = document.getElementById("btn-forgot-send-otp");
    const resendBtn = document.getElementById("btn-forgot-resend");
    const prevSendHtml = sendBtn ? sendBtn.innerHTML : "";

    if (!isResend && sendBtn) {
      sendBtn.disabled = true;
      sendBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin"></i><span>Mengirim OTP...</span>`;
    } else if (isResend && resendBtn) {
      resendBtn.disabled = true;
      resendBtn.textContent = "Mengirim...";
    }

    try {
      const data = await postJson("../api/auth/forgot_request.php", { email });
      forgotState.email = email;
      forgotState.resetToken = "";
      forgotState.expiresAt = Date.now() + ((Number(data.expires_in) || 300) * 1000);
      forgotState.resendLeft = Number(data.resend_cooldown) || 30;

      const targetEl = document.getElementById("forgot-target-email");
      const demoBanner = document.getElementById("forgot-demo-banner");
      const demoOtpEl = document.getElementById("forgot-demo-otp");
      const otpInput = document.getElementById("forgot-otp");

      if (targetEl) targetEl.textContent = email;
      if (otpInput) otpInput.value = "";

      if (data.delivery === "dev_fallback" && data.dev_otp) {
        if (demoOtpEl) demoOtpEl.textContent = data.dev_otp;
        if (demoBanner) demoBanner.classList.remove("hidden");
      } else {
        if (demoOtpEl) demoOtpEl.textContent = "------";
        if (demoBanner) demoBanner.classList.add("hidden");
      }

      startForgotTimers();
      window.goToForgotStep(2);
      window.tampilkanNotifikasi(
        data.message || (isResend ? `Kode OTP baru telah dikirim ke ${email}.` : `Kode OTP telah dikirim ke ${email}.`),
        "sukses"
      );
    } catch (err) {
      updateForgotCountdownUI();
      window.tampilkanNotifikasi(err.message || "Gagal mengirim kode OTP.", "error");
    } finally {
      if (sendBtn) {
        sendBtn.disabled = false;
        sendBtn.innerHTML = prevSendHtml || `<i class="ph-bold ph-paper-plane-tilt"></i><span>Kirim Kode OTP</span>`;
      }
    }
  }

  window.handleForgotStep1 = function() {
    const emailInput = document.getElementById("forgot-email");
    const email = emailInput?.value.trim() || "";
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
      window.tampilkanNotifikasi("Masukkan alamat email yang valid!", "error");
      emailInput?.focus();
      return;
    }

    requestBackendOtp(email, false);
  };

  window.resendForgotOtp = function() {
    if (!forgotState.email || forgotState.resendLeft > 0) return;
    requestBackendOtp(forgotState.email, true);
  };

  window.handleForgotStep2 = async function() {
    const otpInput = document.getElementById("forgot-otp");
    const verifyBtn = document.getElementById("btn-forgot-verify-otp");
    const code = (otpInput?.value || "").replace(/\D/g, "");

    if (code.length !== 6) {
      window.tampilkanNotifikasi("Masukkan tepat 6 digit kode OTP!", "error");
      otpInput?.focus();
      return;
    }

    if (!forgotState.email) {
      window.goToForgotStep(1);
      return;
    }

    const prevHtml = verifyBtn ? verifyBtn.innerHTML : "";
    if (verifyBtn) {
      verifyBtn.disabled = true;
      verifyBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin"></i><span>Memverifikasi...</span>`;
    }

    try {
      const data = await postJson("../api/auth/forgot_verify.php", {
        email: forgotState.email,
        otp: code
      });
      forgotState.resetToken = data.reset_token || "";
      clearForgotTimer();

      const labelEl = document.getElementById("forgot-reset-email-label");
      if (labelEl) labelEl.textContent = forgotState.email;
      window.goToForgotStep(3);
      window.tampilkanNotifikasi(data.message || "Kode OTP terverifikasi! Silakan buat kata sandi baru.", "sukses");
    } catch (err) {
      window.tampilkanNotifikasi(err.message || "Kode OTP tidak sesuai.", "error");
      otpInput?.focus();
    } finally {
      if (verifyBtn) {
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = prevHtml || `<i class="ph-bold ph-shield-check"></i><span>Verifikasi OTP</span>`;
      }
    }
  };

  window.checkForgotPasswordStrength = function(val) {
    const bar = document.getElementById("forgot-strength-bar");
    const txt = document.getElementById("forgot-strength-text");
    if (!bar || !txt) return;

    if (!val) {
      bar.style.width = "0%";
      bar.style.backgroundColor = "transparent";
      txt.textContent = "-";
      txt.style.color = "#9ba5b4";
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
      txt.style.color = "#ef233c";
    } else if (score === 2) {
      bar.style.width = "66%";
      bar.style.backgroundColor = "#f59e0b";
      txt.textContent = "Cukup Baik";
      txt.style.color = "#f59e0b";
    } else {
      bar.style.width = "100%";
      bar.style.backgroundColor = "#19d66b";
      txt.textContent = "Kuat & Aman";
      txt.style.color = "#19d66b";
    }
  };

  window.handleForgotStep3 = async function() {
    const newPassInput = document.getElementById("forgot-new-pass");
    const confirmInput = document.getElementById("forgot-confirm-pass");
    const saveBtn = document.getElementById("btn-forgot-save-pass");
    const newPass = newPassInput?.value || "";
    const confirmPass = confirmInput?.value || "";

    if (newPass.length < 6) {
      window.tampilkanNotifikasi("Kata sandi baru minimal harus 6 karakter!", "error");
      newPassInput?.focus();
      return;
    }

    if (newPass !== confirmPass) {
      window.tampilkanNotifikasi("Kata sandi baru dan konfirmasi tidak cocok!", "error");
      confirmInput?.focus();
      return;
    }

    const prevHtml = saveBtn ? saveBtn.innerHTML : "";
    if (saveBtn) {
      saveBtn.disabled = true;
      saveBtn.innerHTML = `<i class="ph ph-circle-notch animate-spin"></i><span>Menyimpan...</span>`;
    }

    try {
      const data = await postJson("../api/auth/forgot_reset.php", {
        email: forgotState.email,
        reset_token: forgotState.resetToken,
        new_password: newPass
      });

      const loginEmailInput = document.getElementById("input-email");
      const loginPassInput = document.getElementById("input-password");
      if (loginEmailInput && forgotState.email) {
        loginEmailInput.value = forgotState.email;
      }
      if (loginPassInput) {
        loginPassInput.value = "";
      }

      window.closeForgotPasswordModal();
      window.switchAuthMode("login");
      window.tampilkanNotifikasi(data.message || "Kata sandi berhasil diperbarui! Silakan masuk dengan sandi baru.", "sukses");
      setTimeout(() => loginPassInput?.focus(), 120);
    } catch (err) {
      window.tampilkanNotifikasi(err.message || "Gagal memperbarui kata sandi.", "error");
    } finally {
      if (saveBtn) {
        saveBtn.disabled = false;
        saveBtn.innerHTML = prevHtml || `<i class="ph-bold ph-check-circle"></i><span>Simpan Kata Sandi</span>`;
      }
    }
  };

  document.getElementById("forgot-otp")?.addEventListener("input", function() {
    const cleaned = this.value.replace(/\D/g, "").slice(0, 6);
    if (this.value !== cleaned) this.value = cleaned;
  });
});

