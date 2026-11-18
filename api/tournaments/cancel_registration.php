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

    $del = $pdo->prepare('DELETE FROM tournament_teams WHERE tournament_id = ? AND user_id = ?');
    $del->execute([(int) $trn['db_id'], (int) $user['id']]);

    if ($del->rowCount() === 0) {
        jsonError('Pendaftaran tim Anda tidak ditemukan di turnamen ini.', 404);
    }

    jsonSuccess('Pendaftaran tim berhasil dibatalkan. Anda dapat mendaftarkan tim baru.', [
        'slug' => $trn['slug'],
    ]);
} catch (PDOException $e) {
    jsonError('Gagal membatalkan pendaftaran tim.', 500);
}
