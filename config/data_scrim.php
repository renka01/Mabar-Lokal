<?php
/**
 * config/data_scrim.php — Dataset Mock Open Scrim & Sparring MabarLokal
 * Berisi daftar tim yang sedang membuka tantangan scrim / sparring (Esports & Olahraga) di Jawa Timur.
 */

$daftarScrim = [
    // ══════════════════════════════════════════════════════
    // ESPORTS SCRIMS
    // ══════════════════════════════════════════════════════
    [
        'id'          => 'scrim-es-1',
        'kategori'    => 'esports',
        'game'        => 'Valorant',
        'game_icon'   => 'asset/icon/Valoran1.svg',
        'nama_tim'    => 'Surabaya Wolves Esports',
        'logo_initial'=> 'SW',
        'tier'        => 'Ascendant – Immortal',
        'tier_badge'  => 'Kompetitif',
        'tier_color'  => 'red',
        'format'      => 'BO3 (Competitive Custom)',
        'format_short'=> 'BO3',
        'jadwal'      => 'Hari ini, 20:30 WIB',
        'hari_kategori'=> 'hari_ini',
        'tipe_match'  => 'online',
        'lokasi'      => 'Online (Discord / SG Server)',
        'kota'        => 'Surabaya',
        'kapten'      => 'Rian Pratama',
        'kapten_rank' => 'Ascendant 2',
        'catatan'     => 'Cari tim disiplin, map pick ban standard VCT. Bawa 5 pemain inti (no toxic). Livestreaming diizinkan.',
        'status'      => 'open',
        'is_featured' => true,
    ],
    

    // ══════════════════════════════════════════════════════
    // OLAHRAGA (SPARRING LAPANGAN)
    // ══════════════════════════════════════════════════════
    [
        'id'          => 'scrim-ol-1',
        'kategori'    => 'olahraga',
        'game'        => 'Futsal',
        'game_icon'   => 'asset/icon/Futsal.png',
        'nama_tim'    => 'Kera Ngalam Futsal Club',
        'logo_initial'=> 'KN',
        'tier'        => 'Komunitas / Semi-Amatir',
        'tier_badge'  => 'Menengah',
        'tier_color'  => 'green',
        'format'      => '2 x 20 Menit (Lapangan Vinyl)',
        'format_short'=> '2x20M',
        'jadwal'      => 'Sabtu, 19:00 - 21:00 WIB',
        'hari_kategori'=> 'weekend',
        'tipe_match'  => 'offline',
        'lokasi'      => 'Viva Futsal Araya, Malang',
        'kota'        => 'Malang',
        'kapten'      => 'Eko Prasetyo',
        'kapten_rank' => 'Anchor / Eks Porprov',
        'catatan'     => 'Biaya sewa lapangan patungan 50:50. Wajib rompi/jersey beda warna, main keras boleh no kasar.',
        'status'      => 'open',
        'is_featured' => false,
    ],
   
];
