<?php
$pageTitle = $pageTitle ?? 'Cari Sesi';
$basePath = $basePath ?? '';
$activePage = $activePage ?? 'home';
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mabar Lokal - <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= $basePath ?>asset/css/output.css" />
    <link rel="stylesheet" href="<?= $basePath ?>asset/css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400..900;1,400..900&family=Sora:wght@100..800&display=swap"
        rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Library Flatpickr date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Inline: set chat state SEBELUM render agar tidak ada layout shift / glitch -->
    <script>if(localStorage.getItem('mabar_chat_closed')==='true'){document.documentElement.classList.add('chat-is-closed');}</script>

</head>

<body
    class="flex flex-col h-screen bg-bg text-white font-sans text-sm selection:bg-red selection:text-white overflow-hidden">
    <!-- Navbar -->
    <header class="shrink-0 border-b border-border bg-[#080f18] z-50">
        <div
            class="mx-auto flex h-[76px] w-full max-w-[1600px] items-center gap-6 px-4 py-2 lg:px-8">
            <!-- Logo -->
            <a
                href="#"
                class="flex shrink-0 items-center gap-2"
                aria-label="Mabar Lokal">
                <img
                    src="<?= $basePath ?>asset/icon/LogoFull.svg"
                    alt="Mabar Lokal Logo"
                    class="h-10 w-auto" />
                <!-- <div class="flex items-center gap-2">
            <div class="text-[32px] text-red mt-1">
              <i class="ph-fill ph-shield"></i>
            </div>
            <span class="text-xl font-extrabold leading-none tracking-tight">
              MABAR <br>
              <span class="text-red">LOKAL</span>
            </span>
          </div> -->
            </a>

            <!-- Divider -->
            <div class="hidden h-6 w-px bg-border md:block"></div>

            <!-- Desktop Nav -->
            <nav id="desktop-nav" class="relative hidden h-full w-max md:flex items-center">
                <a
                    class="nav-link <?= $activePage === 'home' ? 'active font-bold text-white' : 'font-medium text-muted hover:text-white' ?> flex h-full items-center px-4 text-[13px] transition-colors"
                    href="<?= $basePath ?>index.php"
                    data-page="home">
                    Cari Mabar
                </a>
                <a
                    class="nav-link flex h-full items-center px-4 text-[13px] font-medium text-muted transition-colors hover:text-white"
                    href="#"
                    data-page="lawan">
                    Cari Lawan Scrim
                </a>
                <a
                    class="nav-link <?= $activePage === 'tournaments' ? 'active font-bold text-white' : 'font-medium text-muted hover:text-white' ?> flex h-full items-center px-4 text-[13px] transition-colors"
                    href="<?= $basePath ?>pages/turnamen/index.php"
                    data-page="tournaments">
                    Turnamen
                </a>

                <!-- Sliding Red Indicator -->
                <div
                    id="nav-indicator"
                    class="absolute bottom-0 h-[3px] bg-red pointer-events-none"
                    style="left: 0; width: 0; opacity: 0; border-radius: 2px 2px 0 0;"
                ></div>
            </nav>
            <script src="<?= $basePath ?>asset/js/slider_navigasi.js"></script>

            <!-- Search Bar -->
            <div
                class="order-3 flex h-10 flex-1 min-w-0 w-full items-center gap-2 rounded-md border border-border bg-card-alt px-3 text-muted transition-colors focus-within:border-muted md:order-none">
                <i class="ph ph-magnifying-glass text-lg"></i>
                <input
                    id="search-input"
                    class="w-full bg-transparent text-[13px] text-white outline-none placeholder:text-muted"
                    placeholder="Cari pemain, tim, atau sesi" />
            </div>

            <!-- (Profil icon) Right Nav -->
            <div class="ml-auto flex items-center gap-5">
                <button
                    class="relative hidden text-2xl text-muted transition-colors hover:text-white sm:block"
                    aria-label="Notifikasi">
                    <i class="ph-fill ph-bell"></i>
                    <b
                        class="absolute -right-1 -top-1 grid h-[18px] w-[18px] place-items-center rounded-full bg-red text-[10px] font-bold text-white border-2 border-bg">9</b>
                </button>

                <!-- User Profile & Dropdown -->
                <div class="relative hidden sm:block" id="profile-dropdown-wrapper">
                    <!-- Trigger Button -->
                    <button
                        id="profile-dropdown-btn"
                        type="button"
                        class="flex items-center gap-3 cursor-pointer group p-1 -m-1 rounded-lg transition-colors hover:bg-card-alt/60 focus:outline-none"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <div class="relative h-10 w-10 shrink-0 rounded-full bg-card-alt border border-border group-hover:border-muted transition-colors">
                            <div class="h-full w-full rounded-full bg-border overflow-hidden flex items-center justify-center text-muted" id="header-avatar-container">
                                <img id="header-avatar-img" src="" alt="Avatar" class="h-full w-full object-cover hidden" />
                                <i id="header-avatar-icon" class="ph-fill ph-user text-xl"></i>
                            </div>
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-bg bg-green"></div>
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-[13px] font-medium text-white group-hover:text-red transition-colors flex items-center gap-1.5">
                                <span id="header-user-name">Wawan</span>
                                <i class="ph ph-caret-down text-[11px] text-muted group-hover:text-white transition-transform duration-200" id="profile-caret"></i>
                            </span>
                            <span class="text-[11px] text-muted" id="header-user-tag">wnwnwnrwn</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        id="profile-dropdown-menu"
                        class="hidden absolute right-0 top-full mt-2 w-60 rounded-lg border border-border bg-card p-1.5 shadow-2xl shadow-black/90 z-50"
                        role="menu"
                    >
                        <!-- Mini Header Akun -->
                        <div class="px-3 py-2 border-b border-border/60 mb-1">
                            <p class="text-[10px] font-bold tracking-wider text-muted uppercase">Akun Saya</p>
                            <p class="text-[13px] font-semibold text-white truncate" id="dropdown-user-fullname">Wawan Wirawan</p>
                            <span class="inline-flex items-center gap-1.5 mt-0.5 text-[10px] text-green font-medium">
                                <span class="h-1.5 w-1.5 rounded-full bg-green"></span> Online
                            </span>
                        </div>

                        <!-- Menu Links: Profil & Pengaturan -->
                        <a
                            href="<?= $basePath ?>pages/profile/index.php"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-muted hover:text-white hover:bg-card-alt transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-user text-base text-muted group-hover:text-red transition-colors"></i>
                            <span>Profil Saya</span>
                        </a>

                        <a
                            href="<?= $basePath ?>pages/profile/index.php?tab=settings"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-muted hover:text-white hover:bg-card-alt transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-gear-six text-base text-muted group-hover:text-red transition-colors"></i>
                            <span>Pengaturan</span>
                        </a>

                        <!-- Garis Pembatas Tipis -->
                        <div class="my-1.5 h-px bg-border/60"></div>

                        <!-- Menu Links: Tentang & Bantuan -->
                        <a
                            href="<?= $basePath ?>pages/about/index.php"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-muted hover:text-white hover:bg-card-alt transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-info text-base text-muted group-hover:text-red transition-colors"></i>
                            <span>Tentang MabarLokal</span>
                        </a>

                        <a
                            href="<?= $basePath ?>pages/help/index.php"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-muted hover:text-white hover:bg-card-alt transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-question text-base text-muted group-hover:text-red transition-colors"></i>
                            <span>Bantuan & Syarat Ketentuan</span>
                        </a>

                        <!-- Garis Pembatas Tipis -->
                        <div class="my-1.5 h-px bg-border/60"></div>

                        <!-- Keluar / Logout -->
                        <a
                            href="<?= $basePath ?>pages/login.html"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-red/80 hover:text-red hover:bg-red/10 transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-sign-out text-base group-hover:translate-x-0.5 transition-transform"></i>
                            <span>Keluar</span>
                        </a>    
                    </div>
                </div>
                <script>
                    (function() {
                        try {
                            var av = localStorage.getItem('mabar_profile_avatar');
                            if (av) {
                                var img = document.getElementById('header-avatar-img');
                                var ic = document.getElementById('header-avatar-icon');
                                if (img && ic) {
                                    img.src = av;
                                    img.classList.remove('hidden');
                                    ic.classList.add('hidden');
                                }
                            }
                            var nm = localStorage.getItem('mabar_profile_name');
                            if (nm) {
                                var el = document.getElementById('header-user-name');
                                if (el) el.textContent = nm;
                                var df = document.getElementById('dropdown-user-fullname');
                                if (df) df.textContent = nm;
                            }
                        } catch(e) {}
                    })();
                </script>

                <!-- Open Chat Button (Hidden initially, shows when chat is hidden) -->
                <button
                    id="btn-open-chat"
                    class="hidden rounded-md border border-border bg-card-alt p-2 text-xl text-muted transition-colors hover:text-white hover:border-red cursor-pointer"
                    aria-label="Buka Chat"
                    title="Buka Chat">
                    <i class="ph-fill ph-chat-text"></i>
                </button>

                <button
                    id="menu-btn"
                    class="rounded border border-border p-2 text-2xl text-muted transition-colors hover:text-white md:hidden"
                    aria-label="Buka menu">
                    <i class="ph ph-list"></i>
                </button>
            </div>
        </div>
    </header>