    <!-- Footer -->
    <footer class="mt-auto border-t border-border bg-[#080f18] shrink-0">
      <!-- Kontak Instagram tim -->
      <?php require_once __DIR__ . '/kontak_tim.php'; ?>
      <div class="mx-auto flex max-w-[1600px] flex-wrap items-center gap-4 px-4 py-2 lg:px-8">
        <div class="p-2">
          <img src="<?= $basePath ?>asset/icon/LogoFull.svg" alt="Mabar Lokal" style="height: 30px" />
        </div>
        <span class="h-1 w-1 rounded-full bg-muted"></span>
        <p class="text-[12px] text-muted">&copy; <?= date('Y') ?> Mabar Lokal</p>
        <nav class="ml-auto flex flex-wrap items-center gap-4" aria-label="Tautan footer">
          <a href="<?= $basePath ?>pages/syarat/index.php" class="text-[12px] font-medium text-muted transition-colors hover:text-white">Syarat &amp; Ketentuan</a>
          <a href="<?= $basePath ?>pages/about/index.php" class="text-[12px] font-medium text-muted transition-colors hover:text-white">About Us</a>
          <a href="<?= $basePath ?>pages/about/index.php" class="text-[12px] font-medium text-muted transition-colors hover:text-white">Contact Us</a>
          <a href="<?= $basePath ?>pages/faq/index.php" class="text-[12px] font-medium text-muted transition-colors hover:text-white">FAQ</a>
        </nav>
        <!-- <div class="mx-auto max-w-[1600px] px-4 py-3 lg:px-8">
          <div class="flex flex-wrap items-center gap-4">
            <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-muted">Contact</span>
            <?php foreach ($kontakTim as $orang): ?>
              <a
                href="https://instagram.com/<?= htmlspecialchars($orang['handle'], ENT_QUOTES, 'UTF-8') ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center gap-2 text-[11px] font-medium text-muted transition-colors hover:text-white">
                <i class="ph-bold ph-instagram-logo text-base text-red"></i>
                <span>@<?= htmlspecialchars($orang['handle'], ENT_QUOTES, 'UTF-8') ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div> -->
      </div>
    </footer>

    <!-- Komponen Notifikasi Toast Global -->
    <?php require_once __DIR__ . '/komponen_notifikasi.php'; ?>

    <!-- Komponen Modal Konfirmasi Global (Pengganti confirm() Native) -->
    <?php require_once __DIR__ . '/komponen_konfirmasi.php'; ?>

    <!-- Skrip Modular Aplikasi -->
    <script src="<?= $basePath ?>asset/js/notifikasi.js"></script>
    <script src="<?= $basePath ?>asset/js/konfirmasi.js?v=<?= filemtime(__DIR__ . '/../asset/js/konfirmasi.js') ?>"></script>
    <script src="<?= $basePath ?>asset/js/dropdown_kategori.js"></script>
    <script src="<?= $basePath ?>asset/js/sidebar_mabar.js"></script>
    <script src="<?= $basePath ?>asset/js/app.js"></script>
    <script src="<?= $basePath ?>asset/js/kartu_sesi.js"></script>
  </body>
</html>
