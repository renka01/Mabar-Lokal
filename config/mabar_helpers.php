<?php
if (!function_exists('mabarGameIcon')) {
    function mabarGameIcon(string $game, string $category = 'esports'): string
    {
        $map = [
            'mobile legends'    => 'asset/icon/ml-logo.jpeg',
            'valorant'          => 'asset/icon/Valoran1.svg',
            'pubg mobile'       => 'asset/icon/pubgm-logo.jpeg',
            'pubg pc'           => 'asset/icon/pubg-pc-logo.jpeg',
            'dota 2'            => 'asset/icon/dota2-logo.jpeg',
            'counter-strike 2'  => 'asset/icon/cs2-logo.jpeg',
            'cs2'               => 'asset/icon/cs2-logo.jpeg',
            'league of legends' => 'asset/icon/lol-logo.jpeg',
            'honor of kings'    => 'asset/icon/hok-logo.png',
            'futsal'            => 'asset/icon/Futsal.png',
            'basket'            => 'asset/icon/Basket.png',
            'badminton'         => 'asset/icon/Bulu Tangkis.png',
            'bulu tangkis'      => 'asset/icon/Bulu Tangkis.png',
            'sepak bola'        => 'asset/icon/sepak-bola.svg',
            'mini soccer'       => 'asset/icon/sepak-bola.svg',
            'tenis'             => 'asset/icon/tenis.svg',
            'tenis lapangan'    => 'asset/icon/tenis.svg',
            'voli'              => 'asset/icon/voli.svg',
            'biliar'            => 'asset/icon/Billiard.png',
            'billiard'          => 'asset/icon/Billiard.png',
            'padel'             => 'asset/icon/padel.png',
            'lari'              => 'asset/icon/lARI.png',
        ];

        $key = strtolower(trim($game));
        if (isset($map[$key])) {
            return $map[$key];
        }
        foreach ($map as $k => $icon) {
            if ($key !== '' && str_contains($key, $k)) {
                return $icon;
            }
        }

        return $category === 'olahraga' ? 'asset/icon/Futsal.png' : 'asset/icon/Valoran1.svg';
    }
}

if (!function_exists('mabarGameCapacity')) {
    function mabarGameCapacity(string $game, string $category = 'esports'): int
    {
        $map = [
            'mobile legends'    => 5,
            'honor of kings'    => 5,
            'valorant'          => 5,
            'pubg mobile'       => 4,
            'pubg pc'           => 4,
            'free fire'         => 4,
            'dota 2'            => 5,
            'counter-strike 2'  => 5,
            'cs2'               => 5,
            'league of legends' => 5,
            'apex legends'      => 3,
            'futsal'            => 10,
            'mini soccer'       => 14,
            'sepak bola'        => 22,
            'basket'            => 10,
            'badminton'         => 4,
            'bulu tangkis'      => 4,
            'tenis'             => 8,
            'tenis lapangan'    => 8,
            'tenis meja'        => 4,
            'voli'              => 12,
            'biliar'            => 4,
            'billiard'          => 4,
            'padel'             => 4,
            'lari'              => 12,
        ];

        $key = strtolower(trim($game));
        if (isset($map[$key])) {
            return $map[$key];
        }
        foreach ($map as $k => $cap) {
            if ($key !== '' && str_contains($key, $k)) {
                return $cap;
            }
        }

        return $category === 'olahraga' ? 10 : 5;
    }
}

