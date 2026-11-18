<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$uid      = (int) $user['id'];
$threadId = isset($input['thread_id']) && is_numeric($input['thread_id']) ? (int) $input['thread_id'] : 0;
$body     = trim((string) ($input['body'] ?? ''));

if ($threadId <= 0) {
    jsonError('Thread obrolan tidak valid.', 422);
}
if ($body === '') {
    jsonError('Pesan tidak boleh kosong.', 422);
}
if (mb_strlen($body) > 1000) {
    $body = mb_substr($body, 0, 1000);
}

try {
    $pdo = getDB();

    $tStmt = $pdo->prepare('SELECT id, user_one_id, user_two_id, subtitle FROM chat_threads WHERE id = ? LIMIT 1');
    $tStmt->execute([$threadId]);
    $thread = $tStmt->fetch();

    if (!$thread || ((int) $thread['user_one_id'] !== $uid && (int) $thread['user_two_id'] !== $uid)) {
        jsonError('Anda tidak memiliki akses ke percakapan ini.', 403);
    }

    $recipientId = ((int) $thread['user_one_id'] === $uid)
        ? (int) $thread['user_two_id']
        : (int) $thread['user_one_id'];

    $pdo->beginTransaction();

    $insStmt = $pdo->prepare('
        INSERT INTO chat_messages (thread_id, sender_user_id, body, is_read)
        VALUES (?, ?, ?, FALSE)
        RETURNING id, created_at
    ');
    $insStmt->execute([$threadId, $uid, $body]);
    $inserted = $insStmt->fetch();

    $pdo->prepare('UPDATE chat_threads SET updated_at = CURRENT_TIMESTAMP WHERE id = ?')
        ->execute([$threadId]);

    $preview = mb_strlen($body) > 80 ? (mb_substr($body, 0, 80) . '...') : $body;
    $metaJson = json_encode([
        'chatTarget'   => (string) $user['name'],
        'chatSubtitle' => (string) ($thread['subtitle'] ?: 'Obrolan Langsung (DM)'),
        'threadId'     => $threadId,
    ], JSON_UNESCAPED_UNICODE);

    $pdo->prepare("
        INSERT INTO notifications (user_id, category, title, message, action_label, href, action_type, meta_json, is_read)
        VALUES (?, 'chat', ?, ?, 'Balas di Chat', NULL, 'openChat', ?, FALSE)
    ")->execute([
        $recipientId,
        'Pesan Baru dari ' . (string) $user['name'],
        sprintf('%s: "%s"', (string) $user['name'], $preview),
        $metaJson,
    ]);

    $pdo->commit();

    $ts = strtotime((string) ($inserted['created_at'] ?? 'now'));

    jsonSuccess('Pesan terkirim.', [
        'message_item' => [
            'id'        => (int) ($inserted['id'] ?? 0),
            'from'      => 'me',
            'text'      => $body,
            'time'      => $ts !== false ? date('H:i', $ts) : date('H:i'),
            'dateLabel' => 'Hari ini',
        ],
    ], 201);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonError('Gagal mengirim pesan.', 500);
}
