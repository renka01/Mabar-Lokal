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

    if ((int) $session['host_user_id'] !== (int) $user['id']) {
        jsonError('Hanya Host sesi yang dapat menghapus atau membatalkan sesi.', 403);
    }

    $delStmt = $pdo->prepare('DELETE FROM mabar_sessions WHERE id = ?');
    $delStmt->execute([(int) $session['id']]);

    jsonSuccess('Sesi mabar berhasil dihapus.', [
        'redirect' => '../../index.php',
    ]);
} catch (PDOException $e) {
    jsonError('Gagal menghapus sesi dari database.', 500);
}
