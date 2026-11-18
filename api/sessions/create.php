<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mabar_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$category = strtolower(trim((string) ($input['category'] ?? 'esports')));
if (!in_array($category, ['esports', 'olahraga'], true)) {
    $category = 'esports';
}

$title = trim((string) ($input['title'] ?? ''));
$game  = trim((string) ($input['game'] ?? ''));
$city  = trim((string) ($input['city'] ?? ''));

if ($title === '' || mb_strlen($title) < 3) {
    jsonError('Nama sesi minimal 3 karakter.', 422);
}
if (mb_strlen($title) > 50) {
    $title = mb_substr($title, 0, 50);
}

if ($game === '') {
    jsonError('Harap pilih game atau cabang olahraga.', 422);
}

if ($category === 'olahraga') {
    if ($city === '' || str_contains(strtolower($city), 'online')) {
        jsonError('Untuk sesi olahraga, wajib memilih kota lapangan.', 422);
    }
} else {
    if ($city === '' || str_contains(strtolower($city), 'bebas')) {
        $city = 'Online';
    }
}

$maxAllowed = mabarGameCapacity($game, $category);
$maxSlot    = (int) ($input['max_slot'] ?? $maxAllowed);
$maxSlot    = max(2, min($maxSlot, max($maxAllowed, 22)));

$placeLabel = $category === 'olahraga'
    ? $city
    : (strtolower($city) === 'online' ? 'Online (Server Indo)' : "Online / {$city}");

$description = $category === 'olahraga'
    ? 'Mabar olahraga fun bareng, jaga sportivitas, keringatan sehat & no baperan!'
    : 'Push rank sampe tuntas. Komunikasi wajib mic, playstyle menyesuaikan tim, no toxic & have fun!';

$mode = $category === 'olahraga'
    ? (in_array(strtolower($game), ['futsal', 'basket'], true) ? '5v5' : 'Fun Game')
    : 'Ranked';

$defaultRules = [
    'No Toxic & No Blaming',
    'AFK / Cancel Sepihak Kick Langsung',
    'Sportif & Saling Menghargai',
    'Wajib Konfirmasi Kehadiran',
];

$slug = ($category === 'olahraga' ? 'ol-' : 'es-') . bin2hex(random_bytes(4));

try {
    $pdo = getDB();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO mabar_sessions
            (slug, host_user_id, category, game, title, description, city, place_label, schedule_label, start_time, end_time, mode, join_rule, max_slot, rules_json, specs_json, is_featured, status)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, 'open')
        RETURNING id
    ");
    $stmt->execute([
        $slug,
        (int) $user['id'],
        $category,
        $game,
        $title,
        $description,
        $city,
        $placeLabel,
        'Hari ini, 19:00 WIB',
        '19:00',
        '22:00',
        $mode,
        'Ask For Join',
        $maxSlot,
        json_encode($defaultRules, JSON_UNESCAPED_UNICODE),
        json_encode([], JSON_UNESCAPED_UNICODE),
    ]);
    $sessionId = (int) $stmt->fetchColumn();

    $rankLabel = $category === 'olahraga' ? 'Host Lapangan' : 'Host Sesi';
    $partStmt = $pdo->prepare("
        INSERT INTO mabar_participants (session_id, user_id, role, rank_label, is_ready)
        VALUES (?, ?, 'host', ?, TRUE)
    ");
    $partStmt->execute([$sessionId, (int) $user['id'], $rankLabel]);

    $pdo->commit();

    jsonSuccess('Sesi mabar berhasil dibuat!', [
        'id'       => $sessionId,
        'slug'     => $slug,
        'redirect' => 'pages/sesi/index.php?id=' . urlencode($slug),
    ], 201);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonError('Gagal membuat sesi baru di database.', 500);
}
