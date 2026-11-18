<?php
require_once __DIR__ . '/../../config/session.php';

$user = currentUser(true);
if (!$user) {
    jsonSuccess('Mode tamu.', [
        'authenticated' => false,
        'unread_count'  => 0,
        'notifications' => [],
    ]);
}

$uid = (int) $user['id'];

try {
    $pdo = getDB();

    $stmt = $pdo->prepare('
        SELECT id, category, title, message, action_label, href, action_type, meta_json, is_read, created_at
        FROM notifications
        WHERE user_id = ?
        ORDER BY created_at DESC, id DESC
        LIMIT 40
    ');
    $stmt->execute([$uid]);
    $rows = $stmt->fetchAll();

    $list        = [];
    $unreadCount = 0;

    foreach ($rows as $r) {
        $isRead = filter_var($r['is_read'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if (!$isRead) {
            $unreadCount++;
        }

        $meta = [];
        if (!empty($r['meta_json'])) {
            $decoded = json_decode((string) $r['meta_json'], true);
            if (is_array($decoded)) {
                $meta = $decoded;
            }
        }

        $ts = strtotime((string) $r['created_at']);
        $list[] = [
            'id'           => (int) $r['id'],
            'category'     => (string) $r['category'],
            'title'        => (string) $r['title'],
            'message'      => (string) $r['message'],
            'actionLabel'  => (string) ($r['action_label'] ?? ''),
            'href'         => (string) ($r['href'] ?? ''),
            'action'       => (string) ($r['action_type'] ?? ''),
            'chatTarget'   => (string) ($meta['chatTarget'] ?? ''),
            'chatSubtitle' => (string) ($meta['chatSubtitle'] ?? ''),
            'read'         => $isRead,
            'createdAt'    => $ts !== false ? ($ts * 1000) : (time() * 1000),
        ];
    }

    jsonSuccess('Notifikasi dimuat.', [
        'authenticated' => true,
        'unread_count'  => $unreadCount,
        'notifications' => $list,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memuat notifikasi dari PostgreSQL.', 500);
}
