<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/turnamen_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$trnKey = trim((string) ($input['tournament_id'] ?? $input['slug'] ?? ''));
if ($trnKey === '') {
    jsonError('ID turnamen tidak valid.', 422);
}

try {
    $pdo = getDB();
    $trn = turnamenFindByKey($pdo, $trnKey);
    if (!$trn) {
        jsonError('Turnamen tidak ditemukan.', 404);
    }

    if ($trn['creator_user_id'] === null || (int) $trn['creator_user_id'] !== (int) $user['id']) {
        jsonError('Hanya penyelenggara (Host) yang dapat menghapus turnamen ini.', 403);
    }

    $pdo->prepare('DELETE FROM tournaments WHERE id = ?')->execute([(int) $trn['db_id']]);

    require_once __DIR__ . '/../../config/storage.php';
    $posterPath = (string) ($trn['poster_path'] ?? '');
    if ($posterPath !== '') {
        storageDeleteMedia($posterPath, 'posters');
    }

    jsonSuccess('Turnamen berhasil dibatalkan.', [
        'redirect' => 'index.php',
    ]);
} catch (PDOException $e) {
    jsonError('Gagal menghapus turnamen.', 500);
}
