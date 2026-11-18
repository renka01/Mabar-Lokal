<?php
require_once __DIR__ . '/mabar_helpers.php';

if (!function_exists('scrimFormatLabelFromShort')) {
    function scrimFormatLabelFromShort(string $short): string
    {
        $map = [
            'BO1'   => 'BO1 (Best of 1)',
            'BO3'   => 'BO3 (Best of 3 - Turnamen)',
            'BO5'   => 'BO5 (Best of 5 - Final)',
            '2x20M' => '2 x 20 Menit (Lapangan Vinyl)',
            '2x25M' => '2 x 25 Menit (Futsal / Mini Soccer)',
            '3x20M' => '3 x 20 Menit (Mini Soccer)',
            '4 QTR' => '4 Quarter x 10 Menit (Basket)',
            '3 SET' => '3 Set 21 Poin (Badminton / Voli)',
        ];
        return $map[$short] ?? $short;
    }
}

if (!function_exists('scrimFormatRow')) {
    function scrimFormatRow(array $row): array
    {
        $category = strtolower((string) ($row['category'] ?? 'esports'));
        $game     = (string) ($row['game'] ?? 'Valorant');

        return [
            'db_id'                => (int) $row['id'],
            'id'                   => (string) $row['slug'],
            'slug'                 => (string) $row['slug'],
            'creator_user_id'      => (int) $row['creator_user_id'],
            'creator_avatar'       => (string) ($row['creator_avatar'] ?? ''),
            'kategori'             => $category,
            'game'                 => $game,
            'game_icon'            => mabarGameIcon($game, $category),
            'nama_tim'             => (string) $row['team_name'],
            'logo_initial'         => (string) ($row['logo_initial'] ?: 'TM'),
            'tier'                 => (string) ($row['tier'] ?: 'Menengah / Komunitas'),
            'tier_badge'           => (string) ($row['tier_badge'] ?: 'Kompetitif'),
            'tier_color'           => (string) ($row['tier_color'] ?: ($category === 'olahraga' ? 'green' : 'red')),
            'format'               => (string) ($row['format_label'] ?: $row['format_short']),
            'format_short'         => (string) ($row['format_short'] ?: 'BO3'),
            'jadwal'               => (string) ($row['schedule_label'] ?: 'Hari ini, 20:30 WIB'),
            'hari_kategori'        => (string) ($row['day_category'] ?: 'hari_ini'),
            'tipe_match'           => (string) ($row['match_type'] ?: ($category === 'olahraga' ? 'offline' : 'online')),
            'lokasi'               => (string) ($row['location_label'] ?: $row['city']),
            'kota'                 => (string) ($row['city'] ?: 'Malang'),
            'kapten'               => (string) ($row['captain_name'] ?: ($row['creator_name'] ?? 'Kapten')),
            'kapten_rank'          => (string) ($row['captain_rank'] ?: 'Kapten Tim'),
            'catatan'              => (string) ($row['notes'] ?? ''),
            'status'               => (string) ($row['status'] ?? 'open'),
            'is_featured'          => filter_var($row['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'challenger_user_id'   => $row['challenger_user_id'] !== null ? (int) $row['challenger_user_id'] : null,
            'challenger_name'      => (string) ($row['challenger_user_name'] ?? ''),
            'challenger_avatar'    => (string) ($row['challenger_avatar'] ?? ''),
            'challenger_team_name' => (string) ($row['challenger_team_name'] ?? ''),
            'challenger_contact'   => (string) ($row['challenger_contact'] ?? ''),
            'challenger_message'   => (string) ($row['challenger_message'] ?? ''),
            'host_ready'           => filter_var($row['host_ready'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'challenger_ready'     => filter_var($row['challenger_ready'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'room_id'              => (string) ($row['room_id'] ?? ''),
            'room_password'        => (string) ($row['room_password'] ?? ''),
        ];
    }
}

if (!function_exists('scrimFetchAllActive')) {
    function scrimFetchAllActive(PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT sm.*,
                   u1.name AS creator_name,
                   u1.avatar_path AS creator_avatar,
                   u2.name AS challenger_user_name,
                   u2.avatar_path AS challenger_avatar
            FROM scrim_matches sm
            JOIN users u1 ON u1.id = sm.creator_user_id
            LEFT JOIN users u2 ON u2.id = sm.challenger_user_id
            WHERE sm.status IN ('open', 'matched', 'confirmed')
            ORDER BY sm.is_featured DESC, sm.created_at DESC, sm.id DESC
        ");
        return array_map('scrimFormatRow', $stmt->fetchAll());
    }
}

if (!function_exists('scrimFindByKey')) {
    function scrimFindByKey(PDO $pdo, string $key): ?array
    {
        $key = trim($key);
        if ($key === '') {
            return null;
        }
        $sql = "
            SELECT sm.*,
                   u1.name AS creator_name,
                   u1.avatar_path AS creator_avatar,
                   u2.name AS challenger_user_name,
                   u2.avatar_path AS challenger_avatar
            FROM scrim_matches sm
            JOIN users u1 ON u1.id = sm.creator_user_id
            LEFT JOIN users u2 ON u2.id = sm.challenger_user_id
        ";
        if (ctype_digit($key)) {
            $stmt = $pdo->prepare($sql . ' WHERE sm.id = ? OR sm.slug = ? LIMIT 1');
            $stmt->execute([(int) $key, $key]);
        } else {
            $stmt = $pdo->prepare($sql . ' WHERE sm.slug = ? LIMIT 1');
            $stmt->execute([$key]);
        }
        $row = $stmt->fetch();
        return $row ? scrimFormatRow($row) : null;
    }
}
