<?php
session_start();
// Jika user sudah memiliki session login riil, langsung arahkan ke beranda
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Tangkap mode awal dari parameter URL (?mode=register atau ?mode=login)
$initialMode = isset($_GET['mode']) && $_GET['mode'] === 'register' ? 'register' : 'login';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MabarLokal - Autentikasi Pemain</title>
    <link rel="stylesheet" href="../asset/css/output.css" />
    <link rel="stylesheet" href="../asset/css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400..900;1,400..900&family=Sora:wght@100..800&display=swap"
      rel="stylesheet"
    />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
      /* Smooth Tab Navigation Pills */
      .auth-tab-btn {
        flex: 1;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 8px;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: center;
        color: #9ba5b4;
        cursor: pointer;
        user-select: none;
      }
      .auth-tab-btn:hover {
        color: #ffffff;
      }
      .auth-tab-btn.active {
        background-color: #ef233c;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(239, 35, 60, 0.3);
      }
      /* CSS Grid Stack: Kedua Panel Menempati Sel Grid yang Sama */
      /* Menjamin tinggi wrapper 100% stabil setinggi panel tertinggi, sehingga posisi tab di tengah NOL LONJAKAN */
      .auth-panels-stack {
        display: grid;
        grid-template-columns: 100%;
        width: 100%;
        align-items: start;
      }
      .auth-form-panel {
        display: flex;
        flex-direction: column;
        grid-column: 1 / 2;
        grid-row: 1 / 2;
        width: 100%;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.18s ease-in-out;
      }
      .auth-form-panel.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
      }
    </style>
  </head>
  <body class="flex min-h-screen items-center justify-center bg-[#080f18] auth-cyber-grid text-white font-sans text-sm selection:bg-red selection:text-white p-4 sm:p-6 lg:p-8">
    
    <!-- Main Card Container: Persis Seperti login.html (max-w-[960px]) -->
    <div class="flex w-full max-w-[960px] flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-2xl md:flex-row relative z-10 backdrop-blur-md">
      
      <!-- Sisi Kiri: Branding & Value Showcase (Statis, Gagah, Elegan - Persis login.html) -->
      <div class="relative flex min-h-[320px] flex-1 flex-col items-center justify-between p-8 sm:p-10 text-center md:min-h-[560px] md:border-r md:border-border overflow-hidden bg-gradient-to-b from-[#0f1b2b]/95 via-[#0b1420]/95 to-[#080f18]/95">
        
        <!-- Ambient Red Glow Aura -->
        <div class="absolute -top-24 -left-24 h-[320px] w-[320px] rounded-full bg-red/15 blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 h-[280px] w-[280px] rounded-full bg-red/10 blur-[90px] pointer-events-none"></div>

        <!-- Static Brand Logo (Murni Identitas Visual) -->
        <div class="relative z-10 flex flex-col items-center pt-2 select-none">
          <div class="w-[190px] sm:w-[220px] drop-shadow-[0_8px_24px_rgba(239,35,60,0.3)]">
            <img src="../asset/icon/Logo Full Max.svg" alt="MabarLokal Logo" class="h-full w-auto object-contain" />
          </div>
        </div>

        <!-- Center Value Proposition -->
        <div class="relative z-10 flex flex-col items-center my-auto py-6 w-full">
          <h2 class="text-lg sm:text-[21px] font-extrabold font-heading tracking-tight text-white mb-2 leading-snug">
            Arena Mabar & Turnamen <span class="text-red">Jawa Timur</span>
          </h2>
          <p class="max-w-[340px] text-[12px] sm:text-[13px] text-muted leading-relaxed mb-6">
            Hubungkan passion bermain game dan olahraga bersama kawan komunitas di sekitarmu.
          </p>

          <!-- 3 Value Points (Clean, Non-AI, Mengedepankan Fitur Inti) -->
          <div class="flex flex-col gap-2.5 w-full max-w-[330px] text-left">
            <div class="flex items-center gap-3 p-3 rounded-xl border border-border/70 bg-[#080f18]/80">
              <div class="w-8 h-8 rounded-lg bg-red/15 border border-red/30 flex items-center justify-center text-red shrink-0">
                <i class="ph-bold ph-lightning text-sm"></i>
              </div>
              <div class="text-[11px]">
                <span class="font-bold text-white block">Cari Kawan Mabar</span>
                <span class="text-muted text-[10px]">Temukan room mabar santai & ranked cepat</span>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl border border-border/70 bg-[#080f18]/80">
              <div class="w-8 h-8 rounded-lg bg-red/15 border border-red/30 flex items-center justify-center text-red shrink-0">
                <i class="ph-bold ph-trophy text-sm"></i>
              </div>
              <div class="text-[11px]">
                <span class="font-bold text-white block">Turnamen Regional</span>
                <span class="text-muted text-[10px]">Daftarkan tim & ikuti kompetisi resmi</span>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl border border-border/70 bg-[#080f18]/80">
              <div class="w-8 h-8 rounded-lg bg-[#19d66b]/15 border border-[#19d66b]/30 flex items-center justify-center text-[#19d66b] shrink-0">
                <i class="ph-bold ph-shield-check text-sm"></i>
              </div>
              <div class="text-[11px]">
                <span class="font-bold text-white block">Komunitas Fair Play</span>
                <span class="text-muted text-[10px]">Verifikasi pemain & reputasi sportif</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom Stats Bar -->
        <div class="relative z-10 w-full grid grid-cols-2 gap-3 text-[11px] font-semibold text-muted/90 pt-4 border-t border-border/50">
          <div class="flex items-center justify-center gap-1.5">
            <i class="ph-fill ph-map-pin text-red text-xs"></i>
            <span>38 Kota & Kab Jatim</span>
          </div>
          <div class="flex items-center justify-center gap-1.5">
            <i class="ph-fill ph-check-circle text-green text-xs"></i>
            <span>Komunitas Terverifikasi</span>
          </div>
        </div>
      </div>

      <!-- Sisi Kanan: Form Container (justify-center di Tengah Sempurna & Konsisten) -->
      <div class="flex flex-1 flex-col justify-center p-8 sm:p-10 lg:p-12 bg-card">
        
        <!-- Segmented Tab Switcher (Posisi Solid & Terkunci 100%) -->
        <div class="mb-5 flex rounded-xl border border-border bg-[#0a121d] p-1 shadow-inner shrink-0">
          <button
            type="button"
            id="tab-btn-login"
            onclick="switchAuthMode('login')"
            class="auth-tab-btn <?= $initialMode === 'login' ? 'active' : '' ?>"
          >
            Masuk Akun
          </button>
          <button
            type="button"
            id="tab-btn-register"
            onclick="switchAuthMode('register')"
            class="auth-tab-btn <?= $initialMode === 'register' ? 'active' : '' ?>"
          >
            Daftar Akun Baru
          </button>
        </div>

        <!-- Panels Stack Wrapper (Grid Overlay: Tinggi Terkunci Otomatis, Nol Goyang / Melompat) -->
        <div class="auth-panels-stack">
          <!-- ================= PANEL 1: FORM MASUK (LOGIN) ================= -->
          <div id="panel-login" class="auth-form-panel flex-col <?= $initialMode === 'login' ? 'active' : '' ?>">
          <div class="mb-5">
            <h1 class="text-[24px] font-bold text-white font-heading tracking-tight mb-1">
              Masuk Akun
            </h1>
            <p class="text-[13px] text-muted">
              Silakan masukkan alamat email dan kata sandi Anda.
            </p>
          </div>

          <form id="form-login" class="flex flex-col gap-4" onsubmit="event.preventDefault(); handleLogin();">
            
            <!-- Field: Email -->
            <div class="flex flex-col gap-1.5">
              <label for="input-email" class="text-[12px] font-semibold text-white/90">
                Alamat Email
              </label>
              <input
                type="email"
                id="input-email"
                placeholder="nama@email.com"
                required
                class="auth-input"
              />
            </div>

            <!-- Field: Kata Sandi -->
            <div class="flex flex-col gap-1.5">
              <div class="flex items-center justify-between">
                <label for="input-password" class="text-[12px] font-semibold text-white/90">
                  Kata Sandi
                </label>
                <a
                  href="#"
                  onclick="event.preventDefault(); window.tampilkanNotifikasi('Fitur reset kata sandi sedang dalam tahap pengembangan.', 'error');"
                  class="text-[11px] text-muted hover:text-red transition-colors"
                >
                  Lupa Kata Sandi?
                </a>
              </div>
              
              <div class="relative flex items-center">
                <input
                  type="password"
                  id="input-password"
                  placeholder="Masukkan kata sandi anda"
                  required
                  class="auth-input auth-input-password"
                />
                <button
                  type="button"
                  id="btn-toggle-password"
                  class="auth-eye-btn"
                  title="Lihat / Sembunyikan Kata Sandi"
                >
                  <i id="icon-eye-password" class="ph ph-eye text-lg"></i>
                </button>
              </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center gap-2.5 my-1">
              <input
                type="checkbox"
                id="remember"
                class="h-4 w-4 rounded border-border bg-[#080f18] text-red accent-red cursor-pointer"
              />
              <label for="remember" class="text-[12px] text-muted cursor-pointer select-none">
                Ingat saya di perangkat ini
              </label>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              id="btn-submit-login"
              class="btn-auth-submit w-full h-11 rounded-lg bg-red font-bold text-white text-[13px] tracking-wide transition-all hover:bg-red-bright active:scale-[0.98] shadow-lg shadow-red/25 cursor-pointer mt-1"
            >
              Masuk ke MabarLokal
            </button>

            <!-- Divider -->
            <div class="relative flex items-center justify-center my-1">
              <div class="w-full border-t border-border/60"></div>
              <span class="absolute bg-card px-3 text-[11px] text-muted/70 uppercase tracking-wider font-semibold">Atau</span>
            </div>

            <!-- Quick Demo Login Button (Dev / Evaluasi PBL) -->
            <button
              type="button"
              id="btn-demo-login"
              class="w-full h-10 rounded-lg border border-border bg-[#101a28] hover:bg-[#152336] hover:border-muted text-muted hover:text-white font-semibold text-[12px] transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98]"
              title="Masuk instan menggunakan akun demo Wawan untuk pengujian"
            >
              <i class="ph-bold ph-lightning text-red text-sm"></i>
              <span>Masuk Cepat Demo (Akun Wawan)</span>
            </button>

            <!-- Mode Tamu (Guest Mode) -->
            <button
              type="button"
              id="btn-guest-mode"
              onclick="handleGuestMode()"
              class="w-full text-center text-[11.5px] text-muted/80 hover:text-white transition-colors py-1 cursor-pointer"
            >
              <i class="ph ph-user-circle mr-1"></i> Jelajahi sebagai Tamu (Mode Baca)
            </button>
          </form>

          <p class="mt-4 text-center text-[12px] text-muted">
            Belum punya akun? <a href="javascript:void(0)" onclick="switchAuthMode('register')" class="font-bold text-white hover:text-red transition-colors">Daftar Akun Baru</a>
          </p>
        </div>

        <!-- ================= PANEL 2: FORM DAFTAR (REGISTER) ================= -->
        <div id="panel-register" class="auth-form-panel flex-col <?= $initialMode === 'register' ? 'active' : '' ?>">
          <div class="mb-5">
            <h1 class="text-[24px] font-bold text-white font-heading tracking-tight mb-1">
              Daftar Akun Baru
            </h1>
            <p class="text-[13px] text-muted">
              Lengkapi data di bawah ini untuk membuat profil pemain Anda.
            </p>
          </div>

          <form id="form-register" class="flex flex-col gap-3" onsubmit="event.preventDefault(); handleRegister();">
            
            <!-- Baris 1: Nama Lengkap & Email -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="flex flex-col gap-1">
                <label for="reg-nama" class="text-[12px] font-semibold text-white/90">Nama Lengkap</label>
                <input
                  type="text"
                  id="reg-nama"
                  placeholder="Nama lengkap / panggilan"
                  required
                  class="auth-input"
                />
              </div>

              <div class="flex flex-col gap-1">
                <label for="reg-email" class="text-[12px] font-semibold text-white/90">Alamat Email</label>
                <input
                  type="email"
                  id="reg-email"
                  placeholder="nama@email.com"
                  required
                  class="auth-input"
                />
              </div>
            </div>

            <!-- Baris 2: Kontak & Kota Domisili (Dropdown Jatim) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="flex flex-col gap-1">
                <label for="reg-nohp" class="text-[12px] font-semibold text-white/90">Nomor WhatsApp / HP</label>
                <div class="relative flex items-center">
                  <input
                    type="tel"
                    id="reg-nohp"
                    placeholder="08123456789"
                    required
                    class="auth-input pr-9"
                    maxlength="16"
                  />
                  <!-- Status Indicator Icon (Centang Hijau Minimalis Saat Valid) -->
                  <div
                    id="phone-valid-indicator"
                    class="auth-phone-indicator"
                    title="Nomor WhatsApp Indonesia Valid"
                  >
                    <i class="ph-bold ph-check-circle"></i>
                  </div>
                </div>
              </div>

              <div class="flex flex-col gap-1 relative" id="wrapper-reg-kota">
                <label for="reg-kota" class="text-[12px] font-semibold text-white/90">Kota Domisili</label>
                <div class="relative flex items-center">
                  <input
                    type="text"
                    id="reg-kota"
                    value=""
                    placeholder="Pilih kota domisili..."
                    required
                    autocomplete="off"
                    class="auth-input pr-9 cursor-pointer"
                  />
                  <i id="reg-kota-caret" class="ph ph-caret-down absolute right-3 text-muted pointer-events-none transition-transform duration-200"></i>
                </div>
                <!-- Floating City Dropdown List (Solid Opaque, Searchable Live) -->
                <ul id="reg-kota-list" class="auth-city-dropdown-list hidden"></ul>
              </div>
            </div>

            <!-- Baris 3: Kata Sandi & Konfirmasi Kata Sandi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between">
                  <label for="reg-pass" class="text-[12px] font-semibold text-white/90">Kata Sandi</label>
                  <span id="strength-meter-text" class="text-[10px] text-muted font-medium">-</span>
                </div>
                <div class="relative flex items-center">
                  <input
                    type="password"
                    id="reg-pass"
                    placeholder="Min 6 karakter"
                    required
                    class="auth-input auth-input-password"
                    oninput="checkPasswordStrength(this.value)"
                  />
                  <button
                    type="button"
                    id="btn-toggle-reg-pass"
                    class="auth-eye-btn"
                    title="Lihat / Sembunyikan Kata Sandi"
                  >
                    <i id="icon-reg-pass" class="ph ph-eye text-base"></i>
                  </button>
                </div>
                <!-- Sleek Password Strength Meter -->
                <div class="pass-strength-bar mt-0.5">
                  <div id="strength-meter-bar" class="pass-strength-fill"></div>
                </div>
              </div>

              <div class="flex flex-col gap-1">
                <label for="reg-confirm-pass" class="text-[12px] font-semibold text-white/90">Ulangi Kata Sandi</label>
                <div class="relative flex items-center">
                  <input
                    type="password"
                    id="reg-confirm-pass"
                    placeholder="Ulangi kata sandi"
                    required
                    class="auth-input auth-input-password"
                  />
                  <button
                    type="button"
                    id="btn-toggle-reg-confirm"
                    class="auth-eye-btn"
                    title="Lihat / Sembunyikan Konfirmasi Sandi"
                  >
                    <i id="icon-reg-confirm" class="ph ph-eye text-base"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Checkbox Persetujuan Syarat Ketentuan -->
            <div class="flex items-start gap-2.5 my-1">
              <input
                type="checkbox"
                id="reg-agree"
                required
                class="h-4 w-4 rounded border-border bg-[#080f18] text-red accent-red cursor-pointer mt-0.5 shrink-0"
              />
              <label for="reg-agree" class="text-[11px] text-muted leading-snug cursor-pointer select-none">
                Saya menyetujui <a href="javascript:void(0)" onclick="openTermsModal()" class="text-white underline hover:text-red transition-colors">Syarat & Ketentuan</a> dan siap mabar sportif.
              </label>
            </div>

            <!-- Tombol Submit Daftar -->
            <button
              type="submit"
              id="btn-submit-reg"
              class="btn-auth-submit w-full h-11 rounded-lg bg-red font-bold text-white text-[13px] tracking-wide transition-all hover:bg-red-bright active:scale-[0.98] shadow-lg shadow-red/25 cursor-pointer mt-1"
            >
              Daftar Akun Sekarang
            </button>
          </form>

          <p class="mt-4 text-center text-[12px] text-muted">
            Sudah punya akun? <a href="javascript:void(0)" onclick="switchAuthMode('login')" class="font-bold text-white hover:text-red transition-colors">Masuk Sekarang</a>
          </p>
        </div>
      </div><!-- End auth-panels-stack -->

      </div>
    </div>

    <!-- Floating Global Toast Notification -->
    <div
      id="global-toast"
      style="
        position: fixed;
        bottom: 32px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        opacity: 0;
        pointer-events: none;
        z-index: 999999;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: none;
        align-items: center;
        gap: 9px;
        padding: 10px 20px;
        border-radius: 9999px;
        background-color: #1a0a0d;
        border: 1px solid #ef233c;
        box-shadow: 0 10px 30px rgba(239, 35, 60, 0.35);
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
      "
    >
      <i id="global-toast-icon" class="ph-bold ph-warning-circle text-base" style="color: #ef233c;"></i>
      <span id="global-toast-text">Pemberitahuan</span>
    </div>

    <!-- Modal Syarat & Ketentuan Komunitas (Modular Component) -->
    <?php require __DIR__ . '/../include/modal_syarat_ketentuan.php'; ?>

    <script src="../asset/js/notifikasi.js"></script>
    <script src="../asset/js/auth.js"></script>
  </body>
</html>
