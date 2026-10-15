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
    <link rel="stylesheet" href="<?= $basePath ?>asset/css/styles.css" />
    <link rel="stylesheet" href="<?= $basePath ?>asset/css/output.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

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
            <nav id="desktop-nav" class="hidden h-full w-max md:flex">
                <a
                    class="<?= $activePage === 'home' ? 'relative font-bold text-white' : 'font-medium text-muted' ?> flex h-full items-center px-4 text-[13px] transition-colors hover:text-white hover:text-red"
                    href="<?= $basePath ?>index.php">
                    Cari Mabar
                    <?php if ($activePage === 'home'): ?>
                        <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-red"></div>
                    <?php endif; ?>
                </a>
                <a
                    class="flex h-full items-center px-4 text-[13px] font-medium text-muted transition-colors hover:text-white"
                    href="#">Cari Lawan</a>
                <a
                    class="<?= $activePage === 'tournaments' ? 'relative font-bold text-white' : 'font-medium text-muted' ?> flex h-full items-center px-4 text-[13px] transition-colors hover:text-white hover:text-red"
                    href="<?= $basePath ?>pages/turnamen/index.php">
                    Turnamen
                    <?php if ($activePage === 'tournaments'): ?>
                        <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-red"></div>
                    <?php endif; ?>
                </a>
            </nav>

            <!-- Search Bar -->
            <div
                class="order-3 bg-amber-400 flex h-10 flex-1 min-w-0 w-full items-center gap-2 rounded-md border border-border bg-card-alt px-3 text-muted transition-colors focus-within:border-muted md:order-none">
                <i class="ph ph-magnifying-glass text-lg"></i>
                <input
                    id="search-input"
                    class="w-full bg-transparent text-[13px] text-white outline-none placeholder:text-muted"
                    placeholder="Cari pemain, tim, atau sesi" />
            </div>

            <!-- Right Nav -->
            <div class="ml-auto flex items-center gap-5">
                <button
                    class="relative hidden text-2xl text-muted transition-colors hover:text-white sm:block"
                    aria-label="Notifikasi">
                    <i class="ph-fill ph-bell"></i>
                    <b
                        class="absolute -right-1 -top-1 grid h-[18px] w-[18px] place-items-center rounded-full bg-red text-[10px] font-bold text-white border-2 border-bg">9</b>
                </button>

                <div class="hidden items-center gap-3 sm:flex cursor-pointer group">
                    <div
                        class="relative h-10 w-10 shrink-0 rounded-full bg-card-alt border border-border">
                        <div
                            class="h-full w-full rounded-full bg-border overflow-hidden"></div>
                        <div
                            class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-bg bg-green"></div>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-[13px] font-medium text-white group-hover:text-red transition-colors">Wawan</span>
                        <span class="text-[11px] text-muted">wnwnwnrwn</span>
                    </div>
                </div>

                <!-- Open Chat Button (Hidden initially, shows when chat is hidden) -->
                <button
                    id="btn-open-chat"
                    class="hidden rounded border border-border p-2 text-xl text-muted transition-colors hover:text-white hover:border-red"
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