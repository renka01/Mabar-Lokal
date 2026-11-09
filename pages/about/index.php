<?php
$pageTitle  = 'Tentang Mabar Lokal';
$basePath   = '../../';
$activePage = 'about';

require_once __DIR__ . '/../../include/kontak_tim.php';

require_once __DIR__ . '/../../include/header.php';
?>

<main class="flex-1 overflow-y-auto">
    <div class="mx-auto mt-5 w-full px-4 py-10 lg:px-8 lg:py-16" style="max-width: 1152px;">
        <section class="rounded-lg border border-border bg-card p-5 lg:p-8">
            <h1 class="font-heading text-2xl font-extrabold leading-tight tracking-tight text-white lg:text-6xl">
                Temukan teman bermain di sekitar kamu.
            </h1>
            <p class="mt-6 text-base leading-relaxed text-muted" style="max-width: 65ch;">
                Mabar Lokal membantu kamu menemukan teman mabar, lawan scrim, dan turnamen komunitas
                berdasarkan minat, lokasi, dan waktu bermain.
            </p>
            <a
                href="<?= $basePath ?>index.php"
                class="mt-6 inline-flex h-12 items-center gap-2 rounded-md bg-red px-6 text-base font-semibold text-white transition-colors hover:bg-red-bright focus:outline-none focus:ring-2 focus:ring-red focus:ring-offset-2 focus:ring-offset-bg">
                Mulai cari mabar
                <i class="ph-bold ph-arrow-up-right"></i>
            </a>

            <div class="mt-8 rounded-lg border border-border bg-card-alt p-5">
                <h2 class="font-heading text-2xl font-bold leading-tight text-white">
                    Tim &amp; kontak
                </h2>
                <p class="mt-1 text-sm text-muted">
                    Sapa dan terhubung dengan kami lewat Instagram.
                </p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <?php foreach ($kontakTim as $orang): ?>
                        <a
                            href="https://instagram.com/<?= htmlspecialchars($orang['handle'], ENT_QUOTES, 'UTF-8') ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center gap-3 rounded-md border border-border bg-card p-4 transition-colors hover:border-red focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-red/20 bg-red/10 text-lg text-red">
                                <i class="ph-bold ph-instagram-logo"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <strong class="block truncate font-heading text-base text-white"><?= htmlspecialchars($orang['nama'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <span class="block truncate text-[13px] text-muted">@<?= htmlspecialchars($orang['handle'], ENT_QUOTES, 'UTF-8') ?></span>
                            </span>
                            <i class="ph-bold ph-arrow-up-right shrink-0 text-lg text-red"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../../include/footer.php'; ?>