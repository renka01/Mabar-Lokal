/**
 * asset/js/notifikasi.js — Sistem Notifikasi Toast Global
 * Menyediakan fungsi universal tampilkanNotifikasi() untuk pesan feedback aksi pengguna.
 */
let globalToastTimeout = null;

window.tampilkanNotifikasi = function(text, type = "error") {
  const toast = document.getElementById("global-toast");
  const icon = document.getElementById("global-toast-icon");
  const textEl = document.getElementById("global-toast-text");
  if (!toast || !icon || !textEl) return;

  if (globalToastTimeout) clearTimeout(globalToastTimeout);
  textEl.textContent = text;

  const isSuccess = type === "success" || type === "sukses";

  if (isSuccess) {
    toast.style.backgroundColor = "#0a1c14";
    toast.style.borderColor = "#19d66b";
    toast.style.boxShadow = "0 10px 30px rgba(25, 214, 107, 0.35)";
    icon.className = "ph-bold ph-check-circle text-base";
    icon.style.color = "#19d66b";
  } else {
    toast.style.backgroundColor = "#1a0a0d";
    toast.style.borderColor = "#ef233c";
    toast.style.boxShadow = "0 10px 30px rgba(239, 35, 60, 0.35)";
    icon.className = "ph-bold ph-warning-circle text-base";
    icon.style.color = "#ef233c";
  }

  toast.style.display = "inline-flex";
  requestAnimationFrame(() => {
    toast.style.opacity = "1";
    toast.style.transform = "translateX(-50%) translateY(0)";
  });

  globalToastTimeout = setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateX(-50%) translateY(16px)";
    setTimeout(() => {
      toast.style.display = "none";
    }, 250);
  }, 2600);
};

// Alias agar kompatibel dengan pemanggilan kode yang sudah ada
window.showGlobalToast = window.tampilkanNotifikasi;
