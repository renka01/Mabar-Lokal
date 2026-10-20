<?php
/**
 * config/gameAndolaraga_fields.php
 * Definisi field dinamis per game dan olahraga untuk form Buat Sesi & Detail Sesi.
 * Dilengkapi dengan batasan 'min' dan 'max' pada input angka agar terhindar dari
 * manipulasi data (angka minus atau angka berlebih).
 */

$GAME_FIELDS = [

  // ─────────────────────── ESPORTS ───────────────────────

  'Mobile Legends' => [
    [
      'name'    => 'rank',
      'label'   => 'Rank',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Warrior', 'Elite', 'Master', 'Grandmaster', 'Epic', 'Legend', 'Mythic', 'Mythical Glory'],
    ],
    [
      'name'    => 'role',
      'label'   => 'Role Hero',
      'type'    => 'select',
      'icon'    => 'ph-sword',
      'options' => ['Semua Role', 'Tank', 'Fighter', 'Assassin', 'Mage', 'Marksman', 'Support'],
    ],
    [
      'name'        => 'server_id',
      'label'       => 'ID Server (opsional)',
      'type'        => 'text',
      'icon'        => 'ph-identification-card',
      'placeholder' => 'Contoh: 123456789 (1234)',
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 5,
      'placeholder' => '2 – 5',
    ],
  ],

  'Honor of Kings' => [
    [
      'name'    => 'rank',
      'label'   => 'Rank',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Warrior', 'Elite', 'Master', 'Grandmaster', 'Epic', 'Legend', 'Mythic', 'King'],
    ],
    [
      'name'    => 'role',
      'label'   => 'Role Hero',
      'type'    => 'select',
      'icon'    => 'ph-sword',
      'options' => ['Semua Role', 'Tank', 'Fighter', 'Assassin', 'Mage', 'Marksman', 'Support'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 5,
      'placeholder' => '2 – 5',
    ],
  ],

  'Valorant' => [
    [
      'name'    => 'rank',
      'label'   => 'Rank',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Ascendant', 'Immortal', 'Radiant'],
    ],
    [
      'name'    => 'agent_role',
      'label'   => 'Role Agent',
      'type'    => 'select',
      'icon'    => 'ph-sword',
      'options' => ['Semua Role', 'Duelist', 'Initiator', 'Controller', 'Sentinel'],
    ],
    [
      'name'    => 'server',
      'label'   => 'Server',
      'type'    => 'select',
      'icon'    => 'ph-globe',
      'options' => ['Asia (SG)', 'SEA', 'Indonesia'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 5,
      'placeholder' => '2 – 5',
    ],
  ],

  'PUBG Mobile' => [
    [
      'name'    => 'tier',
      'label'   => 'Tier',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Crown', 'Ace', 'Conqueror'],
    ],
    [
      'name'    => 'squad_type',
      'label'   => 'Mode Squad',
      'type'    => 'select',
      'icon'    => 'ph-users-three',
      'options' => ['Solo', 'Duo', 'Squad'],
    ],
    [
      'name'    => 'map',
      'label'   => 'Map',
      'type'    => 'select',
      'icon'    => 'ph-map-trifold',
      'options' => ['Semua Map', 'Erangel', 'Miramar', 'Sanhok', 'Vikendi', 'Livik'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 4,
      'placeholder' => '2 – 4',
    ],
  ],

  'PUBG PC' => [
    [
      'name'    => 'rank',
      'label'   => 'Rank',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond', 'Master'],
    ],
    [
      'name'    => 'squad_type',
      'label'   => 'Mode',
      'type'    => 'select',
      'icon'    => 'ph-users-three',
      'options' => ['Solo', 'Duo', 'Squad'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 4,
      'placeholder' => '2 – 4',
    ],
  ],

  'Dota 2' => [
    [
      'name'    => 'rank',
      'label'   => 'Medal',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Herald', 'Guardian', 'Crusader', 'Archon', 'Legend', 'Ancient', 'Divine', 'Immortal'],
    ],
    [
      'name'    => 'role',
      'label'   => 'Role',
      'type'    => 'select',
      'icon'    => 'ph-sword',
      'options' => ['Carry', 'Midlaner', 'Offlaner', 'Soft Support', 'Hard Support'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 5,
      'placeholder' => '2 – 5',
    ],
  ],

  'Counter-Strike 2' => [
    [
      'name'    => 'rank',
      'label'   => 'Rank',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Silver I', 'Silver II', 'Silver Elite', 'Gold Nova', 'Master Guardian', 'Legendary Eagle', 'Supreme', 'Global Elite'],
    ],
    [
      'name'    => 'role',
      'label'   => 'Role',
      'type'    => 'select',
      'icon'    => 'ph-sword',
      'options' => ['Entry Fragger', 'AWPer', 'Lurker', 'Support', 'In-Game Leader'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 5,
      'placeholder' => '2 – 5',
    ],
  ],

  'League of Legends' => [
    [
      'name'    => 'rank',
      'label'   => 'Rank',
      'type'    => 'select',
      'icon'    => 'ph-medal',
      'options' => ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Emerald', 'Diamond', 'Master', 'Grandmaster', 'Challenger'],
    ],
    [
      'name'    => 'role',
      'label'   => 'Role',
      'type'    => 'select',
      'icon'    => 'ph-sword',
      'options' => ['Top', 'Jungle', 'Mid', 'ADC', 'Support'],
    ],
    [
      'name'    => 'server',
      'label'   => 'Server',
      'type'    => 'select',
      'icon'    => 'ph-globe',
      'options' => ['SEA', 'GARENA ID', 'Korea', 'North America'],
    ],
    [
      'name'        => 'kebutuhan_slot',
      'label'       => 'Kebutuhan Slot',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 5,
      'placeholder' => '2 – 5',
    ],
  ],

  // ─────────────────────── OLAHRAGA ───────────────────────

  'Futsal' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Semi-Pro', 'Profesional'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama Lapangan / Venue',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: GOR Serbaguna Kota',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 10,
      'placeholder' => '2 – 10',
    ],
  ],

  'Basket' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Semi-Pro'],
    ],
    [
      'name'    => 'tipe_permainan',
      'label'   => 'Tipe Permainan',
      'type'    => 'select',
      'icon'    => 'ph-basketball',
      'options' => ['3 on 3', '5 on 5', 'Fun Game'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama Lapangan / Venue',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: Lapangan Basket GOR',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 10,
      'placeholder' => '2 – 10',
    ],
  ],

  'Badminton' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Semi-Pro', 'Profesional'],
    ],
    [
      'name'    => 'tipe',
      'label'   => 'Tipe Permainan',
      'type'    => 'select',
      'icon'    => 'ph-users',
      'options' => ['Tunggal (Single)', 'Ganda (Double)', 'Mix Double'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama GOR / Venue',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: GOR Badminton Sejahtera',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 4,
      'placeholder' => '2 – 4',
    ],
  ],

  'Bulu Tangkis' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Semi-Pro', 'Profesional'],
    ],
    [
      'name'    => 'tipe',
      'label'   => 'Tipe Permainan',
      'type'    => 'select',
      'icon'    => 'ph-users',
      'options' => ['Tunggal (Single)', 'Ganda (Double)', 'Mix Double'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama GOR / Venue',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: GOR Badminton Sejahtera',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 4,
      'placeholder' => '2 – 4',
    ],
  ],

  'Sepak Bola' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Semi-Pro'],
    ],
    [
      'name'    => 'format',
      'label'   => 'Format',
      'type'    => 'select',
      'icon'    => 'ph-users-three',
      'options' => ['7 vs 7', '8 vs 8', '11 vs 11'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama Lapangan',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: Lapangan Gelora Bung Karno',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 22,
      'placeholder' => '2 – 22',
    ],
  ],

  'Tenis' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Mahir'],
    ],
    [
      'name'    => 'tipe',
      'label'   => 'Tipe',
      'type'    => 'select',
      'icon'    => 'ph-users',
      'options' => ['Single', 'Double'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama Court / Venue',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: Tennis Court ABC',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 4,
      'placeholder' => '2 – 4',
    ],
  ],

  'Voli' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Semi-Pro'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama Lapangan',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: Lapangan Voli RW 07',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 12,
      'placeholder' => '2 – 12',
    ],
  ],

  'Biliar' => [
    [
      'name'    => 'level',
      'label'   => 'Level Bermain',
      'type'    => 'select',
      'icon'    => 'ph-chart-bar',
      'options' => ['Pemula', 'Menengah', 'Mahir'],
    ],
    [
      'name'    => 'game_type',
      'label'   => 'Tipe Game',
      'type'    => 'select',
      'icon'    => 'ph-circle',
      'options' => ['8-Ball', '9-Ball', '10-Ball'],
    ],
    [
      'name'        => 'venue',
      'label'       => 'Nama Pool / Venue',
      'type'        => 'text',
      'icon'        => 'ph-map-pin',
      'placeholder' => 'Contoh: Masterpool Arena',
    ],
    [
      'name'        => 'jumlah_pemain',
      'label'       => 'Kebutuhan Pemain',
      'type'        => 'number',
      'icon'        => 'ph-users',
      'min'         => 2,
      'max'         => 4,
      'placeholder' => '2 – 4',
    ],
  ],

];
