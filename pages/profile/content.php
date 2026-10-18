<!-- MAIN CONTENT: PROFIL -->
<main class="flex-1 overflow-y-auto hide-scrollbar py-6 flex flex-col gap-5">

  <!-- 1. Banner & Profil Card -->
  <div class="relative w-full rounded-xl border border-border bg-card overflow-hidden shadow-lg">
    <!-- Banner Area -->
    <div class="relative w-full overflow-hidden bg-[#0d1622]" style="height: 200px;">
      <img
        id="profile-banner-img"
        src="<?= $basePath ?>asset/icon/profile-banner.jpg"
        alt="Profile Banner"
        class="h-full w-full object-cover object-center transition-all duration-300"
        onerror="this.onerror=null; this.src='<?= $basePath ?>asset/icon/profile-banner.jpg'; localStorage.removeItem('mabar_profile_banner');"
      />
      <!-- Vignette Gradient Overlay -->
      <div
        class="absolute inset-0 pointer-events-none"
        style="background: linear-gradient(to right, rgba(8,15,24,0.92) 0%, rgba(8,15,24,0.55) 45%, rgba(0,0,0,0.15) 100%);"
      ></div>

      <!-- Ubah Banner Button (Top Right) -->
      <div style="position: absolute; top: 16px; right: 20px; z-index: 20;">
        <label
          for="banner-upload"
          class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-border bg-[#080f18]/85 hover:bg-[#080f18] text-white text-xs font-semibold backdrop-blur transition-all shadow-lg hover:border-red cursor-pointer"
          title="Upload banner foto dari file kamu"
        >
          <i class="ph ph-camera text-red text-sm"></i>
          <span>Ubah Banner</span>
          <input type="file" id="banner-upload" class="hidden" accept="image/*" />
        </label>
      </div>

      <!-- User Avatar & Identity (Centered vertically on left side of banner) -->
      <div
        style="position: absolute; top: 0; bottom: 0; left: 28px; display: flex; align-items: center; gap: 20px; z-index: 10;"
      >
        <!-- Avatar Wrapper -->
        <div style="position: relative; flex-shrink: 0;">
          <div id="profile-avatar-circle" class="profile-avatar-circle">
            <img id="profile-avatar-img" src="" alt="Avatar" class="h-full w-full object-cover hidden" />
            <i id="profile-avatar-icon" class="ph-fill ph-user text-5xl text-[#64748b]"></i>
          </div>
          <!-- Camera badge button for avatar -->
          <label
            for="avatar-upload"
            class="profile-avatar-badge"
            title="Upload foto profil dari file kamu"
          >
            <i class="ph ph-camera text-xs"></i>
            <input type="file" id="avatar-upload" class="hidden" accept="image/*" />
          </label>
        </div>

        <!-- Name & Location Info -->
        <div style="display: flex; flex-direction: column; justify-content: center;">
          <h1 id="profile-display-name" class="text-3xl font-extrabold text-white tracking-tight font-heading leading-none drop-shadow-lg" style="margin: 0; padding: 0;">
            Wawan
          </h1>
          <div class="flex items-center gap-1.5 text-xs text-muted font-medium" style="margin-top: 8px;">
            <i class="ph-fill ph-map-pin text-red text-xs"></i>
            <span id="profile-display-location">Malang</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <form id="profile-form" class="flex flex-col gap-5" onsubmit="event.preventDefault(); handleSaveProfile();">
    <!-- 2. Edit Profil Card -->
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

    <!-- 3. Pengaturan Lainnya Card -->
    <div class="rounded-xl border border-border bg-card p-5 sm:p-6 flex flex-col gap-4 shadow-sm">
      <h2 class="text-[14px] font-bold text-white tracking-wide font-heading">Pengaturan Lainnya</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Field: Bahasa -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-medium text-muted">Bahasa</label>
          <div class="relative flex items-center">
            <select
              name="bahasa"
              class="w-full h-11 rounded-md border border-border bg-card-alt px-3.5 pr-10 text-[13px] text-white outline-none appearance-none cursor-pointer focus:border-muted transition-colors"
            >
              <option value="id" selected>Indonesia</option>
              <option value="en">English (US)</option>
            </select>
            <i class="ph ph-caret-down absolute right-3 text-muted pointer-events-none text-xs"></i>
          </div>
        </div>

        <!-- Field: Privacy -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-medium text-muted">Privacy</label>
          <div class="relative flex items-center">
            <select
              name="privacy"
              class="w-full h-11 rounded-md border border-border bg-card-alt px-3.5 pr-10 text-[13px] text-white outline-none appearance-none cursor-pointer focus:border-muted transition-colors"
            >
              <option value="public" selected>Public</option>
              <option value="friends">Teman Saja</option>
              <option value="private">Private</option>
            </select>
            <i class="ph ph-caret-down absolute right-3 text-muted pointer-events-none text-xs"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. Action Buttons (Log Out & Save/Cancel) -->
    <div class="flex items-center justify-between pt-1 pb-6">
      <!-- Log Out Button -->
      <a
        href="<?= $basePath ?>pages/login.html"
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

  <!-- Toast Notification Feedback -->
  <div
    id="profile-toast"
    class="fixed flex items-center gap-2.5 rounded-lg border border-border bg-card px-4 py-3 text-sm text-white shadow-2xl transition-all duration-300"
    style="bottom: 24px; right: 24px; z-index: 9999; opacity: 0; pointer-events: none; transform: translateY(12px);"
  >
    <i class="ph-fill ph-check-circle text-green text-lg"></i>
    <span id="profile-toast-msg">Perubahan profil berhasil disimpan!</span>
  </div>

  <script>
    // 1. Load saved banner & avatar from LocalStorage on start
    document.addEventListener("DOMContentLoaded", () => {
      const savedBanner = localStorage.getItem("mabar_profile_banner");
      const savedAvatar = localStorage.getItem("mabar_profile_avatar");
      const savedName = localStorage.getItem("mabar_profile_name");
      const savedLocation = localStorage.getItem("mabar_profile_location");
      const savedEmail = localStorage.getItem("mabar_profile_email");
      const savedNoHp = localStorage.getItem("mabar_profile_nohp");

      if (savedBanner) {
        if (savedBanner.includes("profile-banner-2") || savedBanner.includes("profile-banner-3")) {
          localStorage.removeItem("mabar_profile_banner");
        } else {
          document.getElementById("profile-banner-img").src = savedBanner;
        }
      }
      if (savedAvatar) {
        setAvatarImage(savedAvatar);
      }
      if (savedName) {
        document.getElementById("input-nama").value = savedName;
        document.getElementById("profile-display-name").textContent = savedName;
      }
      if (savedLocation) {
        document.getElementById("input-lokasi").value = savedLocation;
        document.getElementById("profile-display-location").textContent = savedLocation;
      }
      if (savedEmail) {
        document.getElementById("input-email").value = savedEmail;
      }
      if (savedNoHp) {
        document.getElementById("input-nohp").value = savedNoHp;
      }

      // Live sync as user types
      document.getElementById("input-nama")?.addEventListener("input", (e) => {
        document.getElementById("profile-display-name").textContent = e.target.value || "Wawan";
      });
      document.getElementById("input-lokasi")?.addEventListener("input", (e) => {
        document.getElementById("profile-display-location").textContent = e.target.value || "Malang";
      });
    });

    // 2. Upload Custom Banner Image from File
    document.getElementById("banner-upload")?.addEventListener("change", function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(evt) {
        const base64 = evt.target.result;
        document.getElementById("profile-banner-img").src = base64;
        localStorage.setItem("mabar_profile_banner", base64);
        showToast("Foto banner berhasil diperbarui!");
      };
      reader.readAsDataURL(file);
    });

    // 3. Upload Custom Avatar Picture from File
    document.getElementById("avatar-upload")?.addEventListener("change", function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(evt) {
        const base64 = evt.target.result;
        setAvatarImage(base64);
        localStorage.setItem("mabar_profile_avatar", base64);
        showToast("Foto profil berhasil diperbarui!");
      };
      reader.readAsDataURL(file);
    });

    function setAvatarImage(src) {
      const img = document.getElementById("profile-avatar-img");
      const icon = document.getElementById("profile-avatar-icon");
      if (img && icon) {
        img.src = src;
        img.classList.remove("hidden");
        icon.classList.add("hidden");
      }
    }

    // 4. Save Profile Changes
    function handleSaveProfile() {
      const nama = document.getElementById("input-nama").value.trim();
      const lokasi = document.getElementById("input-lokasi").value.trim();
      const email = document.getElementById("input-email").value.trim();
      const nohp = document.getElementById("input-nohp").value.trim();

      localStorage.setItem("mabar_profile_name", nama);
      localStorage.setItem("mabar_profile_location", lokasi);
      localStorage.setItem("mabar_profile_email", email);
      localStorage.setItem("mabar_profile_nohp", nohp);

      showToast("Perubahan profil berhasil disimpan!");
    }

    function showToast(msg) {
      const toast = document.getElementById("profile-toast");
      const toastMsg = document.getElementById("profile-toast-msg");
      if (!toast) return;
      if (msg && toastMsg) toastMsg.textContent = msg;

      toast.style.opacity = "1";
      toast.style.pointerEvents = "auto";
      toast.style.transform = "translateY(0)";

      setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.pointerEvents = "none";
        toast.style.transform = "translateY(12px)";
      }, 2500);
    }
  </script>
</main>
