-- Password akun Wawan (wawanwrwn@gmail.com) : demopbl123
-- Password akun pemain lain (*@mabarlokal.id): password123

SET TIME ZONE 'Asia/Jakarta';

-- 1. USERS
INSERT INTO users
  (id, name, username_tag, email, password_hash, phone, city, bio, pref_category, pref_games_json, questionnaire_completed)
VALUES
  (
    1,
    'Wawan',
    'wnwnwnrwn',
    'wawanwrwn@gmail.com',
    '$2y$10$cwFYX4OkPnFj7VSjGJcBHuSwFbZ0.C.0tDi5SkuLhJgpqdyTTXPWq',
    '082312345555',
    'Kota Malang',
    'Main santai, komunikasi aktif, wajib no toxic & siap koordinasi tim!',
    'keduanya',
    '["Valorant","Mobile Legends","Futsal"]',
    TRUE
  ),
  (
    2,
    'Rian Pratama',
    'rianpratama',
    'rian@mabarlokal.id',
    '$2y$10$/iRs8o9uEISQRau2BMFJe.56nK33Uj.PH7nMrbnobuizdts9XQQQy',
    '081234560002',
    'Kota Surabaya',
    'Kapten Surabaya Wolves Esports. Fokus scrim Valorant & turnamen Jatim.',
    'esports',
    '["Valorant","CS2"]',
    TRUE
  ),
  (
    3,
    'Eko Prasetyo',
    'ekoprasetyo',
    'eko@mabarlokal.id',
    '$2y$10$/iRs8o9uEISQRau2BMFJe.56nK33Uj.PH7nMrbnobuizdts9XQQQy',
    '081334560003',
    'Kota Malang',
    'Koordinator sparring futsal & tenis lapangan area Malang Raya.',
    'olahraga',
    '["Futsal","Tenis"]',
    TRUE
  ),
  (
    4,
    'Amba',
    'amba',
    'amba@mabarlokal.id',
    '$2y$10$/iRs8o9uEISQRau2BMFJe.56nK33Uj.PH7nMrbnobuizdts9XQQQy',
    '085234560004',
    'Kota Malang',
    'Duelist Valorant & support push rank malam minggu.',
    'esports',
    '["Valorant","Mobile Legends"]',
    TRUE
  ),
  (
    5,
    'Faren',
    'faren',
    'faren@mabarlokal.id',
    '$2y$10$/iRs8o9uEISQRau2BMFJe.56nK33Uj.PH7nMrbnobuizdts9XQQQy',
    '087834560005',
    'Kota Malang',
    'Rutin futsal Araya tiap akhir pekan & ranked santai.',
    'keduanya',
    '["Futsal","Valorant"]',
    TRUE
  )
ON CONFLICT (id) DO NOTHING;

-- 2. MABAR SESSIONS & PARTICIPANTS
INSERT INTO mabar_sessions
  (id, slug, host_user_id, category, game, title, description, city, place_label, schedule_label, start_time, end_time, mode, join_rule, max_slot, rules_json, is_featured, status)
