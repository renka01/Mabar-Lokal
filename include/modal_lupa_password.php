<?php
?>
<!-- MODAL OVERLAY: LUPA KATA SANDI -->
<div id="modal-lupa-password" class="terms-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="forgot-modal-title">
  <!-- Backdrop Click Listener Area -->
  <div class="terms-modal-backdrop" onclick="closeForgotPasswordModal()"></div>

  <!-- Modal Card Content -->
  <div class="terms-modal-container forgot-modal-container">
    <!-- Header -->
    <div class="terms-modal-header">
      <div>
        <h3 id="forgot-modal-title" class="terms-modal-title">Reset Kata Sandi</h3>
        <p id="forgot-modal-subtitle" class="terms-modal-subtitle">Langkah 1 dari 3 • Masukkan alamat email terdaftar</p>
      </div>

      <button
        type="button"
        onclick="closeForgotPasswordModal()"
        class="terms-modal-close"
        title="Tutup Modal"
        aria-label="Tutup modal reset kata sandi"
      >
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Progress Stepper -->
    <div class="forgot-steps-bar" aria-label="Tahapan reset kata sandi">
      <div class="forgot-step-pill is-active" data-forgot-pill="1">
        <span class="forgot-step-num">1</span>
        <span>Email</span>
      </div>
      <span class="forgot-step-divider"></span>
      <div class="forgot-step-pill" data-forgot-pill="2">
        <span class="forgot-step-num">2</span>
        <span>Kode OTP</span>
      </div>
      <span class="forgot-step-divider"></span>
      <div class="forgot-step-pill" data-forgot-pill="3">
        <span class="forgot-step-num">3</span>
        <span>Sandi Baru</span>
      </div>
    </div>

    <!-- Body Content -->
    <div class="terms-modal-body modal-terms-scroll">
      <!-- STEP 1: INPUT EMAIL -->
      <form id="forgot-step-1" class="forgot-step-panel" onsubmit="event.preventDefault(); handleForgotStep1();">
        <p class="forgot-help-text">
          Masukkan alamat email akun MabarLokal Anda. Kode verifikasi 6 digit (OTP) akan dikirim untuk mengonfirmasi permintaan reset kata sandi.
        </p>

        <div class="forgot-field-group">
          <label for="forgot-email" class="forgot-field-label">Alamat Email Terdaftar</label>
          <input
            type="email"
            id="forgot-email"
            placeholder="nama@email.com"
            required
            autocomplete="email"
            class="auth-input"
          />
        </div>

        <div class="forgot-actions">
          <button
            type="button"
            onclick="closeForgotPasswordModal()"
            class="terms-btn-cancel"
          >
            Batal
          </button>
          <button
            type="submit"
            id="btn-forgot-send-otp"
            class="terms-btn-accept forgot-submit-btn"
          >
            <i class="ph-bold ph-paper-plane-tilt"></i>
            <span>Kirim Kode OTP</span>
          </button>
        </div>
      </form>

      <!-- STEP 2: VERIFIKASI KODE OTP -->
      <form id="forgot-step-2" class="forgot-step-panel hidden" onsubmit="event.preventDefault(); handleForgotStep2();">
        <div class="forgot-target-row">
          <p class="forgot-help-text">
            Masukkan 6 digit kode OTP yang dikirim ke <strong id="forgot-target-email" class="forgot-highlight-email">-</strong>
          </p>
          <button type="button" onclick="goToForgotStep(1)" class="forgot-link-btn">
            Ganti Email
          </button>
        </div>

        <!-- Banner info dev lokal: otomatis muncul HANYA jika SMTP Gmail di .env belum diisi -->
        <div id="forgot-demo-banner" class="forgot-demo-banner hidden">
          <i class="ph-fill ph-envelope-simple text-base"></i>
          <div>
            <span class="forgot-demo-title">Mode Dev Lokal (.env Gmail belum diisi)</span>
            <p class="forgot-demo-desc">
              Gunakan kode OTP: <strong id="forgot-demo-otp" class="forgot-demo-code">------</strong>
            </p>
          </div>
        </div>

        <div class="forgot-field-group">
          <label for="forgot-otp" class="forgot-field-label">Kode OTP (6 Digit)</label>
          <input
            type="text"
            id="forgot-otp"
            placeholder="000000"
            maxlength="6"
            inputmode="numeric"
            autocomplete="one-time-code"
            required
            class="auth-input forgot-otp-input"
          />
        </div>

        <div class="forgot-otp-meta">
          <span id="forgot-otp-expiry" class="forgot-meta-muted">Berlaku 05:00</span>
          <button
            type="button"
            id="btn-forgot-resend"
            onclick="resendForgotOtp()"
            class="forgot-link-btn"
          >
            Kirim Ulang Kode
          </button>
        </div>

        <div class="forgot-actions">
          <button
            type="button"
            onclick="goToForgotStep(1)"
            class="terms-btn-cancel"
          >
            Kembali
          </button>
          <button
            type="submit"
            id="btn-forgot-verify-otp"
            class="terms-btn-accept forgot-submit-btn"
          >
            <i class="ph-bold ph-shield-check"></i>
            <span>Verifikasi OTP</span>
          </button>
        </div>
      </form>

      <!-- STEP 3: BUAT KATA SANDI BARU -->
      <form id="forgot-step-3" class="forgot-step-panel hidden" onsubmit="event.preventDefault(); handleForgotStep3();">
        <p class="forgot-help-text">
          Verifikasi berhasil. Buat kata sandi baru minimal 6 karakter untuk akun <strong id="forgot-reset-email-label" class="forgot-highlight-email">-</strong>.
        </p>

        <div class="forgot-field-group">
          <div class="forgot-label-row">
            <label for="forgot-new-pass" class="forgot-field-label">Kata Sandi Baru</label>
            <span id="forgot-strength-text" class="forgot-strength-label">-</span>
          </div>
          <div class="relative flex items-center">
            <input
              type="password"
              id="forgot-new-pass"
              placeholder="Minimal 6 karakter"
              required
              class="auth-input auth-input-password"
              oninput="checkForgotPasswordStrength(this.value)"
            />
            <button
              type="button"
              id="btn-toggle-forgot-pass"
              class="auth-eye-btn"
              title="Lihat / Sembunyikan Kata Sandi Baru"
            >
              <i id="icon-forgot-pass" class="ph ph-eye text-base"></i>
            </button>
          </div>
          <div class="pass-strength-bar">
            <div id="forgot-strength-bar" class="pass-strength-fill"></div>
          </div>
        </div>

        <div class="forgot-field-group">
          <label for="forgot-confirm-pass" class="forgot-field-label">Ulangi Kata Sandi Baru</label>
          <div class="relative flex items-center">
            <input
              type="password"
              id="forgot-confirm-pass"
              placeholder="Ulangi kata sandi baru"
              required
              class="auth-input auth-input-password"
            />
            <button
              type="button"
              id="btn-toggle-forgot-confirm"
              class="auth-eye-btn"
              title="Lihat / Sembunyikan Konfirmasi Kata Sandi"
            >
              <i id="icon-forgot-confirm" class="ph ph-eye text-base"></i>
            </button>
          </div>
        </div>

        <div class="forgot-actions">
          <button
            type="button"
            onclick="closeForgotPasswordModal()"
            class="terms-btn-cancel"
          >
            Batal
          </button>
          <button
            type="submit"
            id="btn-forgot-save-pass"
            class="terms-btn-accept forgot-submit-btn"
          >
            <i class="ph-bold ph-check-circle"></i>
            <span>Simpan Kata Sandi</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

