<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/scrim_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$scrimKey = trim((string) ($input['scrim_id'] ?? $input['slug'] ?? ''));
$action   = strtolower(trim((string) ($input['action'] ?? 'challenger_ready')));

if ($scrimKey === '') {
    jsonError('ID scrim tidak valid.', 422);
}

try {
    $pdo   = getDB();
    $scrim = scrimFindByKey($pdo, $scrimKey);
    if (!$scrim) {
        jsonError('Tantangan scrim tidak ditemukan.', 404);
    }

    $userId = (int) $user['id'];

    if ($action === 'host_confirm') {
        if ((int) $scrim['creator_user_id'] !== $userId) {
            jsonError('Hanya Host yang dapat mengonfirmasi pertandingan.', 403);
        }
        $pdo->prepare("UPDATE scrim_matches SET host_ready = TRUE, status = 'confirmed' WHERE id = ?")
            ->execute([(int) $scrim['db_id']]);

        if (!empty($scrim['challenger_user_id'])) {
            mabarCreateNotification(
                $pdo,
                (int) $scrim['challenger_user_id'],
                'scrim',
                'Match Scrim Dikonfirmasi Host',
                sprintf('%s telah mengonfirmasi pertandingan %s (%s). Ruang tanding siap digunakan.', $scrim['nama_tim'], $scrim['game'], $scrim['format_short']),
                'Buka Ruang Tanding',
                'pages/cari_lawan/detail.php?id=' . urlencode($scrim['slug'])
            );
        }

        jsonSuccess('Match resmi terkonfirmasi! Ruang tanding telah dibuka untuk kedua tim.', [
            'status'     => 'confirmed',
            'host_ready' => true,
        ]);
    }

    if ((int) ($scrim['challenger_user_id'] ?? 0) !== $userId) {
        jsonError('Hanya tim penantang yang dapat mengubah status kesiapan ini.', 403);
    }

    $isReady = filter_var($input['is_ready'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $stmt = $pdo->prepare('UPDATE scrim_matches SET challenger_ready = ? WHERE id = ?');
    $stmt->bindValue(1, $isReady, PDO::PARAM_BOOL);
    $stmt->bindValue(2, (int) $scrim['db_id'], PDO::PARAM_INT);
    $stmt->execute();

    jsonSuccess($isReady ? 'Status tim kamu: SIAP TANDING (Ready).' : 'Status tim kamu: Standby.', [
        'challenger_ready' => $isReady,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memperbarui status kesiapan scrim.', 500);
}
