<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mabar_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$sessionKey   = trim((string) ($input['session_id'] ?? $input['slug'] ?? ''));
$targetUserId = isset($input['target_user_id']) && is_numeric($input['target_user_id']) ? (int) $input['target_user_id'] : 0;

if ($sessionKey === '' || $targetUserId <= 0) {
    jsonError('Parameter sesi atau pemain target tidak valid.', 422);
}

try {
    $pdo     = getDB();
    $session = mabarFindSession($pdo, $sessionKey);
    if (!$session) {
        jsonError('Sesi tidak ditemukan.', 404);
    }

    if ((int) $session['host_user_id'] !== (int) $user['id']) {
        jsonError('Hanya Host saat ini yang dapat mengalihkan kepemimpinan.', 403);
    }

    if ($targetUserId === (int) $user['id']) {
        jsonError('Anda sudah menjadi Host sesi ini.', 422);
    }

    $sessionId = (int) $session['id'];

    $targetStmt = $pdo->prepare("
        SELECT mp.id, u.name
        FROM mabar_participants mp
        JOIN users u ON u.id = mp.user_id
        WHERE mp.session_id = ? AND mp.user_id = ? AND mp.role = 'member'
        LIMIT 1
    ");
    $targetStmt->execute([$sessionId, $targetUserId]);
    $target = $targetStmt->fetch();

    if (!$target) {
        jsonError('Pemain target tidak ditemukan sebagai anggota sesi.', 404);
    }

    $pdo->beginTransaction();
    $pdo->prepare('UPDATE mabar_sessions SET host_user_id = ? WHERE id = ?')
        ->execute([$targetUserId, $sessionId]);
    $pdo->prepare("UPDATE mabar_participants SET role = 'member' WHERE session_id = ? AND user_id = ?")
        ->execute([$sessionId, (int) $user['id']]);
    $pdo->prepare("UPDATE mabar_participants SET role = 'host', is_ready = TRUE WHERE session_id = ? AND user_id = ?")
        ->execute([$sessionId, $targetUserId]);
    $pdo->commit();

    mabarCreateNotification(
        $pdo,
        $targetUserId,
        'mabar',
        'Anda Menjadi Host Sesi Baru',
        sprintf('%s mengalihkan kepemimpinan Host sesi %s kepada Anda.', $user['name'], $session['title']),
        'Kelola Sesi',
        'pages/sesi/index.php?id=' . urlencode((string) $session['slug'])
    );

    jsonSuccess("Kepemimpinan Host berhasil dialihkan ke {$target['name']}!", [
        'new_host_user_id' => $targetUserId,
        'new_host_name'    => (string) $target['name'],
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonError('Gagal mengalihkan Host sesi.', 500);
}
