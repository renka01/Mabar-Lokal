<?php
$pageTitle  = 'Pertanyaan Umum';
$basePath   = '../../';
$activePage = 'faq';
require_once __DIR__ . '/../../include/header.php';
?>

<style>
    .faq-page {
        background:
            linear-gradient(90deg, rgba(239, 35, 60, 0.035) 1px, transparent 1px) 0 0 / 72px 72px,
            #080f18;
    }

    .faq-layout {
        /* display: grid; */
        gap: 1.25rem;
    }

    .faq-summary {
        list-style: none;
    }

    .faq-summary::-webkit-details-marker {
        display: none;
    }

    .faq-item[open] .faq-caret {
        transform: rotate(180deg);
    }

    .faq-item[open] {
        border-color: #ef233c;
        background: #111b28;
    }

    .faq-category-link {
        transition: border-color 160ms ease, color 160ms ease, background-color 160ms ease;
    }

    .faq-category-link:hover,
    .faq-category-link:focus-visible {
        border-color: #ef233c;
        color: #f4f5f7;
    }

    @media (max-width: 767px) {
        .faq-page {
            background: #080f18;
        }
    }

    @media (min-width: 1024px) {
        .faq-layout {
            grid-template-columns: 200px minmax(0, 1fr);
        }
    }
</style>