if (!function_exists('mabarFormatSessionCard')) {
    function mabarFormatSessionCard(array $row): array
    {
        $category = (string) ($row['category'] ?? 'esports');
        $game     = (string) ($row['game'] ?? 'Valorant');

        $rules = [];
        if (!empty($row['rules_json'])) {
            $decoded = json_decode((string) $row['rules_json'], true);
            if (is_array($decoded)) {
                $rules = array_values(array_filter(array_map('strval', $decoded)));
            }
        }

        $specs = [];
        if (!empty($row['specs_json'])) {
            $decoded = json_decode((string) $row['specs_json'], true);
            if (is_array($decoded)) {
                $specs = $decoded;
            }
        }

        return [
            'id'           => (int) $row['id'],
            'slug'         => (string) $row['slug'],
            'host_user_id' => (int) $row['host_user_id'],
            'host_name'    => (string) ($row['host_name'] ?? 'Host'),
            'category'     => $category,
            'game'         => $game,
            'icon'         => mabarGameIcon($game, $category),
            'title'        => (string) $row['title'],
            'description'  => (string) ($row['description'] ?? ''),
            'city'         => (string) ($row['city'] ?? 'Online'),
            'place'        => (string) ($row['place_label'] ?? ($row['city'] ?? 'Online')),
            'date'         => (string) ($row['schedule_label'] ?: (($row['start_time'] ?? '19:00') . ' - ' . ($row['end_time'] ?? '22:00') . ' WIB')),
            'start_time'   => (string) ($row['start_time'] ?? '19:00'),
            'end_time'     => (string) ($row['end_time'] ?? '22:00'),
            'mode'         => (string) ($row['mode'] ?? 'Ranked'),
            'join_rule'    => (string) ($row['join_rule'] ?? 'Ask For Join'),
            'slot'         => (int) ($row['filled_slot'] ?? 0),
            'maxSlot'      => (int) ($row['max_slot'] ?? 5),
            'is_featured'  => filter_var($row['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'status'       => (string) ($row['status'] ?? 'open'),
            'rules'        => $rules,
            'specs'        => $specs,
            'avatars'      => isset($row['avatars']) && is_array($row['avatars']) ? $row['avatars'] : [],
        ];
    }
}

if (!function_exists('mabarFindSession')) {
    function mabarFindSession(PDO $pdo, string $identifier): ?array
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        $sqlBase = "
            SELECT s.*,
                   u.name AS host_name,
                   u.username_tag AS host_tag,
                   u.avatar_path AS host_avatar,
                   (SELECT COUNT(*) FROM mabar_participants mp WHERE mp.session_id = s.id AND mp.role IN ('host', 'member')) AS filled_slot
            FROM mabar_sessions s
            JOIN users u ON u.id = s.host_user_id
        ";

        if (ctype_digit($identifier)) {
            $stmt = $pdo->prepare($sqlBase . ' WHERE s.id = ? OR s.slug = ? LIMIT 1');
            $stmt->execute([(int) $identifier, $identifier]);
        } else {
            $stmt = $pdo->prepare($sqlBase . ' WHERE s.slug = ? LIMIT 1');
            $stmt->execute([$identifier]);
        }

        $row = $stmt->fetch();
        return $row ?: null;
    }
}

if (!function_exists('mabarFetchParticipants')) {
    function mabarFetchParticipants(PDO $pdo, int $sessionId): array
    {
        $stmt = $pdo->prepare("
            SELECT mp.id AS participant_id,
                   mp.session_id,
                   mp.user_id,
                   mp.role,
                   mp.rank_label,
                   mp.is_ready,
                   mp.joined_at,
                   u.name,
                   u.username_tag,
                   u.city,
                   u.bio,
                   u.avatar_path,
                   u.pref_games_json
            FROM mabar_participants mp
            JOIN users u ON u.id = mp.user_id
            WHERE mp.session_id = ? AND mp.role IN ('host', 'member')
            ORDER BY CASE WHEN mp.role = 'host' THEN 0 ELSE 1 END, mp.joined_at ASC, mp.id ASC
        ");
        $stmt->execute([$sessionId]);
        $rows = $stmt->fetchAll();

        $result = [];
        foreach ($rows as $r) {
            $games = [];
            if (!empty($r['pref_games_json'])) {
                $decoded = json_decode((string) $r['pref_games_json'], true);
                if (is_array($decoded)) {
                    $games = array_values(array_filter(array_map('strval', $decoded)));
                }
            }
            $result[] = [
                'participant_id' => (int) $r['participant_id'],
                'user_id'        => (int) $r['user_id'],
                'role'           => (string) $r['role'],
                'isHost'         => $r['role'] === 'host',
                'isLeader'       => $r['role'] === 'host',
                'isReady'        => filter_var($r['is_ready'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'nama'           => (string) $r['name'],
                'username_tag'   => (string) $r['username_tag'],
                'rank'           => (string) ($r['rank_label'] ?: 'Pemain Komunitas'),
                'city'           => (string) ($r['city'] ?: 'Malang'),
                'bio'            => (string) ($r['bio'] ?: ''),
                'avatar'         => (string) ($r['avatar_path'] ?: ''),
                'pref_games'     => $games,
            ];
        }

        return $result;
    }
}

if (!function_exists('mabarSyncSessionStatus')) {
    function mabarSyncSessionStatus(PDO $pdo, int $sessionId): void
    {
        $stmt = $pdo->prepare("
            SELECT s.max_slot, s.status,
                   (SELECT COUNT(*) FROM mabar_participants mp WHERE mp.session_id = s.id AND mp.role IN ('host', 'member')) AS filled
            FROM mabar_sessions s
            WHERE s.id = ?
            LIMIT 1
        ");
        $stmt->execute([$sessionId]);
        $row = $stmt->fetch();
        if (!$row) {
            return;
        }
        if (in_array($row['status'], ['finished', 'cancelled'], true)) {
            return;
        }

        $nextStatus = ((int) $row['filled'] >= (int) $row['max_slot']) ? 'full' : 'open';
        if ($nextStatus !== $row['status']) {
            $pdo->prepare('UPDATE mabar_sessions SET status = ? WHERE id = ?')
                ->execute([$nextStatus, $sessionId]);
        }
    }
}

if (!function_exists('mabarCreateNotification')) {
    function mabarCreateNotification(
        PDO $pdo,
        int $userId,
        string $category,
        string $title,
        string $message,
        ?string $actionLabel = null,
        ?string $href = null
    ): void {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO notifications (user_id, category, title, message, action_label, href, is_read)
                VALUES (?, ?, ?, ?, ?, ?, FALSE)
            ');
            $stmt->execute([$userId, $category, $title, $message, $actionLabel, $href]);
        } catch (Throwable $e) {
        }
    }
}
