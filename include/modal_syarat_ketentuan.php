<?php
/**
 * include/modal_syarat_ketentuan.php — Modal Syarat & Ketentuan Komunitas MabarLokal
 * Dialog panduan aturan komunitas mabar lokal (Clean & Human Design)
 */
?>
<!-- MODAL OVERLAY: SYARAT & KETENTUAN KOMUNITAS -->
<div id="modal-syarat-ketentuan" class="terms-modal-overlay hidden">
  
  <!-- Backdrop Click Listener Area -->
  <div class="terms-modal-backdrop" onclick="closeTermsModal()"></div>

  <!-- Modal Card Content -->
  <div class="terms-modal-container">
    
    <!-- Header -->
    <div class="terms-modal-header">
      <div>
        <h3 class="terms-modal-title">Syarat & Ketentuan Komunitas</h3>
        <p class="terms-modal-subtitle">Pedoman sportivitas & etika mabar komunitas MabarLokal</p>
      </div>

      <button
        type="button"
        onclick="closeTermsModal()"
        class="terms-modal-close"
        title="Tutup Modal"
      >
        <i class="ph ph-x"></i>
      </button>
    </div>

    <!-- Body Content -->
    <div class="terms-modal-body modal-terms-scroll">
      
      <!-- Poin 1: Sportivitas -->
      <div class="terms-item">
        <span class="terms-item-num">01</span>
        <div class="terms-item-content">
          <h4>Sportivitas & Fair Play</h4>
          <p>
            Dilarang menggunakan program pihak ketiga ilegal seperti cheat, script, map hack, bot, atau memanfaatkan bug sistem game. Pelanggaran berat akan langsung berakibat pemblokiran akun.
          </p>
        </div>
      </div>

      <!-- Poin 2: Etika & Anti-Toxic -->
      <div class="terms-item">
        <span class="terms-item-num">02</span>
        <div class="terms-item-content">
          <h4>Etika Komunitas (No-Toxic)</h4>
          <p>
            Jaga suasana bermain tetap asik dan santai. Dilarang melakukan ujaran kebencian, pelecehan, diskriminasi SARA, maupun provokasi berlebihan terhadap kawan satu tim atau lawan.
          </p>
        </div>
      </div>

      <!-- Poin 3: Komitmen Mabar -->
      <div class="terms-item">
        <span class="terms-item-num">03</span>
        <div class="terms-item-content">
          <h4>Komitmen Kehadiran (Anti-Ghosting)</h4>
          <p>
            Hormati waktu pemain lain. Jika sudah konfirmasi bergabung di room mabar atau scrim, hadir tepat waktu dan tidak meninggalkan pertandingan secara mendadak tanpa kabar darurat.
          </p>
        </div>
      </div>

      <!-- Poin 4: Integritas Akun & Sanksi -->
      <div class="terms-item">
        <span class="terms-item-num">04</span>
        <div class="terms-item-content">
          <h4>Integritas Akun & Reputasi</h4>
          <p>
            Gunakan nomor WhatsApp aktif untuk koordinasi room. Laporan ketidakhadiran atau kecurangan berulang akan menurunkan skor reputasi akun dan membatasi akses membuat room.
          </p>
        </div>
      </div>

    </div>

    <!-- Footer Actions -->
    <div class="terms-modal-footer">
      <span class="terms-footer-note">Wajib dipahami sebelum mendaftar</span>
      
      <div class="terms-footer-actions">
        <button
          type="button"
          onclick="closeTermsModal()"
          class="terms-btn-cancel"
        >
          Tutup
        </button>

        <button
          type="button"
          id="btn-agree-terms-modal"
          onclick="acceptTermsAndClose()"
          class="terms-btn-accept"
        >
          <i class="ph-bold ph-check text-xs"></i>
          <span>Saya Setuju & Lanjutkan</span>
        </button>
      </div>
    </div>

  </div>
</div>
