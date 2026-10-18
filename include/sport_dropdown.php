<?php
$basePath = $basePath ?? '';

$dropdownId = $dropdownId ?? ('sport-dropdown-' . uniqid());
$_dropdownId = $dropdownId;
unset($dropdownId);

$gamesList = [
    [
        'name' => 'Futsal',
        'icon' => $basePath . 'asset/icon/Futsal.png',
    ],
    [
        'name' => 'Running',
        'icon' => $basePath . 'asset/icon/Lari.png',
    ],
    [
        'name' => 'Billiard',
        'icon' => $basePath . 'asset/icon/Billiard.png',
    ],
    [
        'name' => 'Bulu Tangkis',
        'icon' => $basePath . 'asset/icon/Bulu Tangkis.png',
    ],
    [
        'name' => 'Padel',
        'icon' => $basePath . 'asset/icon/padel.png',
    ],
    [
        'name' => 'Basket Ball',
        'icon' => $basePath . 'asset/icon/Basket.png',
    ],
];
?>

<!-- HTML Dropdown Game Reusable -->
<div class="relative z-30 flex flex-col gap-3 mt-6">
  <h2 class="text-[11px] font-bold tracking-[0.15em] text-white uppercase">
    Olahraga
  </h2>

  <div class="relative dropdown" data-dropdown-id="<?= $_dropdownId; ?>">
    <!-- Tombol Utama Dropdown (default: placeholder) -->
    <button
      type="button"
      class="dropdown-toggle flex h-11 w-full items-center rounded-md border border-border bg-card-alt px-3 text-[13px] font-medium transition-colors hover:border-muted"
      aria-haspopup="listbox"
      aria-expanded="false"
    >
      <!-- Icon placeholder, disembunyikan saat belum dipilih -->
      <div class="dropdown-icon-wrapper flex h-6 w-6 items-center justify-center overflow-hidden mr-3 shrink-0 hidden">
        <img
          class="dropdown-current-icon h-6 w-6 object-cover rounded"
          src=""
          alt=""
          style="min-width:24px;min-height:24px;max-width:24px;max-height:24px;"
        />
      </div>
      <!-- Icon default: basketball (tampil sebelum ada pilihan) -->
      <i class="dropdown-placeholder-icon ph-fill ph-basketball mr-3 text-lg text-muted"></i>

      <span class="dropdown-value text-muted">Pilih Olahraga...</span>
      <i class="ph ph-caret-down ml-auto text-muted"></i>
    </button>

    <!-- Menu Pilihan (di-looping otomatis dari PHP) -->
    <div
      class="dropdown-menu absolute left-0 right-0 z-50 mt-1 hidden max-h-60 overflow-y-auto rounded-md border border-border bg-card-alt py-1 shadow-lg hide-scrollbar"
      role="listbox"
    >
      <?php foreach ($gamesList as $index => $game): ?>
        <button
          type="button"
          class="dropdown-option flex w-full items-center gap-3 px-3 py-2 text-left text-[13px] transition-colors hover:bg-border text-muted hover:text-white"
          role="option"
          data-icon="<?= $game['icon']; ?>"
          data-name="<?= $game['name']; ?>"
        >
          <div class="shrink-0 overflow-hidden" style="width:20px;height:20px;min-width:20px;">
            <img
              src="<?= $game['icon']; ?>"
              alt=""
              style="width:20px;height:20px;object-fit:cover;border-radius:4px;"
            />
          </div>
          <span><?= $game['name']; ?></span>
        </button>
      <?php endforeach; ?>

      <!-- Opsi: Game Lainnya -->
      <button
        type="button"
        class="dropdown-option dropdown-other-trigger flex w-full items-center gap-3 px-3 py-2 text-left text-[13px] transition-colors hover:bg-border text-muted hover:text-white"
        role="option"
        data-name="__other__"
      >
        <div class="shrink-0 overflow-hidden flex items-center justify-center" style="width:20px;height:20px;min-width:20px;">
          <i class="ph ph-pencil-simple" style="font-size:14px;"></i>
        </div>
        <span>Game lainnya (ketik sendiri)...</span>
      </button>
    </div>
  </div>

  <!-- Input custom untuk "game lainnya", tersembunyi secara default -->
  <input
    type="text"
    class="dropdown-other-input hidden h-11 w-full rounded-md border border-border bg-card-alt px-3 text-[13px] text-white outline-none transition-colors focus:border-red placeholder:text-muted"
    placeholder="Ketik nama game / olahraga..."
    data-for="<?= $_dropdownId; ?>"
  />
</div>