<main class="faq-page flex-1 overflow-y-auto">
    <div class="mx-auto w-full px-4 py-8 lg:px-8 lg:py-8" style="max-width: 1200px;">
        <div class="faq-layout">
            <!-- <aside class="h-max rounded-md border border-border bg-[#0d1622] p-2.5">
                <p class="px-2.5 pb-2 pt-1 text-[10px] font-bold uppercase tracking-[0.16em] text-white">Kategori</p>
                <nav class="space-y-2" aria-label="Kategori FAQ">
                    <a href="#semua" class="faq-category-link flex min-h-9 items-center gap-2 rounded-md border border-red bg-[#111b28] px-2.5 text-[11px] font-semibold text-white">
                        <i class="ph-bold ph-question text-base text-red" aria-hidden="true"></i>
                        Semua
                    </a>
                    <a href="#akun-profil" class="faq-category-link flex min-h-9 items-center gap-2 rounded-md border border-transparent bg-[#111b28] px-2.5 text-[11px] font-semibold text-muted">
                        <i class="ph-bold ph-user text-base" aria-hidden="true"></i>
                        Akun &amp; Profil
                    </a>
                    <a href="#mabar-sesi" class="faq-category-link flex min-h-9 items-center gap-2 rounded-md border border-transparent bg-[#111b28] px-2.5 text-[11px] font-semibold text-muted">
                        <i class="ph-bold ph-users-three text-base" aria-hidden="true"></i>
                        Mabar &amp; Sesi
                    </a>
                    <a href="#turnamen" class="faq-category-link flex min-h-9 items-center gap-2 rounded-md border border-transparent bg-[#111b28] px-2.5 text-[11px] font-semibold text-muted">
                        <i class="ph-bold ph-trophy text-base" aria-hidden="true"></i>
                        Turnamen
                    </a>
                    <a href="#keamanan" class="faq-category-link flex min-h-9 items-center gap-2 rounded-md border border-transparent bg-[#111b28] px-2.5 text-[11px] font-semibold text-muted">
                        <i class="ph-bold ph-shield-check text-base" aria-hidden="true"></i>
                        Keamanan
                    </a>
                </nav>
            </aside> -->

            <section id="semua" class="min-w-0">
                <div class="mb-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-red">Pusat bantuan</p>
                    <h1 class="mt-2 font-heading text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl">FAQ</h1>
                    <p class="mt-1 text-xs text-muted">Temukan jawaban untuk pertanyaan yang sering diajukan.</p>
                </div>

                <div class="space-y-2.5">
                    <details class="faq-item rounded-md border border-red bg-[#111b28] px-4 py-3 mb-3" open>
                        <summary class="faq-summary flex min-h-8 cursor-pointer items-center justify-between gap-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-red">Semua</span>
                                <strong class="block font-heading text-sm text-white sm:text-[15px]">Bagaimana cara mencari teman mabar?</strong>
                            </span>
                            <i class="faq-caret ph-bold ph-caret-down shrink-0 text-base text-red transition-transform" aria-hidden="true"></i>
                        </summary>
                        <p class=" scrim-quote-box border-l-2 border-red pl-3 leading-7 pt-4 pt-3 text-xs leading-5 text-muted">
                            Buka halaman Cari Mabar, lalu gunakan pilihan game atau olahraga, kota, dan waktu bermain untuk menemukan sesi yang paling sesuai. Setelah menemukan sesi, kamu dapat melihat detailnya sebelum bergabung.
                        </p>
                    </details>

                    <details id="mabar-sesi" class="faq-item rounded-md border border-border bg-[#0d1622] px-4 py-3 mb-3">
                        <summary class="faq-summary flex min-h-8 cursor-pointer items-center justify-between gap-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-red">Mabar &amp; Sesi</span>
                                <strong class="block font-heading text-sm text-white sm:text-[15px]">Apakah saya bisa membuat sesi mabar sendiri?</strong>
                            </span>
                            <i class="faq-caret ph-bold ph-caret-down shrink-0 text-base text-red transition-transform" aria-hidden="true"></i>
                        </summary>
                        <p class="pt-3 text-xs leading-5 text-muted scrim-quote-box">Bisa. Buat sesi dengan menentukan aktivitas, lokasi, jadwal, dan jumlah pemain yang dibutuhkan. Informasi yang jelas membantu pemain lain menemukan sesi kamu.</p>
                    </details>

                    <details class="faq-item rounded-md border border-border bg-[#0d1622] px-4 py-3 mb-3">
                        <summary class="faq-summary flex min-h-8 cursor-pointer items-center justify-between gap-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-red">Mabar &amp; Sesi</span>
                                <strong class="block font-heading text-sm text-white sm:text-[15px]">Bagaimana cara menemukan lawan scrim?</strong>
                            </span>
                            <i class="faq-caret ph-bold ph-caret-down shrink-0 text-base text-red transition-transform" aria-hidden="true"></i>
                        </summary>
                        <p class="pt-3 text-xs leading-5 text-muted scrim-quote-box">Pilih Cari Lawan Scrim dari navigasi utama. Kamu dapat melihat tim yang sedang mencari lawan, memeriksa detail pertandingan, lalu mengirim tantangan jika format dan levelnya sesuai.</p>
                    </details>

                    <details class="faq-item rounded-md border border-border bg-[#0d1622] px-4 py-3 mb-3">
                        <summary class="faq-summary flex min-h-8 cursor-pointer items-center justify-between gap-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-red">Mabar &amp; Sesi</span>
                                <strong class="block font-heading text-sm text-white sm:text-[15px]">Informasi apa yang perlu disiapkan untuk scrim?</strong>
                            </span>
                            <i class="faq-caret ph-bold ph-caret-down shrink-0 text-base text-red transition-transform" aria-hidden="true"></i>
                        </summary>
                        <p class="pt-3 text-xs leading-5 text-muted scrim-quote-box">Siapkan nama tim, game atau olahraga yang dimainkan, level pertandingan, jadwal, lokasi jika diperlukan, dan catatan aturan yang ingin disepakati bersama.</p>
                    </details>

                    <details id="turnamen" class="faq-item rounded-md border border-border bg-[#0d1622] px-4 py-3 mb-3">
                        <summary class="faq-summary flex min-h-8 cursor-pointer items-center justify-between gap-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-red">Turnamen</span>
                                <strong class="block font-heading text-sm text-white sm:text-[15px]">Di mana saya bisa melihat turnamen yang tersedia?</strong>
                            </span>
                            <i class="faq-caret ph-bold ph-caret-down shrink-0 text-base text-red transition-transform" aria-hidden="true"></i>
                        </summary>
                        <p class="pt-3 text-xs leading-5 text-muted scrim-quote-box">Buka halaman Turnamen untuk melihat daftar kompetisi komunitas. Pilih turnamen untuk membaca detail penyelenggara, jadwal, lokasi, dan ketentuannya.</p>
                    </details>

                    <details id="keamanan" class="faq-item rounded-md border border-border bg-[#0d1622] px-4 py-3 mb-3">
                        <summary class="faq-summary flex min-h-8 cursor-pointer items-center justify-between gap-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                            <span>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-red">Keamanan</span>
                                <strong class="block font-heading text-sm text-white sm:text-[15px]">Bagaimana menjaga interaksi tetap sportif?</strong>
                            </span>
                            <i class="faq-caret ph-bold ph-caret-down shrink-0 text-base text-red transition-transform" aria-hidden="true"></i>
                        </summary>
                        <p class="pt-3 text-xs leading-5 text-muted scrim-quote-box">Sampaikan informasi dengan jujur, hormati kesepakatan jadwal, dan perlakukan pemain lain dengan baik. Jika terjadi masalah, gunakan informasi sesi dan profil sebagai konteks saat meminta bantuan.</p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../include/footer.php'; ?>
