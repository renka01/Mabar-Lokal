<?php
$pageTitle = 'Cari Sesi';
$basePath = '';
$activePage = 'home';
require_once __DIR__ . '/include/header.php';
?>

<!-- App Body (Flex container to fill remaining height) -->
<div class="flex flex-1 overflow-hidden w-full max-w-[1600px] mx-auto px-4 lg:px-8 gap-6">
  <?php require __DIR__ . '/pages/home/sidebar.php'; ?>

  <?php require __DIR__ . '/pages/home/content.php'; ?>

  <?php require __DIR__ . '/pages/chat.php'; ?>
</div>

<?php require __DIR__ . '/include/footer.php'; ?>