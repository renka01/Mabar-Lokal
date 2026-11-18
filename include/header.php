<?php
require_once __DIR__ . '/../config/session.php';

$pageTitle  = $pageTitle ?? 'Cari Sesi';
$basePath   = $basePath ?? '';
$activePage = $activePage ?? 'home';

$authUser        = currentUser();
$authUserPayload = $authUser ? publicUserPayload($authUser) : null;
$isLoggedIn      = $authUserPayload !== null;

$headerUserName     = $isLoggedIn ? $authUserPayload['name'] : 'Tamu Mabar';
$headerUserTag      = $isLoggedIn
    ? ($authUserPayload['username_tag'] === 'wnwnwnrwn' ? 'wnwnwnrwn' : '@' . $authUserPayload['username_tag'])
    : 'Mode Tamu';
$headerAvatarSrc    = $isLoggedIn ? resolveMediaUrl($authUserPayload['avatar_path'] ?? '', $basePath) : '';
$headerOnlineLabel  = $isLoggedIn ? 'Online' : 'Mode Tamu';
$headerStatusDotCls = $isLoggedIn ? 'bg-green' : 'bg-muted';

$headerUnreadNotifCount = 0;
if ($isLoggedIn) {
    $hdrPdo = tryGetDB();
    if ($hdrPdo instanceof PDO) {
        try {
            $hdrNotifStmt = $hdrPdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE');
            $hdrNotifStmt->execute([(int) $authUserPayload['id']]);
            $headerUnreadNotifCount = (int) $hdrNotifStmt->fetchColumn();
        } catch (PDOException $e) {
            $headerUnreadNotifCount = 0;
        }
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mabar Lokal - <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= $basePath ?>asset/icon/Logo1.svg" />
    <link rel="shortcut icon" type="image/svg+xml" href="<?= $basePath ?>asset/icon/Logo1.svg" />
    <link rel="stylesheet" href="<?= $basePath ?>asset/css/output.css" />
    <link rel="stylesheet" href="<?= $basePath ?>asset/css/styles.css?v=<?= time() ?>" />
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
    <script>
      window.MABAR_BASE_PATH = <?= json_encode($basePath, JSON_UNESCAPED_SLASHES) ?>;
      window.MABAR_AUTH = <?= json_encode([
          'authenticated' => $isLoggedIn,
          'guest'         => !$isLoggedIn,
          'user'          => $authUserPayload,
      ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
      window.mabarResolveAsset = function(src) {
        if (!src) return '';
        if (/^(?:data:|blob:|https?:\/\/|\/)/i.test(src)) return src;
        var bp = window.MABAR_BASE_PATH || '';
        if (bp && src.indexOf(bp) === 0) return src;
        return bp + src;
      };
    </script>

</head>

<body
    class="flex flex-col h-screen bg-bg text-white font-sans text-sm selection:bg-red selection:text-white overflow-hidden">
    <!-- Navbar -->
    <header class="shrink-0 border-b border-border bg-[#080f18] z-50">
        <div
            class="mx-auto flex h-[76px] w-full max-w-[1600px] items-center gap-6 px-4 py-2 lg:px-8">
            <!-- Logo -->
            <a
                href="<?= $basePath ?>index.php"
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
                    class="nav-link <?= $activePage === 'cari_lawan' ? 'active font-bold text-white' : 'font-medium text-muted hover:text-white' ?> flex h-full items-center px-4 text-[13px] transition-colors"
                    href="<?= $basePath ?>pages/cari_lawan/index.php"
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
                <script>
                    (function() {
                        var nav = document.getElementById('desktop-nav');
                        var ind = document.getElementById('nav-indicator');
                        if (nav && ind) {
                            var act = nav.querySelector('.nav-link.active');
                            if (act) {
                                ind.style.left = act.offsetLeft + 'px';
                                ind.style.width = act.offsetWidth + 'px';
                                ind.style.opacity = '1';
                            }
                        }
                    })();
                </script>
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
                <!-- Notification Center -->
                <div
                    class="relative hidden sm:block"
                    id="notification-wrapper"
                    data-base-path="<?= htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <button
                        id="notification-btn"
                        type="button"
                        class="notif-trigger-btn"
                        aria-label="Notifikasi"
                        aria-expanded="false"
                        aria-haspopup="true"
                        title="Notifikasi"
                    >
                        <i class="ph-fill ph-bell"></i>
                        <b id="notification-badge" class="notif-badge <?= $headerUnreadNotifCount > 0 ? '' : 'hidden' ?>"><?= $headerUnreadNotifCount > 99 ? '99+' : (string) $headerUnreadNotifCount ?></b>
                    </button>

                    <!-- Notification Popover Panel -->
                    <div
                        id="notification-popover"
                        class="notif-popover hidden"
                        role="dialog"
                        aria-label="Pusat Notifikasi"
                    >
                        <div class="notif-popover-header">
                            <div class="notif-header-title-wrap">
                                <span class="notif-header-title">Notifikasi</span>
                                <span id="notification-unread-pill" class="notif-unread-pill hidden">0 Baru</span>
                            </div>
                            <button
                                id="btn-notif-mark-all"
                                type="button"
                                class="notif-mark-all-btn"
                            >
                                <i class="ph-bold ph-checks"></i>
                                <span>Tandai semua dibaca</span>
                            </button>
                        </div>

                        <div class="notif-tabs hide-scrollbar" role="tablist" aria-label="Filter Notifikasi">
                            <button type="button" class="notif-tab active" data-notif-filter="all">Semua</button>
                            <button type="button" class="notif-tab" data-notif-filter="unread">Belum Dibaca</button>
                            <button type="button" class="notif-tab" data-notif-filter="mabar">Mabar</button>
                            <button type="button" class="notif-tab" data-notif-filter="scrim">Scrim</button>
                            <button type="button" class="notif-tab" data-notif-filter="turnamen">Turnamen</button>
                            <button type="button" class="notif-tab" data-notif-filter="chat">Chat</button>
                        </div>

                        <div id="notification-list" class="notif-list modal-terms-scroll"></div>

                        <div class="notif-popover-footer">
                            <button
                                id="btn-notif-clear-read"
                                type="button"
                                class="notif-footer-action"
                            >
                                <i class="ph ph-broom"></i>
                                <span>Hapus yang dibaca</span>
                            </button>
                            <span id="notification-total-info" class="notif-footer-meta">0 notifikasi</span>
                        </div>
                    </div>
                </div>

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
                                <img id="header-avatar-img" src="<?= htmlspecialchars($headerAvatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" class="h-full w-full object-cover <?= $headerAvatarSrc !== '' ? '' : 'hidden' ?>" />
                                <i id="header-avatar-icon" class="ph-fill ph-user text-xl <?= $headerAvatarSrc !== '' ? 'hidden' : '' ?>"></i>
                            </div>
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-bg <?= $headerStatusDotCls ?>"></div>
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-[13px] font-medium text-white group-hover:text-red transition-colors flex items-center gap-1.5">
                                <span id="header-user-name"><?= htmlspecialchars($headerUserName, ENT_QUOTES, 'UTF-8') ?></span>
                                <i class="ph ph-caret-down text-[11px] text-muted group-hover:text-white transition-transform duration-200" id="profile-caret"></i>
                            </span>
                            <span class="text-[11px] text-muted" id="header-user-tag"><?= htmlspecialchars($headerUserTag, ENT_QUOTES, 'UTF-8') ?></span>
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
                            <p class="text-[13px] font-semibold text-white truncate" id="dropdown-user-fullname"><?= htmlspecialchars($headerUserName, ENT_QUOTES, 'UTF-8') ?></p>
                            <span class="inline-flex items-center gap-1.5 mt-0.5 text-[10px] <?= $isLoggedIn ? 'text-green' : 'text-muted' ?> font-medium">
                                <span class="h-1.5 w-1.5 rounded-full <?= $headerStatusDotCls ?>"></span> <?= htmlspecialchars($headerOnlineLabel, ENT_QUOTES, 'UTF-8') ?>
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
                            href="#syarat-ketentuan"
                            role="menuitem"
                            onclick="event.preventDefault(); if (typeof window.closeProfileDropdown === 'function') window.closeProfileDropdown(); if (typeof window.openTermsModal === 'function') window.openTermsModal();"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-muted hover:text-white hover:bg-card-alt transition-colors group"
                        >
                            <i class="ph ph-question text-base text-muted group-hover:text-red transition-colors"></i>
                            <span>Bantuan &amp; Syarat Ketentuan</span>
                        </a>

                        <!-- Faq Menu Links -->
                        <a
                            href="<?= $basePath ?>pages/faq/index.php"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-muted hover:text-white hover:bg-card-alt transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-question text-base text-muted group-hover:text-red transition-colors"></i>
                            <span>FAQ</span>
                        </a>

                        <!-- Garis Pembatas Tipis -->
                        <div class="my-1.5 h-px bg-border/60"></div>

                        <!-- Keluar / Masuk -->
                        <?php if ($isLoggedIn): ?>
                        <a
                            href="<?= $basePath ?>api/auth/logout.php?redirect=1"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-red/80 hover:text-red hover:bg-red/10 transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-sign-out text-base group-hover:translate-x-0.5 transition-transform"></i>
                            <span>Keluar</span>
                        </a>
                        <?php else: ?>
                        <a
                            href="<?= $basePath ?>pages/auth.php"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-md text-[13px] text-green/90 hover:text-green hover:bg-green/10 transition-colors group"
                            role="menuitem"
                        >
                            <i class="ph ph-sign-in text-base group-hover:translate-x-0.5 transition-transform"></i>
                            <span>Masuk / Daftar Akun</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <script>
                    (function() {
                        try {
                            var auth = window.MABAR_AUTH || {};
                            if (auth.authenticated && auth.user) {
                                var u = auth.user;
                                localStorage.removeItem('mabar_is_guest');
                                localStorage.setItem('mabar_profile_name', u.name || 'Pemain');
                                localStorage.setItem('mabar_profile_tag', u.username_tag || '');
                                localStorage.setItem('mabar_profile_email', u.email || '');
                                localStorage.setItem('mabar_profile_location', u.city || 'Malang');
                                localStorage.setItem('mabar_profile_nohp', u.phone || '');
                                localStorage.setItem('mabar_profile_bio', u.bio || '');
                                if (u.avatar_path) localStorage.setItem('mabar_profile_avatar', u.avatar_path);
                                else localStorage.removeItem('mabar_profile_avatar');
                                if (u.banner_path) localStorage.setItem('mabar_profile_banner', u.banner_path);
                                else localStorage.removeItem('mabar_profile_banner');
                                if (u.pref_category) localStorage.setItem('mabar_pref_category', u.pref_category);
                                if (Array.isArray(u.pref_games)) {
                                    localStorage.setItem('mabar_pref_games', JSON.stringify(u.pref_games));
                                }
                                if (u.questionnaire_completed) {
                                    localStorage.setItem('mabar_questionnaire_completed', 'true');
                                }
                            } else {
                                localStorage.setItem('mabar_is_guest', 'true');
                                localStorage.setItem('mabar_profile_name', 'Tamu Mabar');
                                localStorage.setItem('mabar_profile_tag', 'Mode Tamu');
                                localStorage.removeItem('mabar_profile_avatar');
                                localStorage.removeItem('mabar_profile_banner');
                                localStorage.removeItem('mabar_profile_email');
                                localStorage.removeItem('mabar_profile_nohp');
                                localStorage.removeItem('mabar_profile_bio');
                            }
                        } catch(e) {}
                    })();
                </script>

                <!-- Open Chat Button (Hidden initially, shows when chat is hidden) -->
                <button
                    id="btn-open-chat"
                    type="button"
                    class="relative hidden rounded-md border border-border bg-card-alt p-2 text-xl text-muted transition-colors hover:text-white hover:border-red cursor-pointer"
                    aria-label="Buka Chat"
                    title="Buka Chat">
                    <i class="ph-fill ph-chat-text"></i>
                    <span id="btn-open-chat-dot" class="chat-open-dot hidden"></span>
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