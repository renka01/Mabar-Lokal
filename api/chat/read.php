<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$uid      = (int) $user['id'];
$threadId = isset($input['thread_id']) && is_numeric($input['thread_id']) ? (int) $input['thread_id'] : 0;

if ($threadId <= 0) {
    jsonError('Thread obrolan tidak valid.', 422);
}

try {
    $pdo = getDB();

    $tStmt = $pdo->prepare('SELECT id, user_one_id, user_two_id FROM chat_threads WHERE id = ? LIMIT 1');
    $tStmt->execute([$threadId]);
    $thread = $tStmt->fetch();

    if (!$thread || ((int) $thread['user_one_id'] !== $uid && (int) $thread['user_two_id'] !== $uid)) {
        jsonError('Anda tidak memiliki akses ke percakapan ini.', 403);
    }

    $pdo->prepare('UPDATE chat_messages SET is_read = TRUE WHERE thread_id = ? AND sender_user_id <> ? AND is_read = FALSE')
        ->execute([$threadId, $uid]);

    jsonSuccess('Pesan ditandai sudah dibaca.', [
        'thread_id' => $threadId,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memperbarui status baca.', 500);
}
