    <!-- Footer -->
    <footer class="mt-auto border-t border-border bg-[#080f18] shrink-0">
      <div class="mx-auto flex h-16 max-w-[1600px] items-center gap-4 px-4 lg:px-8">
        <div class="p-2">
          <img src="<?= $basePath ?>asset/icon/LogoFull.svg" alt="" style="height: 30px" />
        </div>
        <span class="h-1 w-1 rounded-full bg-muted"></span>
      </div>
    </footer>

    <!-- Komponen Notifikasi Toast Global -->
    <?php require_once __DIR__ . '/komponen_notifikasi.php'; ?>

    <!-- Skrip Modular Aplikasi -->
    <script src="<?= $basePath ?>asset/js/notifikasi.js"></script>
    <script src="<?= $basePath ?>asset/js/dropdown_kategori.js"></script>
    <script src="<?= $basePath ?>asset/js/sidebar_mabar.js"></script>
    <script src="<?= $basePath ?>asset/js/app.js"></script>
    <script src="<?= $basePath ?>asset/js/kartu_sesi.js"></script>
  </body>
</html>