VALUES
  (
    1,
    'featured',
    2,
    'esports',
    'Valorant',
    'Ranked Duelist',
    '-2 rank Plat - Diamond, main duelist. Paling engga paham entry site. Playstyle menyesuaikan in-game. Kalo cocok gas push sampe immortal.',
    'Malang',
    'Online (Server Indo)',
    'Hari ini, 20:00 WIB',
    '19:30',
    '22:30',
    'Ranked',
    'Ask For Join',
    5,
    '["No Toxic & No Blaming","AFK / Cancel Sepihak Kick Langsung","Wajib Open Mic Discord"]',
    TRUE,
    'open'
  ),
  (
    2,
    'es-1',
    4,
    'esports',
    'Valorant',
    'Ranked Push Immortal',
    'Push rank serius tapi santai, komunikasi aktif via Discord MabarLokal.',
    'Online',
    'Online (Server Indo)',
    'Hari ini, 20:00 WIB',
    '20:00',
    '23:00',
    'Ranked',
    'Ask For Join',
    5,
    '["No Toxic & No Blaming","Min Rank Ascendant","Wajib Konfirmasi Kehadiran"]',
    FALSE,
    'open'
  ),
  (
    3,
    'es-2',
    2,
    'esports',
    'Valorant',
    'Ranked Trio Diamond - Ascendant',
    'Butuh controller/initiator disiplin untuk push trio malam besok.',
    'Online',
    'Online (Discord Mabar)',
    'Besok, 19:30 WIB',
    '19:30',
    '22:00',
    'Ranked',
    'Langsung Gabung',
    5,
    '["Komunikasi Jelas","Tidak Rage Quit"]',
    FALSE,
    'open'
  ),
  (
    4,
    'featured-sport',
    3,
    'olahraga',
    'Tenis',
    'Tennis Sunday Morning',
    'Sesi mabar tenis santai ganda 4 vs 4 di Lapangan Rampal. Raket cadangan & bola sudah disiapkan. Level pemula sampai menengah dipersilakan!',
    'Malang',
    'Lapangan Rampal, Malang',
    'Minggu, 06:30 WIB',
    '06:30',
    '09:00',
    'Fun Game',
    'Langsung Gabung',
    8,
    '["Datang 15 Menit Sebelum Mulai","Sepatu Lapangan Wajib Dipakai","Sportif & Saling Menghargai"]',
    TRUE,
    'open'
  ),
  (
    5,
    'ol-1',
    3,
    'olahraga',
    'Tenis',
    'Tennis Pagi Pagi',
    'Latihan rally dan main ganda santai akhir pekan di Lapangan Rampal.',
    'Malang',
    'Lapangan Rampal, Malang',
    'Minggu, 07:00 WIB',
    '07:00',
    '09:30',
    'Fun Game',
    'Ask For Join',
    8,
    '["Bawa Minum Pribadi","Patungan Bola Lapangan"]',
    FALSE,
    'open'
  ),
  (
    6,
    'ol-2',
    5,
    'olahraga',
    'Tenis',
    'Mabar Tenis Ganda Putra',
    'Sparring ganda putra level menengah di Court Kodam V Brawijaya.',
    'Surabaya',
    'Court Kodam V Brawijaya, Surabaya',
    'Sabtu, 07:00 WIB',
    '07:00',
    '09:00',
    'Ganda Putra',
    'Ask For Join',
    4,
    '["On Time","Sportif"]',
    FALSE,
    'open'
  )
ON CONFLICT (id) DO NOTHING;

INSERT INTO mabar_participants (session_id, user_id, role, rank_label, is_ready)
VALUES
  (1, 2, 'host',   'Ascendant 2', TRUE),
  (1, 4, 'member', 'Diamond 3',   TRUE),
  (1, 1, 'member', 'Platinum 3',  FALSE),
  (2, 4, 'host',   'Immortal 1',  TRUE),
  (2, 2, 'member', 'Ascendant 3', TRUE),
  (2, 5, 'member', 'Diamond 2',   TRUE),
  (3, 2, 'host',   'Ascendant 2', TRUE),
  (3, 4, 'member', 'Diamond 3',   TRUE),
  (3, 1, 'member', 'Platinum 3',  TRUE),
  (3, 5, 'member', 'Diamond 1',   FALSE),
  (4, 3, 'host',   'Menengah',    TRUE),
  (4, 5, 'member', 'Menengah',    TRUE),
  (4, 1, 'member', 'Pemula+',     TRUE),
  (5, 3, 'host',   'Menengah',    TRUE),
  (5, 5, 'member', 'Menengah',    TRUE),
  (6, 5, 'host',   'Menengah',    TRUE),
  (6, 3, 'member', 'Menengah',    TRUE)
ON CONFLICT (session_id, user_id) DO NOTHING;

-- 3. SCRIM MATCHES
INSERT INTO scrim_matches
  (id, slug, creator_user_id, category, game, team_name, logo_initial, tier, tier_badge, tier_color, format_label, format_short, schedule_label, day_category, match_type, location_label, city, captain_name, captain_rank, notes, status, is_featured)
VALUES
  (
    1,
    'scrim-es-1',
    2,
    'esports',
    'Valorant',
    'Surabaya Wolves Esports',
    'SW',
    'Ascendant – Immortal',
    'Kompetitif',
    'red',
    'BO3 (Competitive Custom)',
    'BO3',
    'Hari ini, 20:30 WIB',
    'hari_ini',
    'online',
    'Online (Discord / SG Server)',
    'Surabaya',
    'Rian Pratama',
    'Ascendant 2',
    'Cari tim disiplin, map pick ban standard VCT. Bawa 5 pemain inti (no toxic). Livestreaming diizinkan.',
    'open',
    TRUE
  ),
  (
    2,
    'scrim-ol-1',
    3,
    'olahraga',
    'Futsal',
    'Kera Ngalam Futsal Club',
    'KN',
    'Komunitas / Semi-Amatir',
    'Menengah',
    'green',
    '2 x 20 Menit (Lapangan Vinyl)',
    '2x20M',
    'Sabtu, 19:00 - 21:00 WIB',
    'weekend',
    'offline',
    'Viva Futsal Araya, Malang',
    'Malang',
    'Eko Prasetyo',
    'Anchor / Eks Porprov',
    'Biaya sewa lapangan patungan 50:50. Wajib rompi/jersey beda warna, main keras boleh no kasar.',
    'open',
    FALSE
  )
