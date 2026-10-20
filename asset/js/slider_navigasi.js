/**
 * asset/js/slider_navigasi.js — Animasi Indikator Slider Merah pada Navigasi Header
 * Menggerakkan garis merah di bawah menu aktif dan mengingat posisi saat berpindah halaman.
 */

(function () {
  function inisialisasiSliderNav() {
    const nav = document.getElementById("desktop-nav");
    const indicator = document.getElementById("nav-indicator");
    if (!nav || !indicator) return;

    const activeLink = nav.querySelector(".nav-link.active");
    if (!activeLink) return;

    let prevData = null;
    try {
      const stored = sessionStorage.getItem("mabar_nav_prev");
      if (stored) {
        prevData = JSON.parse(stored);
        sessionStorage.removeItem("mabar_nav_prev");
      }
    } catch (e) {}

    const now = Date.now();
    const hasValidPrev =
      prevData &&
      now - prevData.time < 3000 &&
      prevData.page !== activeLink.dataset.page;

    if (hasValidPrev) {
      // Mulai dari posisi halaman sebelumnya tanpa transisi
      indicator.style.transition = "none";
      indicator.style.left = prevData.left + "px";
      indicator.style.width = prevData.width + "px";
      indicator.style.opacity = "1";

      // Geser halus ke posisi halaman baru pada frame berikutnya
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          indicator.style.transition =
            "left 0.35s cubic-bezier(0.25, 1, 0.5, 1), width 0.35s cubic-bezier(0.25, 1, 0.5, 1)";
          indicator.style.left = activeLink.offsetLeft + "px";
          indicator.style.width = activeLink.offsetWidth + "px";
        });
      });
    } else {
      // Langsung pasang di tab aktif saat refresh atau buka link langsung
      indicator.style.transition = "none";
      indicator.style.left = activeLink.offsetLeft + "px";
      indicator.style.width = activeLink.offsetWidth + "px";
      indicator.style.opacity = "1";
    }

    // Simpan koordinat tab sebelum berpindah halaman
    nav.querySelectorAll(".nav-link").forEach((link) => {
      link.addEventListener("click", () => {
        const href = link.getAttribute("href");
        if (href && href !== "#" && !link.classList.contains("active")) {
          const cur = nav.querySelector(".nav-link.active") || link;
          sessionStorage.setItem(
            "mabar_nav_prev",
            JSON.stringify({
              page: cur.dataset.page || "",
              left: cur.offsetLeft,
              width: cur.offsetWidth,
              time: Date.now(),
            })
          );
        }
      });
    });

    // Penyesuaian saat window resize
    window.addEventListener("resize", () => {
      const cur = nav.querySelector(".nav-link.active");
      if (cur) {
        indicator.style.transition = "none";
        indicator.style.left = cur.offsetLeft + "px";
        indicator.style.width = cur.offsetWidth + "px";
      }
    });

    // Penyesuaian setelah font selesai dimuat agar ukuran teks akurat
    if (document.fonts) {
      document.fonts.ready.then(() => {
        setTimeout(
          () => {
            const cur = nav.querySelector(".nav-link.active");
            if (cur) {
              indicator.style.left = cur.offsetLeft + "px";
              indicator.style.width = cur.offsetWidth + "px";
            }
          },
          hasValidPrev ? 400 : 50
        );
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", inisialisasiSliderNav);
  } else {
    inisialisasiSliderNav();
  }
})();
