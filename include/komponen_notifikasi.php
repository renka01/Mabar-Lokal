<?php
/**
 * include/komponen_notifikasi.php — Komponen Floating Toast Notification
 * Ditampilkan melayang di tengah bawah (di atas footer) dengan animasi transisi.
 */
?>
<!-- Global Toast Notification (Pinned Tengah Bawah, Di Atas Footer) -->
<div
  id="global-toast"
  style="
    position: fixed;
    bottom: 84px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    opacity: 0;
    pointer-events: none;
    z-index: 999999;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    display: none;
    align-items: center;
    gap: 9px;
    padding: 9px 18px;
    border-radius: 9999px;
    background-color: #1a0a0d;
    border: 1px solid #ef233c;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
  "
>
  <i id="global-toast-icon" class="ph-bold ph-warning-circle text-base" style="color: #ef233c;"></i>
  <span id="global-toast-text">Pemberitahuan</span>
</div>
