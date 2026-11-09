<?php
$pageTitle  = 'Tentang Mabar Lokal';
$basePath   = '../../';
$activePage = 'about';
require_once __DIR__ . '/../../include/header.php';
?>

<main class="flex-1 overflow-y-auto">
    <div class="mx-auto mt-5 w-full px-4 py-10 lg:px-8 lg:py-16" style="max-width: 1152px;">
        <section class="rounded-lg border border-border bg-card  p-5 pb-16 lg:pb-20">
            <h1 class="max-w-3xl font-heading text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-6xl lg:text-7xl">
                Temukan teman bermain di sekitar kamu.
            </h1>
            <p class="mt-6 max-w-2xl text-sm leading-7 text-muted sm:text-base">
                Mabar Lokal membantu kamu menemukan teman mabar, lawan scrim, dan turnamen komunitas
                berdasarkan minat, lokasi, dan waktu bermain.
            </p>
            <a
                href="<?= $basePath ?>index.php"
                class="mb-5 mt-2 inline-flex h-12 items-center gap-2 rounded-md bg-red px-6 text-base font-semibold text-white transition-colors hover:bg-red-bright focus:outline-none focus:ring-2 focus:ring-red focus:ring-offset-2 focus:ring-offset-bg">
                Mulai cari mabar
                <i class="ph-bold ph-arrow-up-right"></i>
            </a>
        </section>

        <section class="mt-5 py-12 lg:py-16">
            <h2 class="font-heading text-2xl font-bold leading-tight text-white sm:text-3xl lg:text-4xl">
                Apa yang bisa kamu lakukan?
            </h2>
            <div class="mt-2 flex flex-col gap-px border-y border-border">
                <a href="<?= $basePath ?>index.php" class="flex min-h-[88px] items-center justify-between gap-5 py-5 text-white transition-colors hover:text-red focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                    <span>
                        <strong class="font-heading text-base">Cari teman mabar</strong>
                        <span class="mt-1 block text-sm text-muted">Temukan pemain dengan minat dan jadwal yang sesuai.</span>
                    </span>
                    <i class="ph-bold ph-arrow-up-right shrink-0 text-xl text-red"></i>
                </a>
                <a href="<?= $basePath ?>pages/cari_lawan/index.php" class="flex min-h-[88px] items-center justify-between gap-5 py-5 text-white transition-colors hover:text-red focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                    <span>
                        <strong class="font-heading text-base">Cari lawan scrim</strong>
                        <span class="mt-1 block text-sm text-muted">Hubungkan tim kamu dengan lawan yang selevel.</span>
                    </span>
                    <i class="ph-bold ph-arrow-up-right shrink-0 text-xl text-red"></i>
                </a>
                <a href="<?= $basePath ?>pages/turnamen/index.php" class="flex min-h-[88px] items-center justify-between gap-5 py-5 text-white transition-colors hover:text-red focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                    <span>
                        <strong class="font-heading text-base">Ikuti turnamen</strong>
                        <span class="mt-1 block text-sm text-muted">Lihat kompetisi komunitas yang tersedia.</span>
                    </span>
                    <i class="ph-bold ph-arrow-up-right shrink-0 text-xl text-red"></i>
                </a>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../../include/footer.php'; ?>
