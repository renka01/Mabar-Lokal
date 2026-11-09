<?php
/**
 * pages/cari_lawan/bagian_pengaturan_scrim.php — Panel Pengaturan Jadwal & Rules Match
 * Tempat kapten menentukan tanggal fix, jam main, format BO, server/room ID, dan password.
 */

// Default form mengikuti jadwal scrim saat ini (mis. "Besok, 19:30 WIB")
$jadwalParts = explode(',', $scrim['jadwal'], 2);
$settingDate = trim($jadwalParts[0] ?? '') ?: 'Hari ini';
$settingTime = trim($jadwalParts[1] ?? '') ?: '20:30 WIB';
?>
<div class="scrim-roster-card">

  <!-- Header Panel Sesuai Role -->
  <div class="flex items-start justify-between pb-4 border-b border-[#232d39]">
    <div>
      <h3 class="text-base font-bold text-white uppercase tracking-wider">
        <?= $isHost ? 'Pengaturan Pertandingan & Jadwal Fix' : 'Informasi Jadwal & Kredensial Room' ?>
      </h3>
      <p class="text-[12px] text-muted mt-0.5">
        <?= $isHost ? 'Sesuaikan tanggal, jam sparring, format match, dan kredensial room bersama kapten lawan.' : 'Detail waktu pelaksanaan, format pertandingan, serta akses server custom room.' ?>
      </p>
    </div>
    <span class="text-[10px] font-bold text-red bg-red/10 border border-red/20 px-2.5 py-1 rounded uppercase tracking-wider">
      <?= $isHost ? 'Panel Kapten Host' : 'Akses Roster Tim' ?>
    </span>
  </div>

  <?php if ($isHost): ?>
  <!-- Form Pengaturan Khusus Host -->
  <form id="form-scrim-settings" onsubmit="simpanPengaturanScrim(event)" class="flex flex-col gap-5">

    <!-- Row 1: Tanggal & Jam Tanding Fix -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label for="scrim-setting-date" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
          Tanggal Pertandingan
        </label>
        <input
          id="scrim-setting-date"
          type="text"
          value="<?= htmlspecialchars($settingDate) ?>"
          placeholder="Pilih tanggal tanding"
          class="scrim-modal-input"
        />
      </div>

      <div>
        <label for="scrim-setting-time" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
          Jam Main (WIB)
        </label>
        <input
          id="scrim-setting-time"
          type="text"
          value="<?= htmlspecialchars($settingTime) ?>"
          placeholder="Contoh: 20:30 WIB"
          class="scrim-modal-input"
        />
      </div>
    </div>

    <!-- Row 2: Format Tanding (Dropdown Maksimal BO5) & Target Tier -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label for="scrim-setting-format" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
          Format Pertandingan (Maksimal BO5)
        </label>
        <select
          id="scrim-setting-format"
          class="scrim-modal-input cursor-pointer"
        >
          <?php if ($isOlahraga): ?>
          <option value="2x20M" <?= $scrim['format_short'] === '2x20M' ? 'selected' : '' ?>>2 x 20 Menit (Standar Futsal)</option>
          <option value="2x25M" <?= $scrim['format_short'] === '2x25M' ? 'selected' : '' ?>>2 x 25 Menit (Futsal / Mini Soccer)</option>
          <option value="3x20M" <?= $scrim['format_short'] === '3x20M' ? 'selected' : '' ?>>3 x 20 Menit (Mini Soccer 7s)</option>
          <option value="4 QTR" <?= $scrim['format_short'] === '4 QTR' ? 'selected' : '' ?>>4 Quarter x 10 Menit (Basket)</option>
          <option value="3 SET" <?= $scrim['format_short'] === '3 SET' ? 'selected' : '' ?>>3 Set 21 Poin (Badminton / Voli)</option>
          <?php else: ?>
          <option value="BO1" <?= $scrim['format_short'] === 'BO1' ? 'selected' : '' ?>>BO1 (Best of 1 - Single Match)</option>
          <option value="BO3" <?= $scrim['format_short'] === 'BO3' || empty($scrim['format_short']) ? 'selected' : '' ?>>BO3 (Best of 3 - Standar Turnamen)</option>
          <option value="BO5" <?= $scrim['format_short'] === 'BO5' ? 'selected' : '' ?>>BO5 (Best of 5 - Babak Final)</option>
          <?php endif; ?>
        </select>
      </div>

      <div>
        <label for="scrim-setting-venue" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
          Server / Venue Pertandingan
        </label>
        <input
          id="scrim-setting-venue"
          type="text"
          value="<?= htmlspecialchars($scrim['lokasi']) ?>"
          placeholder="Contoh: Server SG / Discord atau Lapangan Viva"
          class="scrim-modal-input"
        />
      </div>
    </div>

    <!-- Row 3: Kredensial Room Tanding (Khusus Game Online) -->
    <?php if (!$isOlahraga): ?>
    <div class="p-4 rounded-xl border flex flex-col gap-3" style="background-color: #09121d; border-color: #233348;">
      <div class="flex items-center justify-between">
        <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
          <i class="ph-bold ph-key text-red"></i> Kredensial Custom Room (Aman)
        </h4>
        <span class="text-[11px] text-muted">Hanya tampil untuk roster kedua tim</span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="scrim-setting-room-id" class="block text-[11.5px] text-muted mb-1">
            Custom Room ID / Server Name
          </label>
          <input
            id="scrim-setting-room-id"
            type="text"
            value="VCT-SBY-8821"
            placeholder="ID Custom Room Game"
            class="scrim-modal-input"
          />
        </div>
        <div>
          <label for="scrim-setting-room-pass" class="block text-[11.5px] text-muted mb-1">
            Password Room
          </label>
          <input
            id="scrim-setting-room-pass"
            type="text"
            value="scrim123"
            placeholder="Password Room"
            class="scrim-modal-input"
          />
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Row 4: Catatan & Aturan Khusus -->
    <div>
      <label for="scrim-setting-notes" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
        Catatan & Kesepakatan Khusus
      </label>
      <textarea
        id="scrim-setting-notes"
        rows="3"
        class="scrim-modal-textarea"
      ><?= htmlspecialchars($scrim['catatan']) ?></textarea>
    </div>

    <!-- Bottom Actions -->
    <?php
      $simpanScrimBtnClass = $isOlahraga
        ? 'border border-[#19d66b] text-[#19d66b] bg-[#19d66b]/10 hover:bg-[#19d66b] hover:text-[#06160d] hover:shadow-[0_0_16px_rgba(25,214,107,0.35)]'
        : 'border border-red text-red bg-red/10 hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)]';
    ?>
    <div class="flex items-center justify-between pt-4 border-t border-border/50">
      <button
        type="button"
        onclick="batalTantanganHost()"
        class="inline-flex items-center justify-center h-10 px-5 rounded-md border border-red/60 text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] active:scale-95 cursor-pointer"
      >
        Batalkan Tantangan Scrim
      </button>
      <div class="flex items-center gap-3">
        <button
          type="button"
          onclick="gantiTabScrim('info')"
          class="inline-flex items-center justify-center h-10 px-6 rounded-md border border-border bg-card-alt text-muted text-[13px] font-semibold transition-all hover:text-white hover:border-muted active:scale-95 cursor-pointer"
        >
          Kembali ke Lobby
        </button>
        <button
          type="submit"
          class="inline-flex items-center justify-center h-10 px-6 rounded-md text-[13px] font-bold tracking-wide transition-all active:scale-95 cursor-pointer <?= $simpanScrimBtnClass ?>"
        >
          Simpan Pengaturan Match
        </button>
      </div>
    </div>

  </form>

  <?php else: ?>
  <!-- Tampilan Informasi Jadwal & Server untuk Challenger / Visitor (Read-Only) -->
  <div class="flex flex-col gap-4 mt-2">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
      <div class="p-4 rounded-lg bg-card-alt border border-border">
        <span class="text-muted block text-[11px] mb-1">Tanggal & Waktu Tanding</span>
        <span class="text-white font-bold text-sm" id="challenger-jadwal-view"><?= htmlspecialchars($scrim['jadwal']) ?></span>
      </div>
      <div class="p-4 rounded-lg bg-card-alt border border-border">
        <span class="text-muted block text-[11px] mb-1">Format Pertandingan</span>
        <span class="text-white font-bold text-sm"><?= htmlspecialchars($scrim['format']) ?></span>
      </div>
      <div class="p-4 rounded-lg bg-card-alt border border-border sm:col-span-2">
        <span class="text-muted block text-[11px] mb-1">Lokasi / Venue / Server</span>
        <span class="text-white font-bold text-sm"><?= htmlspecialchars($scrim['lokasi']) ?></span>
      </div>
    </div>

    <?php if (!$isOlahraga): ?>
    <div class="p-4 rounded-xl border flex flex-col gap-3" style="background-color: #09121d; border-color: #233348;">
      <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
        <i class="ph-bold ph-key text-red"></i> Kredensial Custom Room (Disediakan Host)
      </h4>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
        <div>
          <span class="text-muted block text-[11px] mb-0.5">Room ID / Server:</span>
          <span class="text-white font-mono font-bold text-sm">VCT-SBY-8821</span>
        </div>
        <div>
          <span class="text-muted block text-[11px] mb-0.5">Password Room:</span>
          <span class="text-white font-mono font-bold text-sm">scrim123</span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="p-3.5 rounded-lg bg-card-alt border border-border text-xs text-muted flex items-start gap-2">
      <i class="ph-bold ph-info text-amber-400 text-sm shrink-0 mt-0.5"></i>
      <span>Pengaturan waktu dan kredensial server dikelola oleh kapten pembuat tantangan. Hubungi kapten tim melalui chat ruang match untuk koordinasi tambahan.</span>
    </div>

    <div class="flex items-center justify-end pt-3 border-t border-border/50">
      <button
        type="button"
        onclick="gantiTabScrim('info')"
        class="inline-flex items-center justify-center h-10 px-6 rounded-md border border-border bg-card-alt text-muted text-[13px] font-semibold transition-all hover:text-white hover:border-muted active:scale-95 cursor-pointer"
      >
        Kembali ke Lobby Match
      </button>
    </div>
  </div>
  <?php endif; ?>

</div>

