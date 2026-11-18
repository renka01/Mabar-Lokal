<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/scrim_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$category = strtolower(trim((string) ($input['category'] ?? 'esports')));
if (!in_array($category, ['esports', 'olahraga'], true)) {
    $category = 'esports';
}

$teamName    = trim((string) ($input['team_name'] ?? ''));
$game        = trim((string) ($input['game'] ?? ($category === 'olahraga' ? 'Futsal' : 'Valorant')));
$formatShort = trim((string) ($input['format_short'] ?? ($category === 'olahraga' ? '2x20M' : 'BO3')));
$city        = trim((string) ($input['city'] ?? ''));

if ($teamName === '' || mb_strlen($teamName) < 2) {
    jsonError('Nama tim minimal 2 karakter.', 422);
}
if (mb_strlen($teamName) > 120) {
    $teamName = mb_substr($teamName, 0, 120);
}

if ($city === '' || str_contains(strtolower($city), 'bebas')) {
    $city = $category === 'olahraga' ? ($user['city'] ?: 'Kota Malang') : 'Online';
}

$isOlahraga  = $category === 'olahraga';
$matchType   = ($isOlahraga || (!str_contains(strtolower($city), 'online') && $category === 'olahraga')) ? 'offline' : 'online';
$location    = $isOlahraga ? "{$city} • Lapangan Olahraga" : "Online (Discord / Server Indo)";
$formatLabel = scrimFormatLabelFromShort($formatShort);

$parts       = preg_split('/\s+/', $teamName) ?: ['TM'];
$logoInitial = count($parts) >= 2
    ? strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1))
    : strtoupper(mb_substr($teamName, 0, 2));

$slug  = ($isOlahraga ? 'scrim-ol-' : 'scrim-es-') . bin2hex(random_bytes(4));
$notes = 'Tantangan baru dibuka! Roster kami siap tanding. Hubungi kapten untuk konfirmasi jadwal fix.';

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        INSERT INTO scrim_matches
            (slug, creator_user_id, category, game, team_name, logo_initial, tier, tier_badge, tier_color,
             format_label, format_short, schedule_label, day_category, match_type, location_label, city,
             captain_name, captain_rank, notes, status, is_featured, host_ready)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'hari_ini', ?, ?, ?, ?, 'Kapten Tim', ?, 'open', FALSE, TRUE)
        RETURNING id
    ");
    $stmt->execute([
        $slug,
        (int) $user['id'],
        $category,
        $game,
        $teamName,
        $logoInitial,
        'Komunitas / Kompetitif',
        $isOlahraga ? 'Menengah' : 'Kompetitif',
        $isOlahraga ? 'green' : 'red',
        $formatLabel,
        $formatShort,
        'Hari ini, 20:30 WIB',
        $matchType,
        $location,
        $city,
        (string) $user['name'],
        $notes,
    ]);
    $newId = (int) $stmt->fetchColumn();

    jsonSuccess("Tantangan scrim untuk \"{$teamName}\" berhasil dibuka!", [
        'id'       => $newId,
        'slug'     => $slug,
        'redirect' => 'detail.php?id=' . urlencode($slug),
    ], 201);
} catch (PDOException $e) {
    jsonError('Gagal menyimpan tantangan scrim ke PostgreSQL.', 500);
}
