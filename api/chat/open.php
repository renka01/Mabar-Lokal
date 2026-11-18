<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$uid          = (int) $user['id'];
$targetUserId = isset($input['target_user_id']) && is_numeric($input['target_user_id']) ? (int) $input['target_user_id'] : 0;
$targetName   = trim((string) ($input['target_name'] ?? ''));
$subtitle     = trim((string) ($input['subtitle'] ?? 'Obrolan Langsung (DM)'));

try {
    $pdo = getDB();

    if ($targetUserId > 0) {
        $uStmt = $pdo->prepare('SELECT id, name, username_tag, avatar_path FROM users WHERE id = ? AND is_active = TRUE LIMIT 1');
        $uStmt->execute([$targetUserId]);
    } elseif ($targetName !== '') {
        $cleanTarget = trim(preg_replace('/\s*\((?:You|Anda)\)\s*$/i', '', $targetName));
        $uStmt = $pdo->prepare('SELECT id, name, username_tag, avatar_path FROM users WHERE (LOWER(name) = LOWER(?) OR LOWER(username_tag) = LOWER(?)) AND is_active = TRUE LIMIT 1');
        $uStmt->execute([$cleanTarget, ltrim($cleanTarget, '@')]);
    } else {
        jsonError('Target pengguna obrolan tidak valid.', 422);
    }

    $peer = $uStmt->fetch();
    if (!$peer) {
        jsonError('Akun pemain tersebut belum terdaftar di MabarLokal.', 404);
    }

    $peerId = (int) $peer['id'];
    if ($peerId === $uid) {
        jsonError('Anda tidak dapat mengirim pesan ke diri sendiri.', 422);
    }

    $userOne   = min($uid, $peerId);
    $userTwo   = max($uid, $peerId);
    $threadKey = "dm-{$userOne}-{$userTwo}";

    $findStmt = $pdo->prepare('SELECT id, subtitle FROM chat_threads WHERE thread_key = ? LIMIT 1');
    $findStmt->execute([$threadKey]);
    $existing = $findStmt->fetch();

    if ($existing) {
        $threadId = (int) $existing['id'];
        $finalSub = (string) ($existing['subtitle'] ?: $subtitle);
    } else {
        $insStmt = $pdo->prepare('
            INSERT INTO chat_threads (thread_key, user_one_id, user_two_id, subtitle)
            VALUES (?, ?, ?, ?)
            RETURNING id
        ');
        $insStmt->execute([$threadKey, $userOne, $userTwo, mb_substr($subtitle, 0, 140)]);
        $threadId = (int) $insStmt->fetchColumn();
        $finalSub = $subtitle;
    }

    $pdo->prepare('UPDATE chat_messages SET is_read = TRUE WHERE thread_id = ? AND sender_user_id <> ? AND is_read = FALSE')
        ->execute([$threadId, $uid]);

    jsonSuccess('Obrolan siap digunakan.', [
        'thread_id' => $threadId,
        'peer'      => [
            'id'       => $peerId,
            'name'     => (string) $peer['name'],
            'tag'      => (string) $peer['username_tag'],
            'avatar'   => (string) ($peer['avatar_path'] ?? ''),
            'subtitle' => $finalSub,
        ],
    ]);
} catch (PDOException $e) {
    jsonError('Gagal membuka ruang percakapan.', 500);
}
