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

try {
    $pdo     = getDB();
    $session = mabarFindSession($pdo, $sessionKey);
    if (!$session) {
        jsonError('Sesi tidak ditemukan.', 404);
    }

    $sessionId = (int) $session['id'];
    $userId    = (int) $user['id'];

    if ((int) $session['host_user_id'] === $userId) {
        jsonError('Host tidak dapat langsung keluar. Alihkan Host ke pemain lain atau batalkan/hapus sesi.', 409);
    }

    $delStmt = $pdo->prepare('DELETE FROM mabar_participants WHERE session_id = ? AND user_id = ?');
    $delStmt->execute([$sessionId, $userId]);

    mabarSyncSessionStatus($pdo, $sessionId);

    jsonSuccess('Anda telah keluar dari sesi mabar.', [
        'slug' => (string) $session['slug'],
    ]);
} catch (PDOException $e) {
    jsonError('Gagal keluar dari sesi.', 500);
}
