<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/turnamen_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$trnKey     = trim((string) ($input['tournament_id'] ?? $input['slug'] ?? ''));
$teamId     = isset($input['team_id']) && is_numeric($input['team_id']) ? (int) $input['team_id'] : 0;
$isAccepted = filter_var($input['accept'] ?? false, FILTER_VALIDATE_BOOLEAN);

if ($trnKey === '' || $teamId <= 0) {
    jsonError('Parameter turnamen atau tim tidak valid.', 422);
}

try {
    $pdo = getDB();
    $trn = turnamenFindByKey($pdo, $trnKey);
    if (!$trn) {
        jsonError('Turnamen tidak ditemukan.', 404);
    }

    if ($trn['creator_user_id'] === null || (int) $trn['creator_user_id'] !== (int) $user['id']) {
        jsonError('Hanya penyelenggara (Host) yang dapat memverifikasi peserta.', 403);
    }

    $teamStmt = $pdo->prepare('SELECT id, user_id, team_name FROM tournament_teams WHERE id = ? AND tournament_id = ? LIMIT 1');
    $teamStmt->execute([$teamId, (int) $trn['db_id']]);
    $team = $teamStmt->fetch();
    if (!$team) {
        jsonError('Data tim pendaftar tidak ditemukan.', 404);
    }

    $newStatus = $isAccepted ? 'verified' : 'rejected';
    $pdo->prepare('UPDATE tournament_teams SET status = ? WHERE id = ?')
        ->execute([$newStatus, $teamId]);

    if (!empty($team['user_id'])) {
        mabarCreateNotification(
            $pdo,
            (int) $team['user_id'],
            'turnamen',
            $isAccepted ? 'Tim Kamu Resmi Terverifikasi!' : 'Pendaftaran Tim Ditolak',
            $isAccepted
                ? sprintf('Tim "%s" telah diverifikasi untuk bertanding di %s.', $team['team_name'], $trn['judul'])
                : sprintf('Maaf, pendaftaran tim "%s" di %s belum dapat disetujui panitia.', $team['team_name'], $trn['judul']),
            'Lihat Turnamen',
            'pages/turnamen/detail.php?id=' . urlencode($trn['slug'])
        );
    }

    jsonSuccess(
        $isAccepted
            ? "Tim \"{$team['team_name']}\" berhasil diverifikasi!"
            : "Pendaftaran tim \"{$team['team_name']}\" telah ditolak.",
        [
            'team_id' => $teamId,
            'status'  => $newStatus,
        ]
    );
} catch (PDOException $e) {
    jsonError('Gagal memperbarui status verifikasi tim.', 500);
}
