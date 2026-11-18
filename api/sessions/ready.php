<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mabar_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$sessionKey = trim((string) ($input['session_id'] ?? $input['slug'] ?? ''));
if ($sessionKey === '') {
    jsonError('ID sesi tidak valid.', 422);
}

$isReady = filter_var($input['is_ready'] ?? false, FILTER_VALIDATE_BOOLEAN);

try {
    $pdo     = getDB();
    $session = mabarFindSession($pdo, $sessionKey);
    if (!$session) {
        jsonError('Sesi tidak ditemukan.', 404);
    }

    $sessionId = (int) $session['id'];
    $userId    = (int) $user['id'];

    $stmt = $pdo->prepare('UPDATE mabar_participants SET is_ready = ? WHERE session_id = ? AND user_id = ?');
    $stmt->bindValue(1, $isReady, PDO::PARAM_BOOL);
    $stmt->bindValue(2, $sessionId, PDO::PARAM_INT);
    $stmt->bindValue(3, $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        jsonError('Anda bukan anggota dari sesi ini.', 403);
    }

    $statsStmt = $pdo->prepare("
        SELECT COUNT(*) AS total_players,
               COUNT(*) FILTER (WHERE is_ready = TRUE) AS ready_count
        FROM mabar_participants
        WHERE session_id = ? AND role IN ('host', 'member')
    ");
    $statsStmt->execute([$sessionId]);
    $stats = $statsStmt->fetch();

    jsonSuccess($isReady ? 'Status: Siap Bertanding!' : 'Status: Belum Siap', [
        'is_ready'      => $isReady,
        'ready_count'   => (int) ($stats['ready_count'] ?? 0),
        'total_players' => (int) ($stats['total_players'] ?? 0),
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memperbarui status kesiapan.', 500);
}
