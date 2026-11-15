<?php
$pageTitle  = 'Tentang Mabar Lokal';
$basePath   = '../../';
$activePage = 'about';

require_once __DIR__ . '/../../include/kontak_tim.php';

require_once __DIR__ . '/../../include/header.php';
?>

<style>
    .about-feature-grid,
    .about-contact-grid {
        display: grid;
        gap: 0.75rem;
    }

    @media (min-width: 640px) {
        .about-feature-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .about-contact-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .about-contact-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
</style>

<main class="flex-1 overflow-y-auto">
    <div class="mx-auto w-full px-4 py-8 lg:px-8 lg:py-8" style="max-width: 1600px;">
        <div class="mb-5">
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-red">Kenali kami</p>
            <h1 class="mt-2 font-heading text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl">Tentang Kami</h1>
            <p class="mt-1 text-xs text-muted">Temukan jawaban untuk pertanyaan yang sering diajukan.</p>
        </div>

        <section class="rounded-md border border-red bg-[#0d1622] p-5 sm:p-6 lg:p-8">
            <h2 class="font-heading text-2xl font-extrabold leading-tight text-white sm:text-3xl">Tentang Mabar Lokal</h2>
            <div class="mb-6 max-w-5xl flex flex-wrap gap-6 space-y-3 text-xs leading-5 text-muted sm:text-[13px]">
                <p>Mabar Lokal adalah sebuah platform yang hadir untuk mempertemukan para pemain game dan pecinta olahraga agar bisa menemukan teman bermain, membangun tim, dan mengikuti berbagai kegiatan bersama. Kami percaya bahwa bermain akan terasa lebih seru ketika dilakukan bersama orang-orang yang memiliki minat dan semangat yang sama.</p>
                <p>Melalui Mabar Lokal, pengguna dapat mencari teman mabar sesuai dengan game, lokasi, dan waktu bermain yang diinginkan. Tidak hanya itu, pengguna juga bisa mencari lawan untuk bertanding, membentuk tim sendiri, serta mengikuti berbagai turnamen yang tersedia. </p>
                <p>Kami ingin menghilangkan kesulitan dalam mencari teman bermain, terutama bagi mereka yang sering kesulitan menemukan rekan dengan jadwal dan kemampuan yang sesuai. Dengan adanya Mabar Lokal, setiap orang memiliki kesempatan untuk bertemu pemain baru, memperluas pertemanan, dan mendapatkan pengalaman bermain yang lebih menyenangkan.</p>
                <p>Lebih dari sekadar tempat mencari teman bermain, Mabar Lokal juga ingin menjadi wadah bagi komunitas untuk berkembang. Kami berharap setiap pengguna dapat saling mengenal, bekerja sama, menunjukkan kemampuan, dan menciptakan pengalaman yang berkesan bersama.</p>
            </div>

            <div class="about-feature-grid border-t border-border py-8">
                <div class="flex flex-col items-center gap-2 text-center">
                    <!-- <i class="ph-bold ph-users-three text-4xl text-[#f4ead7]" aria-hidden="true"></i> -->
                    <!-- <span class="font-heading text-xs font-semibold text-white">Cari Kawan</span> -->
                    <img src="../../asset/icon/carikawan.svg" alt="">
                </div>
                <div class="flex flex-col items-center gap-2 border-border text-center sm:border-x">
                    <!-- <i class="ph-bold ph-sword text-4xl text-[#f4ead7]" aria-hidden="true"></i>
                    <span class="font-heading text-xs font-semibold text-white">Cari Lawan</span> -->
                    <img src="../../asset/icon/carilawan.svg" alt="">
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <!-- <i class="ph-bold ph-trophy text-4xl text-[#f4ead7]" aria-hidden="true"></i>
                    <span class="font-heading text-xs font-semibold text-white">Turnamen</span> -->
                    <img src="../../asset/icon/Turnamen.svg" alt="">
                </div>
            </div>
        </section>

        <section class="mt-4 rounded-md border border-red bg-[#0d1622] p-5 sm:p-6 lg:p-7">
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-red">Kontak kami</p>
            <h2 class="mt-1 font-heading text-2xl font-extrabold leading-tight text-white">Hubungi Kami</h2>
            <p class="mt-1 text-xs text-muted">Punya pertanyaan? Hubungi kami melalui Instagram.</p>

            <div class="about-contact-grid mt-6">
                    <?php foreach ($kontakTim as $orang): ?>
                        <a
                            href="https://instagram.com/<?= htmlspecialchars($orang['handle'], ENT_QUOTES, 'UTF-8') ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex min-h-14 items-center gap-3 rounded-md border border-red bg-[#111b28] px-4 py-3 transition-colors hover:bg-red hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red focus-visible:ring-offset-2 focus-visible:ring-offset-bg">
                            <span class="min-w-0 flex-1">
                                <strong class="block truncate font-heading text-sm text-white"><?= htmlspecialchars($orang['nama'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <span class="block truncate text-[11px] text-muted group-hover:text-white">@<?= htmlspecialchars($orang['handle'], ENT_QUOTES, 'UTF-8') ?></span>
                            </span>
                            <i class="ph-bold ph-arrow-up-right shrink-0 text-base text-white"></i>
                        </a>
                    <?php endforeach; ?>
            </div>
        </section>

        <!-- <a
            href="<?= $basePath ?>index.php"
            class="mt-5 inline-flex h-11 items-center gap-2 rounded-md bg-red px-5 text-sm font-semibold text-white transition-colors hover:bg-red-bright focus:outline-none focus:ring-2 focus:ring-red focus:ring-offset-2 focus:ring-offset-bg">
            Mulai cari mabar
            <i class="ph-bold ph-arrow-up-right" aria-hidden="true"></i>
        </a> -->
    </div>
</main>

<?php require_once __DIR__ . '/../../include/footer.php'; ?>