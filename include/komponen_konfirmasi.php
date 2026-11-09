<?php
/**
 * include/komponen_konfirmasi.php — Modal Konfirmasi Global
 * Pengganti dialog confirm() bawaan browser. Konsisten dengan bahasa visual
 * modal scrim (.scrim-modal-overlay / .scrim-modal-container) bertema gelap–merah.
 * Dipanggil lewat window.konfirmasiModal({ judul, pesan, teksYa, teksBatal }).
 */
?>
<!-- Global Confirmation Modal (Pengganti confirm() Native) -->
<div
  id="modal-konfirmasi"
  class="scrim-modal-overlay hidden"
  role="dialog"
  aria-modal="true"
  aria-labelledby="konfirmasi-judul"
  aria-describedby="konfirmasi-pesan"
>
  <div class="absolute inset-0 cursor-pointer" data-konfirmasi-tutup></div>

  <div class="scrim-modal-container z-10 flex flex-col max-w-[400px]">
    <div class="flex flex-col items-center text-center">
      <div
        class="flex h-12 w-12 items-center justify-center rounded-full mb-4"
        style="background-color: rgba(239, 35, 60, 0.12); border: 1px solid rgba(239, 35, 60, 0.3);"
      >
        <i class="ph-bold ph-warning text-2xl" style="color: #ef233c;"></i>
      </div>

      <h3 id="konfirmasi-judul" class="text-[17px] font-bold text-white tracking-tight leading-snug">
        Konfirmasi
      </h3>
      <p id="konfirmasi-pesan" class="text-[13px] text-muted mt-2 leading-relaxed">
        Apakah Anda yakin?
      </p>
    </div>

    <div class="flex items-center gap-3 mt-6">
      <button
        type="button"
        id="konfirmasi-batal"
        data-konfirmasi-tutup
        class="flex-1 inline-flex items-center justify-center h-10 px-5 rounded-md border border-border bg-card-alt text-muted text-[13px] font-semibold transition-all hover:text-white hover:border-muted active:scale-95 cursor-pointer"
      >
        Batal
      </button>
      <button
        type="button"
        id="konfirmasi-ya"
        class="flex-1 inline-flex items-center justify-center h-10 px-5 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] active:scale-95 cursor-pointer"
      >
        Ya, Lanjutkan
      </button>
    </div>
  </div>
</div>