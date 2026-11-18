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
        jsonError('Sesi mabar tidak ditemukan.', 404);
    }

    if (!in_array($session['status'], ['open', 'full'], true)) {
        jsonError('Sesi ini sudah tidak menerima peserta baru.', 409);
    }

    $sessionId = (int) $session['id'];
    $userId    = (int) $user['id'];

    $checkStmt = $pdo->prepare('SELECT id, role FROM mabar_participants WHERE session_id = ? AND user_id = ? LIMIT 1');
    $checkStmt->execute([$sessionId, $userId]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        jsonSuccess('Anda sudah berada di dalam lobby sesi ini.', [
            'already_joined' => true,
            'slug'           => (string) $session['slug'],
            'redirect'       => 'pages/sesi/index.php?id=' . urlencode((string) $session['slug']),
        ]);
    }

    $filled = (int) $session['filled_slot'];
    $max    = (int) $session['max_slot'];
    if ($filled >= $max) {
        jsonError('Maaf, slot sesi ini sudah penuh.', 409);
    }

    $rankLabel = ($session['category'] === 'olahraga') ? 'Komunitas' : 'Pemain';
    $insStmt = $pdo->prepare("
        INSERT INTO mabar_participants (session_id, user_id, role, rank_label, is_ready)
        VALUES (?, ?, 'member', ?, FALSE)
    ");
    $insStmt->execute([$sessionId, $userId, $rankLabel]);

    mabarSyncSessionStatus($pdo, $sessionId);

    if ((int) $session['host_user_id'] !== $userId) {
        mabarCreateNotification(
            $pdo,
            (int) $session['host_user_id'],
            'mabar',
            'Pemain Baru Bergabung ke Sesi',
            sprintf('%s bergabung ke sesi %s (%s).', $user['name'], $session['title'], $session['game']),
            'Buka Lobby',
            'pages/sesi/index.php?id=' . urlencode((string) $session['slug'])
        );
    }

    jsonSuccess('Berhasil bergabung ke lobby sesi!', [
        'already_joined' => false,
        'slug'           => (string) $session['slug'],
        'filled_slot'    => $filled + 1,
        'max_slot'       => $max,
        'redirect'       => 'pages/sesi/index.php?id=' . urlencode((string) $session['slug']),
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memproses permintaan gabung sesi.', 500);
}