ON CONFLICT (id) DO NOTHING;

-- 4. TOURNAMENTS, TEAMS, & MATCHES
INSERT INTO tournaments
  (id, slug, creator_user_id, title, game, category, status_label, status_badge, status_type, is_featured, city, location, schedule_text, prize_pool, fee_label, max_teams, format_label, organizer, game_icon, description, prize_distribution_json, winner_team, runner_up_team, final_score)
VALUES
  (
    1,
    'trn-val-01',
    2,
    'Valorant Champions Malang',
    'VALORANT',
    'esports',
    'BERLANGSUNG',
    'Babak Playoff',
    'live',
    TRUE,
    'Malang',
    'Cyber Cafe Gatsu & Discord Server',
    '10 - 12 Oktober 2026',
    'Rp5.000.000',
    'Gratis',
    16,
    'Single Elimination (BO3)',
    'Mabar Lokal Official',
    'asset/icon/Valoran1.svg',
    'Turnamen kompetitif resmi Valorant se-Malang Raya. Format map veto standar VCT, mode Tournament disiarkan langsung melalui YouTube stream.',
    '{"Juara 1":"Rp3.000.000 + E-Certificate","Juara 2":"Rp1.500.000 + E-Certificate","Juara 3":"Rp500.000"}',
    NULL,
    NULL,
    NULL
  ),
  (
    2,
    'trn-fut-03',
    3,
    'Futsal Inter-Community Malang',
    'FUTSAL',
    'olahraga',
    'BERLANGSUNG',
    'Fase Grup',
    'live',
    TRUE,
    'Malang',
    'Viva Futsal Arena, Lowokwaru',
    '18 - 19 Oktober 2026',
    'Rp3.500.000',
    'Rp150.000 / Tim',
    12,
    'Grup & Gugur (2 x 15 Menit)',
    'Komunitas Futsal Malang',
    'asset/icon/Futsal.png',
    'Turnamen futsal persahabatan antar komunitas olahraga di Malang. Standar lapangan vinyl internasional & wasit lisensi daerah.',
    '{"Juara 1":"Rp2.000.000 + Trophy","Juara 2":"Rp1.000.000 + Medali","Juara 3":"Rp500.000"}',
    NULL,
    NULL,
    NULL
  ),
  (
    3,
    'trn-ml-04',
    1,
    'Mabar Lokal Championship MLBB',
    'MOBILE LEGENDS',
    'esports',
    'AKAN DATANG',
    'Slot Terbuka',
    'upcoming',
    FALSE,
    'Malang',
    'Online Discord & In-Game Room',
    '24 Oktober 2026',
    'Rp5.000.000',
    'Gratis',
    16,
    'Single Elimination (BO3)',
    'Mabar Lokal Youth',
    'asset/icon/ml-logo.jpeg',
    'Kualifikasi terbuka untuk tim amatir & semi-pro. Registrasi terbuka hingga H-1 turnamen.',
    NULL,
    NULL,
    NULL,
    NULL
  ),
  (
    4,
    'trn-bad-05',
    3,
    'Badminton Ganda Putra Cup',
    'BULU TANGKIS',
    'olahraga',
    'AKAN DATANG',
    'Sisa 4 Slot',
    'upcoming',
    FALSE,
    'Batu',
    'GOR Ganesha Batu',
    '28 Oktober 2026',
    'Rp2.000.000',
    'Rp75.000 / Pasang',
    12,
    'Ganda Putra Rally Point 3x21',
    'PBSI Pengcab Batu',
    'asset/icon/Bulu Tangkis.png',
    'Kejuaraan bulu tangkis kategori ganda putra umum se-Malang Raya dan Batu. Shuttlecock disediakan panitia.',
    NULL,
    NULL,
    NULL,
    NULL
  ),
  (
    5,
    'trn-pub-06',
    2,
    'PUBG Mobile Squad Warfare',
    'PUBG MOBILE',
    'esports',
    'BERLANGSUNG',
    'Match 3 Erangel',
    'live',
    FALSE,
    'Surabaya',
    'Online Custom Server',
    '10 Oktober 2026',
    'Rp4.000.000',
    'Gratis',
    16,
    'Battle Royale Squad TPP (4 Match)',
    'Surabaya Gaming Guild',
    'asset/icon/pubgm-logo.jpeg',
    'Pertarungan sengit 16 tim squad memperebutkan poin eliminasi dan WWCD di peta Erangel & Miramar.',
    NULL,
    NULL,
    NULL,
    NULL
  ),
  (
    6,
    'trn-bsk-07',
    3,
    'Basket 3v3 Rampal Open',
    'BASKET',
    'olahraga',
    'BERLANGSUNG',
    'Babak Semifinal',
    'live',
    FALSE,
    'Malang',
    'Lapangan Luar Rampal Malang',
    '12 Oktober 2026',
    'Rp2.500.000',
    'Rp100.000 / Tim',
    12,
    'FIBA 3x3 Rules (10 Menit / 21 Pts)',
    'Perbasi Malang Streetball',
    'asset/icon/Basket.png',
    'Kompetisi bola basket 3 on 3 terbuka kategori umum. Wasit berlisensi resmi dan full sound system.',
    NULL,
    NULL,
    NULL,
    NULL
  ),
  (
    7,
    'trn-val-08',
    2,
    'Valorant Premier League Season 1',
    'VALORANT',
    'esports',
    'SELESAI',
    'Selesai',
    'done',
    FALSE,
    'Malang',
    'Cyber Cafe Gatsu',
    '02 Oktober 2026',
    'Rp5.000.000',
    'Gratis',
    12,
    'Single Elimination (BO3)',
    'Mabar Lokal Premier',
    'asset/icon/Valoran1.svg',
    'Astra Orion keluar sebagai juara setelah comeback sengit di map penentu Ascent melawan Surabaya Wolves.',
    NULL,
    'Astra Orion',
    'Surabaya Wolves',
    '2 - 1'
  ),
  (
    8,
    'trn-fut-09',
    3,
    'Futsal Fun Tournament GOR Ken Arok',
    'FUTSAL',
    'olahraga',
    'SELESAI',
    'Selesai',
    'done',
    FALSE,
    'Malang',
    'GOR Ken Arok Malang',
    '28 September 2026',
    'Rp3.000.000',
    'Rp100.000 / Tim',
    8,
    'Grup & Gugur',
    'Ken Arok Sports Center',
    'asset/icon/Futsal.png',
    'Garuda FC menjuarai kompetisi futsal bergengsi akhir pekan setelah mengalahkan Batu United dalam laga sengit bertempo tinggi.',
    NULL,
    'Garuda FC',
    'Batu United',
    '4 - 2'
  )
