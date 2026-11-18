<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/turnamen_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$trnKey      = trim((string) ($input['tournament_id'] ?? $input['slug'] ?? ''));
$teamName    = trim((string) ($input['team_name'] ?? ''));
$captainName = trim((string) ($input['captain_name'] ?? $user['name']));
$contact     = trim((string) ($input['captain_contact'] ?? $user['phone'] ?? ''));
$city        = trim((string) ($input['city'] ?? $user['city'] ?? 'Kota Malang'));
$rosterRaw   = $input['roster'] ?? [];

if ($trnKey === '') {
    jsonError('ID turnamen tidak valid.', 422);
}
if ($teamName === '' || mb_strlen($teamName) < 2) {
    jsonError('Nama tim minimal 2 karakter.', 422);
}
if ($captainName === '') {
    jsonError('Nama kapten wajib diisi.', 422);
}
if ($contact === '') {
    jsonError('Kontak WhatsApp/Discord kapten wajib diisi.', 422);
}

$roster = [];
if (is_array($rosterRaw)) {
    foreach ($rosterRaw as $player) {
        $p = trim((string) $player);
        if ($p !== '') {
            $roster[] = mb_substr($p, 0, 80);
        }
    }
}
if (empty($roster)) {
    $roster = [$captainName];
}

try {
    $pdo = getDB();
    $trn = turnamenFindByKey($pdo, $trnKey);
    if (!$trn) {
        jsonError('Turnamen tidak ditemukan.', 404);
    }

    if ($trn['statusType'] === 'done') {
        jsonError('Turnamen ini sudah selesai dan pendaftaran ditutup.', 409);
    }

    $trnDbId = (int) $trn['db_id'];
    $userId  = (int) $user['id'];

    if ($trn['creator_user_id'] !== null && (int) $trn['creator_user_id'] === $userId) {
        jsonError('Penyelenggara (Host) tidak perlu mendaftar sebagai peserta di turnamen sendiri.', 422);
    }

    $dupStmt = $pdo->prepare("SELECT id FROM tournament_teams WHERE tournament_id = ? AND user_id = ? AND status <> 'rejected' LIMIT 1");
    $dupStmt->execute([$trnDbId, $userId]);
    if ($dupStmt->fetch()) {
        jsonError('Anda sudah memiliki tim yang terdaftar di turnamen ini.', 409);
    }

    if ((int) $trn['registered_count'] >= (int) $trn['max_teams']) {
        jsonError('Maaf, kapasitas tim untuk turnamen ini sudah penuh.', 409);
    }

    $seedLabel = 'Seed #' . ((int) $trn['registered_count'] + 1);
    $stmt = $pdo->prepare("
        INSERT INTO tournament_teams
            (tournament_id, user_id, team_name, captain_name, captain_contact, city, roster_json, seed_label, status)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        RETURNING id
    ");
    $stmt->execute([
        $trnDbId,
        $userId,
        mb_substr($teamName, 0, 120),
        mb_substr($captainName, 0, 100),
        mb_substr($contact, 0, 80),
        mb_substr($city, 0, 80),
        json_encode($roster, JSON_UNESCAPED_UNICODE),
        $seedLabel,
    ]);
    $teamId = (int) $stmt->fetchColumn();

    if ($trn['creator_user_id'] !== null && (int) $trn['creator_user_id'] !== $userId) {
        mabarCreateNotification(
            $pdo,
            (int) $trn['creator_user_id'],
            'turnamen',
            'Pendaftar Baru Menunggu Verifikasi',
            sprintf('Tim "%s" (Kapten %s) mendaftar di %s. Cek roster & verifikasi peserta.', $teamName, $captainName, $trn['judul']),
            'Kelola Pendaftar',
            'pages/turnamen/detail.php?id=' . urlencode($trn['slug'])
        );
    }

    jsonSuccess("Pendaftaran tim \"{$teamName}\" berhasil dikirim! Menunggu verifikasi panitia.", [
        'team_id' => $teamId,
        'status'  => 'pending',
        'slug'    => $trn['slug'],
    ], 201);
} catch (PDOException $e) {
    jsonError('Gagal menyimpan pendaftaran tim ke database.', 500);
}
