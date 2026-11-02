<!-- Modal Tantang Tim -->
<div id="modal-tantang-scrim" class="scrim-modal-overlay hidden">
  <div class="absolute inset-0 cursor-pointer" onclick="tutupModalTantang()"></div>

  <div class="scrim-modal-container z-10 flex flex-col p-7 max-w-[480px]">
    <div class="flex items-start justify-between mb-5">
      <div>
        <h3 class="text-[19px] font-bold text-white tracking-tight leading-snug">
          Tantang Tim Sparring
        </h3>
        <p class="text-[12.5px] text-muted mt-1 leading-normal">
          Kirim ajakan tanding ke <span id="modal-target-team-highlight" class="text-white font-semibold">Nama Tim</span>
        </p>
      </div>

      <button
        type="button"
        onclick="tutupModalTantang()"
        class="text-muted hover:text-white transition-colors p-1 -mr-1 -mt-1 cursor-pointer text-xl leading-none"
        title="Tutup dialog"
      >
        <i class="ph ph-x"></i>
      </button>
    </div>

    <div class="flex flex-wrap items-center gap-2 p-3 rounded-lg mb-5 text-[11.5px]" style="background-color: #0a121c; border: 1px solid #233348;">
      <span id="modal-target-badge" class="font-bold text-red uppercase tracking-wider text-[10px] px-2 py-0.5 rounded bg-red/10 border border-red/20">
        Esports
      </span>
      <span id="modal-target-game" class="font-semibold text-white">
        Valorant
      </span>
      <span class="text-muted">•</span>
      <span id="modal-target-meta" class="text-muted">
        Ascendant – Immortal
      </span>
      <span class="text-muted">•</span>
      <span id="modal-target-format" class="font-bold text-white/90">
        BO3
      </span>
    </div>

    <form id="form-tantang-scrim" onsubmit="kirimTantanganScrim(event)" class="flex flex-col gap-4">
      <input type="hidden" id="modal-target-scrim-id" value="" />

      <div>
        <label for="input-challenger-team" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
          Nama Tim Kamu
        </label>
        <input
          id="input-challenger-team"
          type="text"
          required
          placeholder="cth. Surabaya Wolves Alpha"
          class="scrim-modal-input"
        />
      </div>

      <div>
        <label for="input-challenger-contact" class="block text-[12.5px] font-semibold text-white/90 mb-1.5">
          Kontak Kapten (WhatsApp / Discord)
        </label>
        <input
          id="input-challenger-contact"
          type="text"
          required
          placeholder="cth. 0812-3456-7890 atau username#1234"
          class="scrim-modal-input"
        />
      </div>

      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label for="input-challenger-message" class="text-[12.5px] font-semibold text-white/90">
            Catatan untuk Lawan
          </label>
          <span class="text-[11px] text-muted font-normal">Opsional</span>
        </div>
        <textarea
          id="input-challenger-message"
          rows="3"
          placeholder="Tuliskan usulan jam main atau ketersediaan roster tim kamu..."
          class="scrim-modal-textarea"
        ></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 mt-3 pt-2">
        <button
          type="button"
          onclick="tutupModalTantang()"
          class="inline-flex items-center justify-center h-10 px-5 rounded-md border border-border bg-card-alt text-muted text-[13px] font-semibold transition-all hover:text-white hover:border-muted active:scale-95 cursor-pointer"
        >
          Batal
        </button>
        <button
          type="submit"
          class="inline-flex items-center justify-center h-10 px-6 rounded-md border border-red text-red bg-red/10 text-[13px] font-bold tracking-wide transition-all hover:bg-red hover:text-white hover:shadow-[0_0_16px_rgba(239,35,60,0.35)] active:scale-95 cursor-pointer"
        >
          Kirim Tantangan
        </button>
      </div>
    </form>

  </div>
</div>
