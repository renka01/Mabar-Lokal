<?php
require_once __DIR__ . '/../../config/session.php';

$user = currentUser(true);
if (!$user) {
    jsonSuccess('Mode tamu.', [
        'authenticated' => false,
        'threads'       => [],
    ]);
}

$uid = (int) $user['id'];

function chatFormatDateLabel(string $tsStr): string
{
    $ts = strtotime($tsStr);
    if ($ts === false) {
        return 'Hari ini';
    }
    $today     = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $msgDay    = date('Y-m-d', $ts);

    if ($msgDay === $today) {
        return 'Hari ini';
    }
    if ($msgDay === $yesterday) {
        return 'Kemarin';
    }
    return date('d M', $ts);
}

try {
    $pdo = getDB();

    $stmt = $pdo->prepare("
        SELECT ct.id,
               ct.thread_key,
               ct.subtitle,
               ct.updated_at,
               CASE WHEN ct.user_one_id = ? THEN ct.user_two_id ELSE ct.user_one_id END AS peer_id,
               u.name AS peer_name,
               u.username_tag AS peer_tag,
               u.avatar_path AS peer_avatar,
               (
                   SELECT COUNT(*)
                   FROM chat_messages cm
                   WHERE cm.thread_id = ct.id AND cm.sender_user_id <> ? AND cm.is_read = FALSE
               ) AS unread_count
        FROM chat_threads ct
        JOIN users u ON u.id = (CASE WHEN ct.user_one_id = ? THEN ct.user_two_id ELSE ct.user_one_id END)
        WHERE ct.user_one_id = ? OR ct.user_two_id = ?
        ORDER BY ct.updated_at DESC, ct.id DESC
    ");
    $stmt->execute([$uid, $uid, $uid, $uid, $uid]);
    $threadRows = $stmt->fetchAll();

    $threadIds = array_map(fn($r) => (int) $r['id'], $threadRows);
    $messagesMap = [];

    if (!empty($threadIds)) {
        $placeholders = implode(',', array_fill(0, count($threadIds), '?'));
        $msgStmt = $pdo->prepare("
            SELECT id, thread_id, sender_user_id, body, is_read, created_at
            FROM chat_messages
            WHERE thread_id IN ({$placeholders})
            ORDER BY created_at ASC, id ASC
        ");
        $msgStmt->execute($threadIds);

        foreach ($msgStmt->fetchAll() as $m) {
            $tid = (int) $m['thread_id'];
            if (!isset($messagesMap[$tid])) {
                $messagesMap[$tid] = [];
            }
            $ts = strtotime((string) $m['created_at']);
            $messagesMap[$tid][] = [
                'id'        => (int) $m['id'],
                'from'      => ((int) $m['sender_user_id'] === $uid) ? 'me' : 'them',
                'text'      => (string) $m['body'],
                'time'      => $ts !== false ? date('H:i', $ts) : '',
                'dateLabel' => chatFormatDateLabel((string) $m['created_at']),
            ];
        }
    }

    $threads = [];
    foreach ($threadRows as $tr) {
        $tid = (int) $tr['id'];
        $threads[] = [
            'id'         => $tid,
            'thread_key' => (string) $tr['thread_key'],
            'peer_id'    => (int) $tr['peer_id'],
            'name'       => (string) $tr['peer_name'],
            'tag'        => (string) $tr['peer_tag'],
            'avatar'     => (string) ($tr['peer_avatar'] ?? ''),
            'subtitle'   => (string) ($tr['subtitle'] ?: 'Pemain Mabar Lokal'),
            'unread'     => (int) $tr['unread_count'],
            'online'     => true,
            'messages'   => $messagesMap[$tid] ?? [],
        ];
    }

    jsonSuccess('Daftar percakapan dimuat.', [
        'authenticated' => true,
        'threads'       => $threads,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memuat percakapan dari PostgreSQL.', 500);
}
