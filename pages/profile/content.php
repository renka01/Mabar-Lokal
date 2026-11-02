<!-- MAIN CONTENT: PROFIL -->
<main class="flex-1 overflow-y-auto hide-scrollbar py-6 flex flex-col gap-5">

  <!-- 1. Banner & Profil Card (Layout Sesuai Mockup) -->
  <div class="relative w-full rounded-xl border border-border bg-card overflow-hidden shadow-lg" style="height: 215px; min-height: 215px; max-height: 215px;">
    <!-- Default Banner Graphic when no custom banner is uploaded -->
    <div id="profile-banner-default-bg" class="absolute inset-0 pointer-events-none flex items-center justify-end pr-8 overflow-hidden select-none" style="background: radial-gradient(circle at 80% 20%, rgba(239, 35, 60, 0.16) 0%, rgba(13, 22, 34, 0.95) 60%), linear-gradient(135deg, #09111c 0%, #0d1622 50%, #152232 100%);">
      <i class="ph-fill ph-game-controller text-[220px] text-white/[0.04] -mr-10"></i>
    </div>

    <img
      id="profile-banner-img"
      src=""
      alt="Profile Banner"
      style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;"
      class="transition-all duration-300 hidden"
    />
    <!-- Vignette Gradient Overlay agar teks & avatar di atas banner selalu kontras dan jelas -->
    <div
      class="absolute inset-0 pointer-events-none"
      style="background: linear-gradient(to right, rgba(8,15,24,0.92) 0%, rgba(8,15,24,0.6) 45%, rgba(0,0,0,0.15) 100%);"
    ></div>

    <!-- Ubah Banner Button (Top Right) -->
    <div style="position: absolute; top: 16px; right: 16px; z-index: 30;">
      <label
        for="banner-upload"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-border bg-[#080f18]/85 hover:bg-[#080f18] text-white text-xs font-semibold backdrop-blur transition-all shadow-md hover:border-red cursor-pointer"
        title="Upload banner foto dari file kamu"
      >
        <i class="ph ph-camera text-red text-sm"></i>
        <span>Ubah Banner</span>
        <input type="file" id="banner-upload" class="hidden" accept="image/*" />
      </label>
    </div>

    <!-- User Avatar & Identity (Bottom Left di atas banner) -->
    <div
      style="position: absolute; bottom: 20px; left: 24px; display: flex; align-items: center; gap: 18px; z-index: 20;"
    >
      <!-- Avatar Wrapper -->
      <div class="relative shrink-0">
        <div id="profile-avatar-circle" class="profile-avatar-circle" style="width: 94px; height: 94px; min-width: 94px; min-height: 94px; border-radius: 50% !important; background-color: #dce3ed; border: 3px solid #0d1622 !important; overflow: hidden !important; display: flex !important; align-items: center !important; justify-content: center !important; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6) !important;">
          <img id="profile-avatar-img" src="" alt="Avatar" class="h-full w-full object-cover hidden" />
          <i id="profile-avatar-icon" class="ph-fill ph-user text-5xl text-[#64748b]"></i>
        </div>
        <!-- Camera badge button for avatar -->
        <label
          for="avatar-upload"
          class="profile-avatar-badge"
          style="position: absolute !important; bottom: 0 !important; right: 0 !important; width: 28px !important; height: 28px !important; border-radius: 50% !important; background-color: #182332 !important; border: 2px solid #0d1622 !important; display: flex !important; align-items: center !important; justify-content: center !important; color: #fff !important; cursor: pointer !important; z-index: 25 !important;"
          title="Upload foto profil dari file kamu"
        >
          <i class="ph ph-camera text-xs"></i>
          <input type="file" id="avatar-upload" class="hidden" accept="image/*" />
        </label>
      </div>

      <!-- Name & Location Info -->
      <div>
        <h1 id="profile-display-name" class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading leading-tight drop-shadow-md">
          Wawan
        </h1>
        <div class="flex items-center gap-1.5 text-xs text-muted font-medium mt-1">
          <i class="ph-fill ph-map-pin text-red text-xs"></i>
          <span id="profile-display-location">Malang</span>
        </div>
      </div>
    </div>
  </div>

  <form id="profile-form" class="flex flex-col gap-5" onsubmit="event.preventDefault(); handleSaveProfile();">
    <!-- 2. Edit Profil Card (Nama, Email, Lokasi, No HP) -->
    <div class="rounded-xl border border-border bg-card p-5 sm:p-6 flex flex-col gap-4 shadow-sm">
      <h2 class="text-[14px] font-bold text-white tracking-wide font-heading">Edit Profil</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Field: Nama -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-medium text-muted">Nama</label>
          <div class="relative flex items-center">
            <input
              type="text"
              id="input-nama"
              name="nama"
              value="Wawan"
              class="w-full h-11 rounded-md border border-border bg-card-alt px-3.5 pr-10 text-[13px] text-white outline-none transition-colors focus:border-muted placeholder:text-muted"
            />
            <button
              type="button"
              onclick="document.getElementById('input-nama').focus();"
              class="absolute right-3 text-muted hover:text-white transition-colors cursor-pointer"
              title="Edit Nama"
            >
              <i class="ph ph-pencil-simple text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Field: Email -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-medium text-muted">Email</label>
          <div class="relative flex items-center">
            <input
              type="email"
              id="input-email"
              name="email"
              value="wawanwrwn@gmail.com"
              class="w-full h-11 rounded-md border border-border bg-card-alt px-3.5 pr-10 text-[13px] text-white outline-none transition-colors focus:border-muted placeholder:text-muted"
            />
            <button
              type="button"
              onclick="document.getElementById('input-email').focus();"
              class="absolute right-3 text-muted hover:text-white transition-colors cursor-pointer"
              title="Edit Email"
            >
              <i class="ph ph-pencil-simple text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Field: Lokasi -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-medium text-muted">Lokasi</label>
          <div class="relative flex items-center">
            <input
              type="text"
              id="input-lokasi"
              name="lokasi"
              value="Malang"
              class="w-full h-11 rounded-md border border-border bg-card-alt px-3.5 pr-10 text-[13px] text-white outline-none transition-colors focus:border-muted placeholder:text-muted"
            />
            <button
              type="button"
              onclick="document.getElementById('input-lokasi').focus();"
              class="absolute right-3 text-muted hover:text-white transition-colors cursor-pointer"
              title="Edit Lokasi"
            >
              <i class="ph ph-pencil-simple text-sm"></i>
            </button>
          </div>
          <p class="text-[10.5px] text-red/80 font-medium tracking-wide mt-0.5">*Awas No HP Dipake Judol Developer</p>
        </div>

        <!-- Field: No HP -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-medium text-muted">No HP</label>
          <div class="relative flex items-center">
            <input
              type="text"
              id="input-nohp"
              name="no_hp"
              value="0823****55"
              class="w-full h-11 rounded-md border border-border bg-card-alt px-3.5 pr-10 text-[13px] text-white outline-none transition-colors focus:border-muted placeholder:text-muted"
            />
            <button
              type="button"
              onclick="document.getElementById('input-nohp').focus();"
              class="absolute right-3 text-muted hover:text-white transition-colors cursor-pointer"
              title="Edit No HP"
            >
              <i class="ph ph-pencil-simple text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Deskripsi Profil Card (Sesuai Mockup & Icon Minat Kuesioner) -->
    <div class="rounded-xl border border-border bg-card p-5 sm:p-6 flex flex-col gap-4 shadow-sm">
      <h2 class="text-[14px] font-bold text-white tracking-wide font-heading">Deskripsi profil</h2>

      <div class="flex flex-col gap-1.5">
        <label class="text-[11px] font-medium text-muted">Deskripsi</label>
        <div class="relative flex items-center">
          <textarea
            id="input-bio"
            name="bio"
            rows="4"
            placeholder="Tuliskan deskripsi profil atau gaya bermain kamu..."
            class="w-full rounded-md border border-border bg-card-alt p-3.5 text-[13px] text-white outline-none transition-colors focus:border-muted resize-none placeholder:text-muted"
          ></textarea>
        </div>
      </div>

      <!-- Icon-icon Minat Sesuai Hasil Kuesioner -->
      <div class="flex flex-col gap-2.5 pt-3 border-t border-border/50">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-semibold text-muted uppercase tracking-wider flex items-center gap-1.5">
            <i class="ph-fill ph-game-controller text-red text-sm"></i>
            <span>Minat Game & Olahraga</span>
          </span>
          <a
            href="<?= $basePath ?>pages/kuesioner.php"
            class="inline-flex items-center gap-1 text-[11px] font-semibold text-red hover:underline cursor-pointer"
            title="Ubah pilihan game dan olahraga kamu di kuesioner"
          >
            <i class="ph-bold ph-sliders-horizontal"></i>
            <span>Ubah di Kuesioner</span>
          </a>
        </div>

        <!-- Dynamic Interest Badges -->
        <div id="profile-interest-badges" class="flex flex-wrap gap-2.5 pt-1.5" style="margin-top: 4px;">
          <!-- Loaded dynamically by halaman_profil.js based on mabar_pref_games -->
        </div>
      </div>
    </div>

    <!-- 4. Action Buttons (Log Out & Save/Cancel) -->
    <div class="flex items-center justify-between pt-1 pb-6">
      <!-- Log Out Button -->
      <a
        href="<?= $basePath ?>pages/auth.php"
        class="btn-profile-logout"
      >
        Log Out
      </a>

      <!-- Right Buttons: Batal & Simpan Perubahan -->
      <div class="flex items-center gap-3">
        <button
          type="button"
          onclick="window.history.back();"
          class="btn-profile-cancel"
        >
          Batal
        </button>
        <button
          type="submit"
          class="btn-profile-save"
        >
          Simpan Perubahan
        </button>
      </div>
    </div>
  </form>

</main>

<!-- Skrip Interaktif Halaman Profil -->
<script src="<?= $basePath ?>asset/js/halaman_profil.js"></script>
