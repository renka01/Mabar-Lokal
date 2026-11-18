(function () {
  function inisialisasiSliderNav() {
    const nav = document.getElementById("desktop-nav");
    const indicator = document.getElementById("nav-indicator");
    if (!nav || !indicator) return;

    // Helper untuk memindahkan indikator ke target link dengan transisi mulus
    function moveToLink(link, withTransition = true) {
      if (!link) return;
      if (withTransition) {
        indicator.style.transition =
          "left 0.28s cubic-bezier(0.16, 1, 0.3, 1), width 0.28s cubic-bezier(0.16, 1, 0.3, 1)";
      } else {
        indicator.style.transition = "none";
      }
      indicator.style.left = link.offsetLeft + "px";
      indicator.style.width = link.offsetWidth + "px";
      indicator.style.opacity = "1";
    }

    // Set posisi awal pada link yang aktif tanpa delay
    const getActiveLink = () => nav.querySelector(".nav-link.active") || nav.querySelector(".nav-link");
    moveToLink(getActiveLink(), false);

    nav.querySelectorAll(".nav-link").forEach((link) => {
      link.addEventListener("click", () => {
        const href = link.getAttribute("href");
        if (href && href !== "#") {
          // Meluncur langsung ke link yang diklik dengan halus
          moveToLink(link, true);
        }
      });

      link.addEventListener("mouseenter", () => {
        moveToLink(link, true);
      });
    });

    // Saat kursor meninggalkan area navigasi, kembalikan ke tab aktif
    nav.addEventListener("mouseleave", () => {
      moveToLink(getActiveLink(), true);
    });

    window.addEventListener("resize", () => {
      moveToLink(getActiveLink(), false);
    });

    window.addEventListener("pageshow", () => {
      moveToLink(getActiveLink(), false);
    });
  }

  // Jalankan segera saat file dipanggil
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", inisialisasiSliderNav);
  } else {
    inisialisasiSliderNav();
  }
})();
