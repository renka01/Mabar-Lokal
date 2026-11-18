<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mabar_helpers.php';

$category      = strtolower(trim((string) ($_GET['category'] ?? '')));
$game          = strtolower(trim((string) ($_GET['game'] ?? '')));
$city          = strtolower(trim((string) ($_GET['city'] ?? '')));
$query         = strtolower(trim((string) ($_GET['q'] ?? '')));
$availableOnly = !empty($_GET['available_only']) && $_GET['available_only'] !== '0';
$limit         = max(1, min(50, (int) ($_GET['limit'] ?? 12)));
$offset        = max(0, (int) ($_GET['offset'] ?? 0));

try {
    $pdo = getDB();

    $where  = ["s.status IN ('open', 'full')"];
    $params = [];

    if (in_array($category, ['esports', 'olahraga'], true)) {
        $where[]  = 's.category = ?';
        $params[] = $category;
    }

    if ($game !== '') {
        $where[]  = 'LOWER(s.game) LIKE ?';
        $params[] = '%' . $game . '%';
    }

    if ($city !== '' && !str_contains($city, 'online') && !str_contains($city, 'semua')) {
        $where[]  = '(LOWER(s.city) LIKE ? OR LOWER(s.place_label) LIKE ?)';
        $params[] = '%' . $city . '%';
        $params[] = '%' . $city . '%';
    }

    if ($query !== '') {
        $where[]  = '(LOWER(s.title) LIKE ? OR LOWER(s.game) LIKE ? OR LOWER(s.place_label) LIKE ? OR LOWER(s.city) LIKE ? OR LOWER(u.name) LIKE ?)';
        $like     = '%' . $query . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $whereSql = implode(' AND ', $where);
    $availSql = $availableOnly
        ? "AND (SELECT COUNT(*) FROM mabar_participants mp2 WHERE mp2.session_id = s.id AND mp2.role IN ('host', 'member')) < s.max_slot"
        : '';

    $countSql = "
        SELECT COUNT(*)
        FROM mabar_sessions s
        JOIN users u ON u.id = s.host_user_id
        WHERE {$whereSql} {$availSql}
    ";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();

    $listSql = "
        SELECT s.*,
               u.name AS host_name,
               (SELECT COUNT(*) FROM mabar_participants mp WHERE mp.session_id = s.id AND mp.role IN ('host', 'member')) AS filled_slot
        FROM mabar_sessions s
        JOIN users u ON u.id = s.host_user_id
        WHERE {$whereSql} {$availSql}
        ORDER BY s.is_featured DESC, s.created_at DESC, s.id DESC
        LIMIT ? OFFSET ?
    ";
    $listParams   = array_merge($params, [$limit, $offset]);
    $listStmt     = $pdo->prepare($listSql);
    $listStmt->execute($listParams);
    $rows         = $listStmt->fetchAll();

    $sessionIds = array_map(fn($r) => (int) $r['id'], $rows);
    $avatarsMap = [];
    if (!empty($sessionIds)) {
        $placeholders = implode(',', array_fill(0, count($sessionIds), '?'));
        $avStmt = $pdo->prepare("
            SELECT mp.session_id, u.name, u.avatar_path
            FROM mabar_participants mp
            JOIN users u ON u.id = mp.user_id
            WHERE mp.session_id IN ({$placeholders}) AND mp.role IN ('host', 'member')
            ORDER BY CASE WHEN mp.role = 'host' THEN 0 ELSE 1 END, mp.joined_at ASC
        ");
        $avStmt->execute($sessionIds);
        foreach ($avStmt->fetchAll() as $avRow) {
            $sid = (int) $avRow['session_id'];
            if (!isset($avatarsMap[$sid])) {
                $avatarsMap[$sid] = [];
            }
            if (count($avatarsMap[$sid]) < 4) {
                $avatarsMap[$sid][] = [
                    'name'   => (string) $avRow['name'],
                    'avatar' => (string) ($avRow['avatar_path'] ?? ''),
                ];
            }
        }
    }

    $sessions = [];
    $featured = null;
    foreach ($rows as $r) {
        $r['avatars'] = $avatarsMap[(int) $r['id']] ?? [];
        $card = mabarFormatSessionCard($r);
        if ($featured === null && $card['is_featured']) {
            $featured = $card;
        }
        $sessions[] = $card;
    }

    if ($featured === null && count($sessions) >= 2) {
        $featured = $sessions[0];
    }

    $gridSessions = $sessions;
    if ($featured !== null && $total >= 2 && $offset === 0) {
        $gridSessions = array_values(array_filter(
            $sessions,
            fn($s) => $s['id'] !== $featured['id']
        ));
    }

    jsonSuccess('Daftar sesi berhasil dimuat.', [
        'total'    => $total,
        'has_more' => ($offset + count($rows)) < $total,
        'featured' => ($total >= 2) ? $featured : null,
        'sessions' => $gridSessions,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memuat daftar sesi dari PostgreSQL.', 500);
}
