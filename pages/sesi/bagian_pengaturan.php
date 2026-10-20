<?php
/**
 * pages/sesi/bagian_pengaturan.php — Komponen Tab Detail Sesi
 * Berisi tampilan detail sesi yang adaptif sesuai peran pengguna:
 * - Host (Pembuat): Form interaktif untuk mengedit nama, deskripsi, aturan, dan parameter sesi.
 * - Visitor / Member: Tampilan pratinjau informasi lengkap, spesifikasi game, aturan, dan tombol Gabung.
 */
?>
<!-- TAB CONTENT: PENGATURAN SESI (KHUSUS HOST) -->
<div id="tab-content-detail" style="display: <?= $initialTab === 'detail' ? 'flex' : 'none' ?>; flex-direction: column; gap: 20px;">

  <!-- ════════════════════════════════════════════════════════════
       FORM EDIT PENGATURAN SESI
  ════════════════════════════════════════════════════════════ -->

  <!-- SECTION 1: INFORMASI DASAR SESI & ATURAN SESI -->
  <div class="rounded-xl border p-5 flex flex-col gap-4 shadow-md" style="background-color: #0d1622; border-color: #232d39;">
    <div class="flex items-center justify-between border-b pb-3" style="border-color: #1e2a38;">
      <div class="flex items-center gap-2">
        <i class="ph-bold ph-note-pencil text-base" style="color: <?= $themeColor ?>;"></i>
        <h3 class="text-[13px] font-bold text-white uppercase tracking-wider">Informasi Sesi & Aturan</h3>
      </div>
      <span class="text-[11px] font-medium px-2 py-0.5 rounded bg-card-alt border border-border text-muted">
        Mode Pengeditan Host
      </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
      <!-- Kolom Kiri: Nama & Deskripsi Sesi -->
      <div class="flex flex-col gap-4">
        <!-- Nama Sesi (Wajib) -->
        <div>
          <label for="detail-nama-input" class="text-[11px] font-semibold block mb-1.5" style="color: #9ba5b4;">
            Nama Sesi <span style="color: #ef233c;">*</span>
          </label>
          <input
            id="detail-nama-input"
            type="text"
            value="<?= htmlspecialchars($sesi['nama']) ?>"
            maxlength="50"
            class="h-11 w-full rounded-md border px-3 text-[13px] text-white outline-none transition-colors"
            style="background-color: #111b28; border-color: #253142;"
            placeholder="Masukkan nama sesi..."
          />
          <span class="text-[10px] mt-1.5 block" style="color: #9ba5b4;">Maksimal 50 Karakter</span>
        </div>

        <!-- Deskripsi Sesi (Opsional) -->
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="detail-desc-input" class="text-[11px] font-semibold" style="color: #9ba5b4;">
              Deskripsi Sesi <span class="text-[10px] font-normal text-muted">(Opsional)</span>
            </label>
            <span class="text-[10px]" style="color: #9ba5b4;">Boleh dikosongkan</span>
          </div>
          <textarea
            id="detail-desc-input"
            rows="3"
            maxlength="120"
            class="w-full rounded-md border p-3 text-[13px] text-white outline-none transition-colors resize-none leading-relaxed"
            style="background-color: #111b28; border-color: #253142;"
            placeholder="Jelaskan kebutuhan tim, target, atau playstyle..."
          ><?= htmlspecialchars($sesi['deskripsi']) ?></textarea>
          <span class="text-[10px] mt-1 block" style="color: #9ba5b4;">Maksimal 120 Karakter</span>
        </div>
      </div>

      <!-- Kolom Kanan: Aturan Sesi (Custom input) -->
      <div class="flex flex-col">
        <div class="flex items-center justify-between mb-1.5">
          <label for="detail-aturan-input" class="text-[11px] font-semibold flex items-center gap-1.5" style="color: #9ba5b4;">
            <i class="ph ph-shield-check text-xs" style="color: <?= $themeColor ?>;"></i> Aturan Sesi <span class="text-[10px] font-normal text-muted">(Opsional)</span>
          </label>
          <span class="text-[10px]" style="color: #9ba5b4;">1 aturan per baris</span>
        </div>
        <textarea
          id="detail-aturan-input"
          rows="6"
          class="w-full rounded-md border p-3 text-[13px] text-white outline-none transition-colors resize-none leading-relaxed flex-1"
          style="background-color: #111b28; border-color: #253142; min-height: 140px;"
          placeholder="Tulis aturan khusus sesi mabar kamu di sini (pisahkan per baris)...&#10;Contoh:&#10;No Toxic & No Blaming&#10;AFK / Cancel Sepihak Kick Langsung&#10;Wajib Konfirmasi Kehadiran"
        ><?= htmlspecialchars(implode("\n", $sesi['aturan_sesi'])) ?></textarea>
        <span class="text-[10px] mt-1.5 block" style="color: #9ba5b4;">
          Aturan ini akan tampil di kartu 'Aturan Sesi' di lobby. Kosongkan jika tidak ada aturan khusus.
        </span>
      </div>
    </div>
  </div>

  <!-- SECTION 2: PENGATURAN SESI & PARAMETER DINAMIS GAME/OLAHRAGA -->
  <div class="rounded-xl border p-5 flex flex-col gap-4 shadow-md" style="background-color: #0d1622; border-color: #232d39;">
    <div class="flex items-center gap-2 border-b pb-3" style="border-color: #1e2a38;">
      <i class="ph-bold ph-sliders-horizontal text-base" style="color: <?= $themeColor ?>;"></i>
      <h3 class="text-[13px] font-bold text-white uppercase tracking-wider">
        Pengaturan Sesi & <?= htmlspecialchars($sesi['game']) ?>
      </h3>
    </div>
    
    <!-- Baris 1: Pengaturan Umum Sesi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div>
        <label for="detail-join-input" class="text-[11px] font-semibold flex items-center gap-1.5 mb-1.5" style="color: #9ba5b4;">
          <i class="ph ph-door-open text-xs" style="color: <?= $themeColor ?>;"></i> Izin Bergabung
        </label>
        <select
          id="detail-join-input"
          class="h-11 w-full rounded-md border text-[13px] text-white outline-none cursor-pointer"
          style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
        >
          <option value="Ask For Join" <?= $sesi['join_rule'] === 'Ask For Join' ? 'selected' : '' ?>>Ask For Join (Konfirmasi Host)</option>
          <option value="Langsung Gabung" <?= $sesi['join_rule'] === 'Langsung Gabung' ? 'selected' : '' ?>>Langsung Gabung (Open)</option>
        </select>
      </div>

      <div>
        <label for="detail-jam-mulai-input" class="text-[11px] font-semibold flex items-center justify-between mb-1.5" style="color: #9ba5b4;">
          <span class="flex items-center gap-1.5"><i class="ph ph-clock text-xs" style="color: <?= $themeColor ?>;"></i> Jam Mulai</span>
          <span class="text-[10px] text-muted">Opsional</span>
        </label>
        <input
          id="detail-jam-mulai-input"
          type="text"
          value="<?= htmlspecialchars($sesi['jam_mulai']) ?>"
          class="h-11 w-full rounded-md border text-[13px] text-white outline-none transition-colors"
          style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
          placeholder="Contoh: 15:00"
        />
      </div>

      <div>
        <label for="detail-jam-selesai-input" class="text-[11px] font-semibold flex items-center justify-between mb-1.5" style="color: #9ba5b4;">
          <span class="flex items-center gap-1.5"><i class="ph ph-clock text-xs" style="color: <?= $themeColor ?>;"></i> Jam Selesai</span>
          <span class="text-[10px] text-muted">Opsional</span>
        </label>
        <input
          id="detail-jam-selesai-input"
          type="text"
          value="<?= htmlspecialchars($sesi['jam_selesai']) ?>"
          class="h-11 w-full rounded-md border text-[13px] text-white outline-none transition-colors"
          style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
          placeholder="Contoh: 20:00"
        />
      </div>

      <div>
        <label for="detail-leader-input" class="text-[11px] font-semibold flex items-center gap-1.5 mb-1.5" style="color: #9ba5b4;">
          <i class="ph ph-user text-xs" style="color: <?= $themeColor ?>;"></i> Host
        </label>
        <input
          id="detail-leader-input"
          type="text"
          value="<?= htmlspecialchars($sesi['leader']) ?>"
          class="h-11 w-full rounded-md border text-[13px] text-white outline-none"
          style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
          placeholder="Nama Host"
        />
      </div>
    </div>

    <!-- Baris 2: Parameter Dinamis Game / Olahraga -->
    <div class="pt-3 border-t" style="border-color: #1e2a38;">
      <h4 class="text-[11px] font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-2">
        <i class="ph <?= $isOlahraga ? 'ph-basketball' : 'ph-game-controller' ?> text-xs" style="color: <?= $themeColor ?>;"></i>
        Parameter Khusus <?= htmlspecialchars($sesi['game']) ?>
      </h4>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($currentGameFields as $f): 
          $fVal = $_GET[$f['name']] ?? $mockFieldValues[$f['name']] ?? ($f['options'][0] ?? '');
          $fIcon = $f['icon'] ?? 'ph-circle';
        ?>
          <div>
            <label for="detail-spec-<?= htmlspecialchars($f['name']) ?>" class="text-[11px] font-semibold flex items-center justify-between mb-1.5" style="color: #9ba5b4;">
              <span class="flex items-center gap-1.5 truncate">
                <i class="ph <?= htmlspecialchars($fIcon) ?> text-xs" style="color: <?= $themeColor ?>;"></i> <?= htmlspecialchars($f['label']) ?>
              </span>
              <?php if ($f['type'] !== 'number'): ?>
              <span class="text-[10px] text-muted font-normal">Opsional</span>
              <?php endif; ?>
            </label>

            <?php if ($f['type'] === 'select'): ?>
              <select
                id="detail-spec-<?= htmlspecialchars($f['name']) ?>"
                class="h-11 w-full rounded-md border text-[13px] text-white outline-none cursor-pointer"
                style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
              >
                <option value="" <?= (empty($fVal) || $fVal === 'Bebas' || $fVal === '-') ? 'selected' : '' ?>>-- Bebas / Kosongkan --</option>
                <?php foreach ($f['options'] as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>" <?= (string)$fVal === (string)$opt ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                <?php endforeach; ?>
              </select>

            <?php elseif ($f['type'] === 'number'): 
              $fMin = $f['min'] ?? 2;
              $fMax = $f['max'] ?? 20;
            ?>
              <input
                id="detail-spec-<?= htmlspecialchars($f['name']) ?>"
                type="number"
                min="<?= $fMin ?>"
                max="<?= $fMax ?>"
                value="<?= htmlspecialchars($fVal) ?>"
                placeholder="<?= htmlspecialchars($f['placeholder'] ?? ($fMin . ' – ' . $fMax)) ?>"
                class="h-11 w-full rounded-md border text-[13px] text-white outline-none transition-colors"
                style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
                oninput="validateSlotRange(this)"
              />
              <span class="text-[10px] mt-1 block" style="color: #9ba5b4;">
                Rentang: <?= $fMin ?> – <?= $fMax ?> slot
              </span>

            <?php else: ?>
              <input
                id="detail-spec-<?= htmlspecialchars($f['name']) ?>"
                type="text"
                value="<?= htmlspecialchars($fVal) ?>"
                placeholder="<?= htmlspecialchars($f['placeholder'] ?? 'Boleh dikosongkan') ?>"
                class="h-11 w-full rounded-md border text-[13px] text-white outline-none transition-colors"
                style="background-color: #111b28; border-color: #253142; padding: 0 14px;"
              />
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ACTION BAR HOST: Hapus Sesi, Batal & Simpan Perubahan -->
  <div class="flex items-center justify-between pt-1">
    <button
      id="btn-hapus-sesi"
      type="button"
      class="inline-flex items-center justify-center rounded-md border bg-transparent px-5 py-2.5 text-[13px] font-semibold transition-all hover:bg-red/10 active:scale-[0.98]"
      style="color: #ef233c; border-color: #ef233c; cursor: pointer;"
    >
      Hapus Sesi
    </button>

    <div class="flex items-center gap-3">
      <button
        id="btn-batal-detail"
        type="button"
        class="rounded-md border px-6 py-2.5 text-[13px] font-semibold transition-colors active:scale-[0.98]"
        style="background-color: #111b28; border-color: #253142; color: #f4f5f7; cursor: pointer;"
      >
        Kembali ke Lobby
      </button>
      <button
        id="btn-simpan-detail"
        type="button"
        class="rounded-md px-6 py-2.5 text-[13px] font-bold transition-all hover:opacity-90 active:scale-[0.98] shadow-md"
        style="background-color: <?= $themeColor ?>; color: <?= $themeTextOnBg ?>; border: none; cursor: pointer;"
      >
        Simpan Perubahan
      </button>
    </div>
  </div>

</div>

