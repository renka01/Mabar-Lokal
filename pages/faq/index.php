<?php
$pageTitle  = 'Pertanyaan Umum';
$basePath   = '../../';
$activePage = 'faq';
require_once __DIR__ . '/../../include/header.php';
?>

<style>
    .faq-summary {
        list-style: none;
    }

    .faq-summary::-webkit-details-marker {
        display: none;
    }

    .faq-item[open] .faq-caret {
        transform: rotate(180deg);
    }
</style>

<main class="flex-1 overflow-y-auto">
    <div class="mx-auto w-full px-4 py-8 lg:px-8 lg:py-12" style="max-width: 1152px;">
        <section class="rounded-lg border border-border bg-card px-6 py-10 sm:px-10 lg:px-16 lg:py-14">
            <div class="py-3" style="max-width: 768px;">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-red">Pertanyaan umum</p>
                <h1 class="font-heading text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">
                    Temukan jawaban sebelum mulai bermain.
                </h1>
                <p class="mt-6 text-sm leading-7 text-muted sm:text-base" style="max-width: 672px;">
                    Berikut jawaban untuk hal-hal yang paling penting saat mencari sesi, menemukan lawan
                    scrim, dan mengikuti turnamen di Mabar Lokal.
                </p>
            </div>
        </section>

        <section class="grid gap-10 py-16 lg:grid-cols-2 lg:gap-16 lg:py-20">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-red">Mulai dari sini</p>
                <h2 class="mt-3 font-heading text-2xl font-bold leading-tight text-white sm:text-3xl">
                    Jawaban singkat untuk pengalaman mabar yang lebih lancar.
                </h2>
                <p class="mt-5 text-sm leading-7 text-muted">
                    Buka pertanyaan yang ingin kamu ketahui. Semua jawaban di bawah ini mengikuti fitur
                    yang tersedia di aplikasi saat ini.
                </p>
            </div>

            <div class="space-y-3">
                <details class="faq-item rounded-md border border-border bg-card-alt p-5" open>
                    <summary class="faq-summary flex min-h-11 cursor-pointer items-center justify-between gap-5 font-heading font-bold text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                        <span>Bagaimana cara mencari teman mabar?</span>
                        <i class="faq-caret ph-bold ph-caret-down shrink-0 text-lg text-red transition-transform"></i>
                    </summary>
                    <p class="pt-4 text-sm leading-7 text-muted">
                        Buka halaman Cari Mabar, lalu gunakan pilihan game atau olahraga, kota, dan
                        waktu bermain untuk menemukan sesi yang paling sesuai. Setelah menemukan sesi,
                        kamu dapat melihat detailnya sebelum bergabung.
                    </p>
                </details>

                <details class="faq-item rounded-md border border-border bg-card-alt p-5">
                    <summary class="faq-summary flex min-h-11 cursor-pointer items-center justify-between gap-5 font-heading font-bold text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                        <span>Apakah saya bisa membuat sesi mabar sendiri?</span>
                        <i class="faq-caret ph-bold ph-caret-down shrink-0 text-lg text-red transition-transform"></i>
                    </summary>
                    <p class="pt-4 text-sm leading-7 text-muted">
                        Bisa. Buat sesi dengan menentukan aktivitas, lokasi, jadwal, dan jumlah pemain
                        yang dibutuhkan. Informasi yang jelas membantu pemain lain menemukan sesi kamu.
                    </p>
                </details>

                <details class="faq-item rounded-md border border-border bg-card-alt p-5">
                    <summary class="faq-summary flex min-h-11 cursor-pointer items-center justify-between gap-5 font-heading font-bold text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                        <span>Bagaimana cara menemukan lawan scrim?</span>
                        <i class="faq-caret ph-bold ph-caret-down shrink-0 text-lg text-red transition-transform"></i>
                    </summary>
                    <p class="pt-4 text-sm leading-7 text-muted">
                        Pilih Cari Lawan Scrim dari navigasi utama. Kamu dapat melihat tim yang sedang
                        mencari lawan, memeriksa detail pertandingan, lalu mengirim tantangan jika
                        format dan levelnya sesuai.
                    </p>
                </details>

                <details class="faq-item rounded-md border border-border bg-card-alt p-5">
                    <summary class="faq-summary flex min-h-11 cursor-pointer items-center justify-between gap-5 font-heading font-bold text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                        <span>Informasi apa yang perlu disiapkan untuk scrim?</span>
                        <i class="faq-caret ph-bold ph-caret-down shrink-0 text-lg text-red transition-transform"></i>
                    </summary>
                    <p class="pt-4 text-sm leading-7 text-muted">
                        Siapkan nama tim, game atau olahraga yang dimainkan, level pertandingan, jadwal,
                        lokasi jika diperlukan, dan catatan aturan yang ingin disepakati bersama.
                    </p>
                </details>

                <details class="faq-item rounded-md border border-border bg-card-alt p-5">
                    <summary class="faq-summary flex min-h-11 cursor-pointer items-center justify-between gap-5 font-heading font-bold text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                        <span>Di mana saya bisa melihat turnamen yang tersedia?</span>
                        <i class="faq-caret ph-bold ph-caret-down shrink-0 text-lg text-red transition-transform"></i>
                    </summary>
                    <p class="pt-4 text-sm leading-7 text-muted">
                        Buka halaman Turnamen untuk melihat daftar kompetisi komunitas. Pilih turnamen
                        untuk membaca detail penyelenggara, jadwal, lokasi, dan ketentuannya.
                    </p>
                </details>

                <details class="faq-item rounded-md border border-border bg-card-alt p-5">
                    <summary class="faq-summary flex min-h-11 cursor-pointer items-center justify-between gap-5 font-heading font-bold text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-red">
                        <span>Bagaimana menjaga interaksi tetap sportif?</span>
                        <i class="faq-caret ph-bold ph-caret-down shrink-0 text-lg text-red transition-transform"></i>
                    </summary>
                    <p class="pt-4 text-sm leading-7 text-muted">
                        Sampaikan informasi dengan jujur, hormati kesepakatan jadwal, dan perlakukan
                        pemain lain dengan baik. Jika terjadi masalah, gunakan informasi sesi dan profil
                        sebagai konteks saat meminta bantuan.
                    </p>
                </details>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../../include/footer.php'; ?>