ON CONFLICT (id) DO NOTHING;

INSERT INTO tournament_teams (id, tournament_id, user_id, team_name, captain_name, captain_contact, city, roster_json, seed_label, status)
VALUES
  (1, 1, 2, 'Surabaya Wolves', 'Rian Pratama', '081234560002', 'Surabaya', '["Rian Pratama","Kenz","Nando","Bima","Raka"]', 'Seed #1', 'verified'),
  (2, 1, 4, 'Astra Orion',     'Amba',         '085234560004', 'Malang',   '["Amba","Vex","Kuro","Zed","Lio"]',              'Seed #2', 'verified'),
  (3, 2, 3, 'Kera Ngalam FC',  'Eko Prasetyo', '081334560003', 'Malang',   '["Eko","Bagas","Fajar","Dito","Reno"]',          'Grup A',  'verified'),
  (4, 2, 5, 'Garuda FC',       'Faren',        '087834560005', 'Malang',   '["Faren","Aldi","Gema","Ilham","Yoga"]',         'Grup A',  'verified')
ON CONFLICT (id) DO NOTHING;

INSERT INTO tournament_matches (id, tournament_id, round_name, match_order, team_a_name, team_b_name, score_a, score_b, winner_name, schedule_label, status)
VALUES
  (1, 1, 'Semifinal 1', 1, 'Surabaya Wolves', 'Malang Phantoms', 2, 0, 'Surabaya Wolves', '11 Okt, 19:00 WIB', 'finished'),
  (2, 1, 'Semifinal 2', 2, 'Astra Orion',     'Sidoarjo Rex',    2, 1, 'Astra Orion',     '11 Okt, 21:00 WIB', 'finished'),
  (3, 1, 'Grand Final', 3, 'Surabaya Wolves', 'Astra Orion',     1, 1, NULL,              '12 Okt, 20:00 WIB', 'live')
ON CONFLICT (id) DO NOTHING;

