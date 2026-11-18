<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/scrim_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$scrimKey = trim((string) ($input['scrim_id'] ?? $input['slug'] ?? ''));
$teamName = trim((string) ($input['team_name'] ?? ''));
$contact  = trim((string) ($input['contact'] ?? ''));
$message  = trim((string) ($input['message'] ?? ''));

if ($scrimKey === '') {
    jsonError('ID scrim tidak valid.', 422);
}
if ($teamName === '' || mb_strlen($teamName) < 2) {
    jsonError('Nama tim penantang minimal 2 karakter.', 422);
}
if ($contact === '') {
    jsonError('Kontak kapten wajib diisi.', 422);
}

try {
    $pdo   = getDB();
    $scrim = scrimFindByKey($pdo, $scrimKey);
    if (!$scrim) {
        jsonError('Tantangan scrim tidak ditemukan.', 404);
    }

    if ((int) $scrim['creator_user_id'] === (int) $user['id']) {
        jsonError('Anda tidak dapat menantang tim Anda sendiri.', 422);
    }

    if ($scrim['status'] !== 'open' && (int) ($scrim['challenger_user_id'] ?? 0) !== (int) $user['id']) {
        jsonError('Tantangan scrim ini sudah memiliki tim penantang.', 409);
    }

    $stmt = $pdo->prepare("
        UPDATE scrim_matches
        SET challenger_user_id = ?,
            challenger_team_name = ?,
            challenger_contact = ?,
            challenger_message = ?,
            challenger_ready = FALSE,
            status = 'matched'
        WHERE id = ?
    ");
    $stmt->execute([
        (int) $user['id'],
        mb_substr($teamName, 0, 120),
        mb_substr($contact, 0, 80),
        mb_substr($message, 0, 500),
        (int) $scrim['db_id'],
    ]);

    mabarCreateNotification(
        $pdo,
        (int) $scrim['creator_user_id'],
        'scrim',
        'Tantangan Scrim Baru Masuk',
        sprintf('Tim "%s" (Kapten %s) menantang %s untuk sparring %s (%s).', $teamName, $user['name'], $scrim['nama_tim'], $scrim['game'], $scrim['format_short']),
        'Buka Ruang Tanding',
        'pages/cari_lawan/detail.php?id=' . urlencode($scrim['slug'])
    );

    jsonSuccess("Tantangan dari \"{$teamName}\" berhasil dikirim ke kapten {$scrim['nama_tim']}!", [
        'slug'     => $scrim['slug'],
        'redirect' => 'detail.php?id=' . urlencode($scrim['slug']),
    ]);
} catch (PDOException $e) {
    jsonError('Gagal mengirim tantangan scrim.', 500);
}
