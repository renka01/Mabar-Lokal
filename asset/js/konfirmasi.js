/**
 * asset/js/konfirmasi.js — Sistem Modal Konfirmasi Global
 * Menyediakan window.konfirmasiModal(opts) yang mengembalikan Promise<boolean>
 * sebagai pengganti confirm() native. Markup ada di include/komponen_konfirmasi.php.
 *
 * opts: { judul, pesan, teksYa, teksBatal }
 *   resolve(true)  -> tombol "Ya" ditekan
 *   resolve(false) -> Batal / klik backdrop / tekan Escape
 */
(function () {
  const modal = document.getElementById("modal-konfirmasi");
  let aktifResolver = null;

  function onKey(e) {
    if (e.key === "Escape") {
      e.preventDefault();
      tutup(false);
    }
  }

  function tutup(hasil) {
    if (modal) modal.classList.add("hidden");
    document.removeEventListener("keydown", onKey);
    const resolver = aktifResolver;
    aktifResolver = null;
    if (resolver) resolver(hasil);
  }

  // Ikat sekali di awal (markup sudah termuat sebelum skrip ini di footer).
  if (modal) {
    modal.querySelectorAll("[data-konfirmasi-tutup]").forEach((el) => {
      el.addEventListener("click", () => tutup(false));
    });
    const tombolYa = document.getElementById("konfirmasi-ya");
    if (tombolYa) tombolYa.addEventListener("click", () => tutup(true));
  }

  window.konfirmasiModal = function (opts) {
    opts = opts || {};

    // Fallback aman bila markup modal belum tersedia di halaman.
    if (!modal) {
      return Promise.resolve(window.confirm(opts.pesan || "Apakah Anda yakin?"));
    }

    // Bila masih ada konfirmasi terbuka, batalkan yang lama agar tidak menumpuk.
    if (aktifResolver) tutup(false);

    const judulEl = document.getElementById("konfirmasi-judul");
    const pesanEl = document.getElementById("konfirmasi-pesan");
    const yaEl = document.getElementById("konfirmasi-ya");
    const batalEl = document.getElementById("konfirmasi-batal");

    if (judulEl) judulEl.textContent = opts.judul || "Konfirmasi";
    if (pesanEl) pesanEl.textContent = opts.pesan || "Apakah Anda yakin?";
    if (yaEl) yaEl.textContent = opts.teksYa || "Ya, Lanjutkan";
    if (batalEl) batalEl.textContent = opts.teksBatal || "Batal";

    modal.classList.remove("hidden");
    document.addEventListener("keydown", onKey);

    return new Promise((resolve) => {
      aktifResolver = resolve;
      // Fokus default ke Batal: aksi destruktif tidak terpicu Enter tanpa sengaja.
      if (batalEl) batalEl.focus();
    });
  };
})();