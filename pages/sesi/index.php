<?php
$pageTitle  = 'Detail Sesi';
$basePath   = '../../';
$activePage = 'cari_mabar';
require_once __DIR__ . '/../../include/header.php';
?>

<!-- App Body (3-column layout matching home & design) -->
<div class="flex flex-1 overflow-hidden w-full max-w-[1600px] mx-auto px-4 lg:px-8 gap-6">
  <?php require __DIR__ . '/../../pages/cari_mabar/sidebar.php'; ?>

  <?php require __DIR__ . '/content.php'; ?>

  <?php require __DIR__ . '/../../pages/chat.php'; ?>
</div>

<?php require __DIR__ . '/../../include/footer.php'; ?>