-- 5. CHAT THREADS & MESSAGES
INSERT INTO chat_threads (id, thread_key, user_one_id, user_two_id, subtitle)
VALUES
  (1, 'dm-1-4', 1, 4, 'Valorant • Ranked Push'),
  (2, 'dm-1-5', 1, 5, 'Futsal • Arena Araya'),
  (3, 'dm-1-2', 1, 2, 'Kapten Surabaya Wolves')
ON CONFLICT (id) DO NOTHING;

INSERT INTO chat_messages (id, thread_id, sender_user_id, body, is_read)
VALUES
  (1, 1, 4, 'Jam 8 Gas?', FALSE),
  (2, 2, 5, 'Jam 8 gas?', TRUE),
  (3, 2, 1, 'Ok, ak makan dlu', TRUE),
  (4, 3, 2, 'Room Discord scrim malam ini ready jam 20:15 ya.', TRUE)
ON CONFLICT (id) DO NOTHING;

-- 6. NOTIFICATIONS (User Wawan)
INSERT INTO notifications (id, user_id, category, title, message, action_label, href, action_type, meta_json, is_read)
VALUES
  (1, 1, 'scrim',    'Tantangan Scrim Diterima',            'Surabaya Wolves Esports siap bertanding Valorant (BO3) hari ini pukul 20:30 WIB.', 'Buka Ruang Tanding', 'pages/cari_lawan/detail.php?id=scrim-es-1&matched=1', NULL, NULL, FALSE),
  (2, 1, 'turnamen', 'Pendaftar Baru Menunggu Verifikasi',  '2 tim baru mendaftar di Valorant Champions Malang. Cek roster & verifikasi peserta.', 'Kelola Pendaftar', 'pages/turnamen/detail.php?id=trn-val-01&role=host', NULL, NULL, FALSE),
  (3, 1, 'mabar',    'Pengingat Jadwal Sesi Mabar',         'Sesi Ranked Duelist (Valorant • Malang) akan dimulai dalam 45 menit.', 'Masuk Lobby', 'pages/sesi/index.php?nama=Ranked+Duelist&game=Valorant&kota=Malang&joined=1', NULL, NULL, FALSE),
  (4, 1, 'chat',     'Pesan Baru dari Amba',                'Amba: "Jam 8 Gas?"', 'Balas di Chat', NULL, 'openChat', '{"chatTarget":"Amba","chatSubtitle":"Valorant • Ranked Push"}', FALSE),
  (5, 1, 'turnamen', 'Update Bagan Turnamen',               'Bagan Futsal Inter-Community Malang diperbarui. Cek jadwal pertandingan timmu.', 'Lihat Bagan', 'pages/turnamen/detail.php?id=trn-fut-03', NULL, NULL, TRUE),
  (6, 1, 'sistem',   'Kelengkapan Profil & Koordinasi',     'Pastikan nomor WhatsApp dan minat game di profil sudah sesuai agar koordinasi lebih cepat.', 'Cek Profil', 'pages/profile/index.php', NULL, NULL, TRUE)
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('users', 'id'),              COALESCE((SELECT MAX(id) FROM users), 1),              TRUE);
SELECT setval(pg_get_serial_sequence('mabar_sessions', 'id'),     COALESCE((SELECT MAX(id) FROM mabar_sessions), 1),     TRUE);
SELECT setval(pg_get_serial_sequence('mabar_participants', 'id'), COALESCE((SELECT MAX(id) FROM mabar_participants), 1), TRUE);
SELECT setval(pg_get_serial_sequence('scrim_matches', 'id'),      COALESCE((SELECT MAX(id) FROM scrim_matches), 1),      TRUE);
SELECT setval(pg_get_serial_sequence('tournaments', 'id'),        COALESCE((SELECT MAX(id) FROM tournaments), 1),        TRUE);
SELECT setval(pg_get_serial_sequence('tournament_teams', 'id'),   COALESCE((SELECT MAX(id) FROM tournament_teams), 1),   TRUE);
SELECT setval(pg_get_serial_sequence('tournament_matches', 'id'), COALESCE((SELECT MAX(id) FROM tournament_matches), 1), TRUE);
SELECT setval(pg_get_serial_sequence('chat_threads', 'id'),       COALESCE((SELECT MAX(id) FROM chat_threads), 1),       TRUE);
SELECT setval(pg_get_serial_sequence('chat_messages', 'id'),      COALESCE((SELECT MAX(id) FROM chat_messages), 1),      TRUE);
SELECT setval(pg_get_serial_sequence('notifications', 'id'),      COALESCE((SELECT MAX(id) FROM notifications), 1),      TRUE);
