<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/scrim_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$scrimKey = trim((string) ($input['scrim_id'] ?? $input['slug'] ?? ''));
if ($scrimKey === '') {
    jsonError('ID scrim tidak valid.', 422);
}

try {
    $pdo   = getDB();
    $scrim = scrimFindByKey($pdo, $scrimKey);
    if (!$scrim) {
        jsonError('Tantangan scrim tidak ditemukan.', 404);
    }

    if ((int) $scrim['creator_user_id'] !== (int) $user['id']) {
        jsonError('Hanya Kapten Host yang dapat membatalkan tantangan scrim ini.', 403);
    }

    $pdo->prepare('DELETE FROM scrim_matches WHERE id = ?')->execute([(int) $scrim['db_id']]);

    jsonSuccess('Tantangan sparring berhasil dibatalkan.', [
        'redirect' => 'index.php',
    ]);
} catch (PDOException $e) {
    jsonError('Gagal membatalkan tantangan scrim.', 500);
}
