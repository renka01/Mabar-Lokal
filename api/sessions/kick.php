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
        jsonError('Hanya Host sesi yang dapat mengeluarkan pemain.', 403);
    }

    if ($targetUserId === (int) $user['id']) {
        jsonError('Anda tidak dapat mengeluarkan diri sendiri sebagai Host.', 422);
    }

    $delStmt = $pdo->prepare('DELETE FROM mabar_participants WHERE session_id = ? AND user_id = ?');
    $delStmt->execute([(int) $session['id'], $targetUserId]);

    if ($delStmt->rowCount() === 0) {
        jsonError('Pemain target tidak ditemukan di sesi ini.', 404);
    }

    mabarSyncSessionStatus($pdo, (int) $session['id']);

    mabarCreateNotification(
        $pdo,
        $targetUserId,
        'mabar',
        'Dikeluarkan dari Sesi Mabar',
        sprintf('Anda telah dikeluarkan oleh Host dari sesi %s (%s).', $session['title'], $session['game']),
        'Cari Sesi Lain',
        'index.php'
    );

    jsonSuccess('Pemain telah dikeluarkan dari sesi mabar.', [
        'target_user_id' => $targetUserId,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal mengeluarkan pemain dari sesi.', 500);
}
